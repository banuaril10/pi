<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include "../../config/koneksi.php";

try {
    $stmt = $connec->prepare(
        "SELECT created_at FROM log_print_negatif
         WHERE sales_date = CURRENT_DATE LIMIT 1"
    );
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'printed'    => $row ? true : false,
        'printed_at' => $row ? date('H:i:s', strtotime($row['created_at'])) : null,
    ]);
} catch (Exception $e) {
    echo json_encode(['printed' => false, 'error' => $e->getMessage()]);
}