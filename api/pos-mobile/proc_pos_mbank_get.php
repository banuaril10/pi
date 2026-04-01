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
$filterby = $input["filterby"] ?? "";
$parentid = $input["parentid"] ?? "";
$search = $input["search"] ?? "";

// Validasi input
if (empty($ad_mclient_key) || empty($ad_morg_key)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter ad_mclient_key dan ad_morg_key wajib diisi"
    ]);
    exit;
}

try {
    // Panggil function proc_pos_mbank_get
    $sql = "SELECT * FROM proc_pos_mbank_get(
        :p_ad_mclient_key,
        :p_ad_morg_key,
        :p_filterby,
        :p_parentid,
        :p_search
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_ad_mclient_key", $ad_mclient_key);
    $stmt->bindParam(":p_ad_morg_key", $ad_morg_key);
    $stmt->bindParam(":p_filterby", $filterby);
    $stmt->bindParam(":p_parentid", $parentid);
    $stmt->bindParam(":p_search", $search);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    // Decode JSON data
    $data = $o_data ? json_decode($o_data, true) : [];
    
    // Return sebagai array langsung (bukan object dengan wrapper)
    echo json_encode($data);
    
} catch(PDOException $e) {
    // Jika error, return array kosong
    echo json_encode([]);
}
?>