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
$billno = $input["p_billno"] ?? null;
$pos_mcashier_key = $input["p_pos_mcashier_key"] ?? null;
$ad_muser_key = $input["p_ad_muser_key"] ?? null;
$postby = $input["p_postby"] ?? $userData['username'] ?? 'SYSTEM';

// Validasi input wajib
if (empty($billno) || empty($pos_mcashier_key) || empty($ad_muser_key)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter tidak lengkap"
    ]);
    exit;
}

try {
    // Panggil function proc_pos_dtempsalesline_recall
    $sql = "SELECT * FROM proc_pos_dtempsalesline_recall(
        :p_billno,
        :p_pos_mcashier_key,
        :p_ad_muser_key,
        :p_postby
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_billno", $billno);
    $stmt->bindParam(":p_pos_mcashier_key", $pos_mcashier_key);
    $stmt->bindParam(":p_ad_muser_key", $ad_muser_key);
    $stmt->bindParam(":p_postby", $postby);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    if ($o_message == "success") {
        // Parse o_data yang berupa JSON string
        $dataArray = json_decode($o_data, true);
        
        echo json_encode([
            "status" => "SUCCESS",
            "message" => "success",
            "data" => $dataArray
        ]);
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => $o_message ?: "Gagal recall pending"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>