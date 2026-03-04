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
$ad_muser_key = $input["ad_muser_key"] ?? null;  // <-- TAMBAHKAN INI
$option = $input["option"] ?? [];

// Validasi input
if (empty($ad_mclient_key) || empty($ad_morg_key) || empty($ad_muser_key)) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Parameter ad_mclient_key, ad_morg_key, dan ad_muser_key wajib diisi"
    ]);
    exit;
}

// Default columns jika tidak dikirim
$default_columns = [
    ["data" => "id"],
    ["data" => "billno"],
    ["data" => "sku"],
    ["data" => "name"],
    ["data" => "qty"],
    ["data" => "price"],
    ["data" => "discount"],
    ["data" => "discountname"],
    ["data" => "total"],
    ["data" => "seqno"]
];

// Default order jika tidak dikirim
$default_order = [
    ["column" => 9, "dir" => "asc"]
];

// Format option sesuai dengan yang diharapkan function
$formatted_option = [
    "draw" => (string)($option["draw"] ?? "1"),
    "start" => (string)($option["start"] ?? "0"),
    "length" => (string)($option["length"] ?? "10"),
    "search" => json_encode([
        "value" => $option["search"]["value"] ?? ""
    ]),
    "columns" => $option["columns"] ?? $default_columns,
    "order" => $option["order"] ?? $default_order,
    "filter" => $option["filter"] ?? []
];

$option_json = json_encode($formatted_option);

// Debug
error_log("ad_muser_key: " . $ad_muser_key);
error_log("Option JSON: " . $option_json);

try {
    // Panggil function dengan 4 parameter
    $sql = "SELECT * FROM proc_pos_dtempsalesline_view(
        :p_ad_mclient_key,
        :p_ad_morg_key,
        :p_ad_muser_key,
        :p_option::json
    )";
    
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_ad_mclient_key", $ad_mclient_key);
    $stmt->bindParam(":p_ad_morg_key", $ad_morg_key);
    $stmt->bindParam(":p_ad_muser_key", $ad_muser_key);
    $stmt->bindParam(":p_option", $option_json, PDO::PARAM_STR);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ambil hasil
    $o_draw = $result["o_draw"] ?? 0;
    $o_recordstotal = $result["o_recordstotal"] ?? 0;
    $o_recordsfiltered = $result["o_recordsfiltered"] ?? 0;
    $o_data = $result["o_data"] ?? null;
    $o_message = $result["o_message"] ?? "";
    
    // Decode JSON data
    $data = $o_data ? json_decode($o_data, true) : [];
    
    echo json_encode([
        "status" => "SUCCESS",
        "message" => $o_message ?: "Berhasil mengambil data",
        "draw" => (int)$o_draw,
        "recordsTotal" => (int)$o_recordstotal,
        "recordsFiltered" => (int)$o_recordsfiltered,
        "data" => $data
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>