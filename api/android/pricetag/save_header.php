<?php
include "../../../config/koneksi.php";

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

$input = file_get_contents("php://input");
$data = json_decode($input, true);

$header_number = isset($data['header_number']) ? $data['header_number'] : '';
$created_by = isset($data['created_by']) ? $data['created_by'] : '';

if (empty($header_number) || empty($created_by)) {
    echo json_encode(["success" => false, "message" => "Data kosong"]);
    exit;
}

// Simpan ke DB
$stmt = $pdo->prepare("INSERT INTO price_tag_headers (header_number, created_by, created_at, status, total_items) VALUES (?, ?, NOW(), 'DRAFT', 0)");
$success = $stmt->execute([$header_number, $created_by]);

if ($success) {
    $header_id = $pdo->lastInsertId();
    echo json_encode([
        "success" => true,
        "header_id" => $header_id
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal insert ke database"
    ]);
}
?>