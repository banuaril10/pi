<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
require_once 'auth_middleware.php';

// Authenticate dulu
$userData = authenticate();
include "../../config/koneksi.php";

// Fungsi format rupiah
function rupiah($angka)
{
    return number_format($angka, 0, ',', '.');
}

// Terima parameter
$billno = $_GET['billno'] ?? $_POST['billno'] ?? null;
$sku = $_GET['sku'] ?? $_POST['sku'] ?? null;

// Validasi input
if (empty($sku)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "SKU tidak ditemukan",
        "data" => null
    ]);
    exit;
}

try {
    $nama_product = "";
    $price = 0;
    $discounts = [];

    // Ambil info produk
    $productSql = "SELECT sku, name, price FROM pos_mproduct WHERE sku = :sku or barcode = :sku LIMIT 1";
    $productStmt = $connec->prepare($productSql);
    $productStmt->bindParam(":sku", $sku);
    $productStmt->execute();
    $product = $productStmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $nama_product = $product['name'];
        $price = (int) $product['price'];
        $sku = $product['sku'];
    } else {
        echo json_encode([
            "status" => "SUCCESS",
            "message" => "Produk tidak ditemukan",
            "data" => [
                "product_name" => "",
                "price" => 0,
                "discounts" => []
            ]
        ]);
        exit;
    }

    // 1. Promo reguler
    $regulerSql = "SELECT discount, discountname 
                   FROM pos_mproductdiscount 
                   WHERE sku = :sku 
                   AND DATE(now()) BETWEEN fromdate AND todate";
    $regulerStmt = $connec->prepare($regulerSql);
    $regulerStmt->bindParam(":sku", $sku);
    $regulerStmt->execute();
    $reguler = $regulerStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($reguler as $r) {
        $diskonValue = (int) $r['discount'];
        $priceAfter = $price - $diskonValue;
        $discounts[] = [
            "type" => "REGULER",
            "name" => $r['discountname'],
            "discount_amount" => $diskonValue,
            "discount_amount_formatted" => "Rp " . rupiah($diskonValue),
            "price_after" => $priceAfter,
            "price_after_formatted" => "Rp " . rupiah($priceAfter),
            "description" => "Diskon Rp " . rupiah($diskonValue) . " menjadi Rp " . rupiah($priceAfter)
        ];
    }

    // 2. Promo grosir
    $grosirSql = "SELECT discount, discountname, minbuy 
                  FROM pos_mproductdiscountgrosir_new 
                  WHERE sku = :sku 
                  AND DATE(now()) BETWEEN fromdate AND todate 
                  AND minbuy > 1 
                  ORDER BY minbuy ASC";
    $grosirStmt = $connec->prepare($grosirSql);
    $grosirStmt->bindParam(":sku", $sku);
    $grosirStmt->execute();
    $grosir = $grosirStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($grosir as $g) {
        $diskonValue = (int) $g['discount'];
        $priceAfter = $price - $diskonValue;
        $discounts[] = [
            "type" => "GROSIR",
            "name" => $g['discountname'],
            "min_buy" => (int) $g['minbuy'],
            "discount_amount" => $diskonValue,
            "discount_amount_formatted" => "Rp " . rupiah($diskonValue),
            "price_after" => $priceAfter,
            "price_after_formatted" => "Rp " . rupiah($priceAfter),
            "description" => "Beli " . $g['minbuy'] . ", diskon Rp " . rupiah($diskonValue) . " menjadi Rp " . rupiah($priceAfter)
        ];
    }

    // 3. Promo bundling
    $bundlingSql = "SELECT b.bundling_code, b.minbuy
                    FROM pos_mproductdiscount_bundling a
                    JOIN pos_mproductdiscount_bundling_header b 
                       ON a.discountname = b.bundling_code
                    WHERE a.sku = :sku
                      AND DATE(now()) BETWEEN a.fromdate AND a.todate
                    LIMIT 1";
    $bundlingStmt = $connec->prepare($bundlingSql);
    $bundlingStmt->bindParam(":sku", $sku);
    $bundlingStmt->execute();
    $bundling = $bundlingStmt->fetch(PDO::FETCH_ASSOC);

    if ($bundling) {
        $bundling_code = $bundling['bundling_code'];
        $minbuy = (int) $bundling['minbuy'];

        // Ambil potongan harga
        $bundlingDetailSql = "SELECT discount
                              FROM pos_mproductdiscount_bundling 
                              WHERE discountname = :discountname
                                AND sku = :sku
                                AND DATE(now()) BETWEEN fromdate AND todate";
        $bundlingDetailStmt = $connec->prepare($bundlingDetailSql);
        $bundlingDetailStmt->bindParam(":discountname", $bundling_code);
        $bundlingDetailStmt->bindParam(":sku", $sku);
        $bundlingDetailStmt->execute();
        $bundlingDetail = $bundlingDetailStmt->fetch(PDO::FETCH_ASSOC);

        if ($bundlingDetail) {
            $diskonValue = (int) $bundlingDetail['discount'];
            $priceAfter = $price - $diskonValue;
            $discounts[] = [
                "type" => "BUNDLING",
                "name" => "Promo Bundling",
                "bundling_code" => $bundling_code,
                "min_buy" => $minbuy,
                "discount_amount" => $diskonValue,
                "discount_amount_formatted" => "Rp " . rupiah($diskonValue),
                "price_after" => $priceAfter,
                "price_after_formatted" => "Rp " . rupiah($priceAfter),
                "description" => "Minimal beli " . $minbuy . " item, harga setelah bundling Rp " . rupiah($priceAfter)
            ];
        }
    }

    // 4. Promo combo (A + B bayar harga termahal)
    $comboSql = "SELECT h.promo_name, h.min_items, h.max_items
                 FROM pos_mcombo_promo_detail d
                 JOIN pos_mcombo_promo_header h 
                   ON d.pos_mcombo_promo_header_key = h.pos_mcombo_promo_header_key
                 WHERE d.sku = :sku
                   AND h.isactived = '1'
                   AND DATE(now()) BETWEEN h.fromdate AND h.todate
                 LIMIT 1";
    $comboStmt = $connec->prepare($comboSql);
    $comboStmt->bindParam(":sku", $sku);
    $comboStmt->execute();
    $combo = $comboStmt->fetch(PDO::FETCH_ASSOC);

    if ($combo) {
        $promo_name = $combo['promo_name'];
        $min_items = (int) $combo['min_items'];
        $max_items = (int) $combo['max_items'];

        // Ambil daftar item combo
        $comboItemsSql = "SELECT p.sku, m.name, pmd.price
                          FROM pos_mcombo_promo_detail d
                          JOIN pos_mproduct m ON d.sku = m.sku
                          LEFT JOIN pos_mproduct pmd ON d.sku = pmd.sku
                          WHERE d.pos_mcombo_promo_header_key = (
                              SELECT pos_mcombo_promo_header_key
                              FROM pos_mcombo_promo_detail d2
                              JOIN pos_mcombo_promo_header h2 
                                ON d2.pos_mcombo_promo_header_key = h2.pos_mcombo_promo_header_key
                              WHERE d2.sku = :sku2
                                AND h2.isactived = '1'
                                AND DATE(now()) BETWEEN h2.fromdate AND h2.todate
                              LIMIT 1
                          )";
        $comboItemsStmt = $connec->prepare($comboItemsSql);
        $comboItemsStmt->bindParam(":sku2", $sku);
        $comboItemsStmt->execute();
        $comboItems = $comboItemsStmt->fetchAll(PDO::FETCH_ASSOC);

        $items = [];
        foreach ($comboItems as $item) {
            $items[] = [
                "sku" => $item['sku'],
                "name" => $item['name'],
                "price" => (int) $item['price'],
                "price_formatted" => "Rp " . rupiah($item['price'])
            ];
        }

        $discounts[] = [
            "type" => "COMBO",
            "name" => $promo_name,
            "min_items" => $min_items,
            "max_items" => $max_items,
            "items" => $items,
            "description" => "Beli " . $min_items . " item, cukup bayar harga termahal"
        ];
    }

    // 5. Promo Buy & Get
    $buygetSql = "SELECT DISTINCT
                  b.qtybuy,
                  b.qtyget,
                  (b.priceget - b.discount) AS priceget,
                  p.name AS nama_get,
                  b.skuget
                  FROM pos_mproductbuyget b
                  LEFT JOIN pos_mproduct p 
                    ON b.skuget = p.sku
                  WHERE b.skubuy = :sku
                    AND CURRENT_DATE BETWEEN b.fromdate AND b.todate
                  ORDER BY b.qtybuy, p.name";
    $buygetStmt = $connec->prepare($buygetSql);
    $buygetStmt->bindParam(":sku", $sku);
    $buygetStmt->execute();
    $buyget = $buygetStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($buyget as $bg) {
        $discounts[] = [
            "type" => "BUY_GET",
            "name" => "Buy " . $bg['qtybuy'] . " Get " . $bg['qtyget'],
            "qty_buy" => (int) $bg['qtybuy'],
            "qty_get" => (int) $bg['qtyget'],
            "get_item" => [
                "sku" => $bg['skuget'],
                "name" => $bg['nama_get'],
                "price" => (int) $bg['priceget'],
                "price_formatted" => "Rp " . rupiah($bg['priceget'])
            ],
            "description" => "Beli " . $bg['qtybuy'] . " dapat " . $bg['qtyget'] . " " . $bg['nama_get'] . " dengan harga Rp " . rupiah($bg['priceget'])
        ];
    }

    // Response JSON
    echo json_encode([
        "status" => "SUCCESS",
        "message" => "Data promo ditemukan",
        "data" => [
            "product_name" => $nama_product,
            "price" => $price,
            "price_formatted" => "Rp " . rupiah($price),
            "discounts" => $discounts
        ]
    ]);

} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage(),
        "data" => null
    ]);
}
?>