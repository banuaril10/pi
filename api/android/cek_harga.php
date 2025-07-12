<?php
include "../../config/koneksi.php";

header('Content-Type: application/json');

// Mendapatkan SKU atau barcode dari parameter GET
if (isset($_GET['sku']) || isset($_GET['barcode'])) {
    $sku_or_barcode = isset($_GET['sku']) ? $_GET['sku'] : $_GET['barcode'];

    // Query untuk mendapatkan harga reguler dari tabel pos_mproduct berdasarkan sku, barcode, barcode1-4
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

        // Cek apakah diskon ditemukan
        if ($discount) {
            $discountedPrice = $regularPrice - $discount['discount'];
            $response = [
                'sku' => $sku,
                'name' => $name,
                'regular_price' => $regularPrice,
                'discount' => $discount['discount'],
                'discounted_price' => $discountedPrice,
                'valid_from' => $discount['fromdate'],
                'valid_to' => $discount['todate']
            ];
        } else {
            $response = [
                'sku' => $sku,
                'name' => $name,
                'regular_price' => $regularPrice,
                'discount' => null,
                'discounted_price' => null,
                'valid_from' => null,
                'valid_to' => null
            ];
        }

        echo json_encode($response);

    } else {
        echo json_encode(['error' => 'Product not found']);
    }

} else {
    echo json_encode(['error' => 'SKU or Barcode parameter is required']);
}
?>