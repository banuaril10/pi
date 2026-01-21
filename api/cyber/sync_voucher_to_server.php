<?php
include "../../config/koneksi.php";

$tanggal = $_GET['date'] ?? 'now';

/* ambil id store */
$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}

/* fungsi push */
function push_to_server($url, $payload)
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS => $payload
    ]);

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}


/* ambil voucher */
if ($tanggal != "now") {
    $sql = "
        select * from pos_dvoucher
        where date(insertdate) = '$tanggal'
        and status_intransit is null
    ";
} else {
    $sql = "
        select * from pos_dvoucher
        where date(insertdate) = date(now())
        and status_intransit is null
    ";
}

$jj_header = [];
foreach ($connec->query($sql) as $r) {
    $jj_header[] = [
        "pos_dvoucher_key" => $r['pos_dvoucher_key'],
        "pos_dsales_key"  => $r['pos_dsales_key'],
        "membercard"      => $r['membercard'],
        "voucher_amount"  => $r['voucher_amount'],
        "voucher_code"    => $r['voucher_code'],
        "status"          => $r['status'],
        "valid_from"      => $r['valid_from'],
        "valid_until"     => $r['valid_until'],
        "insertdate"      => $r['insertdate'],
        "useddate"        => $r['useddate'],
        "percent"         => $r['percent'],
        "status_intransit"=> $r['status_intransit'],
        "id_location"     => $r['id_location']
    ];
}

// var_dump($jj_header);

/* kirim ke server */
if (!empty($jj_header)) {

    $url = $base_url . "/store/voucher/sync_voucher.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3";

$payload = json_encode([
    "header"  => $jj_header,
    "idstore" => $idstore
]);

$hasil  = push_to_server($url, $payload);
$result = json_decode($hasil, true);


    if (!empty($result)) {
        foreach ($result as $key) {
            $connec->query("
                update pos_dvoucher
                set status_intransit = '1'
                where pos_dvoucher_key = '$key'
            ");
        }
    }
}
