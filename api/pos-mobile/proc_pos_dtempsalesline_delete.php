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
$p_pos_dtempsalesline_key = $input["pos_dtempsalesline_key"] ?? null;
$p_pos_mcashier_key = $input["pos_mcashier_key"] ?? null;
$p_supervisor_id = $input["supervisor_id"] ?? null;

// Validasi input
if (empty($p_pos_dtempsalesline_key) || empty($p_pos_mcashier_key) || empty($p_supervisor_id)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter tidak lengkap"
    ]);
    exit;
}

try {
    // Panggil function proc_pos_dtempsalesline_delete
    $sql = "SELECT * FROM proc_pos_dtempsalesline_delete(
        :p_pos_dtempsalesline_key,
        :p_pos_mcashier_key,
        :p_supervisor_id
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_pos_dtempsalesline_key", $p_pos_dtempsalesline_key);
    $stmt->bindParam(":p_pos_mcashier_key", $p_pos_mcashier_key);
    $stmt->bindParam(":p_supervisor_id", $p_supervisor_id);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    // Decode JSON data
    $data = $o_data ? json_decode($o_data, true) : null;
    
    if ($o_message == "success") {
        echo json_encode([
            "result" => "success",
            "data" => $data ? $data[0] : null
        ]);
    } else {
        echo json_encode([
            "result" => "error",
            "message" => $o_message ?: "Gagal menghapus item"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "result" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>