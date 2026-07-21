<?php include "../../config/koneksi.php";
$tanggal = $_GET['date'];

$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}
function push_to_posdshopsales($url, $posdshopsales, $idstore)
{
    $postData = array(
        "posdshopsales" => $posdshopsales,
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

$jj_posdshopsales = array();





if ($tanggal != "now") {
    $list_posdshopsales = "select * from pos_dshopsales where date(insertdate) = '" . $tanggal . "' ";
} else {
    $list_posdshopsales = "select * from pos_dshopsales where status_intransit is null and date(insertdate) = date(now())";
}

foreach ($connec->query($list_posdshopsales) as $row5) {
    $jj_posdshopsales[] = array(
        "pos_dshopsales_key" => $row5['pos_dshopsales_key'],
        "ad_mclient_key" => $row5['ad_mclient_key'],
        "ad_morg_key" => $row5['ad_morg_key'],
        "isactived" => $row5['isactived'],
        "insertdate" => $row5['insertdate'],
        "insertby" => $row5['insertby'],
        "postby" => $row5['postby'],
        "postdate" => $row5['postdate'],
        "pos_mshift_key" => $row5['pos_mshift_key'],
        "ad_muser_key" => $row5['ad_muser_key'],
        "salesdate" => $row5['salesdate'],
        "closedate" => $row5['closedate'],
        "balanceamount" => $row5['balanceamount'],
        "salesamount" => $row5['salesamount'],
        "salescashamount" => $row5['salescashamount'],
        "salesdebitamount" => $row5['salesdebitamount'],
        "salescreditamount" => $row5['salescreditamount'],
        "status" => $row5['status'],
        "actualamount" => $row5['actualamount'],
        "remark" => $row5['remark'],
        "issync" => $row5['issync'],
        "refundamount" => $row5['refundamount'],
        "discountamount" => $row5['discountamount'],
        "cancelcount" => $row5['cancelcount'],
        "cancelamount" => $row5['cancelamount'],
        "donasiamount" => $row5['donasiamount'],
        "variantmin" => $row5['variantmin'],
        "variantplus" => $row5['variantplus'],
        "pointamount" => $row5['pointamount'],
        "pointdebitamout" => $row5['pointdebitamout'],
        "pointcreditamount" => $row5['pointcreditamount'],
        "status_intransit" => $row5['status_intransit'],
        "ppobamount" => $row5['ppobamount'],
        "ppobcashamount" => $row5['ppobcashamount'],
        "ppobdebitamount" => $row5['ppobdebitamount'],
        "ppobcreditamount" => $row5['ppobcreditamount']
    );
}

if (!empty($jj_posdshopsales)) {
    $url = $base_url . "/sales_order/sync_sales_posdshopsales.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI";
    $array_posdshopsales = array("posdshopsales" => $jj_posdshopsales);
    $array_posdshopsales_json = json_encode($array_posdshopsales);
    $hasil_posdshopsales = push_to_posdshopsales($url, $array_posdshopsales_json, $idstore);

    // echo $hasil_posdshopsales;


    $j_hasil_posdshopsales = json_decode($hasil_posdshopsales, true);
    if (!empty($j_hasil_posdshopsales)) {
        foreach ($j_hasil_posdshopsales as $r) {
            $statement1 = $connec->query("update pos_dshopsales set status_intransit = '1' 
        where pos_dshopsales_key = '" . $r . "'");
        }
    }
}






$jj_possettlement = array();

if ($tanggal != "now") {
    $list_possettlement = "
        SELECT ps.*
        FROM pos_settlement ps
        INNER JOIN pos_dshopsales pds
            ON pds.pos_dshopsales_key = ps.pos_dshopsales_key
        WHERE DATE(pds.insertdate) = '".$tanggal."'
        AND ps.status_intransit = '0'
    ";
} else {
    $list_possettlement = "
        SELECT ps.*
        FROM pos_settlement ps
        INNER JOIN pos_dshopsales pds
            ON pds.pos_dshopsales_key = ps.pos_dshopsales_key
        WHERE pds.status_intransit IS NULL
        AND DATE(pds.insertdate) = DATE(NOW())
        AND ps.status_intransit = '0'
    ";
}

foreach ($connec->query($list_possettlement) as $row) {
    $jj_possettlement[] = array(
        "pos_settlement_key" => $row['pos_settlement_key'],
        "pos_dshopsales_key" => $row['pos_dshopsales_key'],
        "pos_medc_key" => $row['pos_medc_key'],
        "amount" => $row['amount'],
        "tanggal" => $row['tanggal']
    );
}

if (!empty($jj_possettlement)) {

    $url = $base_url . "/sales_order/sync_sales_possettlement.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI";

    $array_possettlement = array(
        "possettlement" => $jj_possettlement
    );

    $array_possettlement_json = json_encode($array_possettlement);

    $postData = array(
        "possettlement" => $array_possettlement_json,
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
    ));

    $hasil_possettlement = curl_exec($curl);

    curl_close($curl);

    // Update status_intransit menjadi 1 setelah berhasil dikirim
    if ($hasil_possettlement !== false) {
        $response = json_decode($hasil_possettlement, true);
        
        // Cek apakah response berupa array dan tidak kosong (berarti berhasil)
        if (is_array($response) && !empty($response)) {
            foreach ($response as $key) {
                $updateQuery = "UPDATE pos_settlement SET status_intransit = '1' WHERE pos_settlement_key = '" . $key . "'";
                $connec->query($updateQuery);
            }
        }
    }
}








