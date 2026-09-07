<?php 
include "../../config/koneksi.php";

$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}

function get_data($url) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

$url = $base_url . '/store/promo/get_transaksi_limit.php?idstore=' . $idstore;
$hasil = get_data($url);
$j_hasil = json_decode($hasil, true);

$s = array();
foreach ($j_hasil as $value) {
    $s[] = "('" . $value['ad_org_id'] . "', '" . $value['headername'] . "', 
             '" . $value['insertdate'] . "', '" . $value['insertby'] . "', '" . $value['sku'] . "', 
             '" . $value['max_per_struk'] . "', '" . $value['fromdate'] . "', '" . $value['todate'] . "')";
}

if ($s == null) {
    echo json_encode(array("status" => "FAILED", "message" => "Data Not Found"));
    die();
}

// Delete existing
$delete = "DELETE FROM pos_transaksi_limit";
$statement = $connec->prepare($delete);
$statement->execute();

$values = implode(", ", $s);
$insert = "INSERT INTO pos_transaksi_limit (ad_org_id, headername, insertdate, insertby, sku, max_per_struk, fromdate, todate) 
           VALUES " . $values;

$statement = $connec->prepare($insert);
$statement->execute();

echo json_encode(array("status" => "OK", "message" => "Data Inserted"));
?>