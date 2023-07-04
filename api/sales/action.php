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
	if($_GET['act'] == 'pos_dcashierbalance'){
			$items = array();
			
			$query = $connec->query("select * from pos_dcashierbalance where date(insertdate) = date(now())");
								foreach ($query as $r) {
									$items[] = array(
										'pos_dcashierbalance_key'	=>$r['pos_dcashierbalance_key'], 
										'ad_mclient_key' 		=>$r['ad_mclient_key'], 
										'ad_morg_key' 	=>$r['ad_morg_key'], 
										'isactived' 	=>$r['isactived'], 
										'insertdate' 	=>$r['insertdate'], 
										'insertby' 		=>$r['insertby'], 
										'postby' 		=>$r['postby'], 
										'postdate' 		=>$r['postdate'], 
										'pos_mcashier_key' 	=>$r['pos_mcashier_key'], 
										'ad_muser_key' 	=>$r['ad_muser_key'], 
										'pos_mshift_key' 			=>$r['pos_mshift_key'], 
										'startdate' 			=>$r['startdate'], 
										'enddate' 		=>$r['enddate'], 
										'balanceamount' 		=>$r['balanceamount'], 
										'salesamount' 		=>$r['salesamount'], 
										'status' 		=>$r['status'], 
										'salescashamount' =>$r['salescashamount'], 
										'salesdebitamount' 		=>$r['salesdebitamount'], 
										'salescreditamount' 		=>$r['salescreditamount'], 
										'actualamount' 	=>$r['actualamount'],
										'issync' 	=>$r['issync'],
										'refundamount' 	=>$r['refundamount'],
										'discountamount' 	=>$r['discountamount'],
										'cancelcount' 	=>$r['cancelcount'],
										'cancelamount' 	=>$r['cancelamount'],
										'donasiamount' 	=>$r['donasiamount'],
										'pointamount' 	=>$r['pointamount'],
										'pointdebitamout' 	=>$r['pointdebitamout'],
										'pointcreditamount' 	=>$r['pointcreditamount'],
										'variantmin' 	=>$r['variantmin'],
										'variantplus' 	=>$r['variantplus'],
										'paycash' 	=>$r['paycash'],
										'keterangan' 	=>$r['keterangan']
									);
								
								}	
								$items_json = json_encode($items);
								$hasil = pos_dcashierbalance($items_json);
								var_dump($hasil);
								// var_dump($items_json);
								$j_hasil = json_decode($hasil, true);
								
	}else if($_GET['act'] == 'pos_dsales'){
			$items = array();


			$query = $connec->query("select * from pos_dsales_new where date(insertdate) = date(now())");
								foreach ($query as $r) {
									$items[] = array(
										'pos_dsales_key'	=>$r['pos_dsales_key'], 
										'ad_mclient_key' 		=>$r['ad_mclient_key'], 
										'ad_morg_key' 	=>$r['ad_morg_key'], 
										'isactived' 	=>$r['isactived'], 
										'insertdate' 	=>$r['insertdate'], 
										'insertby' 		=>$r['insertby'], 
										'postby' 		=>$r['postby'], 
										'postdate' 		=>$r['postdate'], 
										'pos_medc_key' 	=>$r['pos_medc_key'], 
										'pos_dcashierbalance_key' 	=>$r['pos_dcashierbalance_key'], 
										'pos_mbank_key' 	=>$r['pos_mbank_key'], 
										'ad_muser_key' 	=>$r['ad_muser_key'], 
										'billno' 	=>$r['billno'], 
										'billamount' 	=>$r['billamount'], 
										'paymentmethodname' 	=>$r['paymentmethodname'], 
										'membercard' 	=>$r['membercard'], 
										'cardno' 	=>$r['cardno'], 
										'approvecode' 	=>$r['approvecode'], 
										'edcno' 	=>$r['edcno'], 
										'bankname' 	=>$r['bankname'], 
										'serialno' 	=>$r['serialno'], 
										'billstatus' 	=>$r['billstatus'], 
										'paycashgiven' 	=>$r['paycashgiven'], 
										'paygiven' 	=>$r['paygiven'], 
										'printcount' 	=>$r['printcount'], 
										'issync' 	=>$r['issync'], 
										'donasiamount' 	=>$r['donasiamount'], 
										'dpp' 	=>$r['dpp'], 
										'ppn' 	=>$r['ppn'], 
										'billcode' 	=>$r['billcode'], 
										'ispromomurah' 	=>$r['ispromomurah'], 
										'point' 	=>$r['point'], 
										'pointgive' 	=>$r['pointgive'], 
										'membername' 	=>$r['membername']
										
									);
								
								}	
								$items_json = json_encode($items);
								$hasil = pos_dsales($items_json);
								echo $hasil;
								// var_dump($items_json);
								$j_hasil = json_decode($hasil, true);
								
	}else if($_GET['act'] == 'pos_dsalesline'){
			$items = array();



			$query = $connec->query("select * from pos_dsalesline where date(insertdate) = date(now())");
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
								var_dump($hasil);
								// var_dump($items_json);
								$j_hasil = json_decode($hasil, true);
								
	}else if($_GET['act'] == 'pos_dshopsales'){
			$items = array();



			$query = $connec->query("select * from pos_dshopsales where date(insertdate) = date(now())");
								foreach ($query as $r) {
									$items[] = array(
										'pos_dshopsales_key'	=>$r['pos_dshopsales_key'], 
										'ad_mclient_key' 		=>$r['ad_mclient_key'], 
										'ad_morg_key' 	=>$r['ad_morg_key'], 
										'isactived' 	=>$r['isactived'], 
										'insertdate' 	=>$r['insertdate'], 
										'insertby' 		=>$r['insertby'], 
										'postby' 		=>$r['postby'], 
										'postdate' 		=>$r['postdate'], 
										'pos_mshift_key' 		=>$r['pos_mshift_key'], 
										'ad_muser_key' 		=>$r['ad_muser_key'], 
										'salesdate' 		=>$r['salesdate'], 
										'closedate' 		=>$r['closedate'], 
										'balanceamount' 		=>$r['balanceamount'], 
										'salesamount' 		=>$r['salesamount'], 
										'salescashamount' 		=>$r['salescashamount'], 
										'salesdebitamount' 		=>$r['salesdebitamount'], 
										'salescreditamount' 		=>$r['salescreditamount'], 
										'status' 		=>$r['status'], 
										'actualamount' 		=>$r['actualamount'], 
										'remark' 		=>$r['remark'], 
										'issync' 		=>$r['issync'], 
										'refundamount' 		=>$r['refundamount'], 
										'discountamount' 		=>$r['discountamount'], 
										'cancelcount' 		=>$r['cancelcount'], 
										'cancelamount' 		=>$r['cancelamount'], 
										'donasiamount' 		=>$r['donasiamount'], 
										'variantmin' 		=>$r['variantmin'], 
										'variantplus' 		=>$r['variantplus'], 
										'pointamount' 		=>$r['pointamount'], 
										'pointdebitamout' 		=>$r['pointdebitamout'], 
										'pointcreditamount' 		=>$r['pointcreditamount']
									
									);
								
								}	
								$items_json = json_encode($items);
								$hasil = pos_dshopsales($items_json);
								var_dump($hasil);
								// var_dump($items_json);
								$j_hasil = json_decode($hasil, true);
								
	}else if($_GET['act'] == 'pos_dsalesdeleted'){
			$items = array();
			$query = $connec->query("select * from pos_dsalesdeleted where date(insertdate) = date(now())");
			
								foreach ($query as $r) {
									$items[] = array(
										'pos_dsalesdeleted_key'	=>$r['pos_dsalesdeleted_key'], 
										'ad_mclient_key' 		=>$r['ad_mclient_key'], 
										'ad_morg_key' 	=>$r['ad_morg_key'], 
										'isactived' 	=>$r['isactived'], 
										'insertdate' 	=>$r['insertdate'], 
										'insertby' 		=>$r['insertby'], 
										'postby' 		=>$r['postby'], 
										'postdate' 		=>$r['postdate'], 
										'pos_mshift_key' 		=>$r['pos_mshift_key'], 
										'ad_muser_key' 		=>$r['ad_muser_key'], 
										'pos_dcashierbalance_key' 		=>$r['pos_dcashierbalance_key'], 
										'sku' 		=>$r['sku'], 
										'qty' 		=>$r['qty'], 
										'price' 		=>$r['price'], 
										'discount' 		=>$r['discount'], 
										'billno' 		=>$r['billno'], 
										'approvedby' 		=>$r['approvedby'], 
										'issync' 		=>$r['issync'], 
										
									);
								
								}	
								$items_json = json_encode($items);
								$hasil = pos_dsalesdeleted($items_json);
								var_dump($hasil);
								// var_dump($items_json);
								$j_hasil = json_decode($hasil, true);
								
	}else if($_GET['act'] == 'pos_dsalesline_new'){
			$items = array();

			$query = $connec->query("select * from pos_dsales where date(insertdate) = date(now())");
								foreach ($query as $r) {
									$items[] = array(
										'pos_dsalesline_key'	=>$r['pos_dsales_key'], 
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
								var_dump($hasil);
								// var_dump($items_json);
								$j_hasil = json_decode($hasil, true);
								
	}
}


?>



		