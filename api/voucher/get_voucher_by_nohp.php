<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

include "../../config/koneksi.php";

// Ambil idstore (hanya 1 store)
$ll = "select * from ad_morg where isactived = 'Y' LIMIT 1";
$query = $connec->query($ll);
$idstore = null;
if ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}

if (!$idstore) {
    echo json_encode([
        'valid' => false,
        'message' => 'Store tidak ditemukan'
    ]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$nohp = isset($input['nohp']) ? trim($input['nohp']) : '';

if (empty($nohp)) {
    echo json_encode([
        'valid' => false,
        'message' => 'Nomor HP tidak boleh kosong'
    ]);
    exit;
}

// Bersihkan nomor HP
$cleaned = preg_replace('/[^0-9]/', '', $nohp);

// Validasi format nomor HP
if (!preg_match('/^(08|62)[0-9]{8,11}$/', $cleaned)) {
    echo json_encode([
        'valid' => false,
        'message' => 'Format nomor HP tidak valid'
    ]);
    exit;
}

$today = date('Y-m-d');

/* =========================
   1️⃣ CEK KE SERVER (FINAL)
   ========================= */
$serverUrl = $base_url . "/store/voucher/get_voucher_by_nohp.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3";

$payload = json_encode([
    "nohp" => $cleaned,
    "idstore" => $idstore
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
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200 || !$response) {
    echo json_encode([
        'valid' => false,
        'message' => 'Gagal koneksi ke server pusat'
    ]);
    exit;
}

$server = json_decode($response, true);

if (!$server || ($server['valid'] ?? false) !== true) {
    echo json_encode([
        'valid' => false,
        'message' => $server['message'] ?? 'Tidak ditemukan voucher aktif untuk nomor HP ini'
    ]);
    exit;
}

/* =========================
   2️⃣ CEK DI LOKAL (CACHE)
   ========================= */
$sql = "SELECT voucher_code, voucher_amount, percent, valid_until 
        FROM pos_dvoucher 
        WHERE nohp = ? 
        AND status = 'NEW' 
        AND useddate IS NULL 
        AND valid_from <= ? 
        AND valid_until >= ?
        ORDER BY valid_until ASC 
        LIMIT 1";

$stmt = $connec->prepare($sql);
$stmt->execute([$cleaned, $today, $today]);
$voucher = $stmt->fetch(PDO::FETCH_ASSOC);

if ($voucher) {
    echo json_encode([
        'valid' => true,
        'voucher_code' => $voucher['voucher_code'],
        'voucher_amount' => $voucher['voucher_amount'],
        'percent' => $voucher['percent'],
        'valid_until' => $voucher['valid_until']
    ]);
} else {
    // Data dari server
    echo json_encode([
        'valid' => true,
        'voucher_code' => $server['voucher_code'],
        'voucher_amount' => $server['voucher_amount'],
        'percent' => $server['percent'],
        'valid_until' => $server['valid_until']
    ]);
}
?>