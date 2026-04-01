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

$ad_muser_key = $input["ad_muser_key"] ?? null;

// Validasi input
if (empty($ad_muser_key)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter ad_muser_key wajib diisi"
    ]);
    exit;
}

try {
    // Cek cashier balance untuk user hari ini
    $sql = "
        SELECT 
            pos_dcashierbalance_key,
            pos_mcashier_key,
            pos_mshift_key,
            TO_CHAR(startdate, 'YYYY-MM-DD HH24:MI:SS') as startdate,
            TO_CHAR(enddate, 'YYYY-MM-DD HH24:MI:SS') as enddate,
            balanceamount,
            salesamount,
            status
        FROM pos_dcashierbalance 
        WHERE ad_muser_key = :ad_muser_key 
            AND status = 'RUNNING'
            AND DATE(startdate) = CURRENT_DATE
        ORDER BY startdate DESC
        LIMIT 1
    ";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":ad_muser_key", $ad_muser_key);
    $stmt->execute();
    $cashierBalance = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($cashierBalance) {
        // Jika ada cashier balance aktif
        echo json_encode([
            "status" => "SUCCESS",
            "message" => "Cashier balance ditemukan",
            "data" => [
                "pos_dcashierbalance_key" => $cashierBalance['pos_dcashierbalance_key'],
                "pos_mcashier_key" => $cashierBalance['pos_mcashier_key'],
                "pos_mshift_key" => $cashierBalance['pos_mshift_key'],
                "startdate" => $cashierBalance['startdate'],
                "enddate" => $cashierBalance['enddate'],
                "balanceamount" => $cashierBalance['balanceamount'],
                "salesamount" => $cashierBalance['salesamount'],
                "status" => $cashierBalance['status'],
                "is_active" => true
            ]
        ]);
    } else {
        // Cek apakah ada cashier balance tapi tidak aktif
        $sqlInactive = "
            SELECT 
                pos_dcashierbalance_key,
                pos_mcashier_key,
                status
            FROM pos_dcashierbalance 
            WHERE ad_muser_key = :ad_muser_key 
                AND DATE(startdate) = CURRENT_DATE
            ORDER BY startdate DESC
            LIMIT 1
        ";
        
        $stmtInactive = $connec->prepare($sqlInactive);
        $stmtInactive->bindParam(":ad_muser_key", $ad_muser_key);
        $stmtInactive->execute();
        $inactiveBalance = $stmtInactive->fetch(PDO::FETCH_ASSOC);
        
        if ($inactiveBalance) {
            // Ada cashier balance tapi sudah closed
            echo json_encode([
                "status" => "ERROR",
                "message" => "Cashier sudah ditutup hari ini",
                "data" => [
                    "status" => $inactiveBalance['status'],
                    "is_active" => false
                ]
            ]);
        } else {
            // Belum pernah buka kasir hari ini
            echo json_encode([
                "status" => "ERROR",
                "message" => "Belum buka kasir hari ini",
                "data" => [
                    "is_active" => false
                ]
            ]);
        }
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>