<?php
include "../../config/koneksi.php";

function get_data($url)
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ]);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

// Ganti URL ini sesuai endpoint sumber data voucher kamu
$url = $base_url . '/store/voucher/get_voucher_store.php';

$response = get_data($url);
$data = json_decode($response, true);

if (!$data || count($data) == 0) {
    echo json_encode([
        "status" => "FAILED",
        "message" => "Data Not Found or Invalid Response",
    ]);
    die();
}

$rows = [];
foreach ($data as $d) {
    $id_store = $d['id_store'] ?? '';
    $nominal = $d['nominal'] ?? 0;
    $max_qty = $d['max_qty'] ?? 0;
    $startdate = $d['startdate'] ?? null;
    $enddate = $d['enddate'] ?? null;
    $isactive = isset($d['isactive']) ? ($d['isactive'] ? 'TRUE' : 'FALSE') : 'TRUE';

    if ($id_store == '' || $nominal == 0)
        continue; // skip data kosong

    $rows[] = "(
        '" . addslashes($id_store) . "',
        " . floatval($nominal) . ",
        " . intval($max_qty) . ",
        $isactive,
        'system',
        " . ($startdate ? "'" . addslashes($startdate) . "'" : "NULL") . ",
        " . ($enddate ? "'" . addslashes($enddate) . "'" : "NULL") . "
    )";
}

if (empty($rows)) {
    echo json_encode([
        "status" => "FAILED",
        "message" => "No valid data to sync",
    ]);
    die();
}

// Truncate sebelum insert (bisa diubah ke UPSERT kalau mau merge)
try {
    $connec->beginTransaction();

    $truncate = "TRUNCATE in_config_voucher_store";
    $connec->exec($truncate);

    $values = implode(", ", $rows);
    $insert = "
        INSERT INTO in_config_voucher_store 
        (id_store, nominal, max_qty, isactive, insertby, startdate, enddate) 
        VALUES $values
    ";
    $connec->exec($insert);

    $connec->commit();

    echo json_encode([
        "status" => "OK",
        "message" => "Data Synced Successfully",
        "count" => count($rows),
    ]);
} catch (Exception $e) {
    $connec->rollBack();
    echo json_encode([
        "status" => "FAILED",
        "message" => "Error Syncing Data: " . $e->getMessage(),
    ]);
}
?>