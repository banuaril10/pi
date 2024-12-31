<?php
include "../config/koneksi.php";
ini_set('max_execution_time', '2000');

function get_version($link){
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => $link,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'GET',
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;	
					
					
}


$get_nama_toko = "select * from ad_morg where postby = 'SYSTEM'";
$resultss = $connec->query($get_nama_toko);
foreach ($resultss as $r) {
	$storename = $r["name"];	
	$storecode = $r["value"];	
	$ad_morg_key = $r["ad_morg_key"];	
	$brand = strtoupper($r["address3"]);	
}

$get_sales = "select count(*) std from pos_dsalesline where date(insertdate) = '".date('Y-m-d')."' ";
$gs = $connec->query($get_sales);
$std = 0;
foreach ($gs as $r) {
	$std = $r['std'];
}

			
// $hasil = get_version('https://apipos.idolmart.co.id/pos-api/omset_pembanding.php?id=814672C74A544E5B996A90C5B07B58FB&ad_org_id='.$ad_morg_key.'&std='.$std); //php curl
// $hasil = get_version('https://apipos.idolmart.co.id/pos-api/omset_pembanding.php?id=814672C74A544E5B996A90C5B07B58FB&ad_org_id='.$ad_morg_key.'&std=2'); //php curl

// $hasil = get_version('https://pi.idolmartidolaku.com/api/omset_pembanding.php?id=814672C74A544E5B996A90C5B07B58FB&ad_org_id='.$ad_morg_key.'&std='.$std); //php curl
// $hasil = get_version('https://pi.idolmartidolaku.com/api/omset_pembanding.php?id=814672C74A544E5B996A90C5B07B58FB&ad_org_id='.$ad_morg_key.'&std=2'); //php curl

// echo $hasil;
echo 1;
