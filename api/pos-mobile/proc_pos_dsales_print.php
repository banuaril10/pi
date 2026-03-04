<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../../config/koneksi.php";

// Terima input JSON
$input = json_decode(file_get_contents("php://input"), true);

$billno = $input["billno"] ?? null;

// Validasi input
if (empty($billno)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter billno wajib diisi"
    ]);
    exit;
}

try {
    // Panggil function proc_pos_dsales_print
    $sql = "SELECT * FROM proc_pos_dsales_print(
        :p_billno
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_billno", $billno);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_data_header = $result["o_data_header"] ?? null;
    $o_data_line = $result["o_data_line"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    // Decode JSON data
    $header = $o_data_header ? json_decode($o_data_header, true) : [];
    $line = $o_data_line ? json_decode($o_data_line, true) : [];
    
    if ($o_message == "success") {
        echo json_encode([
            "o_data_header" => $header,
            "o_data_line" => $line,
            "o_message" => $o_message
        ]);
    } else {
        echo json_encode([
            "o_data_header" => [],
            "o_data_line" => [],
            "o_message" => $o_message ?: "Struk tidak ditemukan"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "o_data_header" => [],
        "o_data_line" => [],
        "o_message" => "Database error: " . $e->getMessage()
    ]);
}
?>