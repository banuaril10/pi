<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include "../../config/koneksi.php";

$id = isset($_POST['id']) ? trim($_POST['id']) : '';
if ($id === '') {
    echo json_encode(['status' => 'FAILED', 'message' => 'id kosong']);
    exit;
}

try {
    $stmt = $connec->prepare(
        "INSERT INTO log_print_negatif (id, sales_date, created_at)
         VALUES (:id, CURRENT_DATE, NOW())"
    );
    $stmt->execute([':id' => $id]);
    echo json_encode(['status' => 'OK']);
} catch (Exception $e) {
    echo json_encode(['status' => 'FAILED', 'message' => $e->getMessage()]);
}