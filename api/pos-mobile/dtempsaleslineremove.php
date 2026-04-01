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

$f1 = $input["f1"] ?? null; // pos_dtempsalesline_key
$f2 = $input["f2"] ?? null; // cashier id
$f3 = $input["f3"] ?? null; // supervisor id

// Validasi input
if (empty($f1) || empty($f2) || empty($f3)) {
    echo json_encode([
        "result" => "error",
        "message" => "Parameter tidak lengkap"
    ]);
    exit;
}

try {
    // Panggil function proc_pos_dtempsalesline_update dengan qty = 0 (void)
    // Sesuaikan dengan function yang ada
    $sql = "SELECT * FROM proc_pos_dtempsalesline_update(
        :p_pos_dtempsalesline_key,
        '0', -- qty = 0 untuk void
        '', -- memberid
        ''  -- promoid
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_pos_dtempsalesline_key", $f1);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    if ($o_message == "Success" || $o_message == "success") {
        $data = $o_data ? json_decode($o_data, true) : null;
        
        echo json_encode([
            "result" => "success",
            "data" => $data ? $data[0] : null
        ]);
    } else {
        echo json_encode([
            "result" => $o_message ?: "error"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "result" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>