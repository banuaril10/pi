<?php
include "../../../config/koneksi.php";
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

// Ambil JSON dari body
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Ambil data
$header_id = isset($data['header_id']) ? intval($data['header_id']) : 0;
$sku = isset($data['sku']) ? trim($data['sku']) : '';

if ($header_id <= 0 || empty($sku)) {
    echo json_encode([
        'success' => false,
        'message' => 'Data tidak lengkap (header_id atau sku kosong)'
    ]);
    exit;
}

try {
    $stmt = $connec->prepare("INSERT INTO price_tag_items (header_id, sku) VALUES (:hid, :sku)");
    $stmt->execute(['hid' => $header_id, 'sku' => $sku]);

    echo json_encode([
        'success' => true,
        'message' => 'Item berhasil disimpan'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>