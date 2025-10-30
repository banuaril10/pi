<?php
include "../../../config/koneksi.php";
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// Ambil parameter header_id dari query string
$header_id = isset($_GET['header_id']) ? intval($_GET['header_id']) : 0;

if ($header_id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Parameter header_id tidak valid'
    ]);
    exit;
}

try {
    $stmt = $connec->prepare("SELECT id,sku FROM price_tag_items WHERE header_id = :hid ORDER BY id DESC");
    $stmt->execute(['hid' => $header_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($rows) {
        echo json_encode([
            'success' => true,
            'data' => $rows
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'data' => [],
            'message' => 'Tidak ada item untuk header_id ini'
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>