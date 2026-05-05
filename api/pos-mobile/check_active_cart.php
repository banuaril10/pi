<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
require_once 'auth_middleware.php';

$userData = authenticate();
include "../../config/koneksi.php";

$input = json_decode(file_get_contents("php://input"), true);

$ad_muser_key = $input["ad_muser_key"] ?? null;
$pos_mcashier_key = $input["pos_mcashier_key"] ?? null;
$status = $input["status"] ?? "WAITING";

if (empty($ad_muser_key) || empty($pos_mcashier_key)) {
    echo json_encode([
        "status" => "SUCCESS",
        "message" => "Parameter tidak lengkap",
        "data" => [
            "total_items" => 0,
            "billno" => "",
            "total_amount" => "0"
        ]
    ]);
    exit;
}

try {
    // Query untuk cek apakah ada item di tempsales
    $sql = "SELECT 
                COUNT(*) as total_items,
                COALESCE(MAX(billno), '') as billno,
                COALESCE(SUM(qty * price), 0) as total_amount
            FROM pos_dtempsalesline 
            WHERE ad_muser_key = :ad_muser_key 
            AND pos_mcashier_key = :pos_mcashier_key
            AND status = :status";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":ad_muser_key", $ad_muser_key);
    $stmt->bindParam(":pos_mcashier_key", $pos_mcashier_key);
    $stmt->bindParam(":status", $status);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "status" => "SUCCESS",
        "message" => "OK",
        "data" => [
            "total_items" => (int)($result['total_items'] ?? 0),
            "billno" => $result['billno'] ?? "",
            "total_amount" => "Rp " . number_format($result['total_amount'] ?? 0, 0, ',', '.')
        ]
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage(),
        "data" => [
            "total_items" => 0,
            "billno" => "",
            "total_amount" => "0"
        ]
    ]);
}
?>