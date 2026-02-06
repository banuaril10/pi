<?php
include "../../config/koneksi.php";

header('Content-Type: application/json');

$id_location = '';

//select ad_morg_key from m_org 
$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $id_location = $row['ad_morg_key'];
}

// Mendapatkan SKU atau barcode dari parameter GET
if (isset($_GET['sku']) || isset($_GET['barcode'])) {
    $sku_or_barcode = isset($_GET['sku']) ? $_GET['sku'] : $_GET['barcode'];

    // Query untuk mendapatkan harga reguler dari tabel pos_mproduct berdasarkan sku/barcode/barcode1-4
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

    // Cek apakah produk ditemukan
    if ($product) {
        $name = $product['name'];
        $regularPrice = $product['price'];
        $sku = $product['sku'];

        // Query untuk mendapatkan diskon dari tabel pos_mproductdiscount
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

        // --- TAMBAHAN: Ambil semua promo aktif dari ketiga tabel ---
        $promo_headers = [];

        // Reguler
        $sqlReguler = "
            SELECT discountname, jenis_promo, fromdate, todate, discount
            FROM pos_mproductdiscount
            WHERE sku = :sku AND isactived = '1'
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
                'type' => 'Reguler',
                'discountname' => $p['discountname'],
                'jenis_promo' => $p['jenis_promo'],
                'regular_price' => $regularPrice,
                'after_discount' => $regularPrice - $p['discount'],
                'fromdate' => $p['fromdate'],
                'todate' => $p['todate']
            ];
        }

        // Bundling
        $sqlBundling = "
            SELECT discountname, headername, jenis_promo, fromdate, todate, discount
            FROM pos_mproductdiscount_bundling
            WHERE sku = :sku AND isactived = '1'
            AND CURRENT_DATE BETWEEN fromdate AND todate
        ";
        $stmtBundling = $connec->prepare($sqlBundling);
        $stmtBundling->bindParam(':sku', $sku);
        $stmtBundling->execute();
        $promoBundling = $stmtBundling->fetchAll(PDO::FETCH_ASSOC);
        foreach ($promoBundling as $p) {
            $promo_headers[] = [
                'type' => 'Bundling',
                'discountname' => $p['discountname'],
                'headername' => $p['headername'],
                'jenis_promo' => $p['jenis_promo'],
                'regular_price' => $regularPrice,
                'after_discount' => $regularPrice - $p['discount'],
                'fromdate' => $p['fromdate'],
                'todate' => $p['todate']
            ];
        }

        // Grosir
        $sqlGrosir = "
            SELECT discountname, jenis_promo, fromdate, todate, discount
            FROM pos_mproductdiscountgrosir_new
            WHERE sku = :sku AND isactived = '1'
            AND CURRENT_DATE BETWEEN fromdate AND todate
        ";
        $stmtGrosir = $connec->prepare($sqlGrosir);
        $stmtGrosir->bindParam(':sku', $sku);
        $stmtGrosir->execute();
        $promoGrosir = $stmtGrosir->fetchAll(PDO::FETCH_ASSOC);
        foreach ($promoGrosir as $p) {
            $promo_headers[] = [
                'type' => 'Grosir',
                'discountname' => $p['discountname'],
                'jenis_promo' => $p['jenis_promo'],
                'regular_price' => $regularPrice,
                'after_discount' => $regularPrice - $p['discount'],
                'fromdate' => $p['fromdate'],
                'todate' => $p['todate']
            ];
        }

        // --- Lanjut struktur lama ---
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
                'promo_headers' => $promo_headers // ✅ tambahan
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
                'promo_headers' => $promo_headers // ✅ tetap muncul meski kosong
            ];
        }



        //save ke $cmd_price_audit = [
// 	'CREATE TABLE IF NOT EXISTS price_audit (
// 		price_audit_key varchar(32) PRIMARY KEY DEFAULT get_uuid(),
// 		sku varchar(50),
// 		price numeric,
// 		discount numeric,
// 		insertdate timestamp,
// 		id_location varchar(10)
// 	);'
// ]; tp jgn error klo table ga ada

        $insertAudit = "
            INSERT INTO price_audit (sku, price, discount, insertdate, id_location, scanfrom)
            VALUES (:sku, :price, :discount, NOW(), :id_location, :scanfrom)
        ";
        $stmtAudit = $connec->prepare($insertAudit);
        // $id_location = isset($_GET['location']) ? $_GET['location'] : 'DEFAULT';
        $stmtAudit->bindParam(':sku', $sku);
        $stmtAudit->bindParam(':price', $regularPrice);
        $discountValue = $discount ? $discount['discount'] : 0;
        $stmtAudit->bindParam(':discount', $discountValue);
        $stmtAudit->bindParam(':id_location', $id_location);
        $stmtAudit->bindParam(':scanfrom', 'android');
        

        $stmtAudit->execute();



        echo json_encode($response, JSON_PRETTY_PRINT);



    } else {
        echo json_encode(['error' => 'Product not found']);
    }

} else {
    echo json_encode(['error' => 'SKU or Barcode parameter is required']);
}
?>