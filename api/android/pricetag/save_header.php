<?php
include "../../../config/koneksi.php";
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

// Debug mode ON
error_reporting(E_ALL);
ini_set('display_errors', 1);

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'JSON kosong atau invalid', 'header_id' => null]);
    exit;
}

$header_number = $data['header_number'] ?? '';
$description = $data['description'] ?? '';
$created_by = $data['created_by'] ?? '';

if (empty($header_number) || empty($created_by)) {
    echo json_encode(['success' => false, 'message' => 'Header number / created_by kosong', 'header_id' => null]);
    exit;
}

try {
    $stmt = $connec->prepare("
        INSERT INTO price_tag_headers (header_number, created_by, created_at, status, total_items, description)
        VALUES (:hn, :cb, NOW(), 'DRAFT', 0, :desc)
    ");
    $stmt->execute(['hn' => $header_number, 'cb' => $created_by, 'desc' => $description]);

    $header_id = $connec->lastInsertId();
    echo json_encode(['success' => true, 'message' => 'Header created', 'header_id' => (int) $header_id]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error SQL: ' . $e->getMessage(), 'header_id' => null]);
}
?>