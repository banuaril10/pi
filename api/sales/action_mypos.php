<?php session_start();
include "koneksi.php";
ini_set('max_execution_time', '4000');
$store_code = "";
$org_id = "";
$ad_muser_key = $_SESSION['ad_muser_key'];
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
$getkode = $connec->query("select * from m_profile limit 1");
foreach($getkode as $gk){
	
	$store_code = $gk['storecode'];
	$org_id = $gk['storeid'];
	$alamat = $gk['alamat'];
	$alamat1 = $gk['alamat1'];
	$kota = $gk['kota'];
	$brand = $gk['brand'];
	$footer1 = $gk['footer1'];
	$footer2 = $gk['footer2'];
	$footer3 = $gk['footer3'];
	$setpoint = $gk['setpoint'];
	$tipepoint = $gk['tipepoint'];
	
}


function guid(){
	return str_replace("-","",sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
		mt_rand( 0, 0xffff ),
		mt_rand( 0, 0x0fff ) | 0x4000,
		mt_rand( 0, 0x3fff ) | 0x8000,
		mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
	));
}

function pos_dcashierbalance($a){
			
	// $fields_string = http_build_query($a);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/sales_order/pos_dsalescashier.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $a,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
					
					
}

function pos_dsales($a){
			
	// $fields_string = http_build_query($a);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/sales_order/pos_dbilltoday.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $a,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
					
					
}


function pos_dsalesline($a){
			
	// $fields_string = http_build_query($a);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/sales_order/pos_dbilllinetoday.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $a,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
					
					
}

function pos_dshopsales($a){
			
	// $fields_string = http_build_query($a);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/sales_order/pos_dsalesdaily.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $a,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
					
					
}


function pos_dsalesdeleted($a){
			
	// $fields_string = http_build_query($a);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/sales_order/pos_dsalesdeleted.php?id=OHdkaHkyODczeWQ3ZDM2NzI4MzJoZDk3MzI4OTc5eDcyOTdyNDkycjc5N3N1MHI',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $a,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
					
					
}
function toBase($num, $b=62) {
  $base='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $r = $num  % $b ;
  $res = $base[$r];
  $q = floor($num/$b);
  while ($q) {
    $r = $q % $b;
    $q =floor($q/$b);
    $res = $base[$r].$res;
  }
  return $res;
}



if($_GET['modul'] == 'sales_order'){
	if($_GET['act'] == 'pos_dsalesline'){
			$items = array();

			if($_GET['tgl1'] && !empty($_GET['tgl1']) &&  $_GET['tgl2'] && !empty($_GET['tgl2'])){
				
				$query = $connec->query("select * from pos_dsalesline where date(insertdate) between '".$_GET['tgl1']."' and '".$_GET['tgl2']."' and (status_sales = '0' or status_sales is null)");
			}else{
				
				$query = $connec->query("select * from pos_dsalesline where date(insertdate) = date(now()) and (status_sales = '0' or status_sales is null)");
			}


								foreach ($query as $r) {
									$items[] = array(
										'pos_dsalesline_key'	=>$r['pos_dsalesline_key'], 
										'ad_mclient_key' 		=>$r['ad_mclient_key'], 
										'ad_morg_key' 	=>$r['ad_morg_key'], 
										'isactived' 	=>$r['isactived'], 
										'insertdate' 	=>$r['insertdate'], 
										'insertby' 		=>$r['insertby'], 
										'postby' 		=>$r['postby'], 
										'postdate' 		=>$r['postdate'], 
										'pos_dsales_key' 	=>$r['pos_dsales_key'], 
										'billno' 	=>$r['billno'], 
										'seqno' 	=>$r['seqno'], 
										'sku' 	=>$r['sku'], 
										'qty' 	=>$r['qty'], 
										'price' 	=>$r['price'], 
										'discount' 	=>$r['discount'], 
										'amount' 	=>$r['amount'], 
										'issync' 	=>$r['issync'], 
										'discountname' 	=>$r['discountname'], 
										'buy' 	=>'0', 
									);
								
								}	
								$items_json = json_encode($items);
								$hasil = pos_dsalesline($items_json);
								// var_dump($hasil);
								// var_dump($items_json);
								$j_hasil = json_decode($hasil, true);
								// var_dump($hasil);
								$jum_sales = 0;
								foreach($j_hasil as $r){
									// echo $r['data'];
									$up = $connec->query("update pos_dsalesline set status_sales = '1' where pos_dsalesline_key = '".$r['data']."'");
									if($up){
										
										$jum_sales++;
									}
								}
								
								echo "Berhasil kirim ".$jum_sales." data";
								
	}
}


?>



		