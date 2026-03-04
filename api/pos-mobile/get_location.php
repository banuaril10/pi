<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

include "../../config/koneksi.php";

try {
    // Ambil data lokasi (asumsi hanya 1 row)
    $sql = "SELECT 
                ad_morg_key as id,
                ad_mclient_key,
                name,
                description,
                npwp,
                ad_org_id,
                parent_key,
                orgcode,
                address,
                email,
                phone,
                value,
                ppn,
                address1,
                address2,
                address3,
                addressdonasi,
                note1,
                note2,
                note3,
                isqty
            FROM ad_morg 
            WHERE isactived = 'Y'
            LIMIT 1";
    
    $stmt = $connec->prepare($sql);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo json_encode([
            "status" => "SUCCESS",
            "message" => "Data ditemukan",
            "data" => $result
        ]);
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => "Data tidak ditemukan"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>