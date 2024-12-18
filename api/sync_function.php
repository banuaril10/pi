<?php 
include "../config/koneksi.php";

$text = file_get_contents('https://pi.idolmartidolaku.com/api/function.txt');
$text1 = file_get_contents('https://pi.idolmartidolaku.com/api/function1.txt');
$text2 = file_get_contents('https://pi.idolmartidolaku.com/api/function2.txt');
$text3 = file_get_contents('https://pi.idolmartidolaku.com/api/function3.txt');
$text4 = file_get_contents('https://pi.idolmartidolaku.com/api/function4.txt');
$text5 = file_get_contents('https://pi.idolmartidolaku.com/api/function5.txt');
$text6 = file_get_contents('https://pi.idolmartidolaku.com/api/function6.txt');
$text7 = file_get_contents('https://pi.idolmartidolaku.com/api/function7.txt');
$text8 = file_get_contents('https://pi.idolmartidolaku.com/api/function8.txt');
$text9 = file_get_contents('https://pi.idolmartidolaku.com/api/function9.txt');
$text10 = file_get_contents('https://pi.idolmartidolaku.com/api/function10.txt');
$text11 = file_get_contents('https://pi.idolmartidolaku.com/api/function11.txt');
$text12 = file_get_contents('https://pi.idolmartidolaku.com/api/function12.txt');
$text13 = file_get_contents('https://pi.idolmartidolaku.com/api/function13.txt');
$text14 = file_get_contents('https://pi.idolmartidolaku.com/api/function14.txt');
$text15 = file_get_contents('https://pi.idolmartidolaku.com/api/function15.txt');
// echo $text;



$cp = $connec->query($text);
$cp1 = $connec->query($text1);
$cp2 = $connec->query($text2);
$cp3 = $connec->query($text3);
$cp4 = $connec->query($text4);
$cp5 = $connec->query($text5);
$cp6 = $connec->query($text6);
$cp7 = $connec->query($text7);
$cp8 = $connec->query($text8);
$cp9 = $connec->query($text9);
$cp10 = $connec->query($text10);
$cp11 = $connec->query($text11);
$cp12 = $connec->query($text12);
$cp13 = $connec->query($text13);
$cp14 = $connec->query($text14);
$cp15 = $connec->query($text15);

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







