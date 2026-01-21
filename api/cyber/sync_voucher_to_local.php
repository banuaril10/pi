<?php
include "../../config/koneksi.php";
ini_set('max_execution_time', 300);

$connec->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/* ambil idstore lokal */
$idstore = $connec->query("
    select ad_morg_key 
    from ad_morg 
    where isactived = 'Y' 
    limit 1
")->fetchColumn();

/* =====================
   GET DATA DARI SERVER
   ===================== */
function get($url)
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 60
    ]);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
}

$url = $base_url . "/store/voucher/get_all_voucher.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3";
$payload = json_decode(get($url), true);

if (!isset($payload['header'])) {
    exit("Invalid response server");
}

/* =====================
   AMBIL DATA LOKAL SEKALI
   ===================== */
$local = [];
$q = $connec->query("
    select pos_dvoucher_key, status
    from pos_dvoucher
");
foreach ($q as $r) {
    $local[$r['pos_dvoucher_key']] = $r['status'];
}

/* =====================
   SYNC
   ===================== */
$connec->beginTransaction();

try {

    foreach ($payload['header'] as $r) {

        $key = $r['pos_dvoucher_key'];

        /* BELUM ADA → INSERT */
        if (!isset($local[$key])) {

            $connec->exec("
                insert into pos_dvoucher (
                    pos_dvoucher_key,
                    voucher_code,
                    status,
                    valid_from,
                    valid_until,
                    insertdate,
                    useddate,
                    percent,
                    id_location,
                    status_intransit
                ) values (
                    '{$key}',
                    '{$r['voucher_code']}',
                    '{$r['status']}',
                    '{$r['valid_from']}',
                    '{$r['valid_until']}',
                    '{$r['insertdate']}',
                    ".($r['useddate'] ? "'{$r['useddate']}'" : "NULL").",
                    '{$r['percent']}',
                    '{$r['id_location']}',
                    '1'
                )
            ");
            continue;
        }

        /* SUDAH ADA → UPDATE JIKA SERVER USED */
        if ($r['status'] === 'USED' && $local[$key] === 'NEW') {

            $connec->exec("
                update pos_dvoucher set
                    status   = 'USED',
                    useddate = ".($r['useddate'] ? "'{$r['useddate']}'" : "now()")."
                where pos_dvoucher_key = '{$key}'
            ");
        }
    }

    $connec->commit();

    echo json_encode([
        "status"  => "OK",
        "message" => "Voucher sync completed"
    ]);

} catch (Exception $e) {
    $connec->rollBack();
    echo json_encode([
        "status"  => "FAILED",
        "message" => $e->getMessage()
    ]);
}
