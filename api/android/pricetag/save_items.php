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
    // Cek apakah kombinasi header_id + sku sudah ada
    $check = $connec->prepare("SELECT COUNT(*) FROM price_tag_items WHERE header_id = :hid AND sku = :sku");
    $check->execute(['hid' => $header_id, 'sku' => $sku]);
    $exists = $check->fetchColumn();

    if ($exists > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Item dengan SKU ini sudah ada pada header yang sama'
        ]);
        exit;
    }

    // Jika belum ada, baru insert
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