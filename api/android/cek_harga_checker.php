<?php
include "../../config/koneksi.php";

header('Content-Type: application/json');

$id_location = '';

// =====================================================
// AMBIL LOCATION
// =====================================================
$ll = "SELECT * FROM ad_morg WHERE isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $id_location = $row['ad_morg_key'];
}


// =====================================================
// FUNCTION UPPERCASE TEXT
// =====================================================
function upperText($value)
{
    if ($value === null) {
        return null;
    }

    return mb_strtoupper((string)$value, 'UTF-8');
}


// =====================================================
// CEK PARAMETER SKU / BARCODE
// =====================================================
if (isset($_GET['sku']) || isset($_GET['barcode'])) {

    $sku_or_barcode = isset($_GET['sku'])
        ? $_GET['sku']
        : $_GET['barcode'];


    // =====================================================
    // QUERY PRODUCT
    // =====================================================
    $sqlPrice = "
        SELECT price, sku, name 
        FROM pos_mproduct 
        WHERE isactived = '1'
        AND (
            sku = :sku_or_barcode 
            OR barcode = :sku_or_barcode 
            OR barcode1 = :sku_or_barcode 
            OR barcode2 = :sku_or_barcode 
            OR barcode3 = :sku_or_barcode 
            OR barcode4 = :sku_or_barcode
        )
        LIMIT 1
    ";

    $stmtPrice = $connec->prepare($sqlPrice);
    $stmtPrice->bindParam(':sku_or_barcode', $sku_or_barcode);
    $stmtPrice->execute();

    $product = $stmtPrice->fetch(PDO::FETCH_ASSOC);


    // =====================================================
    // PRODUCT DITEMUKAN
    // =====================================================
    if ($product) {

        // Text dibuat uppercase
        $name = upperText($product['name']);
        $sku  = upperText($product['sku']);

        // Angka tetap angka
        $regularPrice = $product['price'];


        // =====================================================
        // DISCOUNT
        // =====================================================
        $sqlDiscount = "
            SELECT discount, fromdate, todate 
            FROM pos_mproductdiscount 
            WHERE sku = :sku 
            AND isactived = '1' 
            AND CURRENT_DATE BETWEEN fromdate AND todate
            ORDER BY discount DESC
            LIMIT 1
        ";

        $stmtDiscount = $connec->prepare($sqlDiscount);
        $stmtDiscount->bindParam(':sku', $sku);
        $stmtDiscount->execute();

        $discount = $stmtDiscount->fetch(PDO::FETCH_ASSOC);


        // =====================================================
        // PROMO HEADERS
        // =====================================================
        $promo_headers = [];


        // =====================================================
        // REGULER
        // =====================================================
        $sqlReguler = "
            SELECT discountname, jenis_promo, fromdate, todate, discount
            FROM pos_mproductdiscount
            WHERE sku = :sku 
            AND isactived = '1'
            AND CURRENT_DATE BETWEEN fromdate AND todate
            ORDER BY discount DESC
            LIMIT 1
        ";

        $stmtReguler = $connec->prepare($sqlReguler);
        $stmtReguler->bindParam(':sku', $sku);
        $stmtReguler->execute();

        $promoReguler = $stmtReguler->fetchAll(PDO::FETCH_ASSOC);

        foreach ($promoReguler as $p) {

            $promo_headers[] = [
                'type' => 'REGULER',
                'discountname' => upperText($p['discountname']),
                'jenis_promo' => upperText($p['jenis_promo']),
                'regular_price' => $regularPrice,
                'after_discount' => $regularPrice - $p['discount'],
                'fromdate' => $p['fromdate'],
                'todate' => $p['todate']
            ];
        }


        // =====================================================
        // BUNDLING
        // =====================================================
        $sqlBundling = "
            SELECT discountname, headername, jenis_promo, fromdate, todate, discount
            FROM pos_mproductdiscount_bundling
            WHERE sku = :sku 
            AND isactived = '1'
            AND CURRENT_DATE BETWEEN fromdate AND todate
        ";

        $stmtBundling = $connec->prepare($sqlBundling);
        $stmtBundling->bindParam(':sku', $sku);
        $stmtBundling->execute();

        $promoBundling = $stmtBundling->fetchAll(PDO::FETCH_ASSOC);

        foreach ($promoBundling as $p) {

            $promo_headers[] = [
                'type' => 'BUNDLING',
                'discountname' => upperText($p['discountname']),
                'headername' => upperText($p['headername']),
                'jenis_promo' => upperText($p['jenis_promo']),
                'regular_price' => $regularPrice,
                'after_discount' => $regularPrice - $p['discount'],
                'fromdate' => $p['fromdate'],
                'todate' => $p['todate']
            ];
        }


        // =====================================================
        // GROSIR
        // =====================================================
        $sqlGrosir = "
            SELECT discountname, jenis_promo, fromdate, todate, discount
            FROM pos_mproductdiscountgrosir_new
            WHERE sku = :sku 
            AND isactived = '1'
            AND CURRENT_DATE BETWEEN fromdate AND todate
        ";

        $stmtGrosir = $connec->prepare($sqlGrosir);
        $stmtGrosir->bindParam(':sku', $sku);
        $stmtGrosir->execute();

        $promoGrosir = $stmtGrosir->fetchAll(PDO::FETCH_ASSOC);

        foreach ($promoGrosir as $p) {

            $promo_headers[] = [
                'type' => 'GROSIR',
                'discountname' => upperText($p['discountname']),
                'jenis_promo' => upperText($p['jenis_promo']),
                'regular_price' => $regularPrice,
                'after_discount' => $regularPrice - $p['discount'],
                'fromdate' => $p['fromdate'],
                'todate' => $p['todate']
            ];
        }


        // =====================================================
        // RESPONSE
        // =====================================================
        if ($discount) {

            $discountedPrice = $regularPrice - $discount['discount'];

            $response = [
                'sku' => $sku,
                'name' => $name,
                'regular_price' => $regularPrice,
                'discount' => $discount['discount'],
                'discounted_price' => $discountedPrice,
                'valid_from' => $discount['fromdate'],
                'valid_to' => $discount['todate'],
                'promo_headers' => $promo_headers
            ];

        } else {

            $response = [
                'sku' => $sku,
                'name' => $name,
                'regular_price' => $regularPrice,
                'discount' => null,
                'discounted_price' => null,
                'valid_from' => null,
                'valid_to' => null,
                'promo_headers' => $promo_headers
            ];
        }


        // =====================================================
        // SAVE PRICE AUDIT
        // =====================================================
        $insertAudit = "
            INSERT INTO price_audit 
            (
                sku,
                price,
                discount,
                insertdate,
                id_location,
                scanfrom
            )
            VALUES 
            (
                :sku,
                :price,
                :discount,
                NOW(),
                :id_location,
                :scanfrom
            )
        ";

        $scanfrom = 'price_checker';

        try {

            $stmtAudit = $connec->prepare($insertAudit);

            $stmtAudit->bindParam(':sku', $sku);
            $stmtAudit->bindParam(':price', $regularPrice);

            $discountValue = $discount
                ? $discount['discount']
                : 0;

            $stmtAudit->bindParam(':discount', $discountValue);
            $stmtAudit->bindParam(':id_location', $id_location);
            $stmtAudit->bindParam(':scanfrom', $scanfrom);

            $stmtAudit->execute();

        } catch (PDOException $e) {

            // Jangan sampai audit gagal membuat API utama error.
            // Response product tetap dikirim.
        }


        // =====================================================
        // OUTPUT JSON
        // =====================================================
        echo json_encode(
            $response,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE
        );

    } else {

        echo json_encode([
            'error' => 'PRODUCT NOT FOUND'
        ]);
    }

} else {

    echo json_encode([
        'error' => 'SKU OR BARCODE PARAMETER IS REQUIRED'
    ]);
}

?>