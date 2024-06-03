<?php 
include "../config/koneksi.php";

function rupiah($angka){
	
	$hasil_rupiah = number_format($angka,0,',','.');
	return $hasil_rupiah;
 
}

$billno = $_GET['billno'];
$status = '1';
// $billno = "BOSOL-140324S0001";
$cek_qty = "select count(*) jum from pos_dtempsalesline where billno = '".$billno."' ";
$cq = $connec->query($cek_qty);
foreach($cq as $r){
	if($r['jum'] > 0){
		$status = '0';
	}
}
echo $status;







