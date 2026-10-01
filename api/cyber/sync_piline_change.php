<?php 
include "../../config/koneksi.php";

// ===== Ambil idstore aktif =====
$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);
$idstore = '';
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}

// ===== Function kirim data m_piline_change =====
function piline_change_semua($url, $a)
{
    $postData = array(
        "data_change" => $a,
    );
    $fields_string = http_build_query($postData);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $fields_string,
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

// ===== URL endpoint tujuan =====
$url_change = $base_url . '/store/pi/add_mpiline_change.php?idstore=' . $idstore;

// ===== Ambil data m_piline_change yang BELUM sync =====
// status_intransit NULL atau '0' = belum sync
$sql_change = "select * from m_piline_change 
               where (status_intransit is null or status_intransit = '0') 
               order by updateddate asc";

$no_change = 0;
$items_change = array();
$hasil_change = null;
$j_hasil_change = array('data' => array());

foreach ($connec->query($sql_change) as $rchange) {
    $items_change[] = array(
        'm_piline_change_key' => $rchange['m_piline_change_key'],
        'm_piline_key'        => $rchange['m_piline_key'],
        'sku'                 => $rchange['sku'],
        'ad_org_id'           => $rchange['ad_org_id'],
        'insertby'            => $rchange['insertby'],
        'qtybefore'           => $rchange['qtybefore'],
        'qtyafter'            => $rchange['qtyafter'],
        'updateddate'         => $rchange['updateddate'],
        'status_intransit'    => $rchange['status_intransit'],
    );
}

// ===== Kirim kalau ada data =====
if (!empty($items_change)) {
    $change_json = json_encode($items_change);

    $hasil_change = piline_change_semua($url_change, $change_json);
    $j_hasil_change = json_decode($hasil_change, true);

    if (!empty($j_hasil_change['data'])) {
        foreach ($j_hasil_change['data'] as $rc) {
            $m_piline_change_key = $rc['m_piline_change_key'];

            // Kalau sukses (status 1) ATAU sudah ada di pusat (status 2) → tandai sudah sync
            if ($rc['status'] == 1 || $rc['status'] == 2) {
                $connec->query("update m_piline_change 
                                set status_intransit = '1' 
                                where m_piline_change_key = '" . $m_piline_change_key . "'");

                if ($rc['status'] == 1) {
                    $no_change = $no_change + 1;
                }
            }
        }
    }
}

// ===== Response JSON =====
$json = array(
    'result'           => '1',
    'msg'              => 'Berhasil mengirim ' . $no_change . ' data change',
    'keluaran_change'  => print_r($hasil_change, true),
    'data_change'      => $j_hasil_change['data'],
);
echo json_encode($json);