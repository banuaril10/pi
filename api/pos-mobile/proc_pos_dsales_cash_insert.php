<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../../config/koneksi.php";

// Terima input JSON
$input = json_decode(file_get_contents("php://input"), true);

// Mapping parameter sesuai dengan function
$ad_mclient_key = $input["ad_mclient_key"] ?? null;
$ad_morg_key = $input["ad_morg_key"] ?? null;
$serialno = $input["serialno"] ?? "0";
$pos_mcashier_key = $input["pos_mcashier_key"] ?? null;
$billno = $input["billno"] ?? null;
$billamount = $input["billamount"] ?? "0";
$paygiven = $input["paygiven"] ?? "0";
$donasiamount = $input["donasiamount"] ?? "0";
$membercard = $input["membercard"] ?? "";
$point = $input["point"] ?? "0";
$postby = $input["postby"] ?? "SYSTEM";

// Validasi input wajib
if (empty($ad_mclient_key) || empty($ad_morg_key) || empty($pos_mcashier_key) || empty($billno)) {
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

$billamount = sanitizeNumber($billamount);
$paygiven = sanitizeNumber($paygiven);
$donasiamount = sanitizeNumber($donasiamount);
$point = sanitizeNumber($point);

try {
    // Panggil function proc_pos_dsales_cash_insert
    $sql = "SELECT * FROM proc_pos_dsales_cash_insert(
        :p_ad_mclient_key,
        :p_ad_morg_key,
        :p_serialno,
        :p_pos_mcashier_key,
        :p_billno,
        :p_billamount,
        :p_paygiven,
        :p_donasiamount,
        :p_membercard,
        :p_point,
        :p_postby
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_ad_mclient_key", $ad_mclient_key);
    $stmt->bindParam(":p_ad_morg_key", $ad_morg_key);
    $stmt->bindParam(":p_serialno", $serialno);
    $stmt->bindParam(":p_pos_mcashier_key", $pos_mcashier_key);
    $stmt->bindParam(":p_billno", $billno);
    $stmt->bindParam(":p_billamount", $billamount);
    $stmt->bindParam(":p_paygiven", $paygiven);
    $stmt->bindParam(":p_donasiamount", $donasiamount);
    $stmt->bindParam(":p_membercard", $membercard);
    $stmt->bindParam(":p_point", $point);
    $stmt->bindParam(":p_postby", $postby);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    if ($o_message == "success") {
        echo json_encode([
            "status" => "SUCCESS",
            "message" => "Pembayaran berhasil",
            "data" => [
                "new_billno" => $o_data,
                "old_billno" => $billno
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => $o_message ?: "Gagal memproses pembayaran"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>