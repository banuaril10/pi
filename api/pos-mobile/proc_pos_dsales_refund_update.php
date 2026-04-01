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

// Mapping parameter
$posMcashierKey = $input["p_pos_mcashier_key"] ?? null;
$newBillno = $input["p_newbillno"] ?? null;
$refundBillno = $input["p_refundbillno"] ?? null;
$userKey = $input["p_ad_muser_key"] ?? null;
$supervisorName = $input["p_supervisoname"] ?? $userData['username'] ?? 'SYSTEM';

// Validasi input
if (empty($posMcashierKey) || empty($newBillno) || empty($refundBillno) || empty($userKey)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter tidak lengkap"
    ]);
    exit;
}

try {
    // Panggil function
    $sql = "SELECT * FROM proc_pos_dsales_refund_update(
        :p_pos_mcashier_key,
        :p_newbillno,
        :p_refundbillno,
        :p_ad_muser_key,
        :p_supervisoname
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_pos_mcashier_key", $posMcashierKey);
    $stmt->bindParam(":p_newbillno", $newBillno);
    $stmt->bindParam(":p_refundbillno", $refundBillno);
    $stmt->bindParam(":p_ad_muser_key", $userKey);
    $stmt->bindParam(":p_supervisoname", $supervisorName);
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