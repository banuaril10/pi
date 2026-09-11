<?php
include "../../config/koneksi.php";

$tanggal = $_GET['date'] ?? 'now';

// Ambil idstore (contoh: ambil 1 org — sesuaikan)
$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);
$idstore = '';
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}

// ============================================================
// FUNCTION KIRIM KE API
// ============================================================
function push_to_api($url, $header_data, $idstore)
{
    $postData = array(
        "header"  => $header_data,
        "idstore" => $idstore
    );
    $fields_string = http_build_query($postData);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => 'POST',
        CURLOPT_POSTFIELDS     => $fields_string,
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

// ============================================================
// AMBIL DATA NONCASH YANG BELUM DI-SYNC
// ============================================================
$jj_header = [];

if ($tanggal != "now") {
    $list_header = "SELECT n.* 
                    FROM pos_dshopsalesnoncash n
                    INNER JOIN pos_dshopsales ds ON ds.pos_dshopsales_key = n.pos_dshopsales_key
                    WHERE DATE(ds.salesdate) = '" . $tanggal . "' 
                      AND n.isactived = '1'
                      AND ds.status_intransit IS NULL
                      AND n.ad_morg_key IS NOT NULL";
} else {
    $list_header = "SELECT n.* 
                    FROM pos_dshopsalesnoncash n
                    INNER JOIN pos_dshopsales ds ON ds.pos_dshopsales_key = n.pos_dshopsales_key
                    WHERE n.isactived = '1'
                      AND ds.status_intransit IS NULL
                      AND DATE(ds.salesdate) = DATE(NOW())
                      AND n.ad_morg_key IS NOT NULL";
}

foreach ($connec->query($list_header) as $row1) {
    $jj_header[] = array(
        "pos_dshopsalesnoncash_key" => $row1['pos_dshopsalesnoncash_key'],
        "ad_mclient_key"            => $row1['ad_mclient_key'],
        "ad_morg_key"               => $row1['ad_morg_key'],
        "isactived"                 => $row1['isactived'],
        "insertdate"                => $row1['insertdate'],
        "insertby"                  => $row1['insertby'],
        "postby"                    => $row1['postby'],
        "postdate"                  => $row1['postdate'],
        "pos_dshopsales_key"        => $row1['pos_dshopsales_key'],
        "pos_medc_key"              => $row1['pos_medc_key'],
        "salesdate"                 => $row1['salesdate'],
        "paymentmethodname"         => $row1['paymentmethodname'],
        "valueamount"               => $row1['valueamount'],
        "pointamount"               => $row1['pointamount'],
        "voucheramount"             => $row1['voucheramount'],
    );
}

// ============================================================
// KIRIM KE API
// ============================================================
if (!empty($jj_header)) {
    $url = $base_url . "/sales_order/sync_shopsalesnoncash_api.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI";
    $array_header = array("header" => $jj_header);
    $array_header_json = json_encode($array_header);
    
    $hasil = push_to_api($url, $array_header_json, $idstore);
    $j_hasil = json_decode($hasil, true);

    if (!empty($j_hasil) && is_array($j_hasil)) {
        foreach ($j_hasil as $key => $val) {
            // Update flag intransit di source
            $statement1 = $connec->query("UPDATE pos_dshopsales 
                                          SET status_intransit = '1' 
                                          WHERE pos_dshopsales_key = '" . $val . "'");
        }
        echo "✅ Sync berhasil: " . count($j_hasil) . " data";
    } else {
        echo "⚠️ Response kosong atau format salah";
        echo "<pre>" . print_r($hasil, true) . "</pre>";
    }
} else {
    echo "ℹ️ Tidak ada data untuk di-sync";
}