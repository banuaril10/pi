<?php 
include "../config/koneksi.php";

$nominal = $_GET['nominal'];
$cek_ver = "select notes from pos_mproductdiscountgo where date(now()) between fromdate and todate and nominal <= ".$nominal;
$cv = $connec->query($cek_ver);

$notes = "";
foreach ($cv as $r){
	
	$notes = $r['notes'];
}
	

echo $notes;