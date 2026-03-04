<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../../config/koneksi.php";

// Terima input JSON
$input = json_decode(file_get_contents("php://input"), true);

$has = $input["has"] ?? null;
$domain = $input["domain"] ?? null;
$locationid = $input["locationid"] ?? null;

// Validasi input
if (empty($has)) {
    echo json_encode([
        "status" => "FAILED",
        "message" => "Parameter has wajib diisi"
    ]);
    exit;
}

try {
    // Cek apakah device sudah terdaftar di tabel pos_mcashier
    $sql = "SELECT 
                code as has,
                name,
                ad_morg_key as locationid
            FROM pos_mcashier 
            WHERE code = :has AND isactived = '1'";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":has", $has);
    $stmt->execute();
    
    $device = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($device) {
        // Device sudah terdaftar
        echo json_encode([
            "status" => "OK",
            "message" => "Device sudah terdaftar",
            "data" => [
                "has" => $device['has'],
                "domain" => $domain ?: "",
                "locationid" => $device['locationid'],
                "name" => $device['name']
            ]
        ]);
    } else {
        // Device belum terdaftar
        echo json_encode([
            "status" => "FAILED",
            "message" => "Device belum terdaftar",
            "data" => null
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "FAILED",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>