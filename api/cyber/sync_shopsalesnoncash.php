<?php
include "../../config/koneksi.php";

$tanggal = $_GET['date'] ?? 'now';
$debug   = isset($_GET['debug']) && $_GET['debug'] == '1';

// ============================================================
// AMBIL IDSTORE
// ============================================================
$ll = "SELECT * FROM ad_morg WHERE isactived = 'Y'";
$query = $connec->query($ll);
$idstore = '';
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}

if ($debug) {
    echo "<pre style='background:#f5f5f5;padding:15px;'>";
    echo "DEBUG PENGIRIM\n";
    echo "tanggal : $tanggal\n";
    echo "idstore : [$idstore]\n";
    echo "</pre>";
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
    
    if (isset($_GET['debug']) && $_GET['debug'] == '1') {
        echo "<pre style='background:#fff3cd;padding:15px;'>";
        echo "cURL Error: " . curl_error($curl) . "\n";
        echo "HTTP Code: " . curl_getinfo($curl, CURLINFO_HTTP_CODE) . "\n";
        echo "Response : " . htmlspecialchars($response) . "\n";
        echo "</pre>";
    }
    
    curl_close($curl);
    return $response;
}

// ============================================================
// AMBIL DATA
// ✅ FIX: status_intransit ada di tabel pos_dshopsalesnoncash (n)
// ============================================================
$jj_header = [];

if ($tanggal != "now") {
    $list_header = "SELECT n.* 
                    FROM pos_dshopsalesnoncash n
                    INNER JOIN pos_dshopsales ds ON ds.pos_dshopsales_key = n.pos_dshopsales_key
                    WHERE DATE(ds.salesdate) = :tgl
                      AND n.isactived = '1'
                      AND n.status_intransit IS NULL
                      AND n.ad_morg_key IS NOT NULL";
    $stmtList = $connec->prepare($list_header);
    $stmtList->execute([':tgl' => $tanggal]);
} else {
    $list_header = "SELECT n.* 
                    FROM pos_dshopsalesnoncash n
                    INNER JOIN pos_dshopsales ds ON ds.pos_dshopsales_key = n.pos_dshopsales_key
                    WHERE n.isactived = '1'
                      AND n.status_intransit IS NULL
                      AND DATE(ds.salesdate) = DATE(NOW())
                      AND n.ad_morg_key IS NOT NULL";
    $stmtList = $connec->query($list_header);
}

foreach ($stmtList as $row1) {
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

if ($debug) {
    echo "<pre style='background:#e7f3ff;padding:15px;'>";
    echo "Jumlah data: " . count($jj_header) . "\n\n";
    if (!empty($jj_header)) {
        echo "Sample data[0]:\n";
        echo print_r($jj_header[0], true);
    }
    echo "</pre>";
}

// ============================================================
// KIRIM KE API
// ============================================================
if (!empty($jj_header)) {
    $url = $base_url . "/sales_order/sync_shopsalesnoncash_api.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI";
    if ($debug) $url .= "&debug=1";
    
    $array_header = array("header" => $jj_header);
    $array_header_json = json_encode($array_header);
    
    if ($debug) {
        echo "<pre style='background:#f0f0ff;padding:15px;'>";
        echo "URL: $url\n";
        echo "JSON size: " . strlen($array_header_json) . " bytes\n";
        echo "</pre>";
    }
    
    $hasil = push_to_api($url, $array_header_json, $idstore);
    $j_hasil = json_decode($hasil, true);

    if ($debug) {
        echo "<pre style='background:#f0fff0;padding:15px;'>";
        echo "RAW RESPONSE:\n" . htmlspecialchars($hasil) . "\n\n";
        echo "DECODED:\n";
        echo print_r($j_hasil, true);
        echo "</pre>";
    }

    // ✅ FIX: handle format response baru (kalau debug ada 'result')
    $result_keys = [];
    if (isset($j_hasil['result']) && is_array($j_hasil['result'])) {
        // Format debug
        $result_keys = $j_hasil['result'];
    } elseif (is_array($j_hasil)) {
        // Format normal
        $result_keys = $j_hasil;
    }

    if (!empty($result_keys)) {
        $updated = 0;
        foreach ($result_keys as $val) {
            if (!empty($val)) {
                // ✅ FIX: update flag di tabel pos_dshopsalesnoncash, bukan pos_dshopsales
                $connec->query("UPDATE pos_dshopsalesnoncash 
                                SET status_intransit = '1' 
                                WHERE pos_dshopsales_key = '" . $val . "'");
                $updated++;
            }
        }
        echo "✅ Sync berhasil: $updated data";
    } else {
        echo "⚠️ Response kosong atau format salah";
        if (!$debug) {
            echo "<pre>" . htmlspecialchars($hasil) . "</pre>";
        }
    }
} else {
    echo "ℹ️ Tidak ada data untuk di-sync";
}