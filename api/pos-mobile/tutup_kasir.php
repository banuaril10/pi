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

$ad_mclient_key = $input["ad_mclient_key"] ?? null;
$ad_morg_key = $input["ad_morg_key"] ?? null;
$ad_muser_key = $input["ad_muser_key"] ?? null;
$pos_mcashier_key = $input["pos_mcashier_key"] ?? null;
$actualamount = $input["actualamount"] ?? "0";

// Validasi input
if (empty($ad_mclient_key) || empty($ad_morg_key) || empty($ad_muser_key) || empty($pos_mcashier_key)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter tidak lengkap"
    ]);
    exit;
}

// Sanitasi angka
function sanitizeNumber($value) {
    if ($value === null || $value === '') return "0";
    return preg_replace('/[^0-9\-]/', '', $value);
}

$actualamount = sanitizeNumber($actualamount);

try {
    // Panggil function proc_pos_dcashierbalance_close_update
    $sql = "SELECT * FROM proc_pos_dcashierbalance_close_update(
        :p_ad_mclient_key,
        :p_ad_morg_key,
        :p_ad_muser_key,
        :p_pos_mcashier_key,
        :p_actualamount
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_ad_mclient_key", $ad_mclient_key);
    $stmt->bindParam(":p_ad_morg_key", $ad_morg_key);
    $stmt->bindParam(":p_ad_muser_key", $ad_muser_key);
    $stmt->bindParam(":p_pos_mcashier_key", $pos_mcashier_key);
    $stmt->bindParam(":p_actualamount", $actualamount);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    if ($o_message == "success") {
        echo json_encode([
            "status" => "SUCCESS",
            "message" => "Berhasil tutup kasir"
        ]);
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => $o_message ?: "Gagal tutup kasir"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>