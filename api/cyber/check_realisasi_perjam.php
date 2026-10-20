<?php
include "../../config/koneksi.php";
header('Content-type: application/json');

$connec->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$salesDate = trim((string)($_GET['sales_date'] ?? ''));

if ($salesDate === '') {
    echo json_encode(['status'=>'FAILED','message'=>'sales_date wajib','exists'=>false]);
    exit;
}

try {
    $stmt = $connec->prepare("
        SELECT COUNT(*) FROM in_target_pola_perjam
        WHERE sales_date = :tgl
    ");
    $stmt->execute([':tgl' => $salesDate]);
    $count = (int)$stmt->fetchColumn();

    echo json_encode([
        'status'  => 'OK',
        'exists'  => $count > 0,
        'total'   => $count,
    ]);
} catch (Throwable $e) {
    echo json_encode([
        'status'  => 'FAILED',
        'message' => $e->getMessage(),
        'exists'  => false,
    ]);
}