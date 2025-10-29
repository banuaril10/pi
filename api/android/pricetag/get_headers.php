<?php
include "../../../config/koneksi.php";
header('Content-Type: application/json');

try {
    $stmt = $connec->query("
        SELECT 
            id,
            header_number,
            created_by,
            created_at,
            status,
            total_items
        FROM price_tag_headers
        ORDER BY created_at DESC
    ");

    $headers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $headers
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error fetching headers",
        "error" => $e->getMessage()
    ]);
}
?>