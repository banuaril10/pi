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
$cardno = $input["cardno"] ?? "";
$approvecode = $input["approvecode"] ?? "";
$pos_medc_key = $input["pos_medc_key"] ?? null;
$pos_mbank_key = $input["pos_mbank_key"] ?? null;
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

// Validasi khusus credit
if (empty($cardno)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Nomor kartu kredit wajib diisi"
    ]);
    exit;
}

if (empty($approvecode)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Kode approve wajib diisi"
    ]);
    exit;
}

if (empty($pos_medc_key)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "EDC tidak valid"
    ]);
    exit;
}

if (empty($pos_mbank_key)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Bank tidak valid"
    ]);
    exit;
}

// Sanitasi angka
function sanitizeNumber($value) {
    if ($value === null || $value === '') return "0";
    return preg_replace('/[^0-9\-]/', '', $value);
}

$billamount = sanitizeNumber($billamount);
$point = sanitizeNumber($point);

try {
    // Panggil function proc_pos_dsales_credit_insert
    $sql = "SELECT * FROM proc_pos_dsales_credit_insert(
        :p_ad_mclient_key,
        :p_ad_morg_key,
        :p_serialno,
        :p_pos_mcashier_key,
        :p_billno,
        :p_billamount,
        :p_cardno,
        :p_approvecode,
        :p_pos_medc_key,
        :p_pos_mbank_key,
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
    $stmt->bindParam(":p_cardno", $cardno);
    $stmt->bindParam(":p_approvecode", $approvecode);
    $stmt->bindParam(":p_pos_medc_key", $pos_medc_key);
    $stmt->bindParam(":p_pos_mbank_key", $pos_mbank_key);
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
            "message" => "Pembayaran Credit berhasil",
            "data" => [
                "new_billno" => $o_data,
                "old_billno" => $billno,
                "cardno" => substr($cardno, -4) // Hanya tampilkan 4 digit terakhir
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => $o_message ?: "Gagal memproses pembayaran Credit"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>