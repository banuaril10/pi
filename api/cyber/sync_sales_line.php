<?php include "../../config/koneksi.php";
$tanggal = $_GET['date'];

$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}

function push_to_line($url, $line, $idstore)
{
    $postData = array(
        "line" => $line,
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


$jj_line = array();



if ($tanggal != "now") {
    $list_line = "select * from pos_dsalesline where date(insertdate) = '" . $tanggal . "' and isactived = '1' and status_intransit is null ";
} else {
    $list_line = "select * from pos_dsalesline where isactived = '1' and status_intransit is null ";
}



foreach ($connec->query($list_line) as $row2) {

    $jenis_promo = "";

    $get_jenis = $connec->query("select jenis_promo from pos_mproductdiscount 
    where discountname = '" . $row2['discountname'] . "' and sku = '" . $row2['sku'] . "' and date(now()) between fromdate and todate");

    foreach ($get_jenis as $r) {
        $jenis_promo = $r['jenis_promo'];
    }

    if($jenis_promo == ""){
        $get_jenis = $connec->query("select jenis_promo from pos_mproductdiscountgrosir_new
        where discountname = '" . $row2['discountname'] . "' and sku = '" . $row2['sku'] . "' and date(now()) between fromdate and todate");

        foreach ($get_jenis as $r) {
            $jenis_promo = $r['jenis_promo'];
        }
    }

    if($jenis_promo == ""){
        $get_jenis = $connec->query("select jenis_promo from pos_mproductbuyget
        where discountname = '" . $row2['discountname'] . "' and skuget = '" . $row2['sku'] . "' and date(now()) between fromdate and todate");

        foreach ($get_jenis as $r) {
            $jenis_promo = $r['jenis_promo'];
        }
    }


    $jj_line[] = array(
        "pos_dsalesline_key" => $row2['pos_dsalesline_key'],
        "ad_mclient_key" => $row2['ad_mclient_key'],
        "ad_morg_key" => $row2['ad_morg_key'],
        "isactived" => $row2['isactived'],
        "insertdate" => $row2['insertdate'],
        "insertby" => $row2['insertby'],
        "postby" => $row2['postby'],
        "postdate" => $row2['postdate'],
        "pos_dsales_key" => $row2['pos_dsales_key'],
        "billno" => $row2['billno'],
        "seqno" => $row2['seqno'],
        "sku" => $row2['sku'],
        "qty" => $row2['qty'],
        "price" => $row2['price'],
        "discount" => $row2['discount'],
        "amount" => $row2['amount'],
        "issync" => $row2['issync'],
        "discountname" => $row2['discountname'],
        "status_sales" => $row2['status_sales'],
        "status_intransit" => $row2['status_intransit'],
        "jenis_promo" => $jenis_promo
    );
}


// print_r($jj_line);
// echo "test";

if (!empty($jj_line)) {
    $url = $base_url . "/sales_order/sync_sales_line.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI";
    // echo $url;
    $array_line = array("line" => $jj_line);
    $array_line_json = json_encode($array_line);
    // print_r($array_line_json);
    

    $hasil_line = push_to_line($url, $array_line_json, $idstore);
    $j_hasil_line = json_decode($hasil_line, true);
    // print_r($hasil_line);

    if (!empty($j_hasil_line)) {
        foreach ($j_hasil_line as $r) {
            $statement2 = $connec->query("update pos_dsalesline set status_intransit = '1' 
            where pos_dsalesline_key = '" . $r . "'");
        }
    }
}



