<?php include "../../config/koneksi.php";
$tanggal = $_GET['date'];

$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}
function push_to_header($url, $header, $idstore)
{
    $postData = array(
        "header" => $header,
        "idstore" => $idstore
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
    )
    );

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}

$jj_header = array();

if($tanggal != "now"){
    $list_header = "select * from pos_dsales where date(insertdate) = '" . $tanggal . "' 
    and isactived = '1' and status_intransit is null";
}else{
    $list_header = "select * from pos_dsales where isactived = '1' and status_intransit is null";
}


foreach ($connec->query($list_header) as $row1) {
    $jj_header[] = array(
        "pos_dsales_key" => $row1['pos_dsales_key'],
        "ad_mclient_key" => $row1['ad_mclient_key'],
        "ad_morg_key" => $row1['ad_morg_key'],
        "isactived" => $row1['isactived'],
        "insertdate" => $row1['insertdate'],
        "insertby" => $row1['insertby'],
        "postby" => $row1['postby'],
        "postdate" => $row1['postdate'],
        "pos_medc_key" => $row1['pos_medc_key'],
        "pos_dcashierbalance_key" => $row1['pos_dcashierbalance_key'],
        "pos_mbank_key" => $row1['pos_mbank_key'],
        "ad_muser_key" => $row1['ad_muser_key'],
        "billno" => $row1['billno'],
        "billamount" => $row1['billamount'],
        "paymentmethodname" => $row1['paymentmethodname'],
        "membercard" => $row1['membercard'],
        "cardno" => $row1['cardno'],
        "approvecode" => $row1['approvecode'],
        "edcno" => $row1['edcno'],
        "bankname" => $row1['bankname'],
        "serialno" => $row1['serialno'],
        "billstatus" => $row1['billstatus'],
        "paycashgiven" => $row1['paycashgiven'],
        "paygiven" => $row1['paygiven'],
        "printcount" => $row1['printcount'],
        "issync" => $row1['issync'],
        "donasiamount" => $row1['donasiamount'],
        "dpp" => $row1['dpp'],
        "ppn" => $row1['ppn'],
        "billcode" => $row1['billcode'],
        "ispromomurah" => $row1['ispromomurah'],
        "point" => $row1['point'],
        "pointgive" => $row1['pointgive'],
        "membername" => $row1['membername'],
        "status_intransit" => $row1['status_intransit']
    );
}


if (!empty($jj_header)) {
    $url = $base_url . "/sales_order/sync_sales_header.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI";
    $array_header = array("header" => $jj_header);
    $array_header_json = json_encode($array_header);
    $hasil_header = push_to_header($url, $array_header_json, $idstore);
    $j_hasil_header = json_decode($hasil_header, true);

    if (!empty($j_hasil_header)) {
        foreach ($j_hasil_header as $r) {
            $statement1 = $connec->query("update pos_dsales set status_intransit = '1' 
        where pos_dsales_key = '" . $r . "'");
        }
    }
}





