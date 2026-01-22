<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

include "../../config/koneksi.php";

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['vouchercode']) || !isset($input['totalamount'])) {
    echo json_encode([
        'valid' => false,
        'message' => 'Data tidak lengkap',
        'amount' => 0
    ]);
    exit;
}

$voucherCode = trim($input['vouchercode']);
$totalAmount = floatval($input['totalamount']);

/* =========================
   1️⃣ CEK KE SERVER (FINAL)
   ========================= */
$serverUrl = $base_url . "/store/voucher/check_status.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3";

$payload = json_encode([
    "vouchercode" => $voucherCode
]);

$ch = curl_init($serverUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_TIMEOUT        => 5
]);

$response = curl_exec($ch);
curl_close($ch);

$server = json_decode($response, true);

if (!$server || ($server['valid'] ?? false) !== true) {
    echo json_encode([
        'valid' => false,
        'message' => $server['message'] ?? 'Gagal cek voucher ke server, periksa internet/intransit',
        'amount' => 0
    ]);
    exit;
}

/* =========================
   2️⃣ AMBIL DATA LOKAL (CACHE)
   ========================= */
$sql = "SELECT * FROM pos_dvoucher WHERE voucher_code = ? LIMIT 1";
$stmt = $connec->prepare($sql);
$stmt->execute([$voucherCode]);
$voucher = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$voucher) {
    echo json_encode([
        'valid' => false,
        'message' => 'Voucher belum tersinkron di lokal',
        'amount' => 0
    ]);
    exit;
}

/* =========================
   3️⃣ HITUNG DISKON
   ========================= */
$voucherAmount = 0;

if (!empty($voucher['percent']) && $voucher['percent'] > 0) {
    $calc = $totalAmount * ($voucher['percent'] / 100);
    $voucherAmount = $voucher['voucher_amount']
        ? min($calc, $voucher['voucher_amount'])
        : $calc;
} else {
    $voucherAmount = $voucher['voucher_amount'];
}

$voucherAmount = min($voucherAmount, $totalAmount);

/* =========================
   4️⃣ RESPONSE OK
   ========================= */
echo json_encode([
    'valid' => true,
    'message' => 'Voucher valid (server verified)',
    'amount' => $voucherAmount,
    'voucher_data' => [
        'voucher_key' => $server['data']['voucher_key'],
        'percent'     => $server['data']['percent'],
        'valid_until' => $server['data']['valid_until']
    ]
]);
