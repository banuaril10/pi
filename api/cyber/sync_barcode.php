<?php
include "../../config/koneksi.php";

// Ambil ID store yang aktif
$ll = "SELECT * FROM ad_morg WHERE isactived = 'Y'";
$query = $connec->query($ll);

$idstore = null;
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}

// Fungsi GET untuk ambil data barcode dari endpoint
function get($url)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

// Ambil data dari endpoint
$url = $base_url . '/store/items/get_barcode.php?idstore=' . $idstore;
$hasil = get($url);
$j_hasil = json_decode($hasil, true);

// Proses update barcode
$items_updated = 0;
foreach ($j_hasil as $key => $value) {
    $sku = $value['sku'];
    $barcode = $value['barcode'] ?? '';
    $barcode1 = $value['barcode1'] ?? '';
    $barcode2 = $value['barcode2'] ?? '';
    $barcode3 = $value['barcode3'] ?? '';
    $barcode4 = $value['barcode4'] ?? '';

    $update = "
        UPDATE pos_mproduct SET 
            barcode = '" . $barcode . "', 
            barcode1 = '" . $barcode1 . "', 
            barcode2 = '" . $barcode2 . "', 
            barcode3 = '" . $barcode3 . "', 
            barcode4 = '" . $barcode4 . "', 
            postdate = '" . date('Y-m-d H:i:s') . "' 
        WHERE sku = '" . $sku . "'
    ";

    $result = $connec->exec($update);
    if ($result) {
        $items_updated++;
    }
}

// Output JSON
$json = array(
    "status" => "SUCCESS",
    "message" => $items_updated . " barcode(s) updated successfully",
    "data" => $items_updated
);

echo json_encode($json);
?>