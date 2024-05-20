<?php session_start();
ini_set('max_execution_time', '4000');
if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
  
}else{
	$json = array('result'=>'3', 'msg'=>'Session telah habis, reload dulu halamannya');	
}


include '../config/Mobile_Detect.php';
$detect = new Mobile_Detect();

// Check for any mobile device.
if ($detect->isMobile()){
   $insertfrom = "M";
}
else {
   $insertfrom = "W";
   // other content for desktops
}


include "../config/koneksi.php";
// $ch = curl_init();
$username = $_SESSION['username'];
$useridcuy = $_SESSION['userid'];
$org_key = $_SESSION['org_key'];
$ss = $_SESSION['status_sales'];
$kode_toko = $_SESSION['kode_toko'];
$rand_no = rand(1,100);

$get_nama_toko = "select * from ad_morg where postby = 'SYSTEM'";
$resultss = $connec->query($get_nama_toko);
foreach ($resultss as $r) {
	$storename = $r["name"];	
	$storecode = $r["value"];	
	$ad_morg_key = $r["ad_morg_key"];	
	$brand = strtoupper($r["address3"]);	
}

function guid($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function rupiah($angka){
	
	$hasil_rupiah = number_format($angka,0,',','.');
	return $hasil_rupiah;
 
}

function get_data_edc(){
						    
	// $fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://newpos.idolmartidolaku.com/pos-api/sync-edc-bank',
	//CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_pos_fix_new&org='.$a,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'GET',
	// CURLOPT_POSTFIELDS => $postData,
	CURLOPT_HTTPHEADER => array(
		'Authorization: Basic YmFudToxMjM0NTY='
	)
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
					
					
}

function get_data_balance($org, $date){
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=get_data_balance&org_id=".$org."tgl=".$date,
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

function get_data_promo($org){
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_promo&org_id=".$org,
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


function get_data_promo_grosir($org){
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_promo_grosir_new&org_id=".$org,
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




function get_data_promo_code($org){
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_promo_code&org_id=".$org,
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

function get_data_promo_buyget($org){
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_promo_buyget&org_id=".$org,
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

function get_data_sku(){
			
			    
	// $fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_sku',
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

function get_data_grab($kt){
			
			    
	// $fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_grab_toko&kode_toko='.$kt,
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



function get_data_promo_tebus($org){
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_promo_tebus&org_id=".$org,
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

function get_data_gudang($org){
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_inv&org_id=".$org,
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


function get_data_cat($ss, $pc, $org_key, $kode_toko){
	
	$postData = array(
		"org_key" => $org_key,
		"pc" => $pc,
		"ss" => $ss,
		"kode_toko" => $kode_toko,

	
    );				    
	$fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=get_data_cat',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $fields_string,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
	
	
}

function get_data_cat_get($ss, $pc, $org_key, $kode_toko){
	
	// $postData = array(
		// "org_key" => $org_key,
		// "pc" => $pc,
		// "ss" => $ss,
		// "kode_toko" => $kode_toko,

	
    // );				    
	// $fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=get_data_cat_get&org_key='.$org_key.'&pc='.$pc.'&ss='.$ss.'&kode_toko='.$kode_toko,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'GET',
	// CURLOPT_POSTFIELDS => $fields_string,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
	
	
}

function push_to_server($pi_key, $a, $b, $c, $d, $e, $f,$ff, $g, $h, $i, $j, $k, $l, $m, $n){
	
											
						// echo "<script>console.log('Debug Objects: ".$pikey." - " . $a."-". $b."-". $c."-". $d."-". $e."-". $f."-". $g."-". $h."-". $i."-". $j."-". $k."-". $l."-". $m . "' );</script>";
						
	$postData = array(
		"m_pi_key" => $pi_key,
		"ad_client_id" => $a,
		"ad_org_id" => $b,
		"insertdate" => $c,
		"insertby" => $d,
		"m_locator_id" => $e,
		"inventorytype" => $f,
		"name" => $ff,
		"description" => $g,
		"movementdate" => $h,
		"approvedby" => $i,
		"status" => '1',
		"rack_name" => $k,
		"postby" => $l,
		"postdate" => $m,
		"isactived" => $n
    );				

// print_r($postData);	
	

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=pi',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $postData,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
	// if ($server_output == "OK") {$json = array('result'=>'1');	  } else {$json = array('result'=>'0');	 }
	// $json_string = json_encode($json);
	// return $json_string;
}



function push_to_server_line($a, $b, $c, $d, $e, $f, $g, $h, $i, $ii, $j, $k, $kk, $l, $m, $n, $o, $p, $q, $r, $s){
	
			
		
			
	$postData = array(
		"m_piline_key" => $a,
		"m_pi_key" => $b,
		"ad_client_id" => $c,
		"ad_org_id" => $d,
		"isactived" => $e,
		"insertdate" => $f,
		"insertby" => $g,
		"postby" => $h,
		"postdate" => $i,
		"m_storage_id" => $ii,
		"m_product_id" => $j,
		"sku" => $k,
		"name" => $kk,
		"qtyerp" => $l,
		"qtycount" => $m,
		"issync" => $n,
		"status" => $o,
		"verifiedcount" => $p,
		"qtysales" => $q,
		"price" => $r,
		"qtysalesout" => $s,
    );				
	

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=piline',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $postData,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
}

function push_to_server_line_all($a){
	
			
		
			
	$postData = array(
		"data_line" => $a,
    );				
	$fields_string = http_build_query($postData);

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=piline_all',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $fields_string,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
}

function push_stock_grab($a){
	
	$postData = array(
		"data_line" => $a,
    );				
	$fields_string = http_build_query($postData);

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=push_stock_grab',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $fields_string,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
}

function push_to_newpos($a){
	
			
		
			
	$postData = array(
		"data" => $a,
    );				
	$fields_string = http_build_query($postData);

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_cashin',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $fields_string,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
}


function push_to_newpos_price($a){
	
			
		
			
	$postData = array(
		"data" => $a,
    );				
	$fields_string = http_build_query($postData);

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=input_perubahan_harga',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $fields_string,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
}


function push_to_server_line_all2($a){

			
	$postData = array(
		"data_line" => $a,
    );				
	$fields_string = http_build_query($postData);

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=piline_all_all_all',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $fields_string,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
}

function piline_semua($a){

			
	$postData = array(
		"data_line" => $a,
    );				
	$fields_string = http_build_query($postData);

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=piline_semua_new',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $fields_string,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
}

function piline_semua_nasional($a){

			
	$postData = array(
		"data_line" => $a,
    );				
	$fields_string = http_build_query($postData);

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=piline_semua_new_nasional',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $fields_string,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
}

function get_data_erp_borongan($a,$b,$c,$d,$e,$f){
			
	$postData = array(
		"m_locator_id" => $a,
		"m_pro_id" => $b,
		"org_key" => $c,
		"ss" => $d,
		"kode_toko" => $e,
		"rack" => $f
	
    );				    
	$fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=get_data_new',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $fields_string,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
					
					
}

function get_data_erp_borongan_direct_webpos($a,$c,$d,$e,$f){
			
	$postData = array(
		"m_locator_id" => $a,
		"org_key" => $c,
		"ss" => $d,
		"kode_toko" => $e,
		"rack" => $f
	
    );				    
	$fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=get_data_direct_web_pos',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $fields_string,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
					
					
}

function get_data_all_items($a,$b){
			
	$postData = array(
		"m_locator_id" => $a,
		"org_key" => $b
    );				    
	$fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=get_data_all_items',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $fields_string,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
					
					
}



function get_data_erp_borongan_kat($a,$b,$c,$d,$e,$f){
			
	$postData = array(
		"m_locator_id" => $a,
		"m_pro_id" => $b,
		"org_key" => $c,
		"ss" => $d,
		"kode_toko" => $e,
		"kat" => $f
	
    );				    
	$fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=get_data_new_category',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $fields_string,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
					
					
}

function get_data_stock($a,$b){
			
	$postData = array(
		"org_id" => $a,
		"sku" => $b
	
    );				    
	// $fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_pos_peritems',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $postData,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
					
					
}

function get_data_stock_name($a,$b){
			
	$postData = array(
		"org_id" => $a,
		"sku" => $b
	
    );				    
	// $fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_pos_peritems_name',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $postData,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
					
					
}

function get_data_barcode(){
			
			    
	// $fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_barcode',
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

function get_data_harga($ad_morg_key){
			
			    
	// $fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_price&org='.$ad_morg_key,
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

function get_data_harga_null($ad_morg_key){
			
			    
	// $fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_price_null&org='.$ad_morg_key,
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

function get_data_harga_khusus($ad_morg_key){
			
			    
	// $fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_price_khusus&org='.$ad_morg_key,
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


function get_data_stock_peritems($a,$b){
			
	$postData = array(
		"org_id" => $a,
		"mpi" => $b
	
    );				    
	// $fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=get_stock_erp',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $postData,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
					
					
}

function get_data_doc($a,$b){
			
	$postData = array(
		"org_id" => $a,
		"doc_no" => $b
	
    );				    
	// $fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=cek_doc',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $postData,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
					
					
}




function get_data_stock_all($a){
			
	$postData = array(
		"org_id" => $a,
	
    );				    
	// $fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_pos_fix',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $postData,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
					
					
}

function get_data_erp($a,$b,$c,$d){
			
	$postData = array(
		"m_locator_id" => $a,
		"m_pro_id" => $b,
		"org_key" => $c,
		"ss" => $d
	
    );				    
	$fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt($curl,CURLOPT_URL, 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=get_data');
	curl_setopt($curl,CURLOPT_POST, 1);
	curl_setopt($curl,CURLOPT_ENCODING, '');
	curl_setopt($curl,CURLOPT_POST, 1);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl,CURLOPT_MAXREDIRS, 10);
	curl_setopt($curl,CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);

	
	$response = curl_exec($curl);
	
	
	curl_close($curl);
	return $response;	
					
					
}


function sync_approval($m_pi){
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_status&m_pi='.$m_pi,
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

function notif_wa(){
			
	$postData = array(
		"m_locator_id" => 'test',
		// "m_pro_id" => $b,
		// "org_key" => $c,
		// "ss" => $d
	
    );				
	

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=notif_wa',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $postData,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;	
					
					
}


function get_spv($kt, $dn, $selisih){
					
	$postData = array(
		"kode_toko" => $kt,
		"doc_no" => $dn,
		"selisih" => $selisih,
		// "ss" => $d
	
    );				

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=nohp_spv',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $postData,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;	
					
					
}


function monitoring($a){
			
	$postData = array(
		"org_id" => $a,
	
    );				    
	// $fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=monitoring',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $postData,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
					
					
}


function input_shortcut($sku, $shortcut, $username){
			
	$postData = array(
		"sku" => $sku,
		"shortcut" => $shortcut,
		"insert_name" => $username,
	
    );				    
	// $fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=input_shortcut',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $postData,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;
					
					
}



function inputLomba($no_hp, $nama, $kategori, $nama_toko, $ad_org_id){
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=lomba&act=input',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{"no_hp":"'.$no_hp.'","nama":"'.$nama.'","kategori":"'.$kategori.'","nama_toko":"'.$nama_toko.'","ad_org_id":"'.$ad_org_id.'"}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
curl_close($curl);
return $response;

}



if($_GET['modul'] == 'inventory'){	
	$it = $_POST['it'];
	$sl = $_POST['sl'];
	$kat = $_POST['kat'];
	$rack = $_POST['rack'];
	$pc = $_POST['pc'];
	$ss = $_POST['sso'];
	if($it == 'Nasional'){
		$ss = '0';
	}
	
	
	
	// $it = 'Global';
	// $sl = '4DC01BB67AB148C9A02C4F5DB39AF969';
	// $kat = '1';
	// $rack = 'R-1';
	// $pc = 1;
	// $ss = '0';
	
	
	
	if($_GET['act'] == 'input'){
		
		
		if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
				
				$cekrak = "select count(m_pi_key) jum from m_pi where rack_name='".$rack."' and (status != '5') and date(insertdate) = date(now())";
				$cr = $connec->query($cekrak);
				foreach ($cr as $ra) {
				
					$countrak = $ra['jum'];
				}

			if($countrak > 0){
				$json = array('result'=>'0', 'msg'=>'Rack sudah ada');
				
			}else{
				
			
				
				
			$statement = $connec->query("insert into m_pi (
			ad_client_id, ad_org_id, isactived, insertdate, insertby, m_locator_id, inventorytype, name, description, 
			movementdate, approvedby, status, rack_name, postby, postdate, category
			) VALUES ('','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".$sl."', '".$it."','".$kode_toko."-".date('YmdHis')."','PI-".$rack."', 
			'".date('Y-m-d H:i:s')."','user spv','1','".$rack."','".$username."','".date('Y-m-d H:i:s')."', '1') RETURNING m_pi_key");
			
			

			if($statement){
				
				foreach ($statement as $rr) {
				
					$lastid = $rr['m_pi_key'];
					// $lastid = '12322';
						
						
					if($insertfrom == 'M'){
				
						$connec->query("update m_pi set insertfrommobile = 'Y' where m_pi_key = '".$lastid."'");
					}else if($insertfrom == 'W'){
						$connec->query("update m_pi set insertfromweb = 'Y' where m_pi_key = '".$lastid."'");
				
				
					}	
						
						
				}




				//mulai dari sini

				// $sql1 = "select m_product_id,sku from inv_mproduct where rack_name='".$rack."'";
				// $result = $connec->query($sql1);
				// $count = $result->rowCount();
				
				$no = 0;
				$items = array();
				
				$items_json = json_encode($items);
				

				$hasil = get_data_erp_borongan_direct_webpos($sl, $org_key, $ss, $kode_toko, $rack); //php curl
				
				
				// print_r ($hasil);
				
				
				
				$j_hasil = json_decode($hasil, true);
				
				
				// $obj = json_decode($j_hasil);
				// var_dump($j_hasil);
				// die();
				// print_r($items_json);
				
				$num = count($j_hasil);
				
				if($num > 0){
					
					foreach($j_hasil as $r) {
					
					
						$qtysales = 0;
						$sql_sales = "select case when sum(qty) is null THEN '0' ELSE sum(qty) END as qtysales from pos_dsalesline 
						where date(insertdate)=date(now()) and sku='".$r['sku']."'";
					
						$rsa = $connec->query($sql_sales);

						foreach ($rsa as $rsa1) {
						
							$qtysales = $rsa1['qtysales'];
						}
						
						
						
						// $qtysales = $row['qtysales'];
						$cek_count = "select qtycount from m_piline where sku = '".$r['sku']."' and date(insertdate)=date(now())"; //mencari apakah items sdh ada di rack piline
						$rsac = $connec->query($cek_count);
						$ccc = $rsac->rowCount();
						
						if($ccc > 0){
							foreach ($rsac as $rrr) {
						
								$qtycount = $rrr['qtycount'];
							}
							
						}else{
							$qtycount = 0;
							
						}
					
					
					
					
					
					
					$qtyon= $r['qtyon'];			
					$price= $r['price'];			
					$pricebuy= $r['pricebuy'];			
					$statuss= $r['statuss'];			
					$qtyout= $r['qtyout'];			
					$statusss= $r['statusss'];
					$mpi= $r['mpi'];
					$sku= $r['sku'];
					$barcode= $r['barcode'];
					// $qtycount= $r['qtycount'];
					// $qtysales= $r['qtysales'];
					$ketemu= $r['ketemu'];
					
					if($ketemu == 1){
						
					
						
						$statement1 = $connec->query("insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, postdate, m_storage_id, m_product_id, sku, qtyerp, qtycount, qtysales, price, status, qtysalesout, status1, barcode, hargabeli) 
					VALUES ('".$lastid."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".date('Y-m-d H:i:s')."', '".$sl."','".$mpi."', 
					'".$sku."', '".$qtyon."', '".$qtycount."', '".$qtysales."','".$price."', '".$statuss."', '".$qtyout."','".$statusss."', '".$barcode."','".$pricebuy."')");
				
					
					if($statement1){
						
						$connec->query("update pos_mproduct set isactived = 0 where sku = '".$sku."'");
						
						
						$no = $no+1;
						if($no == $count){
							$json = array('result'=>'1');
							
						}else{
							
							$json = array('result'=>'2');
						}
						
						
					}
						
					}
				
					
				}
				
				
				$json = array('result'=>'1', 'msg'=>'Selesai load');
				
					
				}else{
					
					$json = array('result'=>'0', 'msg'=>'Rack tidak ada, hub tim plano untuk cek apakah rack tsb ada');
					
				}
				
				
		
				
		
			}else{
				
				$json = array('result'=>'0', 'msg'=>'Gagal, coba lagi nanti');
			}
				
				
			}
				
				
		}else{
	
				$json = array('result'=>'3', 'msg'=>'Session telah habis, reload halaman dulu');
		}
		
			// $qqq = 	"insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, postdate, m_storage_id, m_product_id, sku, qtyerp, qtycount, qtysales, price, status, qtysalesout, status1, barcode, hargabeli) 
					// VALUES ('".$lastid."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".date('Y-m-d H:i:s')."', '".$sl."','".$mpi."', 
					// '".$sku."', '".$qtyon."', '".$qtycount."', '".$qtysales."','".$price."', '".$statuss."', '".$qtyout."','".$statusss."', '".$barcode."','".$pricebuy."')";
		
			$json_string = json_encode($json);
			echo $json_string;
			// var_dump($qqq);
			// var_dump($j_hasil);
		 
		
	}else if($_GET['act'] == 'input_kat'){
		
		
		$ceknamakat = "select value from inv_mproductcategory where m_product_category_id = '".$pc."'";
				$cnk = $connec->query($ceknamakat);
				foreach ($cnk as $ras) {
				
					$namakat = $ras['value'];
				}
		
		
		
		
		if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
				
				$cekrak = "select count(m_pi_key) jum from m_pi where rack_name='".$namakat."' and status != '5' and date(insertdate) = date(now())";
				$cr = $connec->query($cekrak);
				foreach ($cr as $ra) {
				
					$countrak = $ra['jum'];
				}
				
				
		
			if($countrak > 0){
				$json = array('result'=>'0', 'msg'=>'Category sudah ada');
				
			}else{
				
			
				
				
			$statement = $connec->query("insert into m_pi (
			ad_client_id, ad_org_id, isactived, insertdate, insertby, m_locator_id, inventorytype, name, description, 
			movementdate, approvedby, status, rack_name, postby, postdate, category
			) VALUES ('','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".$sl."', '".$it."','".$kode_toko."-".date('YmdHis')."','PI-".$namakat."', 
			'".date('Y-m-d H:i:s')."','user spv','1','".$namakat."','".$username."','".date('Y-m-d H:i:s')."', '2') RETURNING m_pi_key");
			
			

			if($statement){
				
				foreach ($statement as $rr) {
				
					$lastid = $rr['m_pi_key'];
					// $lastid = '12322';
						
						
					if($insertfrom == 'M'){
				
						$connec->query("update m_pi set insertfrommobile = 'Y' where m_pi_key = '".$lastid."'");
					}else if($insertfrom == 'W'){
						$connec->query("update m_pi set insertfromweb = 'Y' where m_pi_key = '".$lastid."'");
				
				
					}	
						
						
				}

				// $sql1 = "select m_product_id,sku from inv_mproduct where m_product_category_id='".$pc."'";
				// $result = $connec->query($sql1);
				// $count = $result->rowCount();
				
				
				
				
				$no = 0;
				$items = array();
				
				
				
				
				$hasil = get_data_cat_get($ss, $pc, $org_key, $kode_toko);
				// $hasil = get_data_erp_borongan_kat($sl, $items_json, $org_key, $ss, $kode_toko, $pc); //php curl
				
				// print_r ($hasil);
				
				$total = 0;
				
				$j_hasil = json_decode($hasil, true);
				
				
				// $obj = json_decode($j_hasil);
				// var_dump($j_hasil);
				// die();
				// print_r($items_json);
				
				foreach($j_hasil as $r) {
					
					
					$qtyon= $r['qtyon'];			
					$price= $r['price'];	
					$pricebuy= $r['pricebuy'];							
					$qtyout= $r['qtyout'];
					$mpi= $r['mpi'];
					$sku= $r['sku'];
					$namaitem= $r['namaitem'];
					$barcode= $r['barcode'];
					
						$sql_sales = "select case when sum(qty) is null THEN '0' ELSE sum(qty) END as qtysales from pos_dsalesline 
						where date(insertdate)=date(now()) and sku='".$r['sku']."'";
					
						$rsa = $connec->query($sql_sales);

						foreach ($rsa as $rsa1) {
						
							$qtysales = $rsa1['qtysales'];
						}
						
						
						
		
						$cek_count = "select qtycount from m_piline where sku = '".$r['sku']."' and date(insertdate)=date(now())"; //mencari apakah items sdh ada di rack piline
						$rsac = $connec->query($cek_count);
						$ccc = $rsac->rowCount();
						
						if($ccc > 0){
							foreach ($rsac as $rrr) {
						
								$qtycount = $rrr['qtycount'];
							}
							
						}else{
							$qtycount = 0;
							
						}

						$statement1 = $connec->query("insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, postdate, m_storage_id, m_product_id, sku, qtyerp, qtycount, qtysales, price, status, qtysalesout, status1, barcode, hargabeli) 
					VALUES ('".$lastid."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".date('Y-m-d H:i:s')."', '".$sl."','".$mpi."', 
					'".$sku."', '".$qtyon."', '".$qtycount."', '".$qtysales."','".$price."', '1', '".$qtyout."','1', '".$barcode."','".$pricebuy."')");
				
				
				
						
				
					
					if($statement1){
						
						$connec->query("update pos_mproduct set isactived = 0 where sku = '".$sku."'");
						
						
						$no = $no+1;
						if($no == $count){
							$json = array('result'=>'1');
							
						}else{
							
							$json = array('result'=>'2');
						}
						
						
					}
						
					
					$total = $total + 1;
					
				}
				
				if($total == 0){
					
					$json = array('result'=>'0', 'msg'=>'Items tidak ditemukan');
					
				}
				
		
			}else{
				
				$json = array('result'=>'0', 'msg'=>'Gagal, coba lagi nanti');
			}
				
				
			}
				
				
		}else{
	
				$json = array('result'=>'3', 'msg'=>'Session telah habis, reload halaman dulu');
		}
		
			$json_string = json_encode($json);
			echo $json_string;
		 
		
	}else if($_GET['act'] == 'input_kat_nasional'){
		
		
		$ceknamakat = "select value from inv_mproductcategory where m_product_category_id = '".$pc."'";
				$cnk = $connec->query($ceknamakat);
				foreach ($cnk as $ras) {
				
					$namakat = $ras['value'];
				}
		
		
		
		
		if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
				
				$cekrak = "select count(m_pi_key) jum from m_pi where rack_name='".$namakat."' and status != '5' and date(insertdate) = date(now())";
				$cr = $connec->query($cekrak);
				foreach ($cr as $ra) {
				
					$countrak = $ra['jum'];
				}
				
				
		
			if($countrak > 0){
				$json = array('result'=>'0', 'msg'=>'Category sudah ada');
				
			}else{
				
			
				
				
			$statement = $connec->query("insert into m_pi (
			ad_client_id, ad_org_id, isactived, insertdate, insertby, m_locator_id, inventorytype, name, description, 
			movementdate, approvedby, status, rack_name, postby, postdate, category
			) VALUES ('','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".$sl."', '".$it."', '".$kode_toko."-".date('YmdHis').$rand_no."','PI-".$namakat."', 
			'".date('Y-m-d H:i:s')."','user spv','1','".$namakat."','".$username."','".date('Y-m-d H:i:s')."', '2') RETURNING m_pi_key");
			
			

			if($statement){
				foreach ($statement as $rr) {
					$lastid = $rr['m_pi_key'];
				}

				
				$no = 0;
				$items = array();
				$hasil = get_data_cat_get($ss, $pc, $org_key, $kode_toko);
				
				$total = 0;
				
				$j_hasil = json_decode($hasil, true);
				$qtysales = 0;
				$qtyout = 0;
				$qtycount = 0;
				foreach($j_hasil as $r) {
					
					
					$qtyon= $r['qtyon'];			
					$price= $r['price'];	
					$pricebuy= $r['pricebuy'];							
					// $qtyout= $r['qtyout'];
					$mpi= $r['mpi'];
					$sku= $r['sku'];
					$namaitem= $r['namaitem'];
					$barcode= $r['barcode'];
						
		
					// $cek_count = "select qtycount from m_piline where sku = '".$r['sku']."' and date(insertdate)=date(now())"; //mencari apakah items sdh ada di rack piline
					// $rsac = $connec->query($cek_count);
					// $ccc = $rsac->rowCount();
					
					// if($ccc > 0){
						// foreach ($rsac as $rrr) {
					
							// $qtycount = $rrr['qtycount'];
						// }
						
					// }

						$statement1 = $connec->query("insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, postdate, m_storage_id, m_product_id, sku, qtyerp, qtycount, qtysales, price, status, qtysalesout, status1, barcode, hargabeli) 
						VALUES ('".$lastid."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".date('Y-m-d H:i:s')."', '".$sl."','".$mpi."', 
						'".$sku."', '".$qtyon."', '".$qtycount."', '".$qtysales."','".$price."', '1', '".$qtyout."','1', '".$barcode."','".$pricebuy."')");
				
					if($statement1){
						$no = $no+1;
						if($no == $count){
							$json = array('result'=>'1');
							
						}else{
							
							$json = array('result'=>'2');
						}
						
						
					}
						
					
					$total = $total + 1;
					
				}
				
				if($total == 0){
					
					$json = array('result'=>'0', 'msg'=>'Items tidak ditemukan');
					
				}
				
		
			}else{
				
				$json = array('result'=>'0', 'msg'=>'Gagal, coba lagi nanti');
			}
				
				
			}
				
				
		}else{
	
				$json = array('result'=>'3', 'msg'=>'Session telah habis, reload halaman dulu');
		}
		
			$json_string = json_encode($json);
			echo $json_string;
		 
		
	}else if($_GET['act'] == 'inputitems'){
		if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
			$statement = $connec->query("insert into m_pi (
			ad_client_id, ad_org_id, isactived, insertdate, insertby, m_locator_id, inventorytype, name, description, 
			movementdate, approvedby, status, rack_name, postby, postdate, category
			) VALUES ('','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".$sl."', '".$it."','".$kode_toko."-".date('YmdHis')."','PI-ITEMS', 
			'".date('Y-m-d H:i:s')."','user spv','1','ALL','".$username."','".date('Y-m-d H:i:s')."', '3') RETURNING m_pi_key");
			

			if($statement){

				$json = array('result'=>'1');
			}else{
				
				$json = array('result'=>'0', 'msg'=>'Gagal, coba lagi nanti');
			}
			
		}else{
			
			$json = array('result'=>'3', 'msg'=>'Session telah habis, reload halaman dulu');
			
		}
			$json_string = json_encode($json);
			echo $json_string;
		
	}else if($_GET['act'] == 'inputitemsnasional'){
		if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
			$guid_mpi = str_replace('-','',guid());
			
			$q_header = "insert into m_pi (m_pi_key,
			ad_client_id, ad_org_id, isactived, insertdate, insertby, m_locator_id, inventorytype, name, description, 
			movementdate, approvedby, status, rack_name, postby, postdate, category
			) VALUES ('".$guid_mpi."','','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".$sl."', '".$it."','".$kode_toko."-".date('YmdHis')."','PI-ITEMS', 
			'".date('Y-m-d H:i:s')."','user spv','1','ALL','".$username."','".date('Y-m-d H:i:s')."', '3')";
			
			$statement = $connec->query($q_header);
			
			// echo $q_header;
			
			$qtysales = 0;
			$qtycount = 0;
			if($statement){
				
				$hasil = get_data_all_items($sl, $org_key); //php curl
				$j_hasil = json_decode($hasil, true);

				$num = count($j_hasil);
				
				if($num > 0){
					$s = array();
					foreach($j_hasil as $r) {
						$qtyon= $r['qtyon'];			
						$price= $r['price'];			
						$pricebuy= $r['pricebuy'];			
						$statuss= $r['statuss'];			
						$qtyout= $r['qtyout'];			
						$statusss= $r['statusss'];
						$mpi= $r['mpi'];
						$sku= $r['sku'];
						$barcode= $r['barcode'];
						$ketemu= $r['ketemu'];
						
						$s[] = "('".$guid_mpi."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".date('Y-m-d H:i:s')."', '".$sl."','".$mpi."', 
						'".$sku."', '".$qtyon."', '".$qtycount."', '".$qtysales."','".$price."', '".$statuss."', '".$qtyout."','".$statusss."', '".$barcode."','".$pricebuy."')";
					}
					
					$values = implode(", ",$s);
					$q_val = "insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, postdate, m_storage_id, m_product_id, sku, qtyerp, qtycount, qtysales, price, status, qtysalesout, status1, barcode, hargabeli) 
					VALUES ".$values."";
					
					$statement1 = $connec->query($q_val);
				
				if($statement1){
					
					$json = array('result'=>'1');

				}
				
				$json = array('result'=>'1','msg'=>'Berhasil buat header');
			}else{
				
				$json = array('result'=>'0', 'msg'=>'Gagal, coba lagi nanti');
			}
			
			}else{
			
				$json = array('result'=>'3', 'msg'=>'Gagal input header');
			
			}
		}else{
			
				$json = array('result'=>'3', 'msg'=>'Session telah habis, reload halaman dulu');
			
		}
		$json_string = json_encode($json);
		echo $json_string;
		
	}else if($_GET['act'] == 'sync_erp'){
		
				
			$mpi = $_GET['m_pi'];
			// $mpi = 'BE7DA436E891492EB27235BF5DAC588C';
			
			$sql1 = "select m_piline_key, m_pi_key, m_storage_id, m_product_id from m_piline where m_pi_key ='".$mpi."' and status = 0";
				$result = $connec->query($sql1);
				$count = $result->rowCount();
				
				
				
				
				$no = 0;
				foreach ($connec->query($sql1) as $row) {
	
					
					
					
				$hasil = get_data_erp($row['m_storage_id'], $row['m_product_id'], $org_key, $ss); //php curl
				
		
				$j_hasil = json_decode($hasil, true);
				
									
				$qtyon= $j_hasil['qtyon'];			
				$price= $j_hasil['price'];			
				$statuss= $j_hasil['statuss'];			
				$qtyout= $j_hasil['qtyout'];			
				$statusss= $j_hasil['statusss'];
					
					
					
					$connec->query("update m_piline set qtysalesout = '".$qtyout."', qtyerp = '".$qtyon."', price = '".$price."', status = '".$statuss."', status1 = '".$statusss."' where m_piline_key ='".$row['m_piline_key']."'");
					
						
					
					
					
					$json = array('result'=>'1', 'msg'=>'Telah sync '.$no.' dari '.$count.' items');	
					
				}
					
					
		$json_string = json_encode($json);
		echo $json_string;
	}else if($_GET['act'] == 'counter'){
		
		$sku = $_POST['sku'];
		$mpi = $_GET['mpi'];
		
		
			if($insertfrom == 'M'){
				
				$connec->query("update m_pi set insertfrommobile = 'Y' where m_pi_key = '".$mpi."'");
			}else if($insertfrom == 'W'){
				$connec->query("update m_pi set insertfromweb = 'Y' where m_pi_key = '".$mpi."'");
				
				
			}
			
		
		
		$sql = "select m_piline.sku, m_piline.qtycount from m_piline where (m_piline.sku ='".$sku."' or m_piline.barcode ='".$sku."')
		and date(m_piline.insertdate) = date(now()) and m_pi_key = '".$mpi."'";
		$result = $connec->query($sql);
		$count = $result->rowCount();
		
		if($count > 0){
			
			// $insertfrom
			
			
			foreach ($result as $r) {
				$pn = $sku;	
				$getpro = "select name from pos_mproduct where sku = '".$r['sku']."'";
				$gp = $connec->query($getpro);
				
				foreach ($gp as $rgp) {
					
					$pn = $rgp['name'];	
				}
				
			$qtyon = $r['qtycount'];
			

			$lastqty = $qtyon + 1;
		
			if($sku != ""){
				
				
				$statement1 = $connec->query("update m_piline set qtycount = '".$lastqty."' where (sku = '".$sku."' or barcode = '".$sku."') and date(insertdate) = '".date('Y-m-d')."' and m_pi_key = '".$mpi."'");
			}else{
				
				$json = array('result'=>'0', 'msg'=>'SKU tidak boleh kosong');	
			}
		
			
			
			if($statement1){	
				$json = array('result'=>'1', 'msg'=>$sku .' ('.$pn.'), QUANTITY = <font style="color: red">'.$lastqty.'</font>');	
			}else{
				$json = array('result'=>'0', 'msg'=>'Gagal ,coba lagi nanti');	
				
			}				
			}
			
		}else{
			
			
			if($sku != ""){
				
				$sql_pos = "select rack, name from pos_mproduct where (sku ='".$sku."' or barcode = '".$sku."')";
				$cekm = $connec->query($sql_pos);
				$count_pos = $cekm->rowCount();
				
				if($count_pos > 0){
					foreach ($cekm as $haha) {
						
						if($haha['rack'] == NULL) {
							
							$json = array('result'=>'2', 'msg'=>'ITEMS '.$sku.' TIDAK ADA DI LINE, LANJUTKAN?');	
							
						}else{
							$json = array('result'=>'2', 'msg'=>'ITEMS '.$sku.' TIDAK ADA DI LINE, ITEMS INI BERADA DI RAK '.$haha['rack'].', LANJUTKAN?');	
							
						}
						
						
					}
					
					
					
				}else{
					
					$json = array('result'=>'0', 'msg'=>'ITEMS TIDAK ADA DI MASTER PRODUCT');	
				}
			}else{
				
				$json = array('result'=>'0', 'msg'=>'SKU tidak boleh kosong');	
			}
		}
		$json_string = json_encode($json);
		echo $json_string;
	}else if($_GET['act'] == 'counteritems'){
		
		$sku = $_POST['sku'];
		$mpi = $_GET['mpi'];
		
		$sql = "select m_piline.qtycount, pos_mproduct.name from m_piline left join pos_mproduct on m_piline.sku = pos_mproduct.sku where (m_piline.sku ='".$sku."' or m_piline.barcode ='".$sku."')
		and m_piline.m_pi_key = '".$mpi."' and date(m_piline.insertdate) = '".date('Y-m-d')."'";
		$result = $connec->query($sql);
		$count = $result->rowCount();
		
		if($count > 0){
			foreach ($result as $r) {
			$qtyon = $r['qtycount'];
			$pn = $r['name'];	

			$lastqty = $qtyon + 1;
		
		
			if($insertfrom == 'M'){
				
				$connec->query("update m_pi set insertfrommobile = 'Y' where m_pi_key = '".$mpi."'");
			}else if($insertfrom == 'W'){
				$connec->query("update m_pi set insertfromweb = 'Y' where m_pi_key = '".$mpi."'");
				
				
			}
		
			if($sku != ""){
				
				
				$statement1 = $connec->query("update m_piline set qtycount = '".$lastqty."' where (sku = '".$sku."' or barcode = '".$sku."') and date(insertdate) = '".date('Y-m-d')."'");
			}else{
				
				$json = array('result'=>'0', 'msg'=>'SKU tidak boleh kosong');	
			}
		

			
			if($statement1){	
				$json = array('result'=>'1', 'msg'=>$sku .' ('.$pn.'), QUANTITY = <font style="color: red">'.$lastqty.'</font>');	
			}else{
				$json = array('result'=>'0', 'msg'=>'Gagal ,coba lagi nanti');	
				
			}				
			}
			
		}else{
			
			$count1 = 0;
			if($sku != ""){
				
				$ceksku = "select m_product_id, sku, name, coalesce(price, 0) from pos_mproduct where (sku ='".$sku."' or barcode = '".$sku."')";
				$cs = $connec->query($ceksku);
				$count1 = $cs->rowCount();
				
			}
			
			if($count1 > 0){

			foreach($cs as $mpii){
				$m_pro_id = $mpii['m_product_id'];
				$name = $mpii['name'];
				$sku = $mpii['sku'];
				// $price = $mpii['price'];
				
			}

			if($insertfrom == 'M'){
				
				$connec->query("update m_pi set insertfrommobile = 'Y' where m_pi_key = '".$mpi."'");
			}else if($insertfrom == 'W'){
				$connec->query("update m_pi set insertfromweb = 'Y' where m_pi_key = '".$mpi."'");
				
				
			}

				//cek di maaster product
			
			$getmpi = "select * from m_pi where m_pi_key ='".$mpi."'";
			$gm = $connec->query($getmpi);
			
			$sql_sales = "select case when sum(qty) is null THEN '0' ELSE sum(qty) END as qtysales from pos_dsalesline where date(insertdate)=date(now()) and sku='".$sku."'";
					
				    $rsa = $connec->query($sql_sales);

						foreach ($rsa as $rsa1) {
						
							$qtysales = $rsa1['qtysales'];
						}
			foreach($gm as $rr){
					
				$hasil = get_data_erp($rr['m_locator_id'], $m_pro_id, $org_key, $ss); //php curl
				
		
				$j_hasil = json_decode($hasil, true);
				
									
				$qtyon= $j_hasil['qtyon'];			
				$price= $j_hasil['price'];			
				$pricebuy= $j_hasil['pricebuy'];			
				$statuss= $j_hasil['statuss'];			
				$qtyout= $j_hasil['qtyout'];			
				$statusss= $j_hasil['statusss'];			
				$barcode= $j_hasil['barcode'];			
									
							
				
				
						$cek_count = "select qtycount from m_piline where sku = '".$sku."' and date(insertdate) = '".date('Y-m-d')."'";
						$rsac = $connec->query($cek_count);
						$ccc = $rsac->rowCount();
						
						if($ccc > 0){
							foreach ($rsac as $rrr) {
						
								$qtycount = $rrr['qtycount'] + 1;
							}
							
						}else{
							$qtycount = 1;
							
						}
						
				
				
				$statement1 = $connec->query("insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, postdate,m_storage_id, m_product_id, sku, qtyerp, qtycount, qtysales, price, status, qtysalesout, status1, barcode, hargabeli) 
				VALUES ('".$rr['m_pi_key']."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".date('Y-m-d H:i:s')."','".$rr['m_locator_id']."','".$m_pro_id."', '".$sku."', '".$qtyon."', '".$qtycount."', '".$qtysales."', '".$price."', '".$statuss."', '".$qtyout."', '".$statusss."', '".$barcode."','".$pricebuy."')"); 
				
				
				if($statement1){
					$connec->query("update pos_mproduct set isactived = 0 where sku = '".$sku."'");
					$json = array('result'=>'1', 'msg'=>$sku .' ('.$name.'), QUANTITY = <font style="color: red">'.$qtycount.'</font>');	
				}
				
			}
				
			}else{
				
				$json = array('result'=>'0', 'msg'=>'ITEMS TIDAK ADA DI MASTER PRODUCT');	
			}
			
			
		}
		$json_string = json_encode($json);
		echo $json_string;
	}else if($_GET['act'] == 'counteritemsnasional'){
		
		$sku = $_POST['sku'];
		$mpi = $_GET['mpi'];
		
		// $sql = "select * from m_piline where (m_piline.sku ='".$sku."' or m_piline.barcode ='".$sku."') and m_pi_key = '".$mpi."' ";
		$sql = "select * from m_piline a inner join m_pi b on a.m_pi_key = b.m_pi_key where (a.sku ='".$sku."' or a.barcode ='".$sku."') and b.inventorytype = 'Nasional' ";
		$result = $connec->query($sql);
		$count = $result->rowCount();
		
		if($count > 0){
			foreach ($result as $r) {
			$qtyon = $r['qtycount'];
			// $pn = $r['name'];	

			$lastqty = $qtyon + 1;
		
			if($sku != ""){
				$statement1 = $connec->query("update m_piline set qtycount = '".$lastqty."' where (sku = '".$sku."' or barcode = '".$sku."') ");
			}else{
				$json = array('result'=>'0', 'msg'=>'SKU tidak boleh kosong');	
			}
		

			
			if($statement1){	
				$json = array('result'=>'1', 'msg'=>$sku .', QUANTITY = <font style="color: red">'.$lastqty.'</font>');	
			}else{
				$json = array('result'=>'0', 'msg'=>'Gagal ,coba lagi nanti');	
				
			}				
			}
			
		}else{
			
			
			
			// $qtysales = 0;
			// $qtyout= 0;
			// $count1 = 0;
			// if($sku != ""){
				
				// $ceksku = "select m_product_id, sku, name, coalesce(price, 0) from pos_mproduct where (sku ='".$sku."' or barcode = '".$sku."')";
				// $cs = $connec->query($ceksku);
				// $count1 = $cs->rowCount();
				
			// }
			
			if($count1 > 0){

				// foreach($cs as $mpii){
					// $m_pro_id = $mpii['m_product_id'];
					// $name = $mpii['name'];
					// $sku = $mpii['sku'];
				// }
		
				// $getmpi = "select * from m_pi where m_pi_key ='".$mpi."'";
				// $gm = $connec->query($getmpi);
		
				// foreach($gm as $rr){
						
					// $hasil = get_data_erp($rr['m_locator_id'], $m_pro_id, $org_key, $ss); //php curl
					// $j_hasil = json_decode($hasil, true);
								
					// $qtyon= $j_hasil['qtyon'];			
					// $price= $j_hasil['price'];			
					// $pricebuy= $j_hasil['pricebuy'];			
					// $statuss= $j_hasil['statuss'];			
		
					// $statusss= $j_hasil['statusss'];			
					// $barcode= $j_hasil['barcode'];			
		
					// $qtycount = 1;
					// $statement1 = $connec->query("insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, postdate,m_storage_id, m_product_id, sku, qtyerp, qtycount, qtysales, price, status, qtysalesout, status1, barcode, hargabeli) 
					// VALUES ('".$rr['m_pi_key']."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".date('Y-m-d H:i:s')."','".$rr['m_locator_id']."','".$m_pro_id."', '".$sku."', '".$qtyon."', '".$qtycount."', '".$qtysales."', '".$price."', '".$statuss."', '".$qtyout."', '".$statusss."', '".$barcode."','".$pricebuy."')"); 
					
					
					// if($statement1){
						// $connec->query("update pos_mproduct set isactived = 0 where sku = '".$sku."'");
						// $json = array('result'=>'1', 'msg'=>$sku .' ('.$name.'), QUANTITY = <font style="color: red">'.$qtycount.'</font>');	
					// }
					
				// }
			}else{
				
				$json = array('result'=>'0', 'msg'=>'ITEMS TIDAK ADA DI MASTER PRODUCT');	
			}
			
			$json = array('result'=>'0', 'msg'=>'ITEMS BELUM MEMILIKI HEADER');	
			
		}
		$json_string = json_encode($json);
		echo $json_string;
	}else if($_GET['act'] == 'listinvscan'){
		$html = "";
		$sku = $_GET['sku'];
		if($sku != ""){
			
			$list_line = "select * from inv_temp_nasional where sku = '".$sku."' order by sku desc limit 100";
			
		}else{
			
			$list_line = "select * from inv_temp_nasional order by sku desc limit 100";
			
		}
		
		$no = 1;
		foreach ($connec->query($list_line) as $row1) {
		$status = "<font style='color: red'>NOT YET</font>";
		if($row1['status'] == '1'){
			$status = "<font style='color: red'>IMPORTED</font>";
		}
		
		$nama_product = "-";
		$pr = $connec->query("select * from pos_mproduct where (sku = '".$row1['sku']."' or barcode = '".$row1['sku']."') ");
			foreach ($pr as $rows) {
				$nama_product = $rows['name'];
			}
							$html .= '<tr>
								<td>'.$no.'</td>
								<td><font style="font-weight: bold">'.$row1['sku'].'</font><br> <font style="color: green;font-weight: bold">'.$nama_product.'</font></td>
	
								<td>
								
								<div class="form-inline"> 
									'.$row1['qty'].' <br>
								</div>		
										
								
								</td>
								<td>'.$row1['user_input'].'</td>
								<td>'.$status.'</td>
							</tr>
							
							<div class="modal fade" id="exampleModal'.$row1['id'].'" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin delete items?</h5>
								
									<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									SKU : <b>'.$row1['sku'].'</b><br>
									Nama : <b>'.$nama_product.'</b>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
									<button type="button" class="btn btn-danger" onclick="deleteLine(\''.$row1['id'].'\');" class="">YAKIN</button>
								</div>
								</div>
							</div>
							</div>';
			$no++;
			
		}
		
		echo $html;
		
	}else if($_GET['act'] == 'listinvnasional'){
		$html = "";
		$sku = str_replace(' ', '', $_GET['sku']);
		if($sku != ""){
			
			// $list_line = "select distinct m_piline.insertdate, m_piline.m_piline_key, m_piline.barcode, m_piline.sku ,m_piline.qtyerp, m_piline.qtycount, pos_mproduct.name, m_pi.status from m_pi inner join m_piline on m_pi.m_pi_key = m_piline.m_pi_key left join pos_mproduct on m_piline.sku = pos_mproduct.sku where m_pi.m_pi_key = '".$_GET['m_pi']."' and m_pi.status = '1' and 
			// (m_piline.sku like '%".$sku."%' or LOWER(pos_mproduct.name) like LOWER('%".$sku."%')) order by m_piline.insertdate desc limit 50";
			
			$list_line = "select distinct m_piline.insertdate, m_piline.m_piline_key, m_piline.barcode, m_piline.sku ,m_piline.qtyerp, m_piline.qtycount, pos_mproduct.name, m_pi.status from m_pi inner join m_piline on m_pi.m_pi_key = m_piline.m_pi_key left join pos_mproduct on m_piline.sku = pos_mproduct.sku where m_pi.status = '1' and 
			(m_piline.sku like '%".$sku."%' or m_piline.barcode like '%".$sku."%' or LOWER(pos_mproduct.name) like LOWER('%".$sku."%')) order by m_piline.insertdate desc limit 50";
			
		}else{
			
			$list_line = "select distinct m_piline.insertdate, m_piline.m_piline_key, m_piline.barcode, m_piline.sku ,m_piline.qtyerp, m_piline.qtycount, pos_mproduct.name, m_pi.status from m_pi inner join m_piline on m_pi.m_pi_key = m_piline.m_pi_key left join pos_mproduct on m_piline.sku = pos_mproduct.sku where m_pi.m_pi_key = '".$_GET['m_pi']."' and m_pi.status = '1' order by m_piline.insertdate desc limit 50";
			
		}

		$no = 1;
		foreach ($connec->query($list_line) as $row1) {	
		
		$barcode = "";
		if(!empty($row1['barcode']) || $row1['barcode'] != ""){
			$barcode = "(".$row1['barcode'].")";
		}
		
							$html .= '<tr>
								<td>'.$no.'</td>
								<td><button type="button" style="display: inline-block; background: red; color: white" data-toggle="modal" data-target="#exampleModal'.$row1['m_piline_key'].'"><i class="fa fa-times"></i></button><br><font style="font-weight: bold">'.$row1['sku'].' '.$barcode.'</font><br> <font style="color: green;font-weight: bold">'.$row1['name'].'</font></td>
	
								<td>
								
								<div class="form-inline"> 
								<input type="number" onchange="changeQty(\''.$row1['sku'].'\', \''.$row1['name'].'\', \''.$_GET['m_pi'].'\');" id="qtycount'.$row1['sku'].'" class="form-control" value="'.$row1['qtycount'].'"> <br>
								
									<button type="button" style="display: inline-block; background: blue; color: white" onclick="changeQtyPlus(\''.$row1['sku'].'\', \''.$row1['name'].'\', \''.$_GET['m_pi'].'\');" class=""><i class="fa fa-plus"></i></button>
									&nbsp
									<button type="button" style="display: inline-block; background: #ba3737; color: white" onclick="changeQtyMinus(\''.$row1['sku'].'\', \''.$row1['name'].'\', \''.$_GET['m_pi'].'\');" class=""><i class="fa fa-minus"></i></button>

								</div>		
								</td>
							</tr>
							<div class="modal fade" id="exampleModal'.$row1['m_piline_key'].'" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin delete line?</h5>
								
									<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									SKU : <b>'.$row1['sku'].'</b><br>
									Nama : <b>'.$row1['name'].'</b>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
									<button type="button" class="btn btn-danger" onclick="deleteLine(\''.$row1['m_piline_key'].'\');" class="">YAKIN</button>
								</div>
								</div>
							</div>
							</div>';
			$no++;
			
		}
		
		echo $html;
		
	}else if($_GET['act'] == 'verifinvnasional'){
		$html = "";
		$sku = str_replace('', '', $_GET['sku']);
		$show = str_replace('', '', $_GET['show']);
								
		$list_line = "select distinct ((m_piline.qtycount + m_piline.qtysales) - (m_piline.qtyerp - m_piline.qtysalesout)) variant, m_piline.sku, m_piline.barcode ,m_piline.qtyerp, m_piline.qtysales, m_piline.qtycount, m_piline.qtysalesout, pos_mproduct.name, m_pi.status, m_piline.verifiedcount from m_pi inner join m_piline on m_pi.m_pi_key = m_piline.m_pi_key left join pos_mproduct on m_piline.sku = pos_mproduct.sku 
		where m_pi.m_pi_key = '".$_GET['m_pi']."' and m_pi.status = '2'   ";
		
		if($sku != ""){
			$list_line .= " and (m_piline.sku like '%".$sku."%' or m_piline.barcode like '%".$sku."%' or LOWER(pos_mproduct.name) like LOWER('%".$sku."%'))";
			
		}
		
		if($show != "1"){
			if($show == '2'){
				
				$list_line .= " and ((m_piline.qtycount + m_piline.qtysales) - (m_piline.qtyerp - m_piline.qtysalesout)) < 0";
			}
			
			if($show == '3'){
				
				$list_line .= " and ((m_piline.qtycount + m_piline.qtysales) - (m_piline.qtyerp - m_piline.qtysalesout)) > 0";
			}
			
		}
		// else{
			
			// $list_line = "select distinct ((m_piline.qtycount + m_piline.qtysales) - (m_piline.qtyerp - m_piline.qtysalesout)) variant, m_piline.sku, m_piline.barcode ,m_piline.qtyerp, m_piline.qtysales, m_piline.qtycount, m_piline.qtysalesout, pos_mproduct.name, m_pi.status, m_piline.verifiedcount from m_pi inner join m_piline on m_pi.m_pi_key = m_piline.m_pi_key left join pos_mproduct on m_piline.sku = pos_mproduct.sku 
			// where m_pi.m_pi_key = '".$_GET['m_pi']."' and m_pi.status = '2'  order by variant asc";
			
		// }	
		
		
		$list_line .= " order by variant asc";
		
		$no = 1;
		foreach ($connec->query($list_line) as $row1) {	
		$variant = (int)$row1['variant'];
		$qtyerpreal = $row1['qtyerp'] - $row1['qtysalesout'];
		if($row1['verifiedcount'] == ''){
			
			$vc = 0;
		}else{
			
			$vc = $row1['verifiedcount'];
		}
		
		
		if($vc > 0){
			
			$color = 'style="background-color: #ffa597"';
			
		}else{
			$color = '';
			
		}
		
		if($row1['barcode'] != ""){
			
			$barc = '('.$row1['barcode'].')';
		}else{
			$barc = "";
			
		}

							$html .= '<tr class="header" style="background: #e1e5fa">
				
							<td colspan="5"><font style="font-weight: bold">'.$row1['sku'].' '.$barc.'</font> ('.$row1['name'].')</td>
							</tr>
	
							<tr class="header1" style="background: #f0f1f2">
								<td style="width: 150px">Counter</td>
								<td>ERP</td>
								<td>Sales</td>
								<td>Varian</td>
								<td>Verif</td>
								
								

							</tr>
							<tr class="header2" '.$color.' style="font-size: 16px">
	
								<td>
								
								<div class="form-inline"> 
								<input type="number" onkeydown="enterKey(this, event, \''.$row1['sku'].'\', \''.str_replace("'", "",$row1['name']).'\',\''.$_GET['m_pi'].'\');" name="qtycount'.$row1['sku'].'" id="qtycount'.$row1['sku'].'" class="form-control" value="'.$row1['qtycount'].'"> 
								</div>		
										
								
								</td>
								<td>'.$qtyerpreal.'</td>
								<td>'.$row1['qtysales'].'</td>
								<td>'.$variant.'</td>
								<td>'.$vc.'</td>
							</tr>';
					
				
					
		$no++; 
			
		}
		
		echo $html;
		
	}else if($_GET['act'] == 'updatecounter'){
		
		$sku = $_POST['sku'];
		$qtyon = $_POST['quan'];
		$nama = $_POST['nama'];
		$mpi = $_GET['mpi'];
		
		
			if($insertfrom == 'M'){
				
				$connec->query("update m_pi set insertfrommobile = 'Y' where m_pi_key = '".$mpi."'");
			}else if($insertfrom == 'W'){
				$connec->query("update m_pi set insertfromweb = 'Y' where m_pi_key = '".$mpi."'");
				
				
			}
		
			if($sku != ""){
				
				
				$statement1 = $connec->query("update m_piline set qtycount = '".$qtyon."' where sku = '".$sku."' and date(insertdate) = '".date('Y-m-d')."'");
			}else{
				
				$json = array('result'=>'0', 'msg'=>'SKU tidak boleh kosong');	
			}
			
			
			
			if($statement1){
				$json = array('result'=>'1', 'msg'=>$sku .' ('.$nama.') QUANTITY = <font style="color: red">'.$qtyon.'</font>');	
			}else{
				$json = array('result'=>'0', 'msg'=>'Gagal ,coba lagi nanti');	
				
			}				
			

		$json_string = json_encode($json);
		echo $json_string;
	}else if($_GET['act'] == 'updatecounterinvnasional'){
		
			$sku = $_POST['sku'];
			$id = $_POST['id'];
			$quan = $_POST['quan'];

		
			if($sku != ""){
				$quan = 1;
				$statement1 = $connec->query("INSERT INTO inv_temp_nasional
				(id, sku, qty, tanggal, status, user_input)
				VALUES('".guid()."', '".$sku."', ".$quan.", '".date('Y-m-d H:i:s')."', '0', '".$_SESSION['username']."');");
			}else{
				$statement1 = $connec->query("update inv_temp_nasional set qty = '".$quan."' where id = '".$id."'");
				$getsku = $connec->query("select sku from inv_temp_nasional where id = '".$id."'");
				foreach ($getsku as $gs) {
					
					$sku = $gs['sku'];
				}
			}
			if($statement1){
				$json = array('result'=>'1', 'msg'=>$sku .' | QUANTITY = <font style="color: red">'.$quan.'</font>');	
			}else{
				$json = array('result'=>'0', 'msg'=>'Gagal ,coba lagi nanti');	
				
			}				
			

		$json_string = json_encode($json);
		echo $json_string;
	}else if($_GET['act'] == 'prosesdatanasional'){
		$cekqty = "select * from inv_temp_nasional where status != 1 order by sku asc";
		$result = $connec->query($cekqty);
		$count = $result->rowCount();
		$qqq ="";
		$no = 0;
		$nox = 0;
		if($count > 0){
			foreach ($result as $tot) {
				if($tot['sku'] != ""){
					$jum = 0;
					$cekjum = "select count(m_piline_key) jum from m_piline where (sku='".$tot['sku']."' or barcode='".$tot['sku']."') ";
					$result_jum = $connec->query($cekjum);
					foreach($result_jum as $rrr){
						$jum = $rrr['jum'];
					}

					if($jum > 0){
						$upcount = $connec->query("update m_piline set qtycount = qtycount + ".$tot['qty']." where (sku='".$tot['sku']."' or barcode='".$tot['sku']."') ");
						if($upcount){
							$qqq = "update inv_temp_nasional set status = 1 where id = '".$tot['id']."' ";
							$connec->query($qqq);
							$no++;
						}else{
							$nox++;	
						}
					}else{
						$nox++;	
					}
				}		
			}
			$json = array('result'=>'1', 'msg'=>'Berhasil proses '.$no.', Belum ada header '.$nox);
		}else{
			$json = array('result'=>'1', 'msg'=>'Tidak ada items yg diproses');
			
		}
		$json_string = json_encode($json);	
		echo $json_string;	
	
	}else if($_GET['act'] == 'hapusdatanasional'){
		$cekqty = "delete from inv_temp_nasional where user_input = '".$useridcuy."'";
		$result = $connec->query($cekqty);
		if($result){
			$json = array('result'=>'1', 'msg'=>'Berhasil hapus items');
		}else{
			$json = array('result'=>'1', 'msg'=>'Tidak ada items yg dihapus');
			
		}
		$json_string = json_encode($json);	
		echo $json_string;	
	
	}else if($_GET['act'] == 'loopall'){
		$result = $connec->query("select * from pos_mproduct ");
		foreach ($result as $tot) {
			$connec->query("INSERT INTO inv_temp_nasional
			(id, sku, qty, tanggal, status, user_input)
			VALUES('".guid()."', '".$tot['sku']."', 1, '".date('Y-m-d H:i:s')."', '0', '".$_SESSION['username']."');");
			
		}
		

	}else if($_GET['act'] == 'deleteline'){
		
			$m_piline_key = $_POST['m_piline_key'];
			$sku = '';
			$getsku = $connec->query("select sku from m_piline where m_piline_key = '".$m_piline_key."'");
			foreach ($getsku as $gs) {
				
				$sku = $gs['sku'];
			}
			
			$statement1 = $connec->query("delete from m_piline where m_piline_key = '".$m_piline_key."'");
			
			
			
			if($statement1){
				$connec->query("update pos_mproduct set isactived = '1' where sku = '".$sku."'");
				$json = array('result'=>'1', 'msg'=>'Berhasil delete line');	
			}else{
				$json = array('result'=>'0', 'msg'=>'Gagal ,coba lagi nanti');	
				
			}				
			

		$json_string = json_encode($json);
		echo $json_string;
	}
	else if($_GET['act'] == 'updateverifikasi'){
		
		
		
		
		$sku = $_POST['sku'];
		$qtyon = $_POST['quan'];
		$nama = $_POST['nama'];
		$mpi = $_GET['mpi'];
		
		if($insertfrom == 'M'){
				
				$connec->query("update m_pi set insertfrommobile = 'Y' where m_pi_key = '".$mpi."'");
			}else if($insertfrom == 'W'){
				$connec->query("update m_pi set insertfromweb = 'Y' where m_pi_key = '".$mpi."'");
				
				
			}
		
		$sql = "select m_piline.verifiedcount from m_piline where m_piline.sku ='".$sku."'";
		$result = $connec->query($sql);
		foreach ($result as $row) {
				
				if($row['verifiedcount'] == ''){
					$vc = 0;
					
				}else{
					
					$vc = $row['verifiedcount'];
				}
				
			}
		
			$totvc = $vc + 1;
			
			if($sku != ""){
				
				
				$statement1 = $connec->query("update m_piline set qtycount = '".$qtyon."', verifiedcount = '".$totvc."' where sku = '".$sku."' and date(m_piline.insertdate) = '".date('Y-m-d')."'");
			}else{
				
				$json = array('result'=>'0', 'msg'=>'SKU tidak boleh kosong');	
			}
		
			
			
			if($statement1){
				$json = array('result'=>'1', 'msg'=>$sku .' ('.$nama.') QUANTITY = <font style="color: red">'.$qtyon.'</font>');	
			}else{
				$json = array('result'=>'0', 'msg'=>'Gagal ,coba lagi nanti');	
				
			}				
			

		$json_string = json_encode($json);
		echo $json_string;
	}else if($_GET['act'] == 'updatecounternasional'){
		
		$sku = $_POST['sku'];
		$qtyon = $_POST['quan'];
		$nama = $_POST['nama'];
		$mpi = $_GET['mpi'];
		
		
			if($insertfrom == 'M'){
				
				$connec->query("update m_pi set insertfrommobile = 'Y' where m_pi_key = '".$mpi."'");
			}else if($insertfrom == 'W'){
				$connec->query("update m_pi set insertfromweb = 'Y' where m_pi_key = '".$mpi."'");
				
				
			}
		
			if($sku != ""){
				
				
				$statement1 = $connec->query("update m_piline set qtycount = '".$qtyon."' where sku = '".$sku."' ");
			}else{
				
				$json = array('result'=>'0', 'msg'=>'SKU tidak boleh kosong');	
			}
			
			
			
			if($statement1){
				$json = array('result'=>'1', 'msg'=>$sku .' ('.$nama.') QUANTITY = <font style="color: red">'.$qtyon.'</font>');	
			}else{
				$json = array('result'=>'0', 'msg'=>'Gagal ,coba lagi nanti');	
				
			}				
			

		$json_string = json_encode($json);
		echo $json_string;
	}else if($_GET['act'] == 'updateverifikasinasional'){
		
		
		
		
		$sku = $_POST['sku'];
		$qtyon = $_POST['quan'];
		$nama = $_POST['nama'];
		$mpi = $_GET['mpi'];
		
		if($insertfrom == 'M'){
				
				$connec->query("update m_pi set insertfrommobile = 'Y' where m_pi_key = '".$mpi."'");
			}else if($insertfrom == 'W'){
				$connec->query("update m_pi set insertfromweb = 'Y' where m_pi_key = '".$mpi."'");
				
				
			}
		
		$sql = "select m_piline.verifiedcount from m_piline where m_piline.sku ='".$sku."'";
		$result = $connec->query($sql);
		foreach ($result as $row) {
				
				if($row['verifiedcount'] == ''){
					$vc = 0;
					
				}else{
					
					$vc = $row['verifiedcount'];
				}
				
			}
		
			$totvc = $vc + 1;
			
			if($sku != ""){
				
				
				$statement1 = $connec->query("update m_piline set qtycount = '".$qtyon."', verifiedcount = '".$totvc."' where sku = '".$sku."' ");
			}else{
				
				$json = array('result'=>'0', 'msg'=>'SKU tidak boleh kosong');	
			}
		
			
			
			if($statement1){
				$json = array('result'=>'1', 'msg'=>$sku .' ('.$nama.') QUANTITY = <font style="color: red">'.$qtyon.'</font>');	
			}else{
				$json = array('result'=>'0', 'msg'=>'Gagal ,coba lagi nanti');	
				
			}				
			

		$json_string = json_encode($json);
		echo $json_string;
	}else if($_GET['act'] == 'verifikasi'){
		
		$pi_key = $_POST['m_pi'];
		
		
			$statement1 = $connec->query("update m_pi set status = '2' where m_pi_key = '".$pi_key."'");
			
			if($statement1){
				$json = array('result'=>'1');	
			}else{
				$json = array('result'=>'0');	
				
			}				
			

		$json_string = json_encode($json);
		echo $json_string;
	}else if($_GET['act'] == 'batal'){
		
		$pi_key = $_POST['m_pi'];
		
		
			$statement1 = $connec->query("update m_pi set status = '5' where m_pi_key = '".$pi_key."'");
			
			if($statement1){
				
				$list_items = $connec->query("select sku from m_piline where m_pi_key = '".$pi_key."'");
				foreach($list_items as $rrr){
					
					$connec->query("update pos_mproduct set isactived = '1' where sku = '".$rrr['sku']."'");
					
				}
				
				// $statement2 = $connec->query("update pos_mproduct set isactived = '1' where sku in
							// (
								// select sku from m_piline where m_pi_key = '".$pi_key."'
							// )");
				
				// if($statement2){
					// $json = array('result'=>'1');
					
				// }else{
					$json = array('result'=>'1');
				// }
				
				
					
			}else{
				$json = array('result'=>'0');	
				
			}				
			

		$json_string = json_encode($json);
		echo $json_string;
	}else if($_GET['act'] == 'reset'){
		
		
			$connec->query("update pos_mproduct set isactived = 1");
			
			header("Location: ../pigantung.php");
			
			
	}else if($_GET['act'] == 'reset_active'){
		
		$statement1 = $connec->query("update pos_mproduct set isactived ='1'
			where sku not in (select sku from m_piline a inner join m_pi b
			on a.m_pi_key = b.m_pi_key
			where date(b.insertdate) = date(now()) and b.status in ('1','2'))");
			
		if($statement1){
			$json = array('result'=>'1');
		}else{
		
			$json = array('result'=>'0');	
		}
		
		$json_string = json_encode($json);
		echo $json_string;
		
	}else if($_GET['act'] == 'cleansing_data'){
		
		$yd = date('Y-m-d', strtotime("-2 days"));
		
		$delete_header = $connec->query("delete from m_pi where date(insertdate) < '".$yd."' ");
		$delete_line = $connec->query("delete from m_piline where date(insertdate) < '".$yd."' ");
			
		if($delete_line){
			$json = array('result'=>'1');
		}else{
		
			$json = array('result'=>'0');	
		}
		
		$json_string = json_encode($json);
		echo $json_string;
		
	}else if($_GET['act'] == 'send_cashier'){
		
		// $sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		// $results = $connec->query($sqll);
		// foreach ($results as $r) {
			// $org_keys = $r["ad_morg_key"];	
		// }
			
			
		// $jsons = get_data_balance($org_keys);
		// $arr = json_decode($jsons, true);
		// $jum = count($arr);
		// $s = array();
		// if($jum > 0){
			
			// foreach($arr as $item) {
				
				
				
			// }
			
			
		// }	
			$tgl = $_GET['tgl'];
		
			$connec->query("update pos_dcashierbalance  set issync=null where date(insertdate) between '".$tgl."' and '".$tgl."'");
			
			header("Location: ../cashier_balance.php");
			
			
	}else if($_GET['act'] == 'send_cashier'){
		
		// $sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		// $results = $connec->query($sqll);
		// foreach ($results as $r) {
			// $org_keys = $r["ad_morg_key"];	
		// }
			
			
		// $jsons = get_data_balance($org_keys);
		// $arr = json_decode($jsons, true);
		// $jum = count($arr);
		// $s = array();
		// if($jum > 0){
			
			// foreach($arr as $item) {
				
				
				
			// }
			
			
		// }	
			$tgl = $_GET['tgl'];
		
			$connec->query("update pos_dcashierbalance  set issync=null where date(insertdate) between '".$tgl."' and '".$tgl."'");
			
			header("Location: ../cashier_balance.php");
			
			
	}else if($_GET['act'] == 'send_cashier1'){
		
		// $sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		// $results = $connec->query($sqll);
		// foreach ($results as $r) {
			// $org_keys = $r["ad_morg_key"];	
		// }
			
			
		// $jsons = get_data_balance($org_keys);
		// $arr = json_decode($jsons, true);
		// $jum = count($arr);
		// $s = array();
		// if($jum > 0){
			
			// foreach($arr as $item) {
				
				
				
			// }
			
			
		// }	
			
		
			$connec->query("update pos_dcashierbalance  set issync=null where date(insertdate) between '2022-12-17' and '2022-12-19'");
			
			header("Location: ../cashier_balance.php");
			
			
	}else if($_GET['act'] == 'release_all'){

			$no = 0;
			$items = array();
			$pi_key = $_POST['m_pi'];
			$sql = "select * from m_pi where m_pi_key ='".$pi_key."'";
			$result = $connec->query($sql);
			foreach ($result as $row) {
				
						$a = $row['ad_client_id'];
						$b = $row['ad_org_id'];
						$c = $row['insertdate'];
						$d = $row['insertby'];
						$e = $row['m_locator_id'];
						$f = $row['inventorytype'];
						$ff = $row['name'];
						$g = $row['description'];
						$h = $row['movementdate'];
						$i = $row['approvedby'];
						$j = $row['status'];
						$k = $row['rack_name'];
						$l = $row['postby'];
						$m = $row['postdate'];
						$cat = $row['category'];
						$n = $row['isactived'];
						$o = $row['insertfrommobile'];
						$p = $row['insertfromweb'];
						
						
						
						$pi_cuy = array(
							"pi_key"=>$pi_key,
							"ad_client_id"=>$a,
							"ad_org_id"=>$b,
							"insertdate"=>$c,
							"insertby"=>$d,
							"m_locator_id"=>$e,
							"inventorytype"=>$f,
							"name"=>$ff,
							"description"=>$g,
							"movementdate"=>$h,
							"approvedby"=>$i,
							"status"=>$j,
							"rack_name"=>$k,
							"postby"=>$l,
							"postdate"=>$m,
							"category"=>$cat,
							"isactived"=>$n,
							"insertfrommobile"=>$o,
							"insertfromweb"=>$p,
							
						);
							
							$sql_line = "select m_piline.*, pos_mproduct.name from m_piline left join pos_mproduct on m_piline.sku = pos_mproduct.sku where m_piline.m_pi_key ='".$pi_key."' and m_piline.issync =0";
							
							
							
							foreach ($connec->query($sql_line) as $rline) {
								$connec->query("update pos_mproduct set isactived = 1 where sku = '".$rline['sku']."'");
								$items[] = array(
									'm_piline_key'	=>$rline['m_piline_key'], 
									'm_pi_key' 		=>$rline['m_pi_key'], 
									'ad_client_id' 	=>$rline['ad_client_id'], 
									'ad_org_id' 	=>$rline['ad_org_id'], 
									'isactived' 	=>$rline['isactived'], 
									'insertdate' 	=>$rline['insertdate'], 
									'insertby' 		=>$rline['insertby'], 
									'postby' 		=>$rline['postby'], 
									'postdate' 		=>$rline['postdate'], 
									'm_storage_id' 	=>$rline['m_storage_id'], 
									'm_product_id' 	=>$rline['m_product_id'], 
									'sku' 			=>$rline['sku'], 
									'name' 			=>$rline['name'], 
									'qtyerp' 		=>$rline['qtyerp'], 
									'qtycount' 		=>$rline['qtycount'], 
									'issync' 		=>$rline['issync'], 
									'status' 		=>$rline['status'], 
									'verifiedcount' =>$rline['verifiedcount'], 
									'qtysales' 		=>$rline['qtysales'], 
									'price' 		=>$rline['price'], 
									'qtysalesout' 	=>$rline['qtysalesout'],
									'hargabeli' 	=>$rline['hargabeli']
								);
								
							}	
							
							$allarray = array("pi"=>$pi_cuy, "piline"=>$items);
							
							
								$items_json = json_encode($allarray);
								$hasil = piline_semua($items_json);
								// var_dump($hasil);
								$j_hasil = json_decode($hasil, true);
								
								if(!empty($j_hasil)){
									
									$connec->query("update m_pi set status = '3' where m_pi_key ='".$pi_key."'");
									
								}
								// var_dump($allarray);

						
				
				
				
						foreach($j_hasil as $r) {
									$statement1 = $connec->query("update m_piline set issync = '".$r['status']."' where m_piline_key = '".$r['m_piline_key']."' 
									and m_pi_key ='".$r['m_pi_key']."'");
									if($statement1){
										
										$up = $connec->query("update pos_mproduct set isactived = 1 where sku = '".$r['sku']."'");
										if($up){
											
											$no = $no +1;
										}
										
										
									}else{
											$no = $no +1;
				
									}			
					
							}
							
							
							
					
						
				$json = array('result'=>'1', 'msg'=>'Berhasil mengirim '.$no.' data');			
				$json_string = json_encode($json);
				echo $json_string;		
						
			}
			
			

		

	}else if($_GET['act'] == 'release_all_nasional'){

			$no = 0;
			$items = array();
			$pi_key = $_POST['m_pi'];
			$sql = "select * from m_pi where m_pi_key ='".$pi_key."'";
			$result = $connec->query($sql);
			foreach ($result as $row) {
				
						$a = $row['ad_client_id'];
						$b = $row['ad_org_id'];
						$c = $row['insertdate'];
						$d = $row['insertby'];
						$e = $row['m_locator_id'];
						$f = $row['inventorytype'];
						$ff = $row['name'];
						$g = $row['description'];
						$h = $row['movementdate'];
						$i = $row['approvedby'];
						$j = $row['status'];
						$k = $row['rack_name'];
						$l = $row['postby'];
						$m = $row['postdate'];
						$cat = $row['category'];
						$n = $row['isactived'];
						$o = $row['insertfrommobile'];
						$p = $row['insertfromweb'];
						
						
						
						$pi_cuy = array(
							"pi_key"=>$pi_key,
							"ad_client_id"=>$a,
							"ad_org_id"=>$b,
							"insertdate"=>$c,
							"insertby"=>$d,
							"m_locator_id"=>$e,
							"inventorytype"=>$f,
							"name"=>$ff,
							"description"=>$g,
							"movementdate"=>$h,
							"approvedby"=>$i,
							"status"=>$j,
							"rack_name"=>$k,
							"postby"=>$l,
							"postdate"=>$m,
							"category"=>$cat,
							"isactived"=>$n,
							"insertfrommobile"=>$o,
							"insertfromweb"=>$p,
							
						);
							$selisih = 0;
							
							$sql_selisih = "select sum((qtycount-qtyerp)*price) selisih from m_piline where m_piline.m_pi_key ='".$pi_key."' and m_piline.issync = 0 and (m_piline.qtycount != 0 or m_piline.qtyerp != 0)";
							foreach ($connec->query($sql_selisih) as $rline) {
								
								$selisih = $rline['selisih'];
								
							}
							$sql_line = "select m_piline.* from m_piline where m_piline.m_pi_key ='".$pi_key."' and m_piline.issync = 0 
							and (m_piline.qtycount != 0 or m_piline.qtyerp != 0) ";
							
							foreach ($connec->query($sql_line) as $rline) {
								
								$npp = "";
								$sql_npp = "select name from pos_mproduct where sku = '".$rline['sku']."'";
								foreach ($connec->query($sql_npp) as $rpp) {
									$npp = $rpp['name'];
								}
								
								$items[] = array(
									'm_piline_key'	=>$rline['m_piline_key'], 
									'm_pi_key' 		=>$rline['m_pi_key'], 
									'ad_client_id' 	=>$rline['ad_client_id'], 
									'ad_org_id' 	=>$rline['ad_org_id'], 
									'isactived' 	=>$rline['isactived'], 
									'insertdate' 	=>$rline['insertdate'], 
									'insertby' 		=>$rline['insertby'], 
									'postby' 		=>$rline['postby'], 
									'postdate' 		=>$rline['postdate'], 
									'm_storage_id' 	=>$rline['m_storage_id'], 
									'm_product_id' 	=>$rline['m_product_id'], 
									'sku' 			=>$rline['sku'], 
									'name' 			=>$npp, 
									'qtyerp' 		=>$rline['qtyerp'], 
									'qtycount' 		=>$rline['qtycount'], 
									'issync' 		=>$rline['issync'], 
									'status' 		=>$rline['status'], 
									'verifiedcount' =>$rline['verifiedcount'], 
									'qtysales' 		=>$rline['qtysales'], 
									'price' 		=>$rline['price'], 
									'qtysalesout' 	=>$rline['qtysalesout'],
									'hargabeli' 	=>$rline['hargabeli']
								);
								
							}	
							
								$allarray = array("pi"=>$pi_cuy, "piline"=>$items);
							
							
								$items_json = json_encode($allarray);
								$hasil = piline_semua_nasional($items_json);
								$j_hasil = json_decode($hasil, true);
								// print_r($hasil);
								
								if(!empty($j_hasil)){
									$qq_error = "";
									foreach($j_hasil as $rf) {
										
										$qq_error = $rf['qq'];
									}
									
									get_spv($kode_toko, $ff, $selisih);
									$connec->query("update m_pi set status = '3' where m_pi_key ='".$pi_key."'");
									$connec->query("update m_piline set issync = '1' where m_pi_key = '".$pi_key."'");
									$json = array('result'=>'1', 'msg'=>'Berhasil release document..','qu'=>$qq_error);			
									
								}else{
									$json = array('result'=>'0', 'msg'=>'Gagal release document..','qu'=>$qq_error);			
									
									
								}
				$json_string = json_encode($json);
				echo $json_string;		
						
			}
	}else if($_GET['act'] == 'release'){

			$no = 0;
			$items = array();
			$pi_key = $_POST['m_pi'];
			// $pi_key = 'AFE7B608A79C409E806CEFC27794B309';
			$sql = "select * from m_pi where m_pi_key ='".$pi_key."'";
			$result = $connec->query($sql);
			foreach ($result as $row) {
				
						$a = $row['ad_client_id'];
						$b = $row['ad_org_id'];
						$c = $row['insertdate'];
						$d = $row['insertby'];
						$e = $row['m_locator_id'];
						$f = $row['inventorytype'];
						$ff = $row['name'];
						$g = $row['description'];
						$h = $row['movementdate'];
						$i = $row['approvedby'];
						$j = $row['status'];
						$k = $row['rack_name'];
						$l = $row['postby'];
						$m = $row['postdate'];
						$n = $row['isactived'];
						
						$stats = push_to_server($pi_key, $a, $b, $c, $d, $e, $f,$ff, $g, $h, $i, $j, $k, $l, $m, $n);
						
						$jsons = json_decode($stats, true);

						
						// var_dump($jsons);
						
						if($jsons['result'] == '1'){
							
							$connec->query("update m_pi set status = '3' where m_pi_key ='".$pi_key."'");
			
							
							$sql_line = "select m_piline.*, pos_mproduct.name from m_piline left join pos_mproduct on m_piline.sku = pos_mproduct.sku where m_piline.m_pi_key ='".$pi_key."' and m_piline.issync =0";
							
							
							
							
							foreach ($connec->query($sql_line) as $rline) {
								$items[] = array(
									'm_piline_key'	=>$rline['m_piline_key'], 
									'm_pi_key' 		=>$rline['m_pi_key'], 
									'ad_client_id' 	=>$rline['ad_client_id'], 
									'ad_org_id' 	=>$rline['ad_org_id'], 
									'isactived' 	=>$rline['isactived'], 
									'insertdate' 	=>$rline['insertdate'], 
									'insertby' 		=>$rline['insertby'], 
									'postby' 		=>$rline['postby'], 
									'postdate' 		=>$rline['postdate'], 
									'm_storage_id' 	=>$rline['m_storage_id'], 
									'm_product_id' 	=>$rline['m_product_id'], 
									'sku' 			=>$rline['sku'], 
									'name' 			=>$rline['name'], 
									'qtyerp' 		=>$rline['qtyerp'], 
									'qtycount' 		=>$rline['qtycount'], 
									'issync' 		=>$rline['issync'], 
									'status' 		=>$rline['status'], 
									'verifiedcount' =>$rline['verifiedcount'], 
									'qtysales' 		=>$rline['qtysales'], 
									'price' 		=>$rline['price'], 
									'qtysalesout' 	=>$rline['qtysalesout']
								);
								
							}	
								$items_json = json_encode($items);
								$hasil = push_to_server_line_all($items_json);
						
								$j_hasil = json_decode($hasil, true);
								
							
								// var_dump($hasil);

								// die();
				
				
				
						foreach($j_hasil as $r) {
									$statement1 = $connec->query("update m_piline set issync = '".$r['status']."' where m_piline_key = '".$r['m_piline_key']."' 
									and m_pi_key ='".$r['m_pi_key']."'");
									if($statement1){
										
									$connec->query("update pos_mproduct set isactived = 1 where sku = '".$r['sku']."'");
										$no = $no +1;
										
									}else{
										// $json = array('result'=>'0');	
				
									}			
					
							}
							
							
							
						}
						
				$json = array('result'=>'1', 'msg'=>'Berhasil mengirim '.$no.' data');			
				$json_string = json_encode($json);
				echo $json_string;		
						
			}
			
			

		

	}else if($_GET['act'] == 'releasegantung'){
			$no = 0;
			$pi_key = $_POST['m_pi'];

							
							
							$sql_line = "select m_piline.*, pos_mproduct.name from m_piline left join pos_mproduct on m_piline.sku = pos_mproduct.sku where m_piline.m_pi_key ='".$pi_key."' and m_piline.issync =0";
						
							
							foreach ($connec->query($sql_line) as $rline) {
								
								// var_dump($rline['m_piline_key']);
								$stats1 = push_to_server_line($rline['m_piline_key'],
								$rline['m_pi_key'],
								$rline['ad_client_id'],
								$rline['ad_org_id'],
								$rline['isactived'],
								$rline['insertdate'],
								$rline['insertby'],
								$rline['postby'],
								$rline['postdate'],
								$rline['m_storage_id'],
								$rline['m_product_id'],
								$rline['sku'],
								$rline['name'],
								$rline['qtyerp'],
								$rline['qtycount'],
								$rline['issync'],
								$rline['status'],
								$rline['verifiedcount'],
								$rline['qtysales'],
								$rline['price'],
								$rline['qtysalesout']
								
								);
								// var_dump($stats1);
								$jsons1 = json_decode($stats1, true);
								if($jsons1['result'] == '1'){
									$statement1 = $connec->query("update m_piline set issync = '1' where sku = '".$jsons1['sku']."' and m_pi_key ='".$pi_key."'");
									if($statement1){
										
									$connec->query("update pos_mproduct set isactived = 1 where sku = '".$jsons1['sku']."'");
							
										
										
										$no = $no +1;
										$json = array('result'=>'1', 'msg'=>'Berhasil mengirim '.$no.' data');	
									}else{
										$json = array('result'=>'0');	
				
									}			
								}
							}
							
							
						
						
						
				$json_string = json_encode($json);
				echo $json_string;		
						
			
			
			

		

	}else if($_GET['act'] == 'sync_inv'){
		
		// $json_url = "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_inv&org_id=".$org_key;
		$jsons = get_data_gudang($ad_morg_key);
		// $jsons = file_get_contents($json_url);



		$arr = json_decode($jsons, true);
		$jum = count($arr);
		$s = array();
		if($jum > 0){
		$truncate = $connec->query("TRUNCATE TABLE inv_mproduct");
		if($truncate){
			
		
			// echo $jum;
			$no = 0;
			
			foreach($arr as $item) { //foreach element in $arr
				$aoi = $item['ad_org_id']; //etc
				$mpi = $item['m_product_id']; //etc
				$mpci = $item['m_product_category_id']; //etc
				$sku = $item['sku']; //etc
				$n = str_replace("'", "", $item['name']); //etc
				$rn = $item['rack_name']; //etc
				// print_r($aoi);
				
				// $ss = "insert into inv_mproduct (insertdate, isactived, insertby, postby, postdate, ad_mclientkey, ad_morg_key, m_product_id, m_product_category_id, sku, name, rack_name) 
					// VALUES ('".date('Y-m-d H:i:s')."','1', 'SYSTEM', 'SYSTEM', '".date('Y-m-d H:i:s')."','D089DFFA729F4A22816BD8838AB0813C', '".$aoi."', '".$mpi."', '".$mpci."','".$sku."', '".$n."', '".$rn."')";
				// print_r($ss);
				// $statement1 = $connec->query("insert into inv_mproduct (insertdate, insertby, postby, postdate, ad_mclient_key, ad_morg_key, m_product_id, m_product_category_id, sku, name, rack_name) 
					// VALUES ('".date('Y-m-d H:i:s')."','SYSTEM', 'SYSTEM', '".date('Y-m-d H:i:s')."','D089DFFA729F4A22816BD8838AB0813C', '".$aoi."', '".$mpi."', '".$mpci."','".$sku."', '".$n."', '".$rn."')");
					
					 
				$s[] = "('".date('Y-m-d H:i:s')."','SYSTEM', 'SYSTEM', '".date('Y-m-d H:i:s')."','D089DFFA729F4A22816BD8838AB0813C', '".$aoi."', '".$mpi."', '".$mpci."','".$sku."', '".$n."', '".$rn."')";	 
					 
				
									
			}
			
			$jum_s = count($s);
			
			if($jum_s > 0){
				$values = implode(", ",$s);
				$qqq = "insert into inv_mproduct (insertdate, insertby, postby, postdate, ad_mclient_key, ad_morg_key, m_product_id, m_product_category_id, sku, name, rack_name) 
						VALUES ".$values.";";
				$suc = $connec->query($qqq);
				
				
				if($suc){
					
					$json = array('result'=>'1', 'msg'=>'Berhasil sync');
					$json_string = json_encode($json);	
					
				}else{
					
					$json = array('result'=>'1', 'msg'=>'Gagal sync, coba lagi nanti','query'=>$qqq);
					$json_string = json_encode($json);	
				}
				
			}else{
				$json = array('result'=>'1', 'msg'=>'Gagal sync, data rack blm ditemukan');
				$json_string = json_encode($json);	
				
			}
			
		}	
			
	}else{
		
				$json = array('result'=>'1', 'msg'=>'Gagal sync, data rack blm ditemukan');
				$json_string = json_encode($json);	
		
	}
		

	echo $json_string;	
				

	}else if($_GET['act'] == 'sync_promo'){
		
		$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$org_keys = $r["ad_morg_key"];	
		}
		// $org_keys = "123123";	
		
		$jsons = get_data_promo($org_keys);
		$arr = json_decode($jsons, true);
		$jum = count($arr);
		$s = array();
		if($jum > 0){
		$truncate = $connec->query("TRUNCATE TABLE pos_mproductdiscount");
		if($truncate){
			
		
			// echo $jum;
			$no = 0;
			
			foreach($arr as $item) { //foreach element in $arr
				$amk = $item['ad_morg_key']; //etc
				$isactived = $item['isactived']; //etc
				$insertdate = $item['insertdate']; //etc
				$insertby = $item['insertby']; //etc
				$discountname = str_replace("'", "\'", $item['discountname']); //etc
				$discounttype = $item['discounttype']; //etc
				$sku = $item['sku']; //etc
				$discount = $item['discount']; //etc
				$fromdate = $item['fromdate']; //etc
				$todate = $item['todate']; //etc
				$typepromo = $item['typepromo']; //etc
				$maxqty = $item['maxqty']; //etc
				$ad_mclient_key = $item['ad_mclient_key']; //etc
					
					 
				$s[] = "('".$ad_mclient_key."', '".$amk."', '".$isactived."', '".date("Y-m-d H:i:s")."','".$insertdate."', '".$insertby."', '".$discountname."', '".$discounttype."','".$sku."', '".$discount."', '".$fromdate."', '".$todate."', '".$typepromo."', '".$maxqty."')";	 
					 
				
									
			}
			
			$jum_s = count($s);
			
			if($jum_s > 0){
				$values = implode(", ",$s);

				$qqq = "insert into pos_mproductdiscount (ad_mclient_key, ad_morg_key, isactived, postdate, insertdate, insertby, discountname, discounttype, sku, discount, fromdate, todate, typepromo, maxqty) 
						VALUES ".$values.";";
						
				$suc = $connec->query($qqq);
				
				
				if($suc){
					
					$json = array('result'=>'1', 'msg'=>'Berhasil sync');
					$json_string = json_encode($json);	
					
				}else{
					
					$json = array('result'=>'1', 'msg'=>'Gagal sync, coba lagi nanti');
					$json_string = json_encode($json);	
				}
				
			}else{
				$json = array('result'=>'1', 'msg'=>'Gagal sync, data rack blm ditemukan');
				$json_string = json_encode($json);	
				
			}
		}	
			
	}else{
		
				$json = array('result'=>'1', 'msg'=>'Promo reguler tidak ditemukan');
				$json_string = json_encode($json);	
		
	}
		

	echo $json_string;	
				

	}else if($_GET['act'] == 'sync_promo_code'){
		
		$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$org_keys = $r["ad_morg_key"];	
		}
		
		
		$jsons = get_data_promo_code($org_keys);
		$arr = json_decode($jsons, true);
		$jum = count($arr);
		$s = array();
		if($jum > 0){
		$truncate = $connec->query("TRUNCATE TABLE pos_mproductdiscountmember");
		if($truncate){
			
		
			// echo $jum;
			$no = 0;
			
			foreach($arr as $item) { //foreach element in $arr
				$amk = $item['ad_morg_key']; //etc
				$isactived = $item['isactived']; //etc
				$insertdate = $item['insertdate']; //etc
				$insertby = $item['insertby']; //etc
				$discountname = str_replace("'", "\'", $item['discountname']); //etc
				$discounttype = $item['discounttype']; //etc
				$sku = $item['sku']; //etc
				$discount = $item['discount']; //etc
				$fromdate = $item['fromdate']; //etc
				$todate = $item['todate']; //etc
				$typepromo = $item['typepromo']; //etc
				$maxqty = $item['maxqty']; //etc
				$afterdiscount = $item['afterdiscount']; //etc
				$ad_mclient_key = $item['ad_mclient_key']; //etc
					
					 
				$s[] = "('".$ad_mclient_key."','".$amk."', '".$isactived."', '".date("Y-m-d H:i:s")."','".$insertdate."', '".$insertby."', '".$discountname."','".$sku."', '".$afterdiscount."', '".$fromdate."', '".$todate."', '".$maxqty."')";	 
					 
				
									
			}
			
			$jum_s = count($s);
			
			if($jum_s > 0){
				$values = implode(", ",$s);

				$suc = $connec->query("insert into pos_mproductdiscountmember (ad_mclient_key, ad_morg_key, isactived, postdate, insertdate, insertby, discountname, sku, pricediscount, fromdate, todate, maxqty) 
						VALUES ".$values.";");
				
				
				if($suc){
					
					$json = array('result'=>'1', 'msg'=>'Berhasil sync');
					$json_string = json_encode($json);	
					
				}else{
					
					$json = array('result'=>'1', 'msg'=>'Gagal sync, coba lagi nanti');
					$json_string = json_encode($json);	
				}
				
			}else{
				$json = array('result'=>'1', 'msg'=>'Gagal sync, data blm ditemukan');
				$json_string = json_encode($json);	
				
			}
		}	
			
		}else{
			
					$json = array('result'=>'1', 'msg'=>'Promo code tidak ditemukan');
					$json_string = json_encode($json);	
			
		}
		echo $json_string;	

	}else if($_GET['act'] == 'sync_promo_buyget'){
		
		$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$org_keys = $r["ad_morg_key"];	
		}
		
		
		$jsons = get_data_promo_buyget($org_keys);
		$arr = json_decode($jsons, true);
		$jum = count($arr);
		$s = array();
		if($jum > 0){
		$truncate = $connec->query("TRUNCATE TABLE pos_mproductbuyget");
		if($truncate){
			
			// echo $jum;
			$no = 0;
			
			foreach($arr as $item) { //foreach element in $arr
				$ad_mclient_key = $item['ad_mclient_key']; //etc
				$ad_morg_key = $item['ad_morg_key']; //etc
				$isactived = $item['isactived']; //etc
				$insertdate = $item['insertdate']; //etc
				$insertby = $item['insertby']; //etc
				$postby = $item['postby']; //etc
				$postdate = $item['postdate']; //etc
				$discountname = 'Buy & Get'; //etc
				$typepromo = $item['typepromo']; //etc
				$skubuy = $item['skubuy']; //etc
				$qtybuy = $item['qtybuy']; //etc
				$skuget = $item['skuget']; //etc
				$qtyget = $item['qtyget']; //etc
				$priceget = $item['priceget']; //etc
				$fromdate = $item['fromdate']; //etc
				$todate = $item['todate']; //etc
				$discount = $item['discount']; //etc
					 
				$s[] = "('".$isactived."','".$ad_mclient_key."', '".$ad_morg_key."', '".date("Y-m-d H:i:s")."','".$insertby."', '".$insertby."', '".$postdate."', '".$discountname."','".$typepromo."', '".$fromdate."', '".$todate."', '".$skubuy."', '".$qtybuy."', '".$skuget."', '".$qtyget."', '".$priceget."', '".$discount."')";	 
			}
			
			
			$jum_s = count($s);
			
			if($jum_s > 0){
				$values = implode(", ",$s);
					
				$qqq = "insert into pos_mproductbuyget (isactived, ad_mclient_key, ad_morg_key, insertdate, insertby, postby, postdate, discountname, typepromo, fromdate, todate, skubuy, qtybuy, skuget, qtyget, priceget, discount) VALUES ".$values.";";
					
				$suc = $connec->query($qqq);
				
				
				if($suc){
					
					$json = array('result'=>'1', 'msg'=>'Berhasil sync');
					$json_string = json_encode($json);	
					
				}else{
					
					$json = array('result'=>'1', 'msg'=>'Gagal sync, coba lagi nanti', 'qq'=>$qqq);
					$json_string = json_encode($json);	
				}
				
			}else{
				$json = array('result'=>'1', 'msg'=>'Gagal sync, data blm ditemukan');
				$json_string = json_encode($json);	
				
			}
		}	
			
		}else{
			
					$json = array('result'=>'1', 'msg'=>'Promo tidak ditemukan');
					$json_string = json_encode($json);	
			
		}
		echo $json_string;	

	}else if($_GET['act'] == 'sync_promo_tebus'){
		
		$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$org_keys = $r["ad_morg_key"];	
		}
		
		
		$jsons = get_data_promo_tebus($org_keys);
		$arr = json_decode($jsons, true);
		$jum = count($arr);
		$s = array();
		if($jum > 0){
		$truncate = $connec->query("TRUNCATE TABLE pos_mproductdiscountmurah");
		if($truncate){
			
			// echo $jum;
			$no = 0;
			
			foreach($arr as $item) { //foreach element in $arr
				$amk = $item['ad_org_id']; //etc
				$insertdate = $item['insertdate']; //etc
				$insertby = $item['insertby']; //etc
				$discountname = str_replace("'", "\'", $item['headername']); //etc
				$sku = $item['sku']; //etc
				$pricediscount = $item['afterdiscount']; //etc
				$fromdate = $item['fromdate']; //etc
				$todate = $item['todate']; //etc
				$maxqty = $item['maxqty']; //etc
					
					 
				$s[] = "('D089DFFA729F4A22816BD8838AB0813C', '".$amk."', '1', '".$insertdate."', '".date('Y-m-d H:i:s')."', '".$insertby."', '".$discountname."', '".$sku."', '".$pricediscount."', 
				'".$fromdate."', '".$todate."', '".$maxqty."')";	 
							
			}
			
			$jum_s = count($s);
			
			if($jum_s > 0){
				$values = implode(", ",$s);

				$ssql = "INSERT INTO pos_mproductdiscountmurah (ad_mclient_key, ad_morg_key, isactived, insertdate, postdate, insertby, discountname, sku, pricediscount, fromdate, todate, limitamount) VALUES ".$values.";";

				$suc = $connec->query($ssql);
				
				
				
				
				if($suc){
					
					$json = array('result'=>'1', 'msg'=>'Berhasil sync');
					$json_string = json_encode($json);	
					
				}else{
					
					$json = array('result'=>'1', 'msg'=>'Gagal sync, coba lagi nanti');
					$json_string = json_encode($json);	
				}
				
			}else{
				$json = array('result'=>'1', 'msg'=>'Gagal sync, data blm ditemukan');
				$json_string = json_encode($json);	
				
			}
		}	
			
	}else{
		
				$json = array('result'=>'1', 'msg'=>'Promo tebus murah tidak ditemukan');
				$json_string = json_encode($json);	
		
	}
		

	echo $json_string;	
	// echo $ssql;	
				

	}else if($_GET['act'] == 'sync_promo_grosir'){
		
		$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$org_keys = $r["ad_morg_key"];	
		}
		// $org_keys = "123123";	
		
		$jsons = get_data_promo_grosir($org_keys);
		$arr = json_decode($jsons, true);
		$jum = count($arr);
		$s = array();
		if($jum > 0){
		$truncate = $connec->query("TRUNCATE TABLE pos_mproductdiscountgrosir_new");
		if($truncate){
			
		
			// echo $jum;
			$no = 0;
			
			foreach($arr as $item) { //foreach element in $arr
				$ad_mclient_key = $item['ad_mclient_key']; //etc
				$ad_morg_key = $item['ad_morg_key']; //etc
				$isactived = $item['isactived']; //etc
				$insertdate = $item['insertdate']; //etc
				$insertby = $item['insertby']; //etc
				$postby = $item['postby']; //etc
				$postdate = $item['postdate']; //etc
				$discountname = str_replace("'", "\'", $item['discountname']); //etc
				$sku = $item['sku']; //etc
				$minbuy = $item['minbuy']; //etc
				$discount = $item['discount']; //etc
				// $discount_2 = $item['discount_2']; //etc
				// $discount_3 = $item['discount_3']; //etc
				$fromdate = $item['fromdate']; //etc
				$todate = $item['todate']; //etc
				 
				$s[] = "('".$ad_mclient_key."', '".$ad_morg_key."', '".$isactived."', '".$insertdate."', '".$insertby."', '".$postby."', 
				'".$postdate."','".$discountname."', '".$sku."', '".$minbuy."', '".$discount."', '".$fromdate."', '".$todate."')";	 
					 
				
									
			}
			
		
			
			$jum_s = count($s);
			
			if($jum_s > 0){
				$values = implode(", ",$s);

				$qqq = "insert into pos_mproductdiscountgrosir_new (ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, 
				postdate, discountname, sku, minbuy, discount, fromdate, todate) 
						VALUES ".$values.";";
						
				$suc = $connec->query($qqq);
				
				
				if($suc){
					
					$json = array('result'=>'1', 'msg'=>'Berhasil sync');
					$json_string = json_encode($json);	
					
				}else{
					
					$json = array('result'=>'1', 'msg'=>'Gagal sync, coba lagi nanti','q'=>$qqq);
					$json_string = json_encode($json);	
				}
				
			}else{
				$json = array('result'=>'1', 'msg'=>'Gagal sync, data rack blm ditemukan');
				$json_string = json_encode($json);	
				
			}
		}	
			
	}else{
		
				$json = array('result'=>'1', 'msg'=>'Gagal sync, data blm ditemukan');
				$json_string = json_encode($json);	
		
	}
		

	echo $json_string;	
				

	}else if($_GET['act'] == 'sync_cat'){
		$truncate = $connec->query("TRUNCATE TABLE inv_mproductcategory");
		if($truncate){
			
			
			
			$json_url = "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_cat&org_id=".$org_key;
			$json = file_get_contents($json_url);

			$arr = json_decode($json, true);
			$jum = count($arr);
			
			$no = 0;
			foreach($arr as $item) { //foreach element in $arr

				$statement1 = $connec->query("INSERT INTO public.inv_mproductcategory
(ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, m_product_category_id, value, name, description, issummary)
VALUES('".$item['ad_client_id']."', '".$item['ad_org_id']."', '1', '".date('Y-m-d H:i:s')."', '".$item['createdby']."', '".$item['updatedby']."', '".date('Y-m-d H:i:s')."', '".$item['m_product_category_id']."', '".$item['value']."', '".$item['name']."', '".$item['description']."', '".$item['issummary']."');");
					
					 
					if($statement1){
						$no = $no+1;
						// if($no == $jum){
							// $json = array('result'=>'1', 'msg'=>'Berhasil sync '.$no.' dari '.$jum.' items');
								
						// }else{
							
							// $json = array('result'=>'0', 'msg'=>'Berhasil sync '.$no.' dari '.$jum.' items');
						// }
						
						
					}	
					
					
									
			}
			$json = array('result'=>'1', 'msg'=>'Berhasil sync '.$no.' dari '.$jum.' items');
			$json_string = json_encode($json);	
			echo $json_string;	
			
				
			
		}	

				

	}else if($_GET['act'] == 'update_sales'){
		
		if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
			$ss = $_GET['status_sales'];

			
			$cek = $connec->query("select * from m_pi_sales where date(tanggal) = '".date('Y-m-d')."'");
			$count = $cek->rowCount();
			
			if($count > 0){
				$statement1 = $connec->query("update m_pi_sales set status_sales = '".$ss."' where date(tanggal) = '".date('Y-m-d')."'");
				
			}else{
				$connec->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
				$sql = "
				insert into m_pi_sales (tanggal, status_sales) VALUES ('".date('Y-m-d')."', '".$ss."'); 
				update pos_mproduct set isactived = 1;";
				
				$statement1 = $connec->prepare($sql);
				$statement1->execute();
				
				
				// $statement1 = $connec->query("");
				
				// $connec->query("");
				
			}
			
			if($statement1){
				$json = array('result'=>'1', 'msg'=>'Berhasil');	
				$_SESSION['status_sales'] = $ss;
			}else{
				$json = array('result'=>'0', 'msg'=>'Gagal ,coba lagi nanti');	
				
			}
			
		}else{
			
			$json = array('result'=>'3', 'msg'=>'Session telah habis, reload dulu halamannya');	
		}
		
			

		$json_string = json_encode($json);
		echo $json_string;
		
		
	}else if($_GET['act'] == 'cek_approval'){
		
			$yd = date('Y-m-d',strtotime(date('Y-m-d') . "-1 days"));
			$cek = $connec->query("select * from m_pi where date(insertdate) between '".$yd."' and '".date('Y-m-d')."' and status = '3'");
			$count = $cek->rowCount();
				$no = 0;
			
			if($count > 0){
				foreach ($cek as $ra) {
					// print_r($ra);
					$hasil = sync_approval($ra['m_pi_key']);
					
			
					$j_hasil = json_decode($hasil, true);
					// print_r($j_hasil);
										
					$mpk = $j_hasil['m_pi_key'];			
					$status = $j_hasil['status'];	

					if($status == '3'){
						
						$update = $connec->query("update m_pi set status = '4' where m_pi_key = '".$mpk."'");
						if($update){
							$no = $no + 1;
						
						}
					}
					
					if($no == 0){
						
						$json = array('result'=>'1', 'msg'=>'Belum ada perubahan');	
					}else{
						
						$json = array('result'=>'1', 'msg'=>'Berhasil sync '.$no.' header');	
					}
					
					// echo $mpk
					
					// if($update){
						// $json = array('result'=>'1', 'msg'=>'Berhasil');
						
					// }
					
				}
				
			}else{
				
				$json = array('result'=>'1', 'msg'=>'Tidak ada list waiting approval');
			}
				
			
			
		$json_string = json_encode($json);
		echo $json_string;
		
		
	}else if($_GET['act'] == 'notif_wa'){
		
	
		$stats = notif_wa();
						
		$jsons = json_decode($stats, true);

		
		if($jsons['result'] == '1'){
			
			$json = array('result'=>'1', 'msg'=>'Berhasil');
			
		}else{
			$json = array('result'=>'1', 'msg'=>'Maaf notification tidak terkirim');
			
			
		}
		
		$json_string = json_encode($json);
		echo $json_string;
	}else if($_GET['act'] == 'nohp_spv'){
		$kode_toko = $_SESSION['kode_toko'];
		$m_pi = $_GET['m_pi'];	
		$cek = $connec->query("select * from m_pi where m_pi_key = '".$m_pi."'");
		foreach ($cek as $row) {
			$doc_no = $row['name'];
		}
		
		$sql_amount = "select SUM(CASE WHEN issync=1 THEN 1 ELSE 0 END) jumsync, sum(qtysales * price) hargasales, sum(qtysalesout * price) hargagantung,  sum(qtyerp * price) hargaerp, sum(qtycount * price) hargafisik, count(sku) jumline from m_piline where m_pi_key = '".$m_pi."'";
		foreach ($connec->query($sql_amount) as $tot) {
			
			$qtyerp = $tot['hargaerp'] - $tot['hargagantung'] - $tot['hargasales'];
			$qtycount = $tot['hargafisik'];

			$jumline = $tot['jumline'];
			$jumsync = $tot['jumsync'];
			$selisih = $qtycount - $qtyerp;
			
		}
		
		
		$stats = get_spv($kode_toko, $doc_no, $selisih);
						
		$jsons = json_decode($stats, true);

		
		if($jsons['result'] == '1'){
			
			$json = array('result'=>'1', 'msg'=>'Berhasil');
			
		}else{
			$json = array('result'=>'1', 'msg'=>'Maaf notification tidak terkirim');
			
			
		}
		
		$json_string = json_encode($json);
		echo $json_string;
		
				
	}else if($_GET['act'] == 'sync_user'){
		$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$org_keys = $r["ad_morg_key"];	
		}
		
		$json_url = "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_user&org_id=".$org_keys;
			$json = file_get_contents($json_url);

			$arr = json_decode($json, true);
			$jum = count($arr);
		
		if($jum > 0){
					$truncate = $connec->query("TRUNCATE TABLE m_pi_users");
		if($truncate){
			
		
			
			
			// echo $jum;
			$no = 0;
			foreach($arr as $item) { //foreach element in $arr
				$ad_muser_key 	= $item['ad_muser_key']; //etc
				$isactived 		= $item['isactived']; //etc
				$userid 		= $item['userid']; //etc
				$username 		= $item['username']; //etc
				$userpwd 		= $item['userpwd']; //etc
				$ad_org_id 		= $item['ad_org_id']; //etc
				$name 			= $item['name']; //etc
				
				$sql = "insert into m_pi_users (ad_muser_key, isactived, userid, username, userpwd, ad_org_id, name) 
					VALUES ('".$ad_muser_key."', '".$isactived."','".$userid."','".$username."','".$userpwd."','".$ad_org_id."','".$name."')";
				
				$statement1 = $connec->query($sql);
					

					
					 
					if($statement1){
						$no = $no+1;

						
					}	
					
					
									
			}
			$json = array('result'=>'1', 'msg'=>'Berhasil sync '.$no.' dari '.$jum.' users');
			
			
				
			
		}
			
			
		}else{
			$json = array('result'=>'1', 'msg'=>'Gagal connect ke server');
			
			
		}
			$json_string = json_encode($json);	
			echo $json_string;	
	}else if($_GET['act'] == 'sync_user_hris'){
		$sqll = "select value as ad_morg_key from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$org_keys = $r["ad_morg_key"];	
		}
		
		$json_url = "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_user_hris&org_id=".$org_keys;
			$json = file_get_contents($json_url);

			$arr = json_decode($json, true);
			$jum = count($arr);
		
		if($jum > 0){
		$truncate = $connec->query("TRUNCATE TABLE m_pi_hris");
		if($truncate){
			
		
			
			
			// echo $jum;
			$no = 0;
			foreach($arr as $item) { //foreach element in $arr
				$nik 	= $item['nik']; //etc
				$nama 	= $item['nama']; //etc
				
				$sql = "insert into m_pi_hris (nik, nama) 
					VALUES ('".$nik."', '".$nama."')";
				
				$statement1 = $connec->query($sql);
					

					
					 
					if($statement1){
						$no = $no+1;

						
					}	
					
					
									
			}
			$json = array('result'=>'1', 'msg'=>'Berhasil sync '.$no.' dari '.$jum.' users');
			
			
				
			
		}
			
			
		}else{
			$json = array('result'=>'1', 'msg'=>'Gagal connect ke server');
			
			
		}
			$json_string = json_encode($json);	
			echo $json_string;	
	}else if($_GET['act'] == 'proses_inv_temp'){
		$sku = $_POST['sku'];
		$qty = $_POST['qty'];
		$tgl = $_POST['tgl'];
		$jumlahpi = $_POST['jumlahpi'];
		$filename = $_POST['filename'];
		
		// $sku = '8262400000064';
		// $qty = '12';
		// $tgl = '2022-06-23';
		// $jumlahpi = '1212';
		
		
		
		$cekqty = "select qtycount from m_piline where sku = '".$sku."' and date(insertdate) = date(now())";
		$result = $connec->query($cekqty);
		$count = $result->rowCount();
		
		if($count > 0){
			// $sql  = "update m_pi_line set qtycount=? where sku=? and date(insertdate)=?";
			// $stmt = $connec->prepare($sql);
			foreach ($result as $tot) {
				$qtycount = $tot['qtycount'];
				$jumqty = (int)$qtycount + (int)$qty;
				
				
				// $cekstat = "select status from inv_temp where sku = '".$sku."' and date(insertdate) = date(now()) and filename = '".$filename."'";
				// $css = $connec->query($cekstat);
			
					// if($rrr['status'] == '0'){
						
						if($sku != ""){
							
							$upcount = $connec->query("update m_piline set qtycount='".$jumqty."' where sku='".$sku."' and date(insertdate)=date(now()) ");
						}else{
							
							$json = array('result'=>'0');
							
						}
						
						
						if($upcount){
							
							$connec->query("update inv_temp set status = 1 where sku = '".$sku."' and date(tanggal) = date(now()) and filename = '".$filename."'");
							$json = array('result'=>'1', 'sku'=>$sku);
						}else{
							$json = array('result'=>'1', 'sku'=>$sku);
							
						}
						
					// }
					
					
				
				

				
			}

			
		}else{
			$json = array('result'=>'1', 'sku'=>$sku.' Tidak ada di line');
			
		}
		$json_string = json_encode($json);	
		echo $json_string;	
	
	}else if($_GET['act'] == 'proses_inv_temps'){
		$filename = $_POST['filename'];
		$no = 0;
		$getinv = "select sku, qty, filename, sum(qty) as jumqty from inv_temp where filename = '".$filename."' and date(tanggal) = date(now()) and status = '0' and sku !='' group by sku, qty, filename, tanggal";
		$con = $connec->query($getinv);
		$concon = $con->rowCount();
		
		
		foreach ($connec->query($getinv) as $gi) {
			
			$cekqty = "select qtycount from m_piline where (sku = '".$gi['sku']."' or barcode = '".$gi['sku']."') and date(insertdate) = date(now())";
			$result = $connec->query($cekqty);
			$count = $result->rowCount();

			if($count > 0){
	
				foreach ($result as $tot) {
					$qtycount = $tot['qtycount'];
					$jumqty = (int)$qtycount + (int)$gi['jumqty'];
	
							
							
							
							$upcount = $connec->query("update m_piline set qtycount='".$jumqty."' where (sku = '".$gi['sku']."' or barcode = '".$gi['sku']."') and date(insertdate)=date(now()) ");
							if($upcount){
								
								$connec->query("update inv_temp set status = 1 where sku = '".$gi['sku']."' and date(tanggal) = date(now()) and filename = '".$filename."'");
								$no = $no + 1;
							}
				}
	
				
			}
			
			
		}
		
		
		
		$json = array('result'=>'1', 'msg'=>'Berhasil proses '.$no.' dari '.$concon.'');
		$json_string = json_encode($json);	
		echo $json_string;	
	
	}else if($_GET['act'] == 'sync_pos_peritems'){
		
		$sku = $_POST['sku'];
		// $sku = "8151000000129";
		$hasil = get_data_stock($org_key, $sku);
		$j_hasil = json_decode($hasil, true);
		
		// $jum = count($hasil);
		
		// if($jum > 0){
			
		foreach($j_hasil as $r) {
			
			$stock_sales = 0;
			
			
			if($r['result'] == 1){
				$data = array(
				"result"=>1,
				'msg'=>$r['sku'] .' ('.$r['namaitem'].'), QUANTITY = <font style="color: red">'.$r['stockqty'].'</font>'
			
			);
			
			$ceksales = $connec->query("select sku, sum(qty) as jj from pos_dsalesline where sku = '".$r['sku']."' and date(insertdate) = date(now()) group by sku");
			foreach ($ceksales as $rs) {
				
					$stock_sales = $rs['jj'];
				}
			
			
			$totqty = $r['stockqty'] - $stock_sales;
			
			
			$cekitems = $connec->query("select count(sku) as jum from pos_mproduct where sku = '".$r['sku']."'");
			foreach ($cekitems as $ra) {
				
					$haha = $ra['jum'];
				}
				
			if($haha > 0){
				$price = $r['price'];
				if($r['price'] == ""){
					
					$price = 0;
				}
				
				$sql = "update pos_mproduct set stockqty='".$totqty."', name = '".substr($r['namaitem'], 0, 49)."', price = '".$price."' where sku='".$r['sku']."'";
				$upcount = $connec->query($sql);
			}else{
				
				$sql = "insert into pos_mproduct (
ad_mclient_key,
ad_morg_key,
isactived,
insertdate,
insertby,
postby,
postdate,
m_product_id,
m_product_category_id,
c_uom_id,
sku,
name,
price,
stockqty,
m_locator_id,
locator_name) VALUES (
				'".$r['ad_client_id']."',
				'".$r['ad_mor_key']."',
				'".$r['isactive']."',
				'".$r['insertdate']."',
				'".$r['insertby']."',
				'".$r['postby']."',
				'".$r['postdate']."',
				'".$r['m_product_id']."',
				'".$r['m_product_category_id']."',
				'".$r['c_uom_id']."',
				'".$r['sku']."',
				'".substr($r['namaitem'], 0, 49)."',
				'".$r['price']."',
				'".$r['stockqty']."',
				'".$r['m_locator_id']."',
				'".$r['locator_name']."'
)";
				
				$upcount = $connec->query($sql);
				
			}
			
			
			if($upcount){
				$data = array(
					"result"=>1,
					'msg'=>$r['sku'] .' ('.$r['namaitem'].'), STOCK = <font style="color: red">'.$totqty.'</font>'
				);
				
			}else{
				
				$data = array(
					"result"=>1,
					'msg'=>'Gagal update stock',
					'q'=>'Gagal update stock '.$sql
				);
			}
				
			}else{
				$data = array(
					"result"=>0,
					"msg"=>"Items tidak ditemukan di ERP"
			
				);
				
			}
			
			
		}
		// }else{
			
			
		// }
		
		
		$json_string = json_encode($data);	
		echo $json_string;
		// echo $sql;
		
	}else if($_GET['act'] == 'sync_pos_peritems_name'){
		
		$sku = $_POST['sku'];
		// $sku = "8151000000129";
		$hasil = get_data_stock_name($org_key, $sku);
		$j_hasil = json_decode($hasil, true);
		
		// $jum = count($hasil);
		
		// if($jum > 0){
			
		foreach($j_hasil as $r) {
			
			$stock_sales = 0;
			
			
			if($r['result'] == 1){
				$data = array(
				"result"=>1,
				'msg'=>$r['sku'] .' ('.$r['namaitem'].'), QUANTITY = <font style="color: red">'.$r['stockqty'].'</font>'
			
			);
			
			$ceksales = $connec->query("select sku, sum(qty) as jj from pos_dsalesline where sku = '".$r['sku']."' and date(insertdate) = date(now()) group by sku");
			foreach ($ceksales as $rs) {
				
					$stock_sales = $rs['jj'];
				}
			
			
			$totqty = $r['stockqty'] - $stock_sales;
			
			
			$cekitems = $connec->query("select count(sku) as jum from pos_mproduct where sku = '".$r['sku']."'");
			foreach ($cekitems as $ra) {
				
					$haha = $ra['jum'];
				}
				
	
				
			$upcount = $connec->query("update pos_mproduct set sku = '".$r['sku']."' where name='".$sku."'");
		
			
			if($upcount){
				$data = array(
					"result"=>1,
					'msg'=>$r['sku'] .' ('.$r['namaitem'].'), STOCK = <font style="color: red">'.$totqty.'</font>'
				);
				
			}else{
				
				$data = array(
					"result"=>1,
					'msg'=>'Gagal update stock'
				);
			}
				
			}else{
				$data = array(
					"result"=>0,
					"msg"=>"Items tidak ditemukan di ERP"
			
				);
				
			}
			
			
		}
		// }else{
			
			
		// }
		
		
		$json_string = json_encode($data);	
		echo $json_string;
		// echo $sql;
		
	}else if($_GET['act'] == 'sync_pos'){
		
		
		// $sku = "8151000000129";
		
		$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$org_keys = $r["ad_morg_key"];	
		}
		
		$hasil = get_data_stock_all($org_keys);
		$j_hasil = json_decode($hasil, true);
		
		// $jum = count($hasil);
		
		// if($jum > 0){
		$no = 0;	
		foreach($j_hasil as $r) {
			
			
			$stock_sales = 0;
			$haha = 0;
			$ceksales = $connec->query("select sku, sum(qty) as jj from pos_dsalesline where sku = '".$r['sku']."' and date(insertdate) = date(now()) group by sku");
			foreach ($ceksales as $rs) {
				
					$stock_sales = $rs['jj'];
				}
			
			$cekitems = $connec->query("select count(sku) as jum, stockqty from pos_mproduct where sku = '".$r['sku']."' group by sku, stockqty");
			foreach ($cekitems as $ra) {
				
					$haha = $ra['jum'];
				}
			
			$totqty = $r['stockqty'] - $stock_sales;
			// $totqty = $r['stockqty'];
	
			if($haha > 0){
				
				
				
				$upcount = $connec->query("update pos_mproduct set sku = '".$r['sku']."', stockqty='".$totqty."', name = '".substr($r['namaitem'], 0, 49)."', price = '".$r['price']."' where m_product_id='".$r['m_product_id']."'");
				
				
				
			}else{
				$sql = "insert into pos_mproduct (
ad_mclient_key,
ad_morg_key,
isactived,
insertdate,
insertby,
postby,
postdate,
m_product_id,
m_product_category_id,
c_uom_id,
sku,
name,
price,
stockqty,
m_locator_id,
locator_name) VALUES (
				'".$r['ad_client_id']."',
				'".$r['ad_mor_key']."',
				'".$r['isactive']."',
				'".$r['insertdate']."',
				'".$r['insertby']."',
				'".$r['postby']."',
				'".$r['postdate']."',
				'".$r['m_product_id']."',
				'".$r['m_product_category_id']."',
				'".$r['c_uom_id']."',
				'".$r['sku']."',
				'".substr($r['namaitem'], 0, 49)."',
				'".$r['price']."',
				'".$r['stockqty']."',
				'".$r['m_locator_id']."',
				'".$r['locator_name']."'
)";
				$upcount = $connec->query($sql);
				
				// echo $sql;
				
			}
			
			
			
			
			
			
			
			if($upcount){
				$no = $no + 1;
				
			}
			
		

		}
		
		$data = array("result"=>1, "msg"=>"Berhasil sync ".$no." data");
		
		$json_string = json_encode($data);	
		echo $json_string;
		// echo $sql;
		
	}else if($_GET['act'] == 'sync_pos_awal'){
		
		
		// $sku = "8151000000129";
		
		$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$org_keys = $r["ad_morg_key"];	
		}
		
		$hasil = get_data_stock_all($org_keys);
		$j_hasil = json_decode($hasil, true);
		
		// $jum = count($hasil);
		
		// if($jum > 0){
		$no = 0;	
		$s = array();
		foreach($j_hasil as $r) {
			
			
			$stock_sales = 0;
			$haha = 0;
			$ceksales = $connec->query("select sku, sum(qty) as jj from pos_dsalesline where sku = '".$r['sku']."' and date(insertdate) = date(now()) group by sku");
			foreach ($ceksales as $rs) {
				
					$stock_sales = $rs['jj'];
			}
			
			
			$totqty = $r['stockqty'] - $stock_sales;

				$s[] = "(
				'".$r['ad_client_id']."',
				'".$r['ad_mor_key']."',
				'".$r['isactive']."',
				'".$r['insertdate']."',
				'".$r['insertby']."',
				'".$r['postby']."',
				'".$r['postdate']."',
				'".$r['m_product_id']."',
				'".$r['m_product_category_id']."',
				'".$r['c_uom_id']."',
				'".$r['sku']."',
				'".$r['barcode']."',
				'".substr($r['namaitem'], 0, 49)."',
				'".$r['price']."',
				'".$totqty."',
				'".$r['m_locator_id']."',
				'".$r['locator_name']."')";

		}
		
		$jum_arr = count($s);
		
		if($jum_arr > 0){
			
			// $connec->query("truncate table pos_mproduct");
			
			$values = implode(", ",$s);
		
			$insert = "insert into pos_mproduct (
			ad_mclient_key,
			ad_morg_key,
			isactived,
			insertdate,
			insertby,
			postby,
			postdate,
			m_product_id,
			m_product_category_id,
			c_uom_id,
			sku,
			barcode,
			name,
			price,
			stockqty,
			m_locator_id,
			locator_name) VALUES ".$values."";
			
			$insert_bulk = $connec->query($insert);
			if($insert_bulk){
				
				$data = array("result"=>1, "msg"=>"Berhasil sync data");
			}else{
				
				$data = array("result"=>0, "msg"=>"Gagal sync data");
				
			}
			
		}
		
		$json_string = json_encode($data);	
		echo $json_string;
		// echo $sql;
		
	}else if($_GET['act'] == 'input_shortcut'){
				$sku =	$_POST['sku'];
				$shortcut =	$_POST['shortcut'];
				
				
				$hasil = input_shortcut($sku, $shortcut, $username);
				$j_hasil = json_decode($hasil, true);
				
				if($j_hasil['status'] == 1){
					
					$sql = "update pos_mproduct set barcode = '".$shortcut."' where sku = '".$sku."'";		
					$statement1 = $connec->query($sql);
			
					if($statement1){
						$json = array('result'=>'1', 'msg'=>"Berhasil update ke pos lokal");
						
					}else{
						
						$json = array('result'=>'0', 'msg'=>"Gagal update ke pos lokal");
					}
					
				}else{
					
					$json = array('result'=>'0', 'msg'=>$j_hasil['msg']);
					
				}
				

				
		
		
		
		
		$json_string = json_encode($json);
		echo $json_string;
		// echo $sql;
	}else if($_GET['act'] == 'sync_barcode'){
		
		
		// $sku = "8151000000129";
		
		
		$hasil = get_data_barcode();
		$j_hasil = json_decode($hasil, true);
		
		// $jum = count($hasil);
		
		// if($jum > 0){
		$no = 0;	
		foreach($j_hasil as $r) {

				$upcount = $connec->query("update pos_mproduct set barcode='".$r['value']."', shortcut = '".$r['upc']."', name = '".$r['name']."', tag = '".$r['tag']."' where sku='".$r['sku']."'");
	
			if($upcount){
				$no = $no + 1;
				
			}
			
		

		}
		
		$data = array("result"=>1, "msg"=>"Berhasil sync ".$no." data");
		
		$json_string = json_encode($data);	
		echo $json_string;
		// echo $sql;
		
	}else if($_GET['act'] == 'sync_price'){
		
		
		// $sku = "8151000000129";
		
		
		$hasil = get_data_harga_null($ad_morg_key);
		$j_hasil = json_decode($hasil, true);
		
		// $jum = count($hasil);
		
		// if($jum > 0){
		$no = 0;	
		foreach($j_hasil as $r) {

				$upcount = $connec->query("update pos_mproduct set price='".$r['price']."' where sku='".$r['sku']."' and price is null");
	
			if($upcount){
				$no = $no + 1;
				
			}
			
		

		}
		
		$data = array("result"=>1, "msg"=>"Berhasil sync ".$no." data");
		
		$json_string = json_encode($data);	
		echo $json_string;
		// echo $sql;
		
	}else if($_GET['act'] == 'sync_price_all'){
		
		
		// $sku = "8151000000129";
		
		
		$hasil = get_data_harga($ad_morg_key);
		$j_hasil = json_decode($hasil, true);
		
		// $jum = count($hasil);
		
		// if($jum > 0){
		$no = 0;	
		foreach($j_hasil as $r) {

				$upcount = $connec->query("update pos_mproduct set price='".$r['price']."' where sku='".$r['sku']."' ");
	
			if($upcount){
				$no = $no + 1;
				
			}
			
		

		}
		
		$data = array("result"=>1, "msg"=>"Berhasil sync ".$no." data");
		
		$json_string = json_encode($data);	
		echo $json_string;
		// echo $sql;
		
	}else if($_GET['act'] == 'sync_price_khusus'){
		
		
		// $sku = "8151000000129";
		
		
		$hasil = get_data_harga_khusus($ad_morg_key);
		$j_hasil = json_decode($hasil, true);
		
		// $jum = count($hasil);
		
		// if($jum > 0){
		$no = 0;	
		foreach($j_hasil as $r) {

				$upcount = $connec->query("update pos_mproduct set price='".$r['price']."' where sku='".$r['sku']."'");
	
			if($upcount){
				$no = $no + 1;
				
			}
			
		

		}
		
		$data = array("result"=>1, "msg"=>"Berhasil sync ".$no." data");
		
		$json_string = json_encode($data);	
		echo $json_string;
		// echo $sql;
		
	}else if($_GET['act'] == 'sync_grab'){
		
			$getkodetoko = $connec->query("select value from ad_morg where insertby = 'SYSTEM'");
			foreach ($getkodetoko as $ra) {
				$kt = $ra['value'];
			}
		
		$hasil = get_data_grab($kt);
		$j_hasil = json_decode($hasil, true);
		
		$no = 0;	
		foreach($j_hasil as $r) {
			
				$cekitems = $connec->query("select count(sku) as jum from m_grab_sku where sku = '".$r['sku']."'");
				foreach ($cekitems as $ra) {
					$haha = $ra['jum'];
				}
				
				if($haha > 0){
					
					$upcount = $connec->query("update m_grab_sku set stock='".$r['stock']."' where sku='".$r['sku']."'");
				}else{
					$upcount = $connec->query("insert into m_grab_sku (sku, stock) values ('".$r['sku']."', '".$r['stock']."')");
					
				}

				
	
			if($upcount){
				$no = $no + 1;
				
			}
		}
		
		$data = array("result"=>1, "msg"=>"Berhasil sync ".$no." data");
		
		$json_string = json_encode($data);	
		echo $json_string;
		
	}else if($_GET['act'] == 'sync_stock_grab'){
			
			$getkodetoko = $connec->query("select value from ad_morg where insertby = 'SYSTEM'");
			foreach ($getkodetoko as $ra) {
				$kt = $ra['value'];
			}
			
			$cekitems = $connec->query("select pos_mproduct.sku, name, coalesce(stockqty,0) as stock, m_grab_sku.stock as stock_grab from pos_mproduct inner join m_grab_sku on pos_mproduct.sku = m_grab_sku.sku order by name asc");						
			foreach ($cekitems as $rline) {
				$items[] = array(
					'sku'		=>$rline['sku'], 
					'stock' 	=>$rline['stock'],
					'merchant_id' 	=>$kt,
				);
				
			}	
			$items_json = json_encode($items);
			$hasil = push_stock_grab($items_json);
		
		
			$json = array('result'=>'1', 'msg'=>'Berhasil sync stock grab');			
			$json_string = json_encode($json);
			echo $json_string;		
		
	}else if($_GET['act'] == 'sync_sku'){
		
		$hasil = get_data_sku();
		$j_hasil = json_decode($hasil, true);
		
		$no = 0;	
		foreach($j_hasil as $r) {

				$upcount = $connec->query("update pos_mproduct set sku='".$r['sku']."' where m_product_id='".$r['m_product_id']."'");
	
			if($upcount){
				$no = $no + 1;
				
			}
		}
		
		$data = array("result"=>1, "msg"=>"Berhasil sync ".$no." data");
		
		$json_string = json_encode($data);	
		echo $json_string;
		
	}else if($_GET['act'] == 'load_product'){
		$sku = $_POST['sku'];
		$list_line = "select sku, name, coalesce(stockqty,0) as stock from pos_mproduct where sku = '".$sku."' order by name asc";
		$no = 1;
		foreach ($connec->query($list_line) as $row1) {
			
		
			
							echo 
							"<tr>
								<td>".$no."</td>
								<td>".$row1['sku']."<br> ".$row1['name']."</td>
								<td>".$row1['stock']."</td>

							</tr>";
							
							
			
		 $no++;}
		
		
	}else if($_GET['act'] == 'load_product_grab'){
		$sku = $_POST['sku'];
		$list_line = "select pos_mproduct.sku, name, coalesce(stockqty,0) as stock, m_grab_sku.stock as stock_grab from pos_mproduct inner join m_grab_sku on pos_mproduct.sku = m_grab_sku.sku order by name asc";
		

		$no = 1;
		foreach ($connec->query($list_line) as $row1) {
			$style="style='background-color: #deffd9'";
			if($row1['stock'] != $row1['stock_grab']){
			
				$style="style='background-color: #fadde3'";
			
			}
			
							echo 
							"<tr ".$style.">
								<td>".$no."</td>
								<td>".$row1['sku']."<br> ".$row1['name']."</td>
								<td>".$row1['stock']."</td>
								<td>".$row1['stock_grab']."</td>

							</tr>";
							
							
			
		 $no++;}
		
		
	}else if($_GET['act'] == 'load_product_all'){
		$sku = $_POST['sku'];
		$list_line = "select price, sku, name, coalesce(stockqty,0) as stock from pos_mproduct order by name asc";
		$no = 1;
		foreach ($connec->query($list_line) as $row1) {
							$disk = 0;
							$cek_disc = "select discount from pos_mproductdiscount where todate > '".date('Y-m-d')."' and sku = '".$row1['sku']."'";
							foreach ($connec->query($cek_disc) as $row_dis) {
								
								$disk = $row_dis['discount'];
							}
							$harga_last = $row1['price'] - $disk;
			
							echo 
							"<tr>
								<td>".$no."</td>
								<td>".$row1['sku']."<br> ".$row1['name']."</td>
								<td>".$row1['stock']."</td>
								<td>".$row1['price']."</td>
								<td>".$harga_last."</td>

							</tr>";
							
							
			
		 $no++;}
		
		
	}else if($_GET['act'] == 'load_product_barcode'){
		$sku = $_POST['sku'];
		$list_line = "select sku, name, barcode from pos_mproduct where (barcode is not null or barcode != '') order by name asc";
		$no = 1;
		foreach ($connec->query($list_line) as $row1) {
							echo 
							"<tr>
								<td>".$no."</td>
								<td>".$row1['sku']."<br> ".$row1['name']."</td>
								<td>".$row1['barcode']."</td>

							</tr>";
							
							
			
		 $no++;}
		
		
	}else if($_GET['act'] == 'cetak_generic'){
		$mpi = $_POST['mpi'];
		$sort = $_POST['sort'];
		$show = $_POST['show'];
		$jj = array();
		
		
		
		$list_line = "select distinct ((m_piline.qtycount + m_piline.qtysales) - (m_piline.qtyerp - m_piline.qtysalesout)) variant, m_piline.sku, m_piline.barcode ,m_piline.qtyerp, m_piline.qtysales, m_piline.qtycount, m_piline.qtysalesout, pos_mproduct.name, m_pi.status, m_piline.verifiedcount from m_pi inner join m_piline on m_pi.m_pi_key = m_piline.m_pi_key left join pos_mproduct on m_piline.sku = pos_mproduct.sku 
		where m_pi.m_pi_key = '".$mpi."' and m_pi.status = '2'  and ((m_piline.qtycount + m_piline.qtysales) - (m_piline.qtyerp - m_piline.qtysalesout)) != 0 ";

		if($show != '1'){
			if($show == '2'){
				
				$list_line .= " and ((m_piline.qtycount + m_piline.qtysales) - (m_piline.qtyerp - m_piline.qtysalesout)) < 0";
			}
			
			if($show == '3'){
				
				$list_line .= " and ((m_piline.qtycount + m_piline.qtysales) - (m_piline.qtyerp - m_piline.qtysalesout)) > 0";
			}
			
		}else{
			$list_line .= " and ((m_piline.qtycount + m_piline.qtysales) - (m_piline.qtyerp - m_piline.qtysalesout)) != 0";
		}
		
		// $list_line .= " order by variant asc";
		
		if($sort == '1'){
			
			$list_line .= " order by pos_mproduct.name asc";
		}else if($sort == '3'){
			
			$list_line .= " order by m_piline.sku asc";
		}else{
			
			$list_line .= " order by variant asc";
		}
		
		
		
		
		
		// if($sort == '1'){
			
			// $list_line = "select distinct ((m_piline.qtycount + m_piline.qtysales) - (m_piline.qtyerp - m_piline.qtysalesout)) variant, m_piline.sku,m_piline.barcode ,m_piline.qtyerp, m_piline.qtysales, m_piline.qtycount, m_piline.qtysalesout, pos_mproduct.name, m_pi.status, m_piline.verifiedcount from m_pi inner join m_piline on m_pi.m_pi_key = m_piline.m_pi_key left join pos_mproduct on m_piline.sku = pos_mproduct.sku 
		// where m_pi.m_pi_key = '".$mpi."' and m_pi.status = '2' and m_piline.qtycount != (m_piline.qtyerp - m_piline.qtysalesout - m_piline.qtysales) order by pos_mproduct.name asc";
		// }else if($sort == '3'){
			
			// $list_line = "select distinct ((m_piline.qtycount + m_piline.qtysales) - (m_piline.qtyerp - m_piline.qtysalesout)) variant, m_piline.sku,m_piline.barcode ,m_piline.qtyerp, m_piline.qtysales, m_piline.qtycount, m_piline.qtysalesout, pos_mproduct.name, m_pi.status, m_piline.verifiedcount from m_pi inner join m_piline on m_pi.m_pi_key = m_piline.m_pi_key left join pos_mproduct on m_piline.sku = pos_mproduct.sku 
		// where m_pi.m_pi_key = '".$mpi."' and m_pi.status = '2' and m_piline.qtycount != (m_piline.qtyerp - m_piline.qtysalesout - m_piline.qtysales) order by m_piline.sku asc";
		// }else{
			
			// $list_line = "select distinct ((m_piline.qtycount + m_piline.qtysales) - (m_piline.qtyerp - m_piline.qtysalesout)) variant, m_piline.sku,m_piline.barcode ,m_piline.qtyerp, m_piline.qtysales, m_piline.qtycount, m_piline.qtysalesout, pos_mproduct.name, m_pi.status, m_piline.verifiedcount from m_pi inner join m_piline on m_pi.m_pi_key = m_piline.m_pi_key left join pos_mproduct on m_piline.sku = pos_mproduct.sku 
		// where m_pi.m_pi_key = '".$mpi."' and m_pi.status = '2' and m_piline.qtycount != (m_piline.qtyerp - m_piline.qtysalesout - m_piline.qtysales) order by variant asc";
		// }
		
		// $list_line = "select distinct ((m_piline.qtycount + m_piline.qtysales) - (m_piline.qtyerp - m_piline.qtysalesout)) variant, m_piline.sku ,m_piline.qtyerp, m_piline.qtysales, m_piline.qtycount, m_piline.qtysalesout, pos_mproduct.name, m_pi.status, m_piline.verifiedcount from m_pi inner join m_piline on m_pi.m_pi_key = m_piline.m_pi_key left join pos_mproduct on m_piline.sku = pos_mproduct.sku 
		// where m_pi.m_pi_key = '".$mpi."' and m_pi.status = '2' and m_piline.qtycount != (m_piline.qtyerp - m_piline.qtysalesout - m_piline.qtysales) order by pos_mproduct.name asc";
		
		
		
		
		
		$no = 1;
		foreach ($connec->query($list_line) as $row1) {	
			$variant = $row1['variant'];
			$qtyerpreal = $row1['qtyerp'] - $row1['qtysalesout'];
			
			
			$jj[] = array(
				"sku"=> $row1['sku'],
				"barcode"=> $row1['barcode'],
				"name"=> $row1['name'],
				"qtyvariant"=> $variant,
				"qtycount"=> $row1['qtycount']
			);
		}
		

		$json_string = json_encode($jj);	
		echo $json_string;
	}else if($_GET['act'] == 'cetak_pickup'){
		
		
		$all = array();
		$tanggal = $_GET['tanggal'];
		if($_SESSION['name'] == 'Ka. Toko' || $_SESSION['name'] == 'Wk. Ka Toko'){
			
			if($_GET['userid'] == 'all'){
				
				$get_user = "select userid, nama_insert, sum(cash) as totalcash from cash_in where status = '1' and date(insertdate) = '".$tanggal."' 
				group by userid, nama_insert";
			}else{
				
				$get_user = "select userid, nama_insert, sum(cash) as totalcash from cash_in where status = '1' and date(insertdate) = '".$tanggal."' and userid = '".$_GET['userid']."'
				group by userid, nama_insert";
			}
			
			$haha = array();
			// $get_user = "select userid, nama_insert, sum(cash) as totalcash from cash_in where status = '1' and date(insertdate) = date(now()) group by userid, nama_insert";	
			foreach ($connec->query($get_user) as $rr) {
				
				
				$totalcash = $rr['totalcash'];
				$jj = array();
				$list_line = "select * from cash_in where status = '1' and userid = '".$rr['userid']."' and date(insertdate) = '".$tanggal."'";
				foreach ($connec->query($list_line) as $row1) {	
				
					$jam = date('H:i:s', strtotime($row1['insertdate']));
					
					$jj[] = array(
						"jam"=> $jam,
						"cash"=> "Rp ".rupiah($row1['cash']),
						"approvedby"=> $row1['approvedby'],
						
					);
				}
				
				$haha[] = array(
					"tanggal"=>$tanggal,
					"username"=>$rr['nama_insert'],
					"totalcash"=>"Rp ".rupiah($totalcash),
					"data"=>$jj
				);
				
			}
			
			
			
		}else{
			$jj = array();
			$haha = array();
			$list_line = "select * from cash_in where status = '1' and userid = '".$useridcuy."' and date(insertdate) = '".$tanggal."'";
			$get_user = "select userid, nama_insert, sum(cash) as totalcash from cash_in where status = '1' and date(insertdate) = '".$tanggal."' and userid = '".$useridcuy."' 
			group by userid, nama_insert";	
			foreach ($connec->query($get_user) as $rr) {
				
				$totalcash = $rr['totalcash'];
				
			}
			foreach ($connec->query($list_line) as $row1) {	
				
				$jam = date('H:i:s', strtotime($row1['insertdate']));
				
				$jj[] = array(
					"jam"=> $jam,
					"cash"=> "Rp ".rupiah($row1['cash']),
					"approvedby"=> $row1['approvedby'],
					
				);
			}
			
			$haha[] = array(
				"tanggal"=>$tanggal,
				"username"=>$username,
				"totalcash"=>"Rp ".rupiah($totalcash),
				"data"=>$jj
			);
			
		}
		
		$all = array(
				"kasir"=>$haha
			);
		
		$json_string = json_encode($all);	
		echo $json_string;
	}else if($_GET['act'] == 'cetak_pickupdetail'){
		$id = $_GET['id'];
		$jj = array();
		$haha = array();
		$list_line = "select * from cash_in where status = '1' and cashinid = '".$id."'";
		$no = 1;
		foreach ($connec->query($list_line) as $row1) {	
			$approvedby = $row1['approvedby'];
			$setoran = $row1['setoran'];
			$nama_insert = $row1['nama_insert'];
			$jam = date('H:i:s', strtotime($row1['insertdate']));
			$date = date('Y-m-d', strtotime($row1['insertdate']));
			
			$jj[] = array(
				"jam"=> $jam,
				"cash"=> "Rp ".rupiah($row1['cash'])
			);
		}
		
		$haha = array(
			"tanggal"=>$date,
			"username"=>$nama_insert,
			"approvedby"=>$approvedby,
			"setoran"=>$setoran,
			"data"=>$jj
				
			);
		
		
		$json_string = json_encode($haha);	
		echo $json_string;
	}else if($_GET['act'] == 'send_newpos'){
		$userid = $_GET['userid'];
		$tanggal = $_GET['tanggal'];
		$jj = array();
		$list_line = "select * from cash_in where status = '1' and date(insertdate) = '".$tanggal."'";
		$no = 1;
		foreach ($connec->query($list_line) as $row1) {	
			$jj[] = array(
				"cashinid"=> $row1['cashinid'],
				"org_key"=> $row1['org_key'],
				"userid"=> $row1['userid'],
				"nama_insert"=> $row1['nama_insert'],
				"cash"=> $row1['cash'],
				"insertdate"=> $row1['insertdate'],
				"status"=> $row1['status'],
				"approvedby"=> $row1['approvedby'],
				"syncnewpos"=> $row1['syncnewpos']
			);
		}
		
		if (!$jj) {
			$data = array("result"=>0, "msg"=>"Belum ada cash in yg di approved");
			
		}else{
								$allarray = array("cashin"=>$jj);
								$items_json = json_encode($allarray);
								$hasil = push_to_newpos($items_json);
								// var_dump($hasil);
								$j_hasil = json_decode($hasil, true);
								
								if(!empty($j_hasil)){
									
									foreach($j_hasil as $r) {
											$statement1 = $connec->query("update cash_in set syncnewpos = '".$r['status']."' where cashinid = '".$r['cashinid']."'");
											if($statement1){
													$no = $no +1;
											}
									}
									
									$data = array("result"=>1, "msg"=>"Berhasil kirim ke newpos");
									
								}else{
									
									$data = array("result"=>0, "msg"=>"Gagal kirim ke newpos, coba lagi beberapa saat");
									
								}
			
			
		}
								
		
		
		$json_string = json_encode($data);	
		echo $json_string;
	}else if($_GET['act'] == 'send_price'){
						$json_url = "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=cek_perubahan_harga";
						$options = stream_context_create(array('http'=>
							array(
							'timeout' => 10 //10 seconds
							)
						));
						// echo file_get_contents('http://example.com/', false, $options);
						
						
						$json = file_get_contents($json_url, false, $options);
						
						$arr = json_decode($json, true);
						$jum = count($arr);
						
						// var_dump($json);
						$jj = array();
						$s = array();
						if($jum > 0){
						$no = 1;
						foreach ($arr as $row1) { 
							// echo $row1['sku'];
							$sql_list = "select date(now()) as tgl_sekarang, a.sku, a.name ,b.rack_name, a.barcode, a.price, a.tag from pos_mproduct a 
							left join inv_mproduct b on a.sku = b.sku where a.sku = '".$row1['sku']."' 
							and a.price != '".$row1['price']."' group by a.sku, a.name, b.rack_name, a.barcode, a.price, a.tag order by a.name";
							
						foreach ($connec->query($sql_list) as $row) {
							
							
							$jj[] = array(
								"sku"=> $row['sku'],
								"name"=> $row['name'],
								"price"=> $row['price'],
								"price_erp"=> $row1['price'],
								"store_name"=> $storename,
							);
							
							// echo $row['sku'];
							
							
						}
					}
			
				}
		
		if (!$jj) {
			$data = array("result"=>0, "msg"=>"Gagal kirim ke newpos");
			
		}else{
				$allarray = array("cashin"=>$jj);
				$items_json = json_encode($allarray);
				$hasil = push_to_newpos_price($items_json);
				$j_hasil = json_decode($hasil, true);
				
				$data = array("result"=>1, "msg"=>"Berhasil kirim ke newpos");
		}	

		$json_string = json_encode($data);	
		echo $json_string;
		// var_dump($hasil);
	}else if($_GET['act'] == 'send_newposall'){
		$jj = array();
		$list_line = "select * from cash_in where status = '1' and syncnewpos = '0'";
		$no = 1;
		foreach ($connec->query($list_line) as $row1) {	
			$jj[] = array(
				"cashinid"=> $row1['cashinid'],
				"org_key"=> $row1['org_key'],
				"userid"=> $row1['userid'],
				"nama_insert"=> $row1['nama_insert'],
				"cash"=> $row1['cash'],
				"insertdate"=> $row1['insertdate'],
				"status"=> $row1['status'],
				"approvedby"=> $row1['approvedby'],
				"syncnewpos"=> $row1['syncnewpos']
			);
		}
		
		if (!$jj) {
			$data = array("result"=>0, "msg"=>"Belum ada cash in yg di approved");
			
		}else{
								$allarray = array("cashin"=>$jj);
								$items_json = json_encode($allarray);
								$hasil = push_to_newpos($items_json);
								// var_dump($hasil);
								$j_hasil = json_decode($hasil, true);
								
								if(!empty($j_hasil)){
									
									foreach($j_hasil as $r) {
											$statement1 = $connec->query("update cash_in set syncnewpos = '".$r['status']."' where cashinid = '".$r['cashinid']."'");
											if($statement1){
													$no = $no +1;
											}
									}
									
									$data = array("result"=>1, "msg"=>"Berhasil kirim ke newpos");
									
								}else{
									
									$data = array("result"=>0, "msg"=>"Gagal kirim ke newpos, coba lagi beberapa saat");
									
								}
			
			
		}
								
		
		
		$json_string = json_encode($data);	
		echo $json_string;
	}else if($_GET['act'] == 'cetak_excel'){

		$jj = array();
		$userid = $_GET['userid'];
		$tanggal = $_GET['tanggal'];
		
		if($userid == 'all'){
			
			$list_line = "select * from cash_in where status = '1' and date(insertdate) = '".$tanggal."'";
		}else{
			
			$list_line = "select * from cash_in where status = '1' and date(insertdate) = '".$tanggal."' and userid = '".$userid."'";
		}
		
		$count = $connec->query($list_line);
		$jums = $count->rowCount();
		
		$no = 0;
		$tot = 0;
		foreach ($connec->query($list_line) as $row1) {
			
			
			
			$jj[] = array(
				"insertby"=> $row1['nama_insert'],
				"cash"=> (int)$row1['cash'],
				"insertdate"=> $row1['insertdate'],
				"status"=> $row1['status'],
				"approvedby"=> $row1['approvedby'],
			);
			
			$tot += (int)$row1['cash'];
			$no = $no + 1;
			
		}
		
		if($no == $jums){
				
				$jj[] = array(
					"insertby"=> "Total : ",
					"cash"=> (int)$tot,
					"insertdate"=> "",
					"status"=> "",
					"approvedby"=> "",
				);
			}
		

		$json_string = json_encode($jj);	
		echo $json_string;
	}else if($_GET['act'] == 'cetak_excel_stock'){

		$jj = array();
		$mpi = $_GET['m_pi'];
		

		$list_line = "select * from m_piline where m_pi_key = '".$mpi."'";
						
		
		$count = $connec->query($list_line);
		$jums = $count->rowCount();
		
		$no = 0;
		$tot = 0;
		foreach ($connec->query($list_line) as $row1) {
			
			$cek_name = "select * from pos_mproduct where sku = '".$row1['sku']."'";
			
			foreach ($connec->query($cek_name) as $cn) {
				
				$namep = $cn['name'];
			}
			
			$jj[] = array(
				"sku"=> $row1['sku'],
				"barcode_international"=> $row1['barcode'],
				"namaitem"=> $namep,
				"stock"=> (int)$row1['qtycount'],
			);
			
			
			
		}
		
		

		$json_string = json_encode($jj);	
		echo $json_string;
	}else if($_GET['act'] == 'total_pickup'){
		$userid = $_GET['userid'];
		$tanggal = $_GET['tanggal'];
	
		
		$jj = array();
		$haha = array();
		
	if($_SESSION['name'] == 'Ka. Toko' || $_SESSION['name'] == 'Wk. Ka Toko'){
		
		if($userid == 'all'){
			
			$list_line = "select sum(cash) as total from cash_in where status = '1' and date(insertdate) = '".$tanggal."'";
		}else{
			
			$list_line = "select sum(cash) as total from cash_in where status = '1' and date(insertdate) = '".$tanggal."' and userid = '".$userid."'";
		}
		
	}else{
		
		
		$list_line = "select sum(cash) as total from cash_in where status = '1' and date(insertdate) = '".$tanggal."' and userid = '".$useridcuy."'";
		
	}
		
		
		
		$no = 1;
		foreach ($connec->query($list_line) as $row1) {	
			$jj = array(
				"total"=> "Rp ".rupiah($row1['total'])
			);
		}
		

		
		$json_string = json_encode($jj);	
		echo $json_string;
	}else if($_GET['act'] == 'total_pickup_perkasir'){
		$userid = $_GET['userid'];
		$tanggal = $_GET['tanggal'];
	
		$html = "<table>";
		$jj = array();
		$haha = array();
		
	if($_SESSION['name'] == 'Ka. Toko' || $_SESSION['name'] == 'Wk. Ka Toko'){
		
		if($userid == 'all'){
			
			$list_line = "select sum(cash) as total, nama_insert as userid from cash_in where status = '1' and date(insertdate) = '".$tanggal."' group by nama_insert";
		}else{
			
			$list_line = "select sum(cash) as total, nama_insert as userid from cash_in where status = '1' and date(insertdate) = '".$tanggal."' and userid = '".$userid."' group by nama_insert";
		}
		
	}else{
		
		
		$list_line = "select sum(cash) as total, nama_insert as userid from cash_in where status = '1' and date(insertdate) = '".$tanggal."' and userid = '".$useridcuy."' group by nama_insert";
		
	}
		
		
		
		$no = 1;
		foreach ($connec->query($list_line) as $row1) {	
			$html .= "<tr><td><b>".$row1['userid']." (Approved): </b></td><td><b>Rp ".rupiah($row1['total'])."</b></td></tr>";
		}
		
		$html .= "</table>";
		
		$jj = array(
				"total"=> $html
		);
		
		
		$json_string = json_encode($jj);	
		echo $json_string;
	}else if($_GET['act'] == 'cek_session'){
		
		if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
			
			$data = array("result"=>1, "msg"=>"Session masih ada");
			
		}else{
			
			$data = array("result"=>0, "msg"=>"Session telah habis, mohon reload kembali");
		}
		
		$json_string = json_encode($data);	
		echo $json_string;
		
		
		
		
	}else if($_GET['act'] == 'api_datatable'){
		
		
		 $columns = array( 
                               0 =>'sku', 
                               1 =>'name',
                               2=> 'price',
                               3=> 'price_discount',
                           );
 
      $querycount =  $connec->query("SELECT count(*) as jumlah FROM pos_mproduct");
    
		foreach($querycount as $r){
			$datacount = $r['jumlah'];
			
		}
   
        $totalData = $datacount;
             
        $totalFiltered = $totalData; 
 
        $limit = $_POST['length'];
        $start = $_POST['start'];
        $order = $columns[$_POST['order']['0']['column']];
        $dir = $_POST['order']['0']['dir'];
             
        if(empty($_POST['search']['value']))
        {
         $query = $connec->query("SELECT a.sku,a.name,a.price,a.shortcut,a.barcode, (coalesce(a.price,0) - coalesce(b.discount,0)) price_discount, b.discountname, a.stockqty FROM 
		 pos_mproduct a left join (select * from pos_mproductdiscount where todate >= '".date('Y-m-d')."') b on a.sku = b.sku
		 
		 order by $order $dir
                                                      LIMIT $limit
                                                      OFFSET $start");
        }
        else {
            $search = $_POST['search']['value']; 
            $query = $connec->query("SELECT a.sku,a.name,a.price,a.shortcut,a.barcode, (coalesce(a.price,0) - coalesce(b.discount,0)) price_discount, b.discountname, a.stockqty FROM 
		 pos_mproduct a left join (select * from pos_mproductdiscount where todate >= '".date('Y-m-d')."') b on a.sku = b.sku WHERE a.sku ILIKE  '%$search%'
                                                         or a.name ILIKE  '%$search%'
                                                         or a.shortcut ILIKE  '%$search%'
                                                         or a.barcode ILIKE  '%$search%'
                                                         or b.discountname ILIKE  '%$search%'
                                                         order by $order $dir
                                                         LIMIT $limit
                                                         OFFSET $start");
 
 
         $querycount = $connec->query("SELECT count(*) as jumlah FROM 
		 pos_mproduct a left join (select * from pos_mproductdiscount where todate >= '".date('Y-m-d')."') b on a.sku = b.sku WHERE a.sku ILIKE  '%$search%'
                                                         or a.name ILIKE '%$search%'
                                                         or a.shortcut ILIKE  '%$search%'
                                                         or a.barcode ILIKE  '%$search%'
														 or b.discountname ILIKE  '%$search%'
														 ");
        foreach($querycount as $rr){
			$datacount = $rr['jumlah'];
			
		}
           $totalFiltered = $datacount;
        }
 
        $data = array();
        if(!empty($query))
        {
            $no = $start + 1;
			foreach($query as $r){
				
				if($r['discountname'] != ''){
					$discname = '<font style="color: blue; font-weight: bold">('.$r['discountname'].')</font>';
					
				}else{
					
					$discname = '';
				}
				
				if($r['price'] != $r['price_discount']){
					
					$fontdiskon = '<br> <font style="color: red; font-weight: bold">Setelah Diskon : '.rupiah($r['price_discount']).'</font>';
				}else{
					
					$fontdiskon = '';
				}
				
				if($r['barcode'] != ''){
					$sc = '<input type="number" id="sc'.$r['sku'].'" value="'.$r['barcode'].'">
					<script>
					$("#sc'.$r['sku'].'").keyup(function(event) {
						if (event.keyCode === 13) {
							var shortcut = document.getElementById("sc'.$r['sku'].'").value;
							var sku = "'.$r['sku'].'";
							$.ajax({
							url: "api/action.php?modul=inventory&act=input_shortcut",
							type: "POST",
							data : {sku: sku, shortcut: shortcut},
							beforeSend: function(){
								$("#notif").html("Proses get shortcut..");
							},
							success: function(dataResult){
								var dataResult = JSON.parse(dataResult);
								if(dataResult.result == 0){
									
									$("#notif").html(dataResult.msg);
								}else{
									
									$("#notif").html("Berhasil update shortcut");
								}
					
									
					
								
							}
						});
						}
					});
					</script>';
					
				}else{
					
					$sc = '<input type="number" id="sc'.$r['sku'].'">
					<script>
					$("#sc'.$r['sku'].'").keyup(function(event) {
						if (event.keyCode === 13) {
							var shortcut = document.getElementById("sc'.$r['sku'].'").value;
							var sku = "'.$r['sku'].'";
							$.ajax({
							url: "api/action.php?modul=inventory&act=input_shortcut",
							type: "POST",
							data : {sku: sku, shortcut: shortcut},
							beforeSend: function(){
								$("#notif").html("Proses get shortcut..");
							},
							success: function(dataResult){
								var dataResult = JSON.parse(dataResult);
								if(dataResult.result == 0){
									
									$("#notif").html(dataResult.msg);
								}else{
									
									$("#notif").html("Berhasil update shortcut");
								}
					
									
					
								
							}
						});
						}
					});
					</script>';
				}
				
				$nestedData['no'] = $no;
                $nestedData['sku'] = '<font style="font-weight: bold">'.$r['sku'].'</font> '.$discname.' <br> <font style="color: green; font-weight: bold">Reguler : '.rupiah($r['price']).'</font>'.$fontdiskon;
				
				
                $nestedData['name'] = $r['name'];
                $nestedData['shortcut'] = $sc;
                $nestedData['stock'] = $r['stockqty'];
                $nestedData['price'] = rupiah($r['price']);
                $nestedData['price_discount'] = rupiah($r['price_discount']);
                $data[] = $nestedData;
                $no++;
				
			}
			

        }
           
        $json_data = array(
                    "draw"            => intval($_POST['draw']),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data  
                    );
             
        echo json_encode($json_data); 
		
		
		
	
	}else if($_GET['act'] == 'api_datatable_promo'){
		

		 $columns = array( 
                               0 =>'postdate', 
                               1 =>'discountname',
                               2=> 'discounttype',
                               3=> 'sku',
                               4=> 'nama',
                               5=> 'price',
                               6=> 'price_discount',
                               7=> 'price_after_discount',
                               8=> 'fromdate',
                               9=> 'todate',
                           );
 
      $querycount =  $connec->query("SELECT count(*) as jumlah FROM pos_mproductdiscount");
    
		foreach($querycount as $r){
			$datacount = $r['jumlah'];
			
		}
   
        $totalData = $datacount;
             
        $totalFiltered = $totalData; 
 
        $limit = $_POST['length'];
        $start = $_POST['start'];
        $order = $columns[$_POST['order']['0']['column']];
        $dir = $_POST['order']['0']['dir'];
             
        if(empty($_POST['search']['value']))
        {
         $query = $connec->query("select a.*, b.name, b.price from pos_mproductdiscount a left join pos_mproduct b on a.sku = b.sku order by $order $dir
                                                      LIMIT $limit
                                                      OFFSET $start");
        }
        else {
            $search = $_POST['search']['value']; 
            $query = $connec->query("select a.*, b.name, b.price from pos_mproductdiscount a left join pos_mproduct b on a.sku = b.sku WHERE a.sku ILIKE  '%$search%'
                                                         or a.discountname ILIKE  '%$search%'
                                                         or b.name ILIKE  '%$search%'
                                                         order by $order $dir
                                                         LIMIT $limit
                                                         OFFSET $start");
 
 
         $querycount = $connec->query("select count(*) as jumlah from pos_mproductdiscount a left join pos_mproduct b on a.sku = b.sku WHERE a.sku ILIKE  '%$search%' or b.name ILIKE  '%$search%'
                                                         or a.discountname ILIKE  '%$search%'");
        foreach($querycount as $rr){
			$datacount = $rr['jumlah'];
			
		}
           $totalFiltered = $datacount;
        }
 
        $data = array();
        if(!empty($query))
        {
            $no = $start + 1;
			foreach($query as $r){
				
				if($r['discountname'] != ''){
					$discname = '<font style="color: blue; font-weight: bold">'.$r['discountname'].'</font>';
					
				}else{
					
					$discname = '';
				}
				
				$pd = $r['price'] - $r['discount'];
				
				if($r['price'] != $pd){
					
					$fontdiskon = '<br> <font style="color: red; font-weight: bold">Setelah Diskon : '.rupiah($pd).'</font>';
				}else{
					
					$fontdiskon = '';
				}
				

				
				$nestedData['no'] = $no;
				$nestedData['postdate'] = $r['postdate'];
				$nestedData['discounttype'] = $r['discounttype'];
                $nestedData['sku'] = '<font style="font-weight: bold">'.$r['sku'].'</font>';
                $nestedData['name'] = $r['name'];
                $nestedData['hargareguler'] = '<font style="color: blue;font-weight: bold">Rp. '.rupiah($r['price']).'</font>';
                $nestedData['potongan'] = '<font style="color: red;font-weight: bold">Rp. '.rupiah($r['discount']).'</font>';
                $nestedData['afterdiscount'] = '<font style="color: green;font-weight: bold">Rp. '.rupiah($pd).'</font>';
                $nestedData['discountname'] = $discname;
                $nestedData['fromdate'] = $r['fromdate'];
                $nestedData['todate'] = $r['todate'];
                $nestedData['price'] = rupiah($r['price']);
                $nestedData['price_discount'] = rupiah($pd);
                $data[] = $nestedData;
                $no++;
				
			}
			

        }
           
        $json_data = array(
                    "draw"            => intval($_POST['draw']),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data  
                    );
             
        echo json_encode($json_data); 
		
	
	}else if($_GET['act'] == 'api_datatable_promo_grosir'){

		 $columns = array( 
                               0 =>'postdate', 
                               1 =>'sku',
                               2=> 'hargareguler',
                               3=> 'minbuy',
                               4=> 'diskon',
                               5=> 'name',
                               6=> 'discountname',
                               7=> 'fromdate',
                               8=> 'todate',
                           );
 
      $querycount =  $connec->query("SELECT count(*) as jumlah FROM pos_mproductdiscountgrosir_new");
    
		foreach($querycount as $r){
			$datacount = $r['jumlah'];
			
		}
   
        $totalData = $datacount;
             
        $totalFiltered = $totalData; 
 
        $limit = $_POST['length'];
        $start = $_POST['start'];
        $order = $columns[$_POST['order']['0']['column']];
        $dir = $_POST['order']['0']['dir'];
             
        if(empty($_POST['search']['value']))
        {
         $query = $connec->query("select a.*, b.name, b.price from pos_mproductdiscountgrosir_new a inner join pos_mproduct b on a.sku = b.sku order by $order $dir
                                                      LIMIT $limit
                                                      OFFSET $start");
        }
        else {
            $search = $_POST['search']['value']; 
            $query = $connec->query("select a.*, b.name, b.price from pos_mproductdiscountgrosir_new a inner join pos_mproduct b on a.sku = b.sku WHERE a.sku ILIKE  '%$search%'
                                                         or a.discountname ILIKE  '%$search%'
                                                         or b.name ILIKE  '%$search%'
                                                         order by $order $dir
                                                         LIMIT $limit
                                                         OFFSET $start");
 
 
         $querycount = $connec->query("select count(*) as jumlah from pos_mproductdiscountgrosir_new a inner join pos_mproduct b on a.sku = b.sku WHERE a.sku ILIKE  '%$search%' or b.name ILIKE  '%$search%'
                                                         or a.discountname ILIKE  '%$search%'");
        foreach($querycount as $rr){
			$datacount = $rr['jumlah'];
			
		}
           $totalFiltered = $datacount;
        }
 
        $data = array();
        if(!empty($query))
        {
            $no = $start + 1;
			foreach($query as $r){
				
				if($r['discountname'] != ''){
					$discname = '<font style="color: blue; font-weight: bold">'.$r['discountname'].'</font>';
					
				}else{
					
					$discname = '';
				}
				
				
				
				// $pd1 = $r['price'] - $r['discount_1'];
				// $pd2 = $r['price'] - $r['discount_2'];
				// $pd3 = $r['price'] - $r['discount_3'];
				
				
				$pd = $r['price'] - $r['discount'];

				
				$nestedData['no'] = $no;
				$nestedData['postdate'] = $r['postdate'];
				$nestedData['discounttype'] = $r['discounttype'];
                $nestedData['sku'] = '<font style="font-weight: bold">'.$r['sku'].'</font>';
                $nestedData['name'] = $r['name'];
                $nestedData['hargareguler'] = '<font style="color: blue;font-weight: bold">Rp. '.rupiah($r['price']).'</font>';
                // $nestedData['potongan'] = '<font style="color: red;font-weight: bold">Rp. '.rupiah($r['discount']).'</font>';
                $nestedData['discount'] = '<font style="color: green;font-weight: bold">Rp. '.rupiah($pd).'</font>';
                $nestedData['minbuy'] = $r['minbuy'];
                $nestedData['discountname'] = $discname;
                $nestedData['fromdate'] = $r['fromdate'];
                $nestedData['todate'] = $r['todate'];
                $nestedData['price'] = rupiah($r['price']);
                // $nestedData['price_discount'] = rupiah($pd);
                $data[] = $nestedData;
                $no++;
				
			}
			

        }
           
        $json_data = array(
                    "draw"            => intval($_POST['draw']),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data  
                    );
             
        echo json_encode($json_data); 
		
	
	}else if($_GET['act'] == 'api_pricetag'){
		

		 $columns = array( 
                               0 =>'check', 
                               1 =>'no',
                               2=> 'sku',
                               3=> 'barcode',
                               4=> 'name',
                               5=> 'price',
                               6=> 'rack_name',
                           );
 
		if($_POST['rak'] != "all"){
			 $querycount =  $connec->query("select count(*) as jumlah from pos_mproduct a left join inv_mproduct b on a.sku = b.sku where b.rack_name = '".$_POST['rak']."' and 
			 (a.price is not null or a.price != 0) ");
			
		}else{
			
			
			 $querycount =  $connec->query("select count(*) as jumlah from pos_mproduct a left join inv_mproduct b on a.sku = b.sku where (a.price is not null or a.price != 0) ");
		}
 
    
		foreach($querycount as $r){
			$datacount = $r['jumlah'];
			
		}
   
        $totalData = $datacount;
             
        $totalFiltered = $totalData; 
 
        $limit = $_POST['length'];
        $start = $_POST['start'];
        $order = $columns[$_POST['order']['0']['column']];
        $dir = $_POST['order']['0']['dir'];
             
        if(empty($_POST['search']['value']))
        {
			
			
		if($_POST['stock'] != "all"){
			
			$querystock = " and a.stockqty > 0 ";
			
		}else{
			
			$querystock = "";
			
		}	
			
		if($_POST['rak'] != "all"){
			
			 $query = $connec->query("select date(now()) as tgl_sekarang, a.sku, a.name ,b.rack_name, a.price, a.barcode, a.tag from pos_mproduct a left join inv_mproduct b on a.sku = b.sku where (a.price is not null or a.price != 0) and b.rack_name = '".$_POST['rak']."' 
			 ".$querystock."
			 order by a.name asc
                                                      LIMIT $limit
                                                      OFFSET $start");
			
		}else{
			
			
			 $query = $connec->query("select date(now()) as tgl_sekarang, a.sku, a.name ,b.rack_name, a.price, a.barcode, a.tag from pos_mproduct a left join inv_mproduct b on a.sku = b.sku where (a.price is not null or a.price != 0) and a.sku !='' ".$querystock." order by a.name asc
                                                      LIMIT $limit
                                                      OFFSET $start");
		}	
		
		
			
			
           $totalFiltered = $query->rowCount();
        
        }
        else {
			
            $search = $_POST['search']['value']; 
			
			
		if($_POST['stock'] != "all"){
			
			$querystocks = " and a.stockqty > 0";
			
		}else{
			
			$querystocks = "";
			
		}	
			
		if($_POST['rak'] != "all"){
			 $query = $connec->query("select date(now()) as tgl_sekarang, a.sku, a.name ,b.rack_name, a.price, a.barcode, a.tag from pos_mproduct a left join inv_mproduct b on a.sku = b.sku where (a.price is not null or a.price != 0) and b.rack_name = '".$_POST['rak']."' ".$querystock." 
															and a.sku ILIKE  '%$search%'
                                                         or b.rack_name ILIKE  '%$search%'
                                                         or a.barcode ILIKE  '%$search%'
                                                         or a.name ILIKE  '%$search%'
                                                         order by a.name asc
                                                         LIMIT $limit
                                                         OFFSET $start");
 
 
         $querycount = $connec->query("select count(*) as jumlah from pos_mproduct a left join inv_mproduct b on a.sku = b.sku where (a.price is not null or a.price != 0) and b.rack_name = '".$_POST['rak']."' ".$querystock."  and a.sku ILIKE  '%$search%'
                                                         or b.rack_name ILIKE  '%$search%'
                                                         or a.barcode ILIKE  '%$search%'
                                                         or a.name ILIKE  '%$search%'");
			
		}else{
			
			
			  $query = $connec->query("select date(now()) as tgl_sekarang, a.sku, a.name ,b.rack_name, a.price, a.barcode, a.tag from pos_mproduct a left join inv_mproduct b on a.sku = b.sku WHERE (a.price is not null or a.price != 0) and a.sku ILIKE  '%$search%'
                                                         or b.rack_name ILIKE  '%$search%'
                                                         or a.barcode ILIKE  '%$search%'
                                                         or a.name ILIKE  '%$search%' ".$querystock." 
                                                         order by a.name asc
                                                         LIMIT $limit
                                                         OFFSET $start");
 
 
         $querycount = $connec->query("select count(*) as jumlah from pos_mproduct a left join inv_mproduct b on a.sku = b.sku WHERE (a.price is not null or a.price != 0) and a.sku ILIKE  '%$search%'
                                                         or b.rack_name ILIKE  '%$search%'
                                                         or a.barcode ILIKE  '%$search%'
                                                         or a.name ILIKE  '%$search%' ".$querystock." ");
		}	
			
			
			
           
        foreach($querycount as $rr){
			$datacount = $rr['jumlah'];
			
		}
           $totalFiltered = $datacount;
        }
 
        $data = array();
        if(!empty($query))
        {
            $no = $start + 1;
			foreach($query as $r){
				
					$date = $storecode.'/'.date('dmy');
				if($r['price'] != '1'){
					$disk = 0;
					$cek_disc = "select discount from pos_mproductdiscount where todate >= '".date('Y-m-d')."' and sku = '".$row['sku']."'";
					foreach ($connec->query($cek_disc) as $row_dis) {
						
						$disk = $row_dis['discount'];
					}
					$harga_last = $row['price'] - $disk;
					
				
					$nestedData['no'] = $no;
					$nestedData['check'] = '<input type="checkbox" id="checkbox'.$r['sku'].'" name="checkbox[]" value="'.$r['sku'].'|'.$r['name'].'|'.$r['price'].'|'.$r['tgl_sekarang'].'|'.$r['rack_name'].'|'.$r['shortcut'].'|'.$harga_last.'|'.$r['tag'].'|'.$date.'|'.$r['barcode'].'">';
					$nestedData['sku'] = '<label for="checkbox'.$r['sku'].'">'.$r['sku'].'</label>';
					$nestedData['barcode'] = $r['barcode'];
					$nestedData['name'] = '<label for="checkbox'.$r['sku'].'">'.$r['name'].'</label>';
					$nestedData['price'] = $r['price'];
					$nestedData['rack_name'] = $r['rack_name'];
					$nestedData['tag'] = $r['tag'];
	
					$data[] = $nestedData;
					$no++;
					
				}
							
				
			}
			

        }
           
        $json_data = array(
                    "draw"            => intval($_POST['draw']),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data  
                    );
             
        echo json_encode($json_data); 
		
		
		
	
	}else if($_GET['act'] == 'api_datatable_promo_code'){
		

		 $columns = array( 
                               0 =>'postdate', 
                               1 =>'discountname',
                               2=> 'discounttype',
                               3=> 'sku',
                               4=> 'nama',
                               5=> 'price',
                               6=> 'price_discount',
                               7=> 'price_after_discount',
                               8=> 'fromdate',
                               9=> 'todate',
                           );
 
      $querycount =  $connec->query("SELECT count(*) as jumlah FROM pos_mproductdiscountmember");
    
		foreach($querycount as $r){
			$datacount = $r['jumlah'];
			
		}
   
        $totalData = $datacount;
             
        $totalFiltered = $totalData; 
 
        $limit = $_POST['length'];
        $start = $_POST['start'];
        $order = $columns[$_POST['order']['0']['column']];
        $dir = $_POST['order']['0']['dir'];
             
        if(empty($_POST['search']['value']))
        {
         $query = $connec->query("select a.*, b.name, b.price from pos_mproductdiscountmember a left join pos_mproduct b on a.sku = b.sku order by $order $dir
                                                      LIMIT $limit
                                                      OFFSET $start");
        }
        else {
            $search = $_POST['search']['value']; 
            $query = $connec->query("select a.*, b.name, b.price from pos_mproductdiscountmember a left join pos_mproduct b on a.sku = b.sku WHERE a.sku ILIKE  '%$search%'
                                                         or a.discountname ILIKE  '%$search%'
                                                         or b.name ILIKE  '%$search%'
                                                         order by $order $dir
                                                         LIMIT $limit
                                                         OFFSET $start");
 
 
         $querycount = $connec->query("select count(*) as jumlah from pos_mproductdiscountmember a left join pos_mproduct b on a.sku = b.sku WHERE a.sku ILIKE  '%$search%' or b.name ILIKE  '%$search%'
                                                         or a.discountname ILIKE  '%$search%'");
        foreach($querycount as $rr){
			$datacount = $rr['jumlah'];
			
		}
           $totalFiltered = $datacount;
        }
 
        $data = array();
        if(!empty($query))
        {
            $no = $start + 1;
			foreach($query as $r){
				
				if($r['discountname'] != ''){
					$discname = '<font style="color: blue; font-weight: bold">'.$r['discountname'].'</font>';
					
				}else{
					
					$discname = '';
				}
				
				// $pd = $r['price'] - $r['discount'];
				$pd = $r['pricediscount'];
				$potongan = $r['price'] - $r['pricediscount'];
				if($r['price'] != $pd){
					
					$fontdiskon = '<br> <font style="color: red; font-weight: bold">Setelah Diskon : '.rupiah($pd).'</font>';
				}else{
					
					$fontdiskon = '';
				}
				

				
				$nestedData['no'] = $no;
				$nestedData['postdate'] = $r['postdate'];
				$nestedData['discounttype'] = $r['discounttype'];
                $nestedData['sku'] = '<font style="font-weight: bold">'.$r['sku'].'</font>';
                $nestedData['name'] = $r['name'];
                $nestedData['hargareguler'] = '<font style="color: blue;font-weight: bold">Rp. '.rupiah($r['price']).'</font>';
                $nestedData['potongan'] = '<font style="color: red;font-weight: bold">Rp. '.rupiah($potongan).'</font>';
                $nestedData['afterdiscount'] = '<font style="color: green;font-weight: bold">Rp. '.rupiah($pd).'</font>';
                $nestedData['discountname'] = $discname;
                $nestedData['fromdate'] = $r['fromdate'];
                $nestedData['todate'] = $r['todate'];
                $nestedData['price'] = rupiah($r['price']);
                $nestedData['price_discount'] = rupiah($pd);
                $data[] = $nestedData;
                $no++;
				
			}
			

        }
           
        $json_data = array(
                    "draw"            => intval($_POST['draw']),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data  
                    );
             
        echo json_encode($json_data); 
		
		
		
	
	}else if($_GET['act'] == 'api_datatable_promo_tebus_murah'){
		

		 $columns = array( 
                               0 =>'postdate', 
                               1 =>'discountname',
                               2=> 'discounttype',
                               3=> 'sku',
                               4=> 'nama',
                               5=> 'price',
                               6=> 'price_discount',
                               7=> 'price_after_discount',
                               8=> 'fromdate',
                               9=> 'todate',
                           );
 
      $querycount =  $connec->query("SELECT count(*) as jumlah FROM pos_mproductdiscountmurah");
    
		foreach($querycount as $r){
			$datacount = $r['jumlah'];
			
		}
   
        $totalData = $datacount;
             
        $totalFiltered = $totalData; 
 

        $limit = $_POST['length'];
        $start = $_POST['start'];
        $order = $columns[$_POST['order']['0']['column']];
        $dir = $_POST['order']['0']['dir'];
             
        if(empty($_POST['search']['value']))
        {
         $query = $connec->query("select a.*, b.name, b.price from pos_mproductdiscountmurah a left join pos_mproduct b on a.sku = b.sku order by $order $dir
                                                      LIMIT $limit
                                                      OFFSET $start");
        }
        else {
            $search = $_POST['search']['value']; 
            $query = $connec->query("select a.*, b.name, b.price from pos_mproductdiscountmurah a left join pos_mproduct b on a.sku = b.sku WHERE a.sku ILIKE  '%$search%'
                                                         or a.discountname ILIKE  '%$search%'
                                                         or b.name ILIKE  '%$search%'
                                                         order by $order $dir
                                                         LIMIT $limit
                                                         OFFSET $start");
 
 
         $querycount = $connec->query("select count(*) as jumlah from pos_mproductdiscountmurah a left join pos_mproduct b on a.sku = b.sku WHERE a.sku ILIKE  '%$search%' or b.name ILIKE  '%$search%'
                                                         or a.discountname ILIKE  '%$search%'");
        foreach($querycount as $rr){
			$datacount = $rr['jumlah'];
			
		}
           $totalFiltered = $datacount;
        }
 
        $data = array();
        if(!empty($query))
        {
            $no = $start + 1;
			foreach($query as $r){
				
				if($r['discountname'] != ''){
					$discname = '<font style="color: blue; font-weight: bold">'.$r['discountname'].'</font>';
					
				}else{
					
					$discname = '';
				}
				
				// $pd = $r['price'] - $r['discount'];
				$pd = $r['pricediscount'];
				$potongan = $r['price'] - $r['pricediscount'];
				if($r['price'] != $pd){
					
					$fontdiskon = '<br> <font style="color: red; font-weight: bold">Setelah Diskon : '.rupiah($pd).'</font>';
				}else{
					
					$fontdiskon = '';
				}
				
                  // { "data": "no" },
				$nestedData['no'] = $no;
				$nestedData['postdate'] = $r['postdate'];
                $nestedData['sku'] = '<font style="font-weight: bold">'.$r['sku'].'</font>';
                $nestedData['name'] = $r['name'];
                $nestedData['hargareguler'] = '<font style="color: blue;font-weight: bold">Rp. '.rupiah($r['price']).'</font>';
                $nestedData['potongan'] = '<font style="color: red;font-weight: bold">Rp. '.rupiah($potongan).'</font>';
                $nestedData['afterdiscount'] = '<font style="color: green;font-weight: bold">Rp. '.rupiah($pd).'</font>';
                $nestedData['discountname'] = $discname;
                $nestedData['fromdate'] = $r['fromdate'];
                $nestedData['todate'] = $r['todate'];
                $nestedData['price'] = rupiah($r['price']);
                $nestedData['price_discount'] = rupiah($pd);
                $data[] = $nestedData;
                $no++;
				
			}
			

        }
           
        $json_data = array(
                    "draw"            => intval($_POST['draw']),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data  
                    );
             
        echo json_encode($json_data); 
		
		
		
	
	}else if($_GET['act'] == 'get_items'){
		
		
		 $sku = $_POST['sku'];
		 $query = $connec->query("SELECT a.sku,a.name,a.price, (coalesce(a.price,0) - coalesce(b.discount,0)) price_discount, b.discountname FROM 
		 pos_mproduct a left join (select * from pos_mproductdiscount where todate > '".date('Y-m-d')."') b on a.sku = b.sku WHERE (a.sku = '".$sku."' 
		 or a.shortcut = '".$sku."')");
		 $count = $query->rowCount();
		 
		 if($count > 0){
			 
		foreach($query as $r){
			 
			$json_data = array(
				"result"=>1,
				"sku"=>$r['sku'],
				"name"=>$r['name'],
				"price_discount"=>$r['price_discount']
		  
			);
		 }
		 }else{
			 
			 $json_data = array(
				"result"=>0
		  
			);
		 }
		 
		
		 
		  echo json_encode($json_data); 
		 
		 
	}else if($_GET['act'] == 'api_mmember'){
		
		
		 $columns = array( 
                               0 =>'membercardno', 
                               1 =>'nohp',
                               2 =>'name',
                               3=> 'point',
                               4=> 'insertby',
                           );
 
      $querycount =  $connec->query("SELECT count(*) as jumlah FROM pos_mmember where nohp NOT ILIKE '%X%'");
    
		foreach($querycount as $r){
			$datacount = $r['jumlah'];
			
		}
   
        $totalData = $datacount;
             
        $totalFiltered = $totalData; 
 
        $limit = $_POST['length'];
        $start = $_POST['start'];
        $order = $columns[$_POST['order']['0']['column']];
        $dir = $_POST['order']['0']['dir'];
             
        if(empty($_POST['search']['value']))
        {
         $query = $connec->query("select * from pos_mmember where nohp NOT ILIKE '%X%' order by $order $dir
                                                      LIMIT $limit
                                                      OFFSET $start");
        }
        else {
            $search = $_POST['search']['value']; 
            $query = $connec->query("select * from pos_mmember a WHERE nohp NOT ILIKE '%X%' and a.membercardno ILIKE  '%$search%'
                                                         or a.nohp ILIKE  '%$search%'
                                                         or a.name ILIKE  '%$search%'
                                                         order by $order $dir
                                                         LIMIT $limit
                                                         OFFSET $start");
 
 
         $querycount = $connec->query("SELECT count(*) as jumlah FROM pos_mmember a WHERE nohp NOT ILIKE '%X%' and a.membercardno ILIKE  '%$search%'
                                                         or a.nohp ILIKE '%$search%'
                                                         or a.name ILIKE  '%$search%'
														 ");
        foreach($querycount as $rr){
			$datacount = $rr['jumlah'];
			
		}
           $totalFiltered = $datacount;
        }
 
        $data = array();
        if(!empty($query))
        {
            $no = $start + 1;
			foreach($query as $r){
				$nestedData['no'] = $no;
                $nestedData['membercardno'] = $r['membercardno'];
                $nestedData['nohp'] = $r['nohp'];
                $nestedData['name'] = $r['name'];
                $nestedData['point'] = (int)$r['point'];
                $nestedData['insertby'] = $r['insertby'];
                $data[] = $nestedData;
                $no++;
				
			}
			

        }
           
        $json_data = array(
                    "draw"            => intval($_POST['draw']),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data  
                    );
             
        echo json_encode($json_data); 
		
		
		
	
	}else if($_GET['act'] == 'input_member'){
				$ad_morg_key =	$_POST['ad_morg_key'];
				$isactived = $_POST['isactived'];
				$insertdate =	$_POST['insertdate'];
				$insertby =	$_POST['insertby'];
				$postby =	$_POST['postby'];
				$postdate =	$_POST['postdate'];	
				$memberid =	$_POST['memberid'];
				$name =	str_replace("'", "", $_POST['name']);
				$dateofbirth =	$_POST['dateofbirth'];
				$point =	$_POST['point'];
				$membercardno =	$_POST['membercardno'];	
				$nohp =	$_POST['nohp'];
				$seqno =	$_POST['seqno'];

		$sql = "INSERT INTO pos_mmember (ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, memberid, name, dateofbirth, point, membercardno, nohp)
			VALUES('', '".$ad_morg_key."', '".$isactived."', '".$insertdate."', '".$insertby."', '".$postby."', '".$postdate."', '".$memberid."', '".$name."', 
			'".$dateofbirth."', '0', '".$membercardno."', '".$nohp."')";	
					
					
					
		$statement1 = $connec->query($sql);

		if($statement1){
			$json = array('result'=>'1', 'msg'=>'Berhasil insert ke pos lokal');
			
		}else{
			
			$json = array('result'=>'0', 'msg'=>'Gagal input ke pos lokal');
		}
		
		
		
		$json_string = json_encode($json);
		echo $json_string;
		// echo $sql;
	}else if($_GET['act'] == 'api_mproduct'){
		
		
		 $columns = array( 
                               0 =>'no', 
                               1 =>'sku',
                               2 =>'name',
                               3=> 'rack_name',
                           );
 
      $querycount =  $connec->query("SELECT count(*) as jumlah FROM inv_mproduct");
    
		foreach($querycount as $r){
			$datacount = $r['jumlah'];
			
		}
   
        $totalData = $datacount;
             
        $totalFiltered = $totalData; 
 
        $limit = $_POST['length'];
        $start = $_POST['start'];
        $order = $columns[$_POST['order']['0']['column']];
        $dir = $_POST['order']['0']['dir'];
             
        if(empty($_POST['search']['value']))
        {
         $query = $connec->query("select * from inv_mproduct order by $order $dir LIMIT $limit OFFSET $start");
        }
        else {
            $search = $_POST['search']['value']; 
            $query = $connec->query("select * from inv_mproduct a WHERE a.sku ILIKE  '%$search%'
                                                         or a.rack_name ILIKE  '%$search%'
                                                         or a.name ILIKE  '%$search%'
                                                         order by $order $dir
                                                         LIMIT $limit
                                                         OFFSET $start");
 
 
         $querycount = $connec->query("SELECT count(*) as jumlah FROM inv_mproduct a WHERE a.sku ILIKE  '%$search%'
                                                         or a.rack_name ILIKE '%$search%'
                                                         or a.name ILIKE  '%$search%'
														 ");
        foreach($querycount as $rr){
			$datacount = $rr['jumlah'];
			
		}
           $totalFiltered = $datacount;
        }
 
        $data = array();
        if(!empty($query))
        {
            $no = $start + 1;
			foreach($query as $rrr){
				$nestedData['no'] = $no;
                $nestedData['sku'] = $rrr['sku'];
                $nestedData['name'] = $rrr['name'];
                $nestedData['rack_name'] = $rrr['rack_name'];
                $data[] = $nestedData;
                $no++;
				
			}


        }
           
        $json_data = array(
                    "draw"            => intval($_POST['draw']),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data  
                    );
             
        echo json_encode($json_data); 
		
		
		
	
	}else if($_GET['act'] == 'api_mcategory'){
		
		
		 $columns = array( 
                               // 0 =>'no', 
                               0 =>'value',
                               1 =>'name',
                           );
 
      $querycount =  $connec->query("SELECT count(*) as jumlah FROM inv_mproductcategory");
    
		foreach($querycount as $r){
			$datacount = $r['jumlah'];
			
		}
   
        $totalData = $datacount;
             
        $totalFiltered = $totalData; 
 
        $limit = $_POST['length'];
        $start = $_POST['start'];
        $order = $columns[$_POST['order']['0']['column']];
        $dir = $_POST['order']['0']['dir'];
             
        if(empty($_POST['search']['value']))
        {
         $query = $connec->query("select * from inv_mproductcategory order by $order $dir LIMIT $limit OFFSET $start");
        }
        else {
            $search = $_POST['search']['value']; 
            $query = $connec->query("select * from inv_mproductcategory a WHERE a.value ILIKE  '%$search%'
                                                         or a.name ILIKE  '%$search%'
                                                         order by $order $dir
                                                         LIMIT $limit
                                                         OFFSET $start");
 
 
         $querycount = $connec->query("SELECT count(*) as jumlah FROM inv_mproductcategory a WHERE a.value ILIKE  '%$search%'
                                                         or a.name ILIKE '%$search%'
														 ");
        foreach($querycount as $rr){
			$datacount = $rr['jumlah'];
			
		}
           $totalFiltered = $datacount;
        }
 
        $data = array();
        if(!empty($query))
        {
            $no = $start + 1;
			foreach($query as $rrr){
				// $nestedData['no'] = $no;
                $nestedData['value'] = $rrr['value'];
                $nestedData['name'] = $rrr['name'];
                $data[] = $nestedData;
                $no++;
				
			}


        }
           
        $json_data = array(
                    "draw"            => intval($_POST['draw']),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data  
                    );
             
        echo json_encode($json_data); 
		
		
		
	
	}else if($_GET['act'] == 'api_mproducts'){
		
		$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$org_key = $r["ad_morg_key"];	
		}
		
		
		// $org_caman = $org_key;
		// $hasil = monitoring($org_caman);
		
		// $json = file_get_contents("https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=monitoring_cek&org_key=".$org_key);
		// $j_hasil = json_decode($json, TRUE);
		
		//$j_hasil = json_decode($hasil, true);
		// $js = array();
		// foreach($j_hasil as $res){
					// $sku= $res['sku'];
					// $m_product_id= $res['m_product_id'];
					// $name= $res['name'];
					// $stockk = $res['stockqty'];
					
					
					$query = $connec->query("select sku, name, CAST(stockqty AS varchar) as stockqty, 
CAST(description AS varchar) as description, 
CASE WHEN CAST(stockqty AS varchar) = CAST(description AS varchar) THEN 'Sesuai'
ELSE 'Belum Sesuai' END AS status from pos_mproduct");
					foreach($query as $rr){
						$stock_lokal = 0;
						
						
					$ceksales = $connec->query("select sku, sum(qty) as jj from pos_dsalesline where sku = '".$rr['sku']."' and date(insertdate) = date(now()) group by sku");
					
					$stock_sales = 0;
					foreach ($ceksales as $rs) {
				
						$stock_sales = $rs['jj'];
					}
					
					
						
						
						
						
						$stock_lokal = $rr['stockqty'];
						$stockk = $rr['description'];
						$status = $rr['status'];
						
						
						$lok = $stock_lokal + $stock_sales;
					
					if($lok == $stockk){
						
						$set = "Sesuai";
					}else{
						
						$set = "Belum Sesuai";
					}
					
					$js[] = array("sku" => $rr['sku'], "name" => $rr['name'], "stock_lokal"=>$stock_lokal, "qty_sales"=>$stock_sales, "stock_erp"=>$stockk, "set"=>$status);
						
			
					}
					
					
		// }
		
	

             
        echo json_encode($js); 
		
		
	
						
	
	}else if($_GET['act'] == 'api_monitoring'){
		
		
		 $columns = array( 
                               0 =>'sku', 
                               1 =>'name',
                               2=> 'stock_lokal',
                               3=> 'stock_sales',
                               4=> 'stock_erp',
                           
                           );
 
      $querycount =  $connec->query("SELECT count(*) as jumlah FROM pos_mproduct");
    
		foreach($querycount as $r){
			$datacount = $r['jumlah'];
			
		}
   
        $totalData = $datacount;
             
        $totalFiltered = $totalData; 
 
        $limit = $_POST['length'];
        $start = $_POST['start'];
        $order = $columns[$_POST['order']['0']['column']];
        $dir = $_POST['order']['0']['dir'];
             
        if(empty($_POST['search']['value']))
        {
         $query = $connec->query("select m_product_id, sku, name, CAST(stockqty AS varchar) as stockqty, 
CAST(description AS varchar) as description, 
CASE WHEN CAST(stockqty AS varchar) = CAST(description AS varchar) THEN 'Sesuai'
ELSE 'Belum Sesuai' END AS status from pos_mproduct order by $order $dir
                                                      LIMIT $limit
                                                      OFFSET $start");
        }
        else {
            $search = $_POST['search']['value']; 
            $query = $connec->query("select m_product_id, sku, name, CAST(stockqty AS varchar) as stockqty, 
CAST(description AS varchar) as description, 
CASE WHEN CAST(stockqty AS varchar) = CAST(description AS varchar) THEN 'Sesuai'
ELSE 'Belum Sesuai' END AS status from pos_mproduct a WHERE a.sku ILIKE  '%$search%'
                                                         or a.name ILIKE  '%$search%'   
                                                         order by $order $dir
                                                         LIMIT $limit
                                                         OFFSET $start");
 
 
         $querycount = $connec->query("SELECT count(*) as jumlah FROM pos_mproduct a WHERE a.sku ILIKE  '%$search%' or a.name ILIKE '%$search%'");
        foreach($querycount as $rr){
			$datacount = $rr['jumlah'];
			
		}
           $totalFiltered = $datacount;
        }
 
        $data = array();
		$items = array();
        if(!empty($query))
        {
            $no = $start + 1;
			foreach($query as $r){
				
				$ceksales = $connec->query("select sku, sum(qty) as jj from pos_dsalesline where sku = '".$r['sku']."' and date(insertdate) = date(now()) group by sku");
					
					$stock_sales = 0;
					foreach ($ceksales as $rs) {
				
						$stock_sales = $rs['jj'];
					}
					
					$items[] = array(
									'm_product_id'	=>$r['m_product_id'],
									'stock_sales' => $stock_sales,
									'stock_lokal' => $r['stockqty'],
								);
					
			}		
			
								
						
			
					
					$items_json = json_encode($items);
					$hasil = get_data_stock_peritems($org_key, $items_json);
					$j_hasil = json_decode($hasil, true);
					
					$stock_erp = 0;
					$stock_sales = 0;
					$stock_erp = 0;
					$namaitem = "";
					foreach($j_hasil as $rrr){
						$m_product_id = $rrr['m_product_id'];
						$stock_erp = $rrr['stock_erp'];
						$stock_sales = $rrr['stock_sales'];
						$stock_lokal = $rrr['stock_lokal'];
						
					
					
					
					$lok = (int)$stock_lokal + $stock_sales;
					
					if($lok == (int)$stock_erp){
						
						$set = "Sesuai";
					}else{
						
						$set = "Belum Sesuai";
					}
					
					
					
					$ceknama = $connec->query("select sku, name from pos_mproduct where m_product_id = '".$m_product_id."'");
					
					
					foreach ($ceknama as $rn) {
				
						$namaitem = $rn['name'];
						$sku = $rn['sku'];
					}
				
                $nestedData['sku'] = '<font style="font-weight: bold">'.$sku.'</font>';
                $nestedData['name'] = $namaitem;
                $nestedData['stock_lokal'] = $stock_lokal;
                $nestedData['stock_sales'] = $stock_sales;
                $nestedData['stock_erp'] = (int)$stock_erp;
                $nestedData['status'] = $set;
                $data[] = $nestedData;
                $no++;
			}	
			
			

        }
           
        $json_data = array(
                    "draw"            => intval($_POST['draw']),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data  
                    );
             
        echo json_encode($json_data); 
		
		
		

	}else if($_GET['act'] == 'api_cashin'){
		
		$tanggal = $_GET['tanggal'];
		$userid = $_GET['userid'];
		 $columns = array( 
                               0 =>'nama_insert', 
                               1 =>'cash',
                               2=> 'insertdate',
                               3=> 'status',
                               4=> 'approvedby',
                               5=> 'syncnewpos',
                               6=> 'setoran',
                           
                           );
 
		if($_SESSION['name'] == 'Ka. Toko' || $_SESSION['name'] == 'Wk. Ka Toko'){
			if($userid == "all"){
				
				$querycount =  $connec->query("SELECT count(*) as jumlah FROM cash_in where date(insertdate) = '".$tanggal."'");
			}else{
				
				$querycount =  $connec->query("SELECT count(*) as jumlah FROM cash_in where date(insertdate) = '".$tanggal."' and userid='".$userid."'");
			}
			
		}else{
			
			 $querycount =  $connec->query("SELECT count(*) as jumlah FROM cash_in where date(insertdate) = '".$tanggal."' and userid='".$useridcuy."'");
		}
 
		
     
    
		foreach($querycount as $r){
			$datacount = $r['jumlah'];
			
		}
   
        $totalData = $datacount;
             
        $totalFiltered = $totalData; 
 
        $limit = $_POST['length'];
        $start = $_POST['start'];
        $order = $columns[$_POST['order']['0']['column']];
        $dir = $_POST['order']['0']['dir'];
             
        if(empty($_POST['search']['value']))
        {
			
			
		if($_SESSION['name'] == 'Ka. Toko' || $_SESSION['name'] == 'Wk. Ka Toko'){
			
			if($userid == "all"){
				
				 $query =  $connec->query("select * from cash_in where date(insertdate) = '".$tanggal."' order by $order $dir LIMIT $limit OFFSET $start");
			}else{
				 $query =  $connec->query("select * from cash_in where date(insertdate) = '".$tanggal."' and userid='".$userid."' order by $order $dir LIMIT $limit OFFSET $start");
	
			}
			
			
		}else{
			
			 $query =  $connec->query("select * from cash_in where date(insertdate) = '".$tanggal."' and userid='".$useridcuy."' order by $order $dir LIMIT $limit OFFSET $start");
		}
			

        }
        else {
            $search = $_POST['search']['value']; 
			
			
			
		if($_SESSION['name'] == 'Ka. Toko' || $_SESSION['name'] == 'Wk. Ka Toko'){
			
			
			
			
			if($userid == "all"){
				
				 $query = $connec->query("select * from cash_in a where date(insertdate) = '".$tanggal."' AND a.nama_insert ILIKE  '%$search%'
                                                         order by $order $dir
                                                         LIMIT $limit
                                                         OFFSET $start");
			$querycount = $connec->query("SELECT count(*) as jumlah FROM cash_in a where date(insertdate) = '".$tanggal."' AND a.nama_insert ILIKE  '%$search%'");
			}else{
				
				 
				$query = $connec->query("select * from cash_in a where date(insertdate) = '".$tanggal."' and userid='".$userid."' AND a.nama_insert ILIKE  '%$search%'
                                                         order by $order $dir
                                                         LIMIT $limit
                                                         OFFSET $start");
				$querycount = $connec->query("SELECT count(*) as jumlah FROM cash_in a where date(insertdate) = '".$tanggal."' and userid='".$userid."' AND a.nama_insert ILIKE  '%$search%'");
	
			}
			
			
		}else{
			
			$query = $connec->query("select * from cash_in a where date(insertdate) = '".$tanggal."' and userid='".$useridcuy."' AND a.nama_insert ILIKE  '%$search%'
                                                         order by $order $dir
                                                         LIMIT $limit
                                                         OFFSET $start");
 
 
            $querycount = $connec->query("SELECT count(*) as jumlah FROM cash_in a where date(insertdate) = '".$tanggal."' and userid='".$useridcuy."' AND a.nama_insert ILIKE  '%$search%'");
		}
			
			
			
            
        foreach($querycount as $rr){
			$datacount = $rr['jumlah'];
			
		}
           $totalFiltered = $datacount;
        }
 
        $data = array();
		$items = array();
        if(!empty($query))
        {
            $no = $start + 1;
			
			foreach($query as $r){
				
				if($r['syncnewpos'] == 1){
					
					
					$snp = "<font style='background-color: green; color: #fff; padding: 5px'>Sudah</font>";
				}else{
					$snp = "<font style='background-color: red; color: #fff; padding: 5px'>Belum</font>";
					
					
				}
				
				if($r['status'] == 1){
					
					if($_SESSION['name'] == 'Ka. Toko' || $_SESSION['name'] == 'Wk. Ka Toko'){
						$st = "<font style='background-color: green; color: #fff; padding: 5px'>Completed</font> &nbsp <button type='button' class='btn btn-primary' onclick='cetakStrukDetail(\"".$r['cashinid']."\")'>Reprint</button>";
						
					}else{
						
						$st = "<font style='background-color: green; color: #fff; padding: 5px'>Completed</font>";
						
					}
					
					
				}else{
					
					$st = "<font style='background-color: red; color: #fff; padding: 5px'>Draft</font> &nbsp 
					
					<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#exampleModal".$r['cashinid']."'>Approve</button><div class='modal fade' id='exampleModal".$r['cashinid']."' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
<div id='overlay'>
			<div class='cv-spinner'>
				<span class='spinner'></span>
			</div>
		</div>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <h5 class='modal-title' id='exampleModalLabel'>Masukan UserID dan Password Wakil / Kepala Toko</h5><br>
       
		 
        <button type='button' class='close' data-bs-dismiss='modal' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
        </button>
		
		
      </div>
	  
      <div class='modal-body' style='background: #cacaca'>
	
	    <p id='notif".$r['cashinid']."' style='color: red; font-weight: bold; background: #fff; padding: 10px'>
		Perhatikan nominal pick up sebelum diapprove apakah sudah benar
		</p>
		
		<div class='row-info'> 
			

		<div class='row'>
		<div class='col-25'>
			<label style='color: #fff'for='fname' >Username</label>
		</div>
		<div class='col-75'>
			<input class='form-control' type='text' id='username".$r['cashinid']."' placeholder='Masukan userid' autocomplete='off'>
		</div>
		</div>
		<div class='row'>
		<div class='col-25'>
			<label style='color: #fff'for='fname' >Password</label>
		</div>
		<div class='col-75'>
			<input class='form-control' type='password' name='password".$r['cashinid']."' id='password".$r['cashinid']."' placeholder='Masukan password' autocomplete='off' 
			readonly onfocus='this.removeAttribute(\"readonly\");'>
		</div>
		</div>	
		
			
		</div> 
		
		
      </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>CANCEL</button>
        <button type='button' id='butsave' onclick='cekCredentialDetail(\"".$r['cashinid']."\")' class='btn btn-primary'>SUBMIT</button>
      </div>
    </div>
  </div>
</div><script>document.getElementById('password".$r['cashinid']."').addEventListener('keypress', function(event) {
	if (event.key === 'Enter') {
		cekCredentialDetail(\"".$r['cashinid']."\")
		
	}
	});
	</script>";
				}
				
					$nestedData['nama_insert'] = '<font style="font-weight: bold">'.$r['nama_insert'].'</font>';
					$nestedData['cash'] = "Rp. ".rupiah($r['cash']);
					$nestedData['insertdate'] = $r['insertdate'];
					$nestedData['status'] = $st;
					$nestedData['approvedby'] = $r['approvedby'];
					$nestedData['syncnewpos'] = $snp;
					$nestedData['setoran'] = $r['setoran'];
					$data[] = $nestedData;
					$no++;
				
			}
           
				
			
			

        }
           
        $json_data = array(
                    "draw"            => intval($_POST['draw']),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data  
                    );
             
        echo json_encode($json_data); 
		
		
		
	
	}else if($_GET['act'] == 'get_data_doc'){
		$doc_no = $_GET['doc_no'];
		$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$org_key = $r["ad_morg_key"];	
		}
		
		// $org_key = '62BE07BC50FA4B5495D73B38861CDE34';			
		
		$hasil = get_data_doc($org_key, $doc_no); //php curl
				
				// print_r ($hasil);
		echo $hasil;		
				
				
		// $j_hasil = json_decode($hasil, true);
				
				
				// $obj = json_decode($j_hasil);
				// var_dump($j_hasil);
				// die();
				// print_r($items_json);
	}else if($_GET['act'] == 'sync_erp_stock'){
		
		
		

		$doc_no = $_GET['doc_no'];
		$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$org_key = $r["ad_morg_key"];	
		}
		
		// $org_key = '62BE07BC50FA4B5495D73B38861CDE34';			
		
		$hasil = get_data_doc($org_key, $doc_no); //php curl
				
		
	}else if($_GET['act'] == 'input_cashin'){
				$org_key =	$_SESSION['org_key'];
				$nama_insert = $_SESSION['username'];
			
				
				$cash =	$_POST['cash'];
				$date = date("Y-m-d H:i:s");
				
		if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
			$sql = "INSERT INTO cash_in (org_key, userid, nama_insert, cash, insertdate, status)
				VALUES('".$org_key."', '".$useridcuy."', '".$nama_insert."', '".$cash."', '".$date."','0')";	
						
						
						
			$statement1 = $connec->query($sql);
	
			if($statement1){
				$json = array('result'=>'1', 'msg'=>'Berhasil insert cash in');
				
			}else{
				
				$json = array('result'=>'0', 'msg'=>'Gagal input cash in');
			}
		}else{
			$json = array('result'=>'3', 'msg'=>'Sesi user telah habis, reload halamannya');
		}		

		
		
		// $sqls = "INSERT INTO cash_in (org_key, userid, nama_insert, cash, insertdate, status)
				// VALUES('".$org_key."', '".$useridcuy."', '".$nama_insert."', '".$cash."', '".$date."','0')";	
		
		$json_string = json_encode($json);
		echo $json_string;
		// echo $sqls;
	}else if($_GET['act'] == 'cek_credentials'){
				$username =	$_POST['username'];
				$password = $_POST['password'];
				
				$pwd = hash_hmac("sha256", $password, 'marinuak');
		$sql = "select count(*) jum from m_pi_users where userid = '".$username."' and userpwd = '".$pwd."' and (name = 'Ka. Toko' or name = 'Wk. Ka Toko')";	
					
					
					
		$statement1 = $connec->query($sql);
		
		

		if($statement1){
			$jum = 0;
			foreach($statement1 as $r){
				
				$jum = $r['jum'];
			}
			
			if($jum > 0){
				
				
				$connec->query("update cash_in set status = 1 where date(insertdate) = date(now()) and userid = '".$useridcuy."'");
				
				
				$json = array('result'=>'1', 'msg'=>'Berhasil credentials');
			}else{
				$json = array('result'=>'0', 'msg'=>'Username / password salah');
				
			}
			
			
			
			
		}else{
			
			$json = array('result'=>'0', 'msg'=>'Gagal credentials');
		}
		
		
		
		$json_string = json_encode($json);
		echo $json_string;
		// echo $sql;
	}else if($_GET['act'] == 'cek_credentials_detail'){
				$username =	$_POST['username'];
				$password = $_POST['password'];
				$id = $_GET['id'];
				
		if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
  		$pwd = hash_hmac("sha256", $password, 'marinuak');
		$sql = "select count(*) jum, username from m_pi_users where userid = '".$username."' and userpwd = '".$pwd."' and (name = 'Ka. Toko' or name = 'Wk. Ka Toko') group by username";			
		$statement1 = $connec->query($sql);
		if($statement1){
			$jum = 0;
			foreach($statement1 as $r){
				
					$jum = $r['jum'];
					$nama = $r['username'];
			}
			
			if($jum > 0){
				
				
				$cek_userid = "select userid from cash_in where cashinid = '".$id."'";			
				$cu = $connec->query($cek_userid); 
				foreach($cu as $rr){
				
				
					$user_kasir = $rr['userid'];
				}
				
				$cek_setoran = "select setoran from cash_in where status = '1' and userid = '".$user_kasir."' and date(insertdate) = date(now()) order by setoran desc limit 1";			
				$cs = $connec->query($cek_setoran); 
				
				foreach($cs as $rrr){
				
				
					$setoran = $rrr['setoran'];
				}
				
				$setoran = $setoran + 1;
				
				$connec->query("update cash_in set status = 1, approvedby = '".$nama."', setoran = '".$setoran."' where date(insertdate) = date(now()) and cashinid = '".$id."'");
				
				
				$json = array('result'=>'1', 'msg'=>'Berhasil credentials', 'username'=>$nama, 'setoran'=>$setoran);
			}else{
				$json = array('result'=>'0', 'msg'=>'Username / password salah');
				
			}
		}else{
			
			$json = array('result'=>'0', 'msg'=>'Gagal credentials');
		}
		}else{
			$json = array('result'=>'3', 'msg'=>'Sesi user anda telah habis, reload dan login kembali');	

		}		
				
				

		
		
		
		$json_string = json_encode($json);
		echo $json_string;
		// echo $sql;
	}else if($_GET['act'] == 'get_plano'){

		$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$org_key = $r["ad_morg_key"];	
		}
		
		
		
		$jsons = get_data_gudang($org_key);
		

		

		$arr = json_decode($jsons, true);
		$jum = count($arr);
		
		// var_dump($jsons);
		
		$s = array();
		if($jum > 0){
		$no = 1;
		foreach ($arr as $row1) {
			
							echo 
							"<tr>
								<td>".$no."</td>
								<td>".$row1['sku']."</td>
								<td>".$row1['name']."</td>
								<td>".$row1['rack_name']."</td>
								<td>".$row1['type']."</td>

							</tr>";
							
							
			
			$no++;
			}
		}
		
	}else if($_GET['act'] == 'get_type'){
		$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$org_keys = $r["ad_morg_key"];	
		}
		
			$json_url = "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=get_type&org_id=".$org_keys;
			$json = file_get_contents($json_url);
		
	
			$json = array('result'=>'1', 'msg'=>'Gagal connect ke server', 'type'=>$json);
			

			$json_string = json_encode($json);	
			echo $json_string;	
	}else if($_GET['act'] == 'in_store'){

		$sqll = "select name from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$name = $r["name"];	
		}

		$json_url = "https://pi.idolmartidolaku.com/api/mkt.php?modul=view&act=in_store&name=".$name;
		$json = file_get_contents($json_url);
	

		$arr = json_decode($json, true);
		$jum = count($arr);
		
		// var_dump($jsons);
		
		$s = array();
		if($jum > 0){
		$no = 1;
		foreach ($arr as $row1) {
			
				$stats = '<font style="background-color: green;color:#fff;padding:10px">Aktif</font>';
				if($row1['status'] == '0'){
					$stats = '<font style="background-color: red;color:#fff;padding:10px">Nonaktif</font>';
					
				}
			
							echo 
							"<tr>
								<td>".$no."</td>
								
								<td>".$row1['nama_supplier']."</td>
								<td>".$row1['no_skp']."</td>
								<td>".$row1['toko']."</td>
								<td>".$row1['jenis_sewa']."</td>
								<td>".$row1['posisi_display']."</td>
								<td>Rp ".rupiah($row1['rupiah'])."</td>
								<td>".$row1['periode']."</td>
								<td>".$row1['periode_akhir']."</td>
								<td>".$row1['pembayaran']."</td>
								<td>".$row1['input_date']."</td>
								<td>".$stats."</td>

							</tr>";
							
							
			
			$no++;
			}
		}
		
	}else if($_GET['act'] == 'out_store'){
		
		$sqll = "select name from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$name = $r["name"];	
		}

		$json_url = "https://pi.idolmartidolaku.com/api/mkt.php?modul=view&act=out_store&name=".$name;
		$json = file_get_contents($json_url);
		
		$arr = json_decode($json, true);
		$jum = count($arr);
		
		// var_dump($jsons);
		
		$s = array();
		if($jum > 0){
		$no = 1;
		foreach ($arr as $row1) {
			
							echo 
							"<tr>
								<td>".$no."</td>
								<td>".$row1['nama_penyewa']."</td>
								<td>".$row1['no_sks']."</td>
								<td>".$row1['toko']."</td>
								<td>".$row1['jenis_usaha']."</td>
								<td>".$row1['posisi']."</td>
								<td>Rp ".rupiah($row1['rupiah'])."</td>
								<td>".$row1['periode']."</td>
								<td>".$row1['periode_akhir']."</td>
								<td>".$row1['pembayaran']."</td>
								<td>".$row1['input_date']."</td>
								<td>".$row1['status']."</td>

							</tr>";
							
							
			
			$no++;
			}
		}
		
	}else if($_GET['act'] == 'edc'){
		
		
		$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$org_keys = $r["ad_morg_key"];	
		}
		
		$hasil = get_data_edc();
		$j_hasil = json_decode($hasil, true);	


			$s = array();
			$p = array();
			foreach ($j_hasil['edc'] as $rrr) {
				$s[] = "('".$rrr['pos_medc_key']."','".$org_keys."','".$rrr['insertdate']."', '".$rrr['insertby']."','".$rrr['name']."','".$rrr['description']."','".$rrr['code']."','".$rrr['isactived']."')";
	
			}
			
			// foreach ($j_hasil['bank'] as $rrr) {
				// $p[] = "('".$rrr['pos_mbank_key']."','".$rrr['insertdate']."', '".$rrr['insertby']."','".$rrr['name']."','".$rrr['description']."')";
	
			// }
			 
			$edc = implode(", ",$s); 
			// $bank = implode(", ",$p); 
			
			// $connec->query("truncate table pos_mbank");
			
			
			
			if(count($s) > 0){
				
				$tr = $connec->query("truncate table pos_medc");
				if($tr){
					$sql_edc = "INSERT INTO pos_medc(pos_medc_key, ad_morg_key, insertdate, insertby, name, description, code, isactived) values ".$edc."";
					$connec->query($sql_edc);
					
				}
				
			}
			
			
			
			// echo $edc;
			
			
			// if($truncate){
				
				// $sql_bank = "INSERT INTO pos_mbank (pos_mbank_key, insertdate, insertby, name, description) values ".$bank."";
				// $connec->query($sql_bank);
				
				
			
			
		
				// echo $sql_edc;
				
				
			// }
		$data = array("result"=>1, "msg"=>"Berhasil sync data", "q"=>$sql_edc);
		
		$json_string = json_encode($data);	
		echo $json_string;
		// echo $j_hasil['edc'];
	}								


	

}else if($_GET['modul'] == 'lomba'){
    if($_GET['act'] == 'input'){
		
		$json_de = json_decode(inputLomba($_POST['no_hp'], $_POST['nama'], $_POST['kategori'], $storename, $ad_morg_key), true);
		echo json_encode($json_de);
		

    }else if($_GET['act'] == 'cetak_generic'){
		
		$id = $_POST['id'];
		
		$json_url = "https://pi.idolmartidolaku.com/api/action.php?modul=lomba&act=get_id&id=".$id;
		$json = file_get_contents($json_url);
		
		
		$arr = json_decode($json, true);
		$jum = count($arr);
		// var_dump($jsons);
		$jj = array();
		$s = array();
		if($jum > 0){
		$no = 1;
		
		
		foreach ($arr as $row1) {
		$kt = explode('-', $row1['nama_toko']);
				$jj[] = array(
					"kode_struk"=> $row1['kode_struk'],
					"kode_pendaftaran"=> $row1['kode_pendaftaran'],
					"nomor_urut"=> $row1['nomor_urut'],
					"nama"=> $row1['nama'],
					"no_hp"=> $row1['no_hp'],
					"kategori"=> $row1['kategori'],
					"ad_org_id"=> $row1['ad_org_id'],
					"nama_toko"=> $kt[1],
					"insertdate"=> $row1['insertdate'],
					"datenow"=> date('Y-m-d H:i:s'),
					"brand"=> $brand,
				);
			
			}
		}
		$json_string = json_encode($jj);	
		echo $json_string;
		
	}
}else if($_GET['modul'] == 'referal'){
	
	if($_GET['act'] == 'update'){
		
		$billno = $_POST['billno'];
		$referal = $_POST['referal'];
		$update = $connec->query("update pos_dsalesline set postby = '".$referal."' where billno = '".$billno."'");
						
		if($update){
			$json = array('result'=>'1');
			
		}else{
			
			$json = array('result'=>'0');
		}
		
		$json_string = json_encode($json);	
		echo $json_string;
	}else if($_GET['act'] == 'data_hris'){
		
		$get = $connec->query("select * from m_pi_hris");
		$s = array();
		foreach($get as $r){
			
			$header = array(
					'nik'=>$r['nik'],
					'nama'=>$r['nama']
			);

			$s[] = $header;
			
		}
			$json_data = array(
				"result"=>1,
				"msg"=>'Proses cetak',
				"header"=>$s,
			);
		
		
		echo json_encode($json_data); 				
	}else if($_GET['act'] == 'data_hris_nik'){
		
		$get = $connec->query("select * from m_pi_hris where nik = '".$_GET['nik']."'");
		$s = array();
		$nama = "";
		foreach($get as $r){
			
			// $header = array(
					// 'nik'=>$r['nik'],
					// 'nama'=>$r['nama']
			// );

			// $s[] = $header;
			$nama = $r['nama'];
			
		}
		
		echo $nama;		
	}
	
}










    

?>