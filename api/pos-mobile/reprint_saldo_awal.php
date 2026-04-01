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

// Validasi input
if (empty($ad_muser_key)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter ad_muser_key wajib diisi"
    ]);
    exit;
}

try {
    // Panggil function proc_pos_dcashierbalance_print
    $sql = "SELECT * FROM proc_pos_dcashierbalance_print(
        :p_ad_muser_key
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_ad_muser_key", $ad_muser_key);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    // Decode JSON data
    $header = $o_data ? json_decode($o_data, true) : [];
    
    if ($o_message == "success") {
        if (!empty($header)) {
            echo json_encode([
                "o_data_header" => $header,
                "o_data_edc" => [],
                "o_data_category" => [],
                "o_message" => $o_message
            ]);
        } else {
            echo json_encode([
                "o_data_header" => [],
                "o_data_edc" => [],
                "o_data_category" => [],
                "o_message" => "Data saldo awal tidak ditemukan"
            ]);
        }
    } else {
        echo json_encode([
            "o_data_header" => [],
            "o_data_edc" => [],
            "o_data_category" => [],
            "o_message" => $o_message ?: "Gagal mengambil data saldo awal"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>