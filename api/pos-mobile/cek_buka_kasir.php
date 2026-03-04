<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../../config/koneksi.php";

// Terima input JSON
$input = json_decode(file_get_contents("php://input"), true);

$ad_muser_key = $input["ad_muser_key"] ?? null;

// Validasi input
if (empty($ad_muser_key)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter ad_muser_key wajib diisi"
    ]);
    exit;
}

try {
    // Panggil function proc_pos_dcashierbalance_insert_check
    $sql = "SELECT * FROM proc_pos_dcashierbalance_insert_check(
        :p_ad_muser_key
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_ad_muser_key", $ad_muser_key);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    if ($o_message == "Confirm") {
        echo json_encode([
            "status" => "SUCCESS",
            "message" => "Boleh buka kasir",
            "can_open" => true
        ]);
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => $o_message ?: "Tidak bisa buka kasir",
            "can_open" => false
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage(),
        "can_open" => false
    ]);
}
?>