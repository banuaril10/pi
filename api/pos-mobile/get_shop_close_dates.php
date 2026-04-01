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

$ad_mclient_key = $input["ad_mclient_key"] ?? $userData['cln'] ?? null;
$ad_morg_key = $input["ad_morg_key"] ?? $userData['org'] ?? null;
$ad_muser_key = $input["ad_muser_key"] ?? $userData['user_id'] ?? null;

// Validasi input
if (empty($ad_mclient_key) || empty($ad_morg_key) || empty($ad_muser_key)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter tidak lengkap"
    ]);
    exit;
}

try {
    // Panggil function proc_pos_dcashierbalance_shopclose_check
    $sql = "SELECT * FROM proc_pos_dcashierbalance_shopclose_check(
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
    
    // Decode JSON data
    $data = $o_data ? json_decode($o_data, true) : [];
    
    if ($o_message == "Confirm") {
        // Format data untuk dropdown
        $formattedData = [];
        foreach ($data as $item) {
            $formattedData[] = [
                "id" => $item['id'],
                "text" => $item['salesdate'] . " - " . $item['balanceamount'] // Format: "24-Feb-26 - 1,000,000"
            ];
        }
        
        echo json_encode([
            "status" => "SUCCESS",
            "message" => "Data ditemukan",
            "data" => $formattedData
        ]);
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => $o_message ?: "Tidak ada data",
            "data" => []
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>