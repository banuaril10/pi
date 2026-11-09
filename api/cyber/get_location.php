<?php include "../../config/koneksi.php";
//get total product

$jum = 0;
$check = $connec->query("select ad_morg_key from ad_morg limit 1");
foreach ($check as $row) {
    $jum = $row["ad_morg_key"];
}


echo $jum;
?>