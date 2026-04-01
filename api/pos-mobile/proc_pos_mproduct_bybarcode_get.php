<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
require_once 'auth_middleware.php';

// Authenticate dulu
$userData = authenticate();
include "../../config/koneksi.php";

// Terima parameter dari request (GET atau POST)
$billno = $_REQUEST["billno"] ?? null;
$barcode = $_REQUEST["barcode"] ?? null;
$memberid = $_REQUEST["memberid"] ?? "";
$promoname = $_REQUEST["promoname"] ?? "";

// Validasi input
if (empty($billno) || empty($barcode)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter billno dan barcode wajib diisi"
    ]);
    exit;
}

try {
    // Panggil function proc_pos_mproduct_bybarcode_get
    $sql = "SELECT * FROM proc_pos_mproduct_bybarcode_get(
        :p_billno,
        :p_barcode,
        :p_memberid,
        :p_promoname
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_billno", $billno);
    $stmt->bindParam(":p_barcode", $barcode);
    $stmt->bindParam(":p_memberid", $memberid);
    $stmt->bindParam(":p_promoname", $promoname);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    // Decode JSON data
    $data = $o_data ? json_decode($o_data, true) : null;
    
    if (!empty($data) && is_array($data)) {
        echo json_encode([
            "status" => "SUCCESS",
            "message" => $o_message,
            "data" => $data
        ]);
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => "Produk tidak ditemukan",
            "data" => []
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>