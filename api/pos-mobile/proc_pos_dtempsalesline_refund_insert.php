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

// Mapping parameter sesuai function
$ad_mclient_key = $input["p_ad_mclient_key"] ?? null;
$ad_morg_key = $input["p_ad_morg_key"] ?? null;
$pos_mcashier_key = $input["p_pos_mcashier_key"] ?? null;
$billno = $input["p_billno"] ?? null;
$pos_dsalesline_key = $input["p_pos_dsalesline_key"] ?? null;
$qty = $input["p_qty"] ?? "1";
$approveby = $input["p_approveby"] ?? $userData['username'] ?? 'SYSTEM';
$ad_muser_key = $input["p_ad_muser_key"] ?? null;
$postby = $input["p_postby"] ?? $userData['username'] ?? 'SYSTEM';

// Validasi input wajib
if (empty($ad_mclient_key) || empty($ad_morg_key) || empty($pos_mcashier_key) || 
    empty($billno) || empty($pos_dsalesline_key) || empty($ad_muser_key)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter tidak lengkap"
    ]);
    exit;
}

try {
    // Panggil function
    $sql = "SELECT * FROM proc_pos_dtempsalesline_refund_insert(
        :p_ad_mclient_key,
        :p_ad_morg_key,
        :p_pos_mcashier_key,
        :p_billno,
        :p_pos_dsalesline_key,
        :p_qty,
        :p_approveby,
        :p_ad_muser_key,
        :p_postby
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_ad_mclient_key", $ad_mclient_key);
    $stmt->bindParam(":p_ad_morg_key", $ad_morg_key);
    $stmt->bindParam(":p_pos_mcashier_key", $pos_mcashier_key);
    $stmt->bindParam(":p_billno", $billno);
    $stmt->bindParam(":p_pos_dsalesline_key", $pos_dsalesline_key);
    $stmt->bindParam(":p_qty", $qty);
    $stmt->bindParam(":p_approveby", $approveby);
    $stmt->bindParam(":p_ad_muser_key", $ad_muser_key);
    $stmt->bindParam(":p_postby", $postby);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $o_data = $result["o_data"] ?? null;
        $o_message = $result["o_message"] ?? "";
        
        if ($o_message == "success") {
            $dataArray = json_decode($o_data, true);
            
            echo json_encode([
                "status" => "SUCCESS",
                "message" => "success",
                "data" => $dataArray
            ]);
        } else {
            echo json_encode([
                "status" => "ERROR",
                "message" => $o_message ?: "Gagal proses refund"
            ]);
        }
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => "Tidak ada response dari database"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>