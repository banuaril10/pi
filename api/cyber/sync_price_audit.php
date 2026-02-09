<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include "../../config/koneksi.php";
//show error




$tanggal = $_GET['date'] ?? 'now';

// ambil idstore aktif
$idstore = '';
$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}

// function push data
function push_to_line($url, $payload, $idstore)
{
    $postData = array(
        "data" => $payload,
        "idstore" => $idstore
    );

    $fields_string = http_build_query($postData);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $fields_string
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}

// ================= ambil data price_audit =================
$jj_audit = array();

if ($tanggal != "now") {
    $sql = "
        select * from price_audit
        where status_intransit is null
    ";
} else {
    $sql = "
        select * from price_audit
        where status_intransit is null
    ";
}

echo $sql;

foreach ($connec->query($sql) as $row) {
    $jj_audit[] = array(
        "price_audit_key" => $row['price_audit_key'],
        "sku"             => $row['sku'],
        "price"           => $row['price'],
        "discount"        => $row['discount'],
        "insertdate"      => $row['insertdate'],
        "id_location"     => $row['id_location'],
        "scanfrom"        => $row['scanfrom'],
        "status_intransit"=> $row['status_intransit']
    );
}

echo json_encode($jj_audit);

// ================= push ke server pusat =================
if (!empty($jj_audit)) {

    $url = $base_url . "/store/price_checker/sync_price_audit.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI";

    $payload = json_encode(array(
        "price_audit" => $jj_audit
    ));

    $hasil = push_to_line($url, $payload, $idstore);
    $j_hasil = json_decode($hasil, true);

    // ================= update status_intransit =================
    if (!empty($j_hasil)) {
        foreach ($j_hasil as $key) {
            $connec->query("
                update price_audit
                set status_intransit = '1'
                where price_audit_key = '".$key."'
            ");
        }
    }
}
