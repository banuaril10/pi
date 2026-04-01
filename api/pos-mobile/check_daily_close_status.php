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
$ad_morg_key = $input["ad_morg_key"] ?? $userData['org'] ?? null;

if (empty($ad_muser_key) || empty($ad_morg_key)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter tidak lengkap"
    ]);
    exit;
}

$today = date('Y-m-d');

try {
    // Cek apakah sudah tutup kasir hari ini
    $sqlCashier = "SELECT COUNT(*) as total 
                   FROM pos_dcashierbalance 
                   WHERE ad_muser_key = :ad_muser_key 
                   AND DATE(startdate) = :today 
                   AND status = 'CLOSE'";
    
    $stmtCashier = $connec->prepare($sqlCashier);
    $stmtCashier->bindParam(":ad_muser_key", $ad_muser_key);
    $stmtCashier->bindParam(":today", $today);
    $stmtCashier->execute();
    $cashierResult = $stmtCashier->fetch(PDO::FETCH_ASSOC);
    $cashierClosed = ($cashierResult['total'] > 0);

    // Cek apakah sudah tutup harian hari ini
    $sqlDaily = "SELECT COUNT(*) as total 
                 FROM pos_dshopsales 
                 WHERE ad_morg_key = :ad_morg_key 
                 AND DATE(salesdate) = :today";
    
    $stmtDaily = $connec->prepare($sqlDaily);
    $stmtDaily->bindParam(":ad_morg_key", $ad_morg_key);
    $stmtDaily->bindParam(":today", $today);
    $stmtDaily->execute();
    $dailyResult = $stmtDaily->fetch(PDO::FETCH_ASSOC);
    $dailyClosed = ($dailyResult['total'] > 0);

    echo json_encode([
        "status" => "SUCCESS",
        "message" => "Status berhasil didapatkan",
        "data" => [
            "cashier_closed" => $cashierClosed,
            "daily_closed" => $dailyClosed,
            "cashier_date" => $today,
            "daily_date" => $today
        ]
    ]);

} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>