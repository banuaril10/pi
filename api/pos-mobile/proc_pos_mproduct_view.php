<?php
header("Content-Type: application/json");
include "../../config/koneksi.php";

$input = json_decode(file_get_contents("php://input"), true);

$ad_client_id = $input["ad_client_id"] ?? null;
$ad_org_id    = $input["ad_org_id"] ?? null;
$opt = $input["option"] ?? [];

// Pastikan semua key ada dengan nilai default
$formatted_option = [
    "draw" => (string)($opt["draw"] ?? "1"),
    "start" => (string)($opt["start"] ?? "0"),
    "length" => (string)($opt["length"] ?? "20"),
    "search" => json_encode([
        "value" => $opt["search"]["value"] ?? ""
    ]),
    "columns" => $opt["columns"] ?? [
        ["data" => "id"],
        ["data" => "sku"],
        ["data" => "name"],
        ["data" => "price"],
        ["data" => "stockqty"],
        ["data" => "postdate"]
    ],
    "order" => $opt["order"] ?? [
        ["column" => 5, "dir" => "asc"]  // default sort by postdate asc
    ]
];

$option = json_encode($formatted_option);

// DEBUG
error_log("Option yang dikirim: " . $option);

try {
    $sql = "
        SELECT 
            o_draw,
            o_recordstotal,
            o_recordsfiltered,
            o_data,
            o_message
        FROM proc_pos_mproduct_all_view(
            :p_ad_client_id,
            :p_ad_org_id,
            :p_option::json
        )
    ";

    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_ad_client_id", $ad_client_id);
    $stmt->bindParam(":p_ad_org_id", $ad_org_id);
    $stmt->bindParam(":p_option", $option, PDO::PARAM_STR);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "o_draw"            => (int)($result["o_draw"] ?? 0),
        "o_recordstotal"    => (int)($result["o_recordstotal"] ?? 0),
        "o_recordsfiltered" => (int)($result["o_recordsfiltered"] ?? 0),
        "o_data"            => $result["o_data"] 
                                ? json_decode($result["o_data"]) 
                                : [],
        "o_message"         => $result["o_message"] ?? ""
    ]);

} catch(PDOException $e){
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
?>