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
$pos_mcashier_key = $input["pos_mcashier_key"] ?? null;
$billno = $input["billno"] ?? null;
$sku = $input["sku"] ?? null;
$qty = $input["qty"] ?? "1";
$price = $input["price"] ?? "0";
$discount = $input["discount"] ?? "0";
$discountname = $input["discountname"] ?? "";
$memberid = $input["memberid"] ?? "";
$postby = $input["postby"] ?? "SYSTEM";
$ad_muser_key = $input["ad_muser_key"] ?? null;

// Validasi input wajib
if (empty($ad_mclient_key) || empty($ad_morg_key) || empty($billno) || empty($sku) || empty($ad_muser_key)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter tidak lengkap"
    ]);
    exit;
}

// ========== SANITASI ANGKA ==========
// Hapus semua karakter non-numeric (koma, titik, Rp, spasi, dll)
function sanitizeNumber($value) {
    // Jika null atau empty, return "0"
    if ($value === null || $value === '') {
        return "0";
    }
    // Hapus semua karakter kecuali angka dan minus
    return preg_replace('/[^0-9\-]/', '', $value);
}

$original_price = $price;
$original_discount = $discount;

$price = sanitizeNumber($price);
$discount = sanitizeNumber($discount);

// Log untuk debugging (bisa dihapus nanti)
error_log("Original price: " . $original_price . " -> Cleaned: " . $price);
error_log("Original discount: " . $original_discount . " -> Cleaned: " . $discount);

// Jika hasil sanitasi kosong, set ke "0"
if (empty($price)) $price = "0";
if (empty($discount)) $discount = "0";
// ====================================

try {
    // Panggil function proc_pos_dtempsalesline_insert
    $sql = "SELECT * FROM proc_pos_dtempsalesline_insert(
        :p_ad_mclient_key,
        :p_ad_morg_key,
        :p_pos_mcashier_key,
        :p_billno,
        :p_sku,
        :p_qty,
        :p_price,
        :p_discount,
        :p_discountname,
        :p_memberid,
        :p_postby,
        :p_ad_muser_key
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_ad_mclient_key", $ad_mclient_key);
    $stmt->bindParam(":p_ad_morg_key", $ad_morg_key);
    $stmt->bindParam(":p_pos_mcashier_key", $pos_mcashier_key);
    $stmt->bindParam(":p_billno", $billno);
    $stmt->bindParam(":p_sku", $sku);
    $stmt->bindParam(":p_qty", $qty);
    $stmt->bindParam(":p_price", $price);
    $stmt->bindParam(":p_discount", $discount);
    $stmt->bindParam(":p_discountname", $discountname);
    $stmt->bindParam(":p_memberid", $memberid);
    $stmt->bindParam(":p_postby", $postby);
    $stmt->bindParam(":p_ad_muser_key", $ad_muser_key);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    // Decode JSON data
    $data = $o_data ? json_decode($o_data, true) : null;
    
    if ($o_message == "Success" || $o_message == "success") {
        echo json_encode([
            "status" => "SUCCESS",
            "message" => "Berhasil menyimpan item",
            "data" => $data ? $data[0] : null
        ]);
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => $o_message ?: "Gagal menyimpan item"
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>