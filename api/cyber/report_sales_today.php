<?php include "../../config/koneksi.php";
//get


$namestore = '';
$ad_org_id = '';
$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $namestore = $row['name'];
    $ad_org_id = $row['ad_morg_key'];
}

//group by date(insertdate)
$json = array();
$groupdate = "SELECT date(insertdate) as date FROM pos_dsales WHERE isactived = '1' and ad_morg_key = '".$ad_org_id."' group by date(insertdate) order by 
date(insertdate) desc";
$statement_groupdate = $connec->query($groupdate);
foreach ($statement_groupdate as $r_groupdate) {
    $date = $r_groupdate['date'];
    $omset = 0;
    $std = 0;
    $apc = 0;

    $query_omset = "SELECT sum(qty*price) omset FROM pos_dsalesline WHERE isactived = '1' and ad_morg_key = '" . $ad_org_id . "' and date(insertdate) = '".$date."'";
    $statement_omset = $connec->query($query_omset);
    foreach ($statement_omset as $r_omset) {
        $omset = $r_omset['omset'];
    }

    $query_std = "SELECT count(billno) std FROM pos_dsales WHERE isactived = '1' and ad_morg_key = '" . $ad_org_id . "' and date(insertdate) = '".$date."'";
    $statement_std = $connec->query($query_std);
    foreach ($statement_std as $r_std) {
        $std = $r_std['std'];
    }

    
    $json[] = array(
        "namestore" => $namestore,
        "omset" => rupiah_pos($omset),
        "std" => $std,
        "apc" => rupiah_pos($omset/$std),
        "tanggal" => date_format_pos($date)
    );

}



$json_string = json_encode($json);
echo $json_string;

?>