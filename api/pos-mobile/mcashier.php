<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../../config/koneksi.php";

// Ambil parameter dari request
$f1 = $_REQUEST['f1'] ?? null; // has (code device)

// Validasi input
if (empty($f1)) {
    echo json_encode([
        "data" => null,
        "message" => "Parameter f1 (has) wajib diisi"
    ]);
    exit;
}

try {
    // Panggil function proc_pos_mcashier_bycode_get
    // Catatan: function membutuhkan 3 parameter, tapi kita hanya punya has
    // Kita akan kirim null untuk p_ad_mclient_key dan p_ad_morg_key
    $sql = "SELECT * FROM proc_pos_mcashier_bycode_get(
        NULL, -- p_ad_mclient_key
        NULL, -- p_ad_morg_key
        :p_search
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_search", $f1);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    // Decode JSON data
    $data = $o_data ? json_decode($o_data, true) : null;
    
    echo json_encode([
        "data" => $data,
        "message" => $o_message
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        "data" => null,
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>