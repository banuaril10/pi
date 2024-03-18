<?php 
include "../config/koneksi.php";

$text = file_get_contents('https://pi.idolmartidolaku.com/api/function.txt');
// echo $text;



$cp = $connec->query($text);

if($cp){
	$json = array('result'=>'1', 'msg'=>'Berhasil sync function');
}else{

	$json = array('result'=>'0', 'msg'=>'Gagal sync function');	
}

$json_string = json_encode($json);
echo $json_string;
		

// $sync_function = "";
// $cq = $connec->query($cek_qty);
// $info = "Info Promo..";
// $nama_product = "";
// $discount_name = "";
// foreach($cq as $r){
	// $notes = "";
	// $cek_grosir = "select discount, discountname from pos_mproductdiscountgrosir_new where sku = '".$r['sku']."' and DATE(now()) between fromdate and todate order by minbuy asc";
	// $cv = $connec->query($cek_grosir);
	// foreach ($cv as $r1){
		// $discount_name .= $r1['discountname'].', Potongan '.rupiah($r1['discount']).'/Pcs <br>';
	// }
	
	// $product = "select * from pos_mproduct where sku = '".$r['sku']."'";
	// $cp = $connec->query($product);
	// foreach ($cp as $r1){
		// $nama_product = $r1['name'];
	// }
	
	// $info = $nama_product.'<br> '.$discount_name.'';
// }
// echo $info;







