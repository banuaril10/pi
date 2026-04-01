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

$ad_muser_key = $input["ad_muser_key"] ?? null;
$status = $input["status"] ?? "RUNNING"; // Default RUNNING

// Validasi input
if (empty($ad_muser_key)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter ad_muser_key wajib diisi"
    ]);
    exit;
}

try {
    // Panggil function proc_pos_dcashierbalance_bycashier_get
    $sql = "SELECT * FROM proc_pos_dcashierbalance_bycashier_get(
        :p_ad_muser_key,
        :p_status
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_ad_muser_key", $ad_muser_key);
    $stmt->bindParam(":p_status", $status);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    // Decode JSON data
    $data = $o_data ? json_decode($o_data, true) : [];
    
    // Tentukan status berdasarkan pesan dari function
    $responseStatus = "SUCCESS";
    $responseMessage = $o_message;
    
    // Jika data kosong, tetap return success tapi dengan data kosong
    if (empty($data)) {
        echo json_encode([
            "status" => "SUCCESS",
            "message" => $o_message ?: "Tidak ada data cashier balance",
            "data" => null,
            "cashier_status" => $o_message
        ]);
    } else {
        // Ambil data pertama (karena array)
        $balanceData = $data[0];
        
        echo json_encode([
            "status" => "SUCCESS",
            "message" => $o_message,
            "data" => [
                "pos_dcashierbalance_key" => $balanceData['id'] ?? null,
                "balanceamount" => $balanceData['balanceamount'] ?? "0",
                "balanceamountvalue" => $balanceData['balanceamountvalue'] ?? 0,
                "salesamount" => $balanceData['salesamount'] ?? "0",
                "salescashamount" => $balanceData['salescashamount'] ?? "0",
                "salesdebitamount" => $balanceData['salesdebitamount'] ?? "0",
                "salescreditamount" => $balanceData['salescreditamount'] ?? "0",
                "totalcashamount" => $balanceData['totalcashamount'] ?? "0",
                "startdate" => $balanceData['startdate'] ?? null,
                "enddate" => $balanceData['enddate'] ?? null,
                "salesdate" => $balanceData['salesdate'] ?? null,
                "intbill" => $balanceData['intbill'] ?? 0
            ],
            "cashier_status" => $o_message
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>