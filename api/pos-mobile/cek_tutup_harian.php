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

$ad_morg_key = $input["ad_morg_key"] ?? null;
$salesdate = $input["salesdate"] ?? date('d-M-y');

// Validasi input
if (empty($ad_morg_key)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter ad_morg_key wajib diisi"
    ]);
    exit;
}

try {
    // Cek apakah ada cashier balance dengan status CLOSE untuk tanggal tersebut
    $sqlCheck = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'CLOSE' THEN 1 ELSE 0 END) as closed,
                    SUM(CASE WHEN status = 'RUNNING' THEN 1 ELSE 0 END) as running
                FROM pos_dcashierbalance 
                WHERE ad_morg_key = :ad_morg_key 
                AND to_char(startdate, 'DD-Mon-YY') = :salesdate";
    
    $stmt = $connec->prepare($sqlCheck);
    $stmt->bindParam(":ad_morg_key", $ad_morg_key);
    $stmt->bindParam(":salesdate", $salesdate);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['running'] > 0) {
        echo json_encode([
            "status" => "ERROR",
            "message" => "Masih ada kasir yang belum ditutup",
            "data" => [
                "total" => $result['total'],
                "closed" => $result['closed'],
                "running" => $result['running']
            ]
        ]);
    } elseif ($result['closed'] == 0) {
        echo json_encode([
            "status" => "ERROR",
            "message" => "Tidak ada data kasir untuk tanggal ini",
            "data" => [
                "total" => $result['total'],
                "closed" => $result['closed'],
                "running" => $result['running']
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "SUCCESS",
            "message" => "Siap tutup harian",
            "data" => [
                "total" => $result['total'],
                "closed" => $result['closed'],
                "running" => $result['running']
            ]
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>