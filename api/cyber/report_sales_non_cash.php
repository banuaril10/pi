<?php include "../../config/koneksi.php";
//get

$query = "SELECT * FROM pos_dshopsalesnoncash WHERE isactived = '1' order by date(insertdate) desc";

$json = array();
$no = 1;
$statement = $connec->query($query);
foreach ($statement as $r) {
    
    $pos_dshopsalesnoncash_key = $r['pos_dshopsalesnoncash_key'];
    $ad_mclient_key = $r['ad_mclient_key'];
    $ad_morg_key = $r['ad_morg_key'];
    $isactived = $r['isactived'];
    $insertdate = $r['insertdate'];
    $insertby = $r['insertby'];
    $postby = $r['postby'];
    $postdate = $r['postdate'];
    $pos_dshopsales_key = $r['pos_dshopsales_key'];
    $pos_medc_key = $r['pos_medc_key'];
    $salesdate = $r['salesdate'];
    $paymentmethodname = $r['paymentmethodname'];
    $valueamount = $r['valueamount'];
    $pointamount = $r['pointamount'];

    //get edcname
    $query_edc = "SELECT * FROM pos_medc WHERE pos_medc_key = '".$pos_medc_key."' ";
    $statement_edc = $connec->query($query_edc);
    foreach ($statement_edc as $r_edc) {
        $edcname = $r_edc['name'];
    }


    $json[] = array(
        "no" => $no,
        "pos_dshopsalesnoncash_key" => $pos_dshopsalesnoncash_key,
        "ad_mclient_key" => $ad_mclient_key,
        "ad_morg_key" => $ad_morg_key,
        "isactived" => $isactived,
        "insertdate" => date_format_pos($insertdate),
        "insertby" => $insertby,
        "postby" => $postby,
        "postdate" => $postdate,
        "pos_dshopsales_key" => $pos_dshopsales_key,
        "pos_medc_key" => $pos_medc_key,
        "edcname" => $edcname,
        "salesdate" => $salesdate,
        "paymentmethodname" => $paymentmethodname,
        "valueamount" => rupiah_pos($valueamount),
        "pointamount" => $pointamount
    );

    $no++;
}


$json_string = json_encode($json);
echo $json_string;

?>