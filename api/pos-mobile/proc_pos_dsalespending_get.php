<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
require_once 'auth_middleware.php';

// Authenticate dulu
$userData = authenticate();
include "../../config/koneksi.php";

// Terima input JSON
$input = json_decode(file_get_contents("php://input"), true);

// Mapping parameter sesuai dengan function
$ad_mclient_key = $input["p_ad_mclient_key"] ?? null;
$ad_morg_key = $input["p_ad_morg_key"] ?? null;
$ad_muser_key = $input["p_ad_muser_key"] ?? null;

// Validasi input wajib
if (empty($ad_mclient_key) || empty($ad_morg_key) || empty($ad_muser_key)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter tidak lengkap"
    ]);
    exit;
}

try {
    // Panggil function proc_pos_dsalespending_get
    $sql = "SELECT * FROM proc_pos_dsalespending_get(
        :p_ad_mclient_key,
        :p_ad_morg_key,
        :p_ad_muser_key
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_ad_mclient_key", $ad_mclient_key);
    $stmt->bindParam(":p_ad_morg_key", $ad_morg_key);
    $stmt->bindParam(":p_ad_muser_key", $ad_muser_key);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    // Parse JSON dari o_data
    $dataArray = json_decode($o_data, true);
    
    // Jika o_data null atau empty array, berarti tidak ada pending
    if (empty($dataArray)) {
        echo json_encode([
            "status" => "SUCCESS",
            "message" => "Tidak ada transaksi pending",
            "data" => []
        ]);
    } else {
        echo json_encode([
            "status" => "SUCCESS",
            "message" => $o_message,
            "data" => $dataArray
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>