<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

include "../../config/koneksi.php";

try {
    // Query untuk mengambil IP Public dari kolom note3 di tabel ad_morg
    $sql = "SELECT website as ip_public FROM ad_morg LIMIT 1";
    
    $stmt = $connec->prepare($sql);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && !empty($result['ip_public'])) {
        echo json_encode([
            "status" => "SUCCESS",
            "message" => "IP Public ditemukan",
            "data" => [
                "ip_public" => $result['ip_public']
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => "IP Public tidak ditemukan",
            "data" => null
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>