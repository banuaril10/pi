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
    // Panggil function proc_pos_dsalespending_insert
    $sql = "SELECT * FROM proc_pos_dsalespending_insert(
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
    
    if ($o_message == "success") {
        // UBAH INI: kirim sebagai ARRAY dengan 1 elemen
        echo json_encode([
            "status" => "SUCCESS",
            "message" => "success",
            "data" => [  // INI TETAP ARRAY
                [
                    "tempbillno" => "TEMP#1",
                    "message" => "Transaksi berhasil dipending"
                ]
            ]
        ]);
    } else if ($o_message == "exists") {
        echo json_encode([
            "status" => "SUCCESS", 
            "message" => "exists",
            "data" => []  // KIRIM ARRAY KOSONG
        ]);
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => $o_message ?: "Gagal memproses pending"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>