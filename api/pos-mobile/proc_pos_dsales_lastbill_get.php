<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../../config/koneksi.php";

// Terima input JSON
$input = json_decode(file_get_contents("php://input"), true);

$ad_mclient_key = $input["ad_mclient_key"] ?? null;
$ad_morg_key = $input["ad_morg_key"] ?? null;
$ad_muser_key = $input["ad_muser_key"] ?? null;

// Validasi input
if (empty($ad_mclient_key) || empty($ad_morg_key) || empty($ad_muser_key)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter tidak lengkap"
    ]);
    exit;
}

try {
    // Panggil function proc_pos_dsales_lastbill_get
    $sql = "SELECT * FROM proc_pos_dsales_lastbill_get(
        :p_ad_mclient_key,
        :p_ad_morg_key,
        :p_ad_muser_key
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_ad_mclient_key", $ad_mclient_key);
    $stmt->bindParam(":p_ad_morg_key", $ad_morg_key);
    $stmt->bindParam(":p_ad_muser_key", $ad_muser_key);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil dari function
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    // Decode JSON data
    $data = $o_data ? json_decode($o_data, true) : null;
    
    if ($o_message == "success" && !empty($data) && is_array($data)) {
        // Ambil data pertama (karena array)
        $billData = $data[0];
        
        echo json_encode([
            "status" => "SUCCESS",
            "message" => "Berhasil mendapatkan bill",
            "data" => [
                "serialno" => $billData["serialno"] ?? 0,
                "lastbillno" => $billData["lastbillno"] ?? "",
                "lasttempvalue" => $billData["lasttempvalue"] ?? 0,
                "lasttempstring" => $billData["lasttempstring"] ?? "Rp 0",
                "memberid" => $billData["memberid"] ?? null,
                "membername" => $billData["membername"] ?? null,
                "isbirthday" => $billData["isbirthday"] ?? false,
                "memberpoint" => $billData["memberpoint"] ?? 0,
                "membercardno" => $billData["membercardno"] ?? null,
                "membertext" => $billData["membertext"] ?? null
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "ERROR",
            "message" => "Gagal mendapatkan bill: " . $o_message
        ]);
    }
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>