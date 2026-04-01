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

$ad_mclient_key = $input["ad_mclient_key"] ?? null;
$ad_morg_key = $input["ad_morg_key"] ?? null;
$ad_muser_key = $input["ad_muser_key"] ?? null;
$pos_mcashier_key = $input["pos_mcashier_key"] ?? null;
$pos_mshift_key = $input["pos_mshift_key"] ?? null;
$balanceamount = $input["balanceamount"] ?? "0";
$postby = $input["postby"] ?? "SYSTEM";

// Validasi input wajib
if (empty($ad_mclient_key) || empty($ad_morg_key) || empty($ad_muser_key) || empty($pos_mcashier_key)) {
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

$balanceamount = sanitizeNumber($balanceamount);

try {
    // Panggil function proc_pos_dcashierbalance_update
    $sql = "SELECT * FROM proc_pos_dcashierbalance_update(
        :p_ad_mclient_key,
        :p_ad_morg_key,
        :p_ad_muser_key,
        :p_pos_mcashier_key,
        :p_pos_mshift_key,
        :p_balanceamount,
        :p_postby
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_ad_mclient_key", $ad_mclient_key);
    $stmt->bindParam(":p_ad_morg_key", $ad_morg_key);
    $stmt->bindParam(":p_ad_muser_key", $ad_muser_key);
    $stmt->bindParam(":p_pos_mcashier_key", $pos_mcashier_key);
    $stmt->bindParam(":p_pos_mshift_key", $pos_mshift_key);
    $stmt->bindParam(":p_balanceamount", $balanceamount);
    $stmt->bindParam(":p_postby", $postby);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    if ($o_message == "success") {
        // Ambil data cashier balance yang baru
        $sqlBalance = "
            SELECT 
                pos_dcashierbalance_key,
                pos_mcashier_key,
                pos_mshift_key,
                TO_CHAR(startdate, 'YYYY-MM-DD HH24:MI:SS') as startdate,
                balanceamount,
                status
            FROM pos_dcashierbalance 
            WHERE ad_muser_key = :ad_muser_key 
                AND status = 'RUNNING'
                AND DATE(startdate) = CURRENT_DATE
            ORDER BY startdate DESC
            LIMIT 1
        ";
        
        $stmtBalance = $connec->prepare($sqlBalance);
        $stmtBalance->bindParam(":ad_muser_key", $ad_muser_key);
        $stmtBalance->execute();
        $cashierBalance = $stmtBalance->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            "status" => "SUCCESS",
            "message" => "Berhasil buka kasir",
            "data" => [
                "pos_dcashierbalance_key" => $cashierBalance['pos_dcashierbalance_key'],
                "pos_mcashier_key" => $cashierBalance['pos_mcashier_key'],
                "pos_mshift_key" => $cashierBalance['pos_mshift_key'],
                "startdate" => $cashierBalance['startdate'],
                "balanceamount" => $cashierBalance['balanceamount'],
                "status" => $cashierBalance['status']
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => $o_message ?: "Gagal buka kasir"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>