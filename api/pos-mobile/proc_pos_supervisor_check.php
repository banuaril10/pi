<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../../config/koneksi.php";

// Terima input JSON
$input = json_decode(file_get_contents("php://input"), true);

$f1 = $input["f1"] ?? null; // ad_muser_key (supervisor id)
$f2 = $input["f2"] ?? null; // gen id
$f3 = $input["f3"] ?? null; // gen password (hasil perhitungan)

// Validasi input
if (empty($f1) || empty($f2) || empty($f3)) {
    echo json_encode([
        "result" => "Error: Parameter tidak lengkap"
    ]);
    exit;
}

try {
    // Panggil function proc_pos_supervisor_check
    $sql = "SELECT * FROM proc_pos_supervisor_check(
        :p_ad_muser_key,
        :p_genid,
        :p_genpassword
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_ad_muser_key", $f1);
    $stmt->bindParam(":p_genid", $f2);
    $stmt->bindParam(":p_genpassword", $f3);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    // Response sesuai dengan yang diharapkan Android
    if ($o_message == "Confirm") {
        echo json_encode([
            "result" => "Confirm"
        ]);
    } else {
        echo json_encode([
            "result" => $o_message ?: "PASSWORD SALAH !!"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "result" => "Database error: " . $e->getMessage()
    ]);
}
?>