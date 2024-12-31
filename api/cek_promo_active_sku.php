<?php 
include "../config/koneksi.php";

function rupiah($angka){
	
	$hasil_rupiah = number_format($angka,0,',','.');
	return $hasil_rupiah;
 
}

$billno = $_GET['billno'];
$sku = $_GET['sku'];
// $billno = "BOSOL-140324S0001";
// $cek_qty = "select * from pos_dtempsalesline where billno = '".$billno."' and sku = '".$sku."' order by seqno desc limit 1";
// $cq = $connec->query($cek_qty);
$info = "Info Promo..";
$nama_product = "";
$discount_name = "";


// foreach($cq as $r){
	$notes = "";
	
	$price = 0;
	$product = "select * from pos_mproduct where sku = '".$sku."'";
	$cp = $connec->query($product);
	foreach ($cp as $r1){
		$nama_product = $r1['name'];
		$price = $r1['price'];
	}
	
	
	
	$cek_reguler = "select discount, discountname from pos_mproductdiscount where sku = '".$sku."' and DATE(now()) between fromdate and todate ";
	$cr = $connec->query($cek_reguler);
	foreach ($cr as $r1){
		$diskon = $price - $r1['discount'];
		$discount_name .= 'Diskon <font style="color: red">'.rupiah($r1['discount']).'</font> Menjadi <font style="color: red">'.rupiah($diskon).'</font><br>';
	}
	
	$cek_grosir = "select discount, discountname, minbuy from pos_mproductdiscountgrosir_new where sku = '".$sku."' and DATE(now()) between fromdate and todate and minbuy > 1 order by minbuy asc";
	$cv = $connec->query($cek_grosir);
	foreach ($cv as $r1){
		$diskon = $price - $r1['discount'];
		$discount_name .= 'Beli '.$r1['minbuy'].', Diskon <font style="color: red">'.rupiah($r1['discount']).' </font> Menjadi <font style="color: red">'.rupiah($diskon).'</font><br>';
		
	}
	
	$info = $nama_product.'<br> '.$discount_name.'';
	
	// $info = $nama_product.'<br> '.$discount_name.'';
// }
echo $info;







