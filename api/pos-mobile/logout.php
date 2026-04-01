<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include "../../config/koneksi.php";
require_once 'auth_middleware.php';

// Authenticate dulu
$userData = authenticate();

// Terima input JSON
$input = json_decode(file_get_contents("php://input"), true);

$ad_muser_key = $input["ad_muser_key"] ?? $userData['user_id'] ?? null;
$pos_mcashier_key = $input["pos_mcashier_key"] ?? null;

// Validasi input
if (empty($ad_muser_key)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter ad_muser_key wajib diisi"
    ]);
    exit;
}

try {
    // Panggil function proc_pos_logout_update
    $sql = "SELECT * FROM proc_pos_logout_update(
        :p_ad_muser_key,
        :p_pos_mcashier_key
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_ad_muser_key", $ad_muser_key);
    $stmt->bindParam(":p_pos_mcashier_key", $pos_mcashier_key);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    if ($o_message == "Success") {
        echo json_encode([
            "status" => "SUCCESS",
            "message" => "Logout berhasil"
        ]);
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => $o_message ?: "Logout gagal"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>