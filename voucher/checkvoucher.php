<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

include "../../config/koneksi.php";

/* =========================
   1️⃣ AMBIL INPUT
   ========================= */
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['vouchercode'])) {
    echo json_encode([
        'valid' => false,
        'message' => 'Voucher code required'
    ]);
    exit;
}

$voucherCode = trim($input['vouchercode']);

/* =========================
   2️⃣ HIT KE SERVER
   ========================= */
$serverUrl = $base_url . "/store/voucher/check_voucher_used.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3";

$payload = json_encode([
    "vouchercode" => $voucherCode
]);

$ch = curl_init($serverUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_TIMEOUT        => 10
]);

$response = curl_exec($ch);

if ($response === false) {
    echo json_encode([
        'valid' => false,
        'message' => 'Gagal koneksi ke server, url : ' . $serverUrl
    ]);
    curl_close($ch);
    exit;
}

curl_close($ch);

$server = json_decode($response, true);

/* =========================
   3️⃣ VALIDASI RESPONSE
   ========================= */
if (!$server || ($server['valid'] ?? false) !== true) {
    echo json_encode([
        'valid' => false,
        'message' => $server['message'] ?? 'Voucher tidak valid'
    ]);
    exit;
}

/* =========================
   4️⃣ RESPONSE OK
   ========================= */
echo json_encode([
    'valid'   => true,
    'message' => 'Voucher ditemukan',
    'data'    => [
        'voucher_code' => $server['data']['voucher_code'],
        'toko'         => $server['data']['toko'],
        'kasir'        => $server['data']['kasir'],
        'tanggal'      => $server['data']['tanggal'],
        'jam'          => $server['data']['jam']
    ]
]);
