<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once 'auth_middleware.php';

// Authenticate dulu
$userData = authenticate();

include "../../config/koneksi.php";


// Terima input JSON
$input = json_decode(file_get_contents("php://input"), true);

$ad_mclient_key = $input["ad_mclient_key"] ?? null;
$ad_morg_key = $input["ad_morg_key"] ?? null;
$ad_muser_key = $input["ad_muser_key"] ?? $userData['user_id'] ?? null;
$salesdate = $input["salesdate"] ?? null;
$remark = $input["remark"] ?? "";
$postby = $input["postby"] ?? $userData['username'] ?? "SYSTEM";

// Validasi input
if (empty($ad_mclient_key) || empty($ad_morg_key) || empty($ad_muser_key) || empty($salesdate)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter tidak lengkap"
    ]);
    exit;
}

// Validasi format tanggal (DD-Mon-YY)
if (!preg_match('/^[0-9]{2}-[A-Za-z]{3}-[0-9]{2}$/', $salesdate)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Format tanggal harus DD-Mon-YY (contoh: 24-Feb-26)"
    ]);
    exit;
}

try {
    // Panggil function proc_pos_dshopsales_insert
    $sql = "SELECT * FROM proc_pos_dshopsales_insert(
        :p_ad_mclient_key,
        :p_ad_morg_key,
        :p_ad_muser_key,
        :p_salesdate,
        :p_remark,
        :p_postby
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_ad_mclient_key", $ad_mclient_key);
    $stmt->bindParam(":p_ad_morg_key", $ad_morg_key);
    $stmt->bindParam(":p_ad_muser_key", $ad_muser_key);
    $stmt->bindParam(":p_salesdate", $salesdate);
    $stmt->bindParam(":p_remark", $remark);
    $stmt->bindParam(":p_postby", $postby);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    // Decode JSON data
    $data = $o_data ? json_decode($o_data, true) : null;
    
    if ($o_message == "success") {
        echo json_encode([
            "status" => "SUCCESS",
            "message" => "Tutup harian berhasil",
            "data" => $data ? $data[0] : null
        ]);
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => $o_message ?: "Gagal tutup harian"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>