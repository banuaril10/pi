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

$voucherCode  = trim($input['vouchercode']);
$totalAmount  = floatval($input['totalamount']);
$currentDate  = date('Y-m-d');

try {

    // 1. Ambil voucher berdasarkan kode
    $sql = "SELECT * FROM pos_dvoucher 
            WHERE voucher_code = ?
            LIMIT 1";
    $stmt = $connec->prepare($sql);
    $stmt->execute([$voucherCode]);
    $voucher = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Voucher tidak ditemukan
    if (!$voucher) {
        echo json_encode([
            'valid' => false,
            'message' => 'Voucher tidak ditemukan',
            'amount' => 0
        ]);
        exit;
    }

    // 3. Voucher sudah digunakan
    if ($voucher['useddate'] !== null || $voucher['status'] !== 'NEW') {
        echo json_encode([
            'valid' => false,
            'message' => 'Voucher sudah digunakan',
            'amount' => 0
        ]);
        exit;
    }

    // 4. Voucher belum berlaku
    if ($currentDate < $voucher['valid_from']) {
        echo json_encode([
            'valid' => false,
            'message' => 'Voucher belum berlaku',
            'amount' => 0
        ]);
        exit;
    }

    // 5. Voucher sudah kadaluarsa
    if ($currentDate > $voucher['valid_until']) {
        echo json_encode([
            'valid' => false,
            'message' => 'Voucher sudah kadaluarsa',
            'amount' => 0
        ]);
        exit;
    }

    // 6. Hitung nilai voucher
    $voucherAmount = 0;

    if (!empty($voucher['percent']) && $voucher['percent'] > 0) {
        $percent = floatval($voucher['percent']);
        $calculatedAmount = $totalAmount * ($percent / 100);

        if (!empty($voucher['voucher_amount']) && $voucher['voucher_amount'] > 0) {
            $voucherAmount = min($calculatedAmount, $voucher['voucher_amount']);
        } else {
            $voucherAmount = $calculatedAmount;
        }

        $voucherAmount = min($voucherAmount, $totalAmount);
    } else {
        $voucherAmount = min(floatval($voucher['voucher_amount']), $totalAmount);
    }

    // 7. Response sukses
    echo json_encode([
        'valid' => true,
        'message' => 'Voucher valid',
        'amount' => $voucherAmount,
        'voucher_data' => [
            'voucher_key'     => $voucher['pos_dvoucher_key'],
            'original_amount' => $voucher['voucher_amount'] ?? 0,
            'percent'         => $voucher['percent'] ?? 0,
            'valid_until'     => $voucher['valid_until']
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'valid' => false,
        'message' => 'Error database',
        'amount' => 0
    ]);
}
