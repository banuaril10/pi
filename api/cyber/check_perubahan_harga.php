<?php
include "../../config/koneksi.php";

function get_data_api($url)
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

// Cek parameter tanggal

$tanggal = date("Y-m-d");
$api_url = $base_url . "/store/price/get_price_change.php?tanggal=" . urlencode($tanggal);

// Ambil data dari API
$response = get_data_api($api_url);
$data_api = json_decode($response, true);

// Validasi response
if (!$data_api || !is_array($data_api)) {
    echo json_encode([
        "status" => "FAILED",
        "message" => "Gagal mengambil data dari API"
    ]);
    exit;
}

// Ambil hanya data SKU
$sku_list = array_map(function ($item) {
    return $item['sku'];
}, $data_api);

// Kembalikan JSON hanya SKU saja
echo json_encode([
    "status" => "OK",
    "tanggal" => $tanggal,
    "jumlah" => count($sku_list),
    "data" => $sku_list
]);
?>