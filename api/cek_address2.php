<?php 
include "../config/koneksi.php";


$cek_ver = "select address2 from ad_morg where postby = 'SYSTEM'";
$cv = $connec->query($cek_ver);

$printer = "";
foreach ($cv as $r){
	
	$printer = $r['address2'];
}
	

echo $printer;