<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
require_once 'auth_middleware.php';

$userData = authenticate();
include "../../config/koneksi.php";

$input = json_decode(file_get_contents("php://input"), true);

$clientKey = $input["p_ad_mclient_key"] ?? null;
$orgKey = $input["p_ad_morg_key"] ?? null;
$opt = $input["p_option"] ?? [];

// Ambil nilai search
$searchValue = $opt["search"]["value"] ?? "";
$filterValue = $opt["filter"]["txtsearch"] ?? $searchValue;

// Tambahkan wildcard %
$searchWithWildcard = "%" . $searchValue . "%";
$filterWithWildcard = "%" . $filterValue . "%";

// Format option - FILTER HARUS ARRAY
$formatted_option = [
    "draw" => (string)($opt["draw"] ?? "1"),
    "start" => (string)($opt["start"] ?? "0"),
    "length" => (string)($opt["length"] ?? "100"),
    "search" => [
        "value" => $searchWithWildcard,
        "regex" => $opt["search"]["regex"] ?? false
    ],
    "columns" => $opt["columns"] ?? [],
    "order" => $opt["order"] ?? [],
    "filter" => [  // ARRAY, bukan object
        [
            "id" => "txtsearch",
            "value" => $filterWithWildcard
        ]
    ]
];

$option = json_encode($formatted_option);

try {
    $sql = "
        SELECT 
            o_draw,
            o_recordstotal,
            o_recordsfiltered,
            o_data,
            o_message
        FROM proc_pos_dsales_refund_view(
            :p_ad_mclient_key,
            :p_ad_morg_key,
            :p_option::json
        )
    ";

    $stmt = $connec->prepare($sql);
    $stmt->bindParam(":p_ad_mclient_key", $clientKey);
    $stmt->bindParam(":p_ad_morg_key", $orgKey);
    $stmt->bindParam(":p_option", $option, PDO::PARAM_STR);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "draw" => (int)($result["o_draw"] ?? 0),
        "recordsTotal" => (int)($result["o_recordstotal"] ?? 0),
        "recordsFiltered" => (int)($result["o_recordsfiltered"] ?? 0),
        "data" => $result["o_data"] ? json_decode($result["o_data"]) : [],
        "message" => $result["o_message"] ?? ""
    ]);

} catch(PDOException $e) {
    echo json_encode([
        "draw" => 1,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => [],
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>