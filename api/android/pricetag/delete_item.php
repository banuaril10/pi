<?php
include "../../../config/koneksi.php";
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Ambil JSON dari body
$input = file_get_contents("php://input");
$data = json_decode($input, true);

$item_id = isset($data['id']) ? intval($data['id']) : 0;

if ($item_id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Parameter id tidak valid'
    ]);
    exit;
}

try {
    $stmt = $connec->prepare("DELETE FROM price_tag_items WHERE id = :id");
    $stmt->execute(['id' => $item_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Item berhasil dihapus'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Item tidak ditemukan'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>