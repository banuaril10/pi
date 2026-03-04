<?php include "../../config/koneksi.php";
$jenis = $_GET['jenis'];
$statement = $connec->query("select * from pos_medc where jenis = '$jenis' order by name asc");

$edc = array();
foreach ($statement as $r) {
    $edc[] = array(
        "id" => $r['pos_medc_key'],
        "ad_mclient_key" => $r['ad_mclient_key'],
        "ad_morg_key" => $r['ad_morg_key'],
        "isactived" => $r['isactived'],
        "insertdate" => $r['insertdate'],
        "insertby" => $r['insertby'],
        "postby" => $r['postby'],
        "postdate" => $r['postdate'],
        "name" => $r['name'],
        "description" => $r['description'],
        "code" => $r['code'],
    );
}
echo json_encode($edc);