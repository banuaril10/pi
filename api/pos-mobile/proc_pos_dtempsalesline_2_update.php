<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../../config/koneksi.php";

// Terima input JSON
$input = json_decode(file_get_contents("php://input"), true);

// Mapping parameter sesuai dengan function
$pos_dtempsalesline_key = $input["pos_dtempsalesline_key"] ?? null;
$qty = $input["qty"] ?? null;
$approvedby = $input["approvedby"] ?? "SYSTEM";

// Validasi input wajib
if (empty($pos_dtempsalesline_key) || empty($qty)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter pos_dtempsalesline_key dan qty wajib diisi"
    ]);
    exit;
}

// Validasi qty harus angka positif
if (!is_numeric($qty) || $qty <= 0) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Qty harus berupa angka positif"
    ]);
    exit;
}

try {
    // Panggil function proc_pos_dtempsalesline_2_update
    $sql = "SELECT * FROM proc_pos_dtempsalesline_2_update(
        :p_pos_dtempsalesline_key,
        :p_qty,
        :p_approvedby
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_pos_dtempsalesline_key", $pos_dtempsalesline_key);
    $stmt->bindParam(":p_qty", $qty);
    $stmt->bindParam(":p_approvedby", $approvedby);
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
            "message" => "Berhasil mengupdate qty",
            "data" => $data ? $data[0] : null
        ]);
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => $o_message ?: "Gagal mengupdate qty"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>