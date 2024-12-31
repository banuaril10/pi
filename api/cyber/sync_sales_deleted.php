<?php include "../../config/koneksi.php";
$tanggal = $_GET['date'];

$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}
function push_to_deleted($url, $deleted, $idstore)
{
    $postData = array(
        "deleted" => $deleted,
        "idstore" => $idstore
    );
    $fields_string = http_build_query($postData);

    $curl = curl_init();

    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fields_string,
        )
    );

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}

$jj_deleted = array();

if ($tanggal != "now") {
    $list_deleted = "select * from pos_dsalesdeleted where date(insertdate) = '" . $tanggal . "' and status_intransit is null";
} else {
    $list_deleted = "select * from pos_dsalesdeleted where status_intransit is null";
}


foreach ($connec->query($list_deleted) as $row3) {
    $jj_deleted[] = array(
        "pos_dsalesdeleted_key" => $row3['pos_dsalesdeleted_key'],
        "ad_mclient_key" => $row3['ad_mclient_key'],
        "ad_morg_key" => $row3['ad_morg_key'],
        "isactived" => $row3['isactived'],
        "insertdate" => $row3['insertdate'],
        "insertby" => $row3['insertby'],
        "postby" => $row3['postby'],
        "postdate" => $row3['postdate'],
        "ad_muser_key" => $row3['ad_muser_key'],
        "pos_dcashierbalance_key" => $row3['pos_dcashierbalance_key'],
        "sku" => $row3['sku'],
        "qty" => $row3['qty'],
        "price" => $row3['price'],
        "discount" => $row3['discount'],
        "billno" => $row3['billno'],
        "approvedby" => $row3['approvedby'],
        "issync" => $row3['issync'],
        "status_intransit" => $row3['status_intransit']
    );
}

if (!empty($jj_deleted)) {
    $url = $base_url . "/sales_order/sync_sales_deleted.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI";
    $array_deleted = array("deleted" => $jj_deleted);
    $array_deleted_json = json_encode($array_deleted);
    $hasil_deleted = push_to_deleted($url, $array_deleted_json, $idstore);
    $j_hasil_deleted = json_decode($hasil_deleted, true);
    if (!empty($j_hasil_deleted)) {
        foreach ($j_hasil_deleted as $r) {
            $statement1 = $connec->query("update pos_dsalesdeleted set status_intransit = '1' 
        where pos_dsalesdeleted_key = '" . $r . "'");
        }
    }
}