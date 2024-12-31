<?php 
include "../config/koneksi.php";


$cek_ver = "select note4 from ad_morg where postby = 'SYSTEM'";
$cv = $connec->query($cek_ver);

$printer = "";
foreach ($cv as $r){
	
	$printer = $r['note4'];
}
	

echo $printer;

