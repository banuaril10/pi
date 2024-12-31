<?php 
include "../config/koneksi.php";


$cek_ver = "select address1 from ad_morg where postby = 'SYSTEM'";
$cv = $connec->query($cek_ver);

$printer = "";
foreach ($cv as $r){
	
	$printer = $r['address1'];
}
	

echo $printer;