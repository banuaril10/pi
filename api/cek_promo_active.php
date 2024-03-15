<?php 
include "../config/koneksi.php";

function rupiah($angka){
	
	$hasil_rupiah = number_format($angka,0,',','.');
	return $hasil_rupiah;
 
}

$billno = $_GET['billno'];
// $billno = "BOSOL-140324S0001";
$cek_qty = "select * from pos_dtempsalesline where billno = '".$billno."' order by seqno desc limit 1";
$cq = $connec->query($cek_qty);
$info = "Info Promo..";
$nama_product = "";
$discount_name = "";
foreach($cq as $r){
	$notes = "";
	$cek_grosir = "select discount, discountname from pos_mproductdiscountgrosir_new where sku = '".$r['sku']."' and DATE(now()) between fromdate and todate order by minbuy asc";
	$cv = $connec->query($cek_grosir);
	foreach ($cv as $r1){
		$discount_name .= $r1['discountname'].', Potongan '.rupiah($r1['discount']).'/Pcs <br>';
	}
	
	$product = "select * from pos_mproduct where sku = '".$r['sku']."'";
	$cp = $connec->query($product);
	foreach ($cp as $r1){
		$nama_product = $r1['name'];
	}
	
	$info = $nama_product.'<br> '.$discount_name.'';
}
echo $info;







