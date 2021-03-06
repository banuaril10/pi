<?php session_start();
ini_set('max_execution_time', '4000');
if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
  
}else{
	$json = array('result'=>'3', 'msg'=>'Session telah habis, reload dulu halamannya');	
	// header("Location: ../index.php");
}


include "../config/koneksi.php";
// $ch = curl_init();
$username = $_SESSION['username'];
$org_key = $_SESSION['org_key'];
$ss = $_SESSION['status_sales'];
$kode_toko = $_SESSION['kode_toko'];


function rupiah($angka){
	
	$hasil_rupiah = number_format($angka,0,',','.');
	return $hasil_rupiah;
 
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

function get_data_erp_borongan($a,$b,$c,$d){
			
	$postData = array(
		"m_locator_id" => $a,
		"m_pro_id" => $b,
		"org_key" => $c,
		"ss" => $d
	
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

if($_GET['modul'] == 'inventory'){	
	$it = $_POST['it'];
	$sl = $_POST['sl'];
	$kat = $_POST['kat'];
	$rack = $_POST['rack'];
	$pc = $_POST['pc'];
	$ss = $_POST['sso'];
	
	// $it = 'Global';
	// $sl = '4DC01BB67AB148C9A02C4F5DB39AF969';
	// $kat = '1';
	// $rack = 'R-1';
	// $pc = 1;
	// $ss = '0';
	
	
	
	if($_GET['act'] == 'input'){
		
		
		if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
				
				$cekrak = "select count(m_pi_key) jum from m_pi where rack_name='".$rack."' and status != '3' and status != '5' and date(insertdate) = date(now())";
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
				
				}

				$sql1 = "select m_product_id,sku from inv_mproduct where rack_name='".$rack."'";
				$result = $connec->query($sql1);
				$count = $result->rowCount();
				
				
				
				
				$no = 0;
				$items = array();

				
				
				
				
				foreach ($connec->query($sql1) as $row) {
						
						$sql_sales = "select case when sum(qty) is null THEN '0' ELSE sum(qty) END as qtysales from pos_dsalesline 
						where date(insertdate)=date(now()) and sku='".$row['sku']."'";
					
						$rsa = $connec->query($sql_sales);

						foreach ($rsa as $rsa1) {
						
							$qtysales = $rsa1['qtysales'];
						}
						
						
						
						// $qtysales = $row['qtysales'];
						$cek_count = "select qtycount from m_piline where sku = '".$row['sku']."' and date(insertdate)=date(now())"; //mencari apakah items sdh ada di rack piline
						$rsac = $connec->query($cek_count);
						$ccc = $rsac->rowCount();
						
						if($ccc > 0){
							foreach ($rsac as $rrr) {
						
								$qtycount = $rrr['qtycount'];
							}
							
						}else{
							$qtycount = 0;
							
						}
						
					$items[] = array('sku'=> $row['sku'], 'mpi' =>$row['m_product_id'], 'qtycount'=>$qtycount, 'qtysales'=>$qtysales);
						

					
			
				}
				
				$items_json = json_encode($items);
				
				
				
				
				
				$hasil = get_data_erp_borongan($sl, $items_json, $org_key, $ss); //php curl
				
				// print_r ($hasil);
				
				
				
				$j_hasil = json_decode($hasil, true);
				
				
				// $obj = json_decode($j_hasil);
				// var_dump($j_hasil);
				// die();
				// print_r($items_json);
				
				foreach($j_hasil as $r) {
					$qtyon= $r['qtyon'];			
					$price= $r['price'];			
					$statuss= $r['statuss'];			
					$qtyout= $r['qtyout'];			
					$statusss= $r['statusss'];
					$mpi= $r['mpi'];
					$sku= $r['sku'];
					$qtycount= $r['qtycount'];
					$qtysales= $r['qtysales'];
				
					$statement1 = $connec->query("insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, postdate, m_storage_id, m_product_id, sku, qtyerp, qtycount, qtysales, price, status, qtysalesout, status1) 
					VALUES ('".$lastid."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".date('Y-m-d H:i:s')."', '".$sl."','".$mpi."', 
					'".$sku."', '".$qtyon."', '".$qtycount."', '".$qtysales."','".$price."', '".$statuss."', '".$qtyout."','".$statusss."')");
					
					// echo "insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, postdate, m_storage_id, m_product_id, sku, qtyerp, qtycount, qtysales, price, status, qtysalesout, status1) 
					// VALUES ('".$lastid."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".date('Y-m-d H:i:s')."', '".$sl."','".$mpi."', 
					// '".$sku."', '".$qtyon."', '".$qtycount."', '".$qtysales."','".$price."', '".$statuss."', '".$qtyout."','".$statusss."')";
					
					
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
		
		
		$sql = "select m_piline.qtycount, pos_mproduct.name from m_piline left join pos_mproduct on m_piline.sku = pos_mproduct.sku where m_piline.sku ='".$sku."'
		and date(m_piline.insertdate) = date(now())";
		$result = $connec->query($sql);
		$count = $result->rowCount();
		
		if($count > 0){
			foreach ($result as $r) {
			$qtyon = $r['qtycount'];
			$pn = $r['name'];	

			$lastqty = $qtyon + 1;
		
			$statement1 = $connec->query("update m_piline set qtycount = '".$lastqty."' where sku = '".$sku."' and date(insertdate) = '".date('Y-m-d')."'");
			
			if($statement1){	
				$json = array('result'=>'1', 'msg'=>$sku .' ('.$pn.'), QUANTITY = <font style="color: red">'.$lastqty.'</font>');	
			}else{
				$json = array('result'=>'0', 'msg'=>'Gagal ,coba lagi nanti');	
				
			}				
			}
			
		}else{
			
			
			$sql_pos = "select rack, name from pos_mproduct where sku ='".$sku."'";
			$cekm = $connec->query($sql_pos);
			$count_pos = $cekm->rowCount();
			
			if($count_pos > 0){
				foreach ($cekm as $haha) {
					
					if($haha['rack'] == NULL) {
						
						$json = array('result'=>'2', 'msg'=>'ITEMS '.$sku.' TIDAK PUNYA RACK, LANJUTKAN?');	
						
					}else{
						$json = array('result'=>'2', 'msg'=>'ITEMS '.$sku.' BUKAN PUNYA RAK SINI, ITEMS INI PUNYA RAK '.$haha['rack'].', LANJUTKAN?');	
						
					}
					
					
				}
				
				
				
			}else{
				
				$json = array('result'=>'0', 'msg'=>'ITEMS TIDAK ADA DI MASTER PRODUCT');	
			}
			
			
		}
		$json_string = json_encode($json);
		echo $json_string;
	}else if($_GET['act'] == 'counteritems'){
		
		$sku = $_POST['sku'];
		$mpi = $_GET['mpi'];
		
		// $sku = '80400172';
		// $mpi = '9A9A49646582464DA72328F188BC640A';
		
		// $kat = $_POST['kat'];

		
		$sql = "select m_piline.qtycount, pos_mproduct.name from m_piline left join pos_mproduct on m_piline.sku = pos_mproduct.sku where m_piline.sku ='".$sku."' 
		and m_piline.m_pi_key = '".$mpi."' and date(m_piline.insertdate) = '".date('Y-m-d')."'";
		$result = $connec->query($sql);
		$count = $result->rowCount();
		
		if($count > 0){
			foreach ($result as $r) {
			$qtyon = $r['qtycount'];
			$pn = $r['name'];	

			$lastqty = $qtyon + 1;
		
			$statement1 = $connec->query("update m_piline set qtycount = '".$lastqty."' where sku = '".$sku."' and date(m_piline.insertdate) = '".date('Y-m-d')."'"); //klo udah ada update
			
			if($statement1){	
				$json = array('result'=>'1', 'msg'=>$sku .' ('.$pn.'), QUANTITY = <font style="color: red">'.$lastqty.'</font>');	
			}else{
				$json = array('result'=>'0', 'msg'=>'Gagal ,coba lagi nanti');	
				
			}				
			}
			
		}else{
			
			
			$ceksku = "select m_product_id, sku, name, coalesce(price, 0) from pos_mproduct where sku ='".$sku."'";
			$cs = $connec->query($ceksku);
			$count1 = $cs->rowCount();
			
			
			
			
			if($count1 > 0){ //cek di maaster product
			
			$getmpi = "select * from m_pi where m_pi_key ='".$mpi."'";
			$gm = $connec->query($getmpi);
			
			$sql_sales = "select case when sum(qty) is null THEN '0' ELSE sum(qty) END as qtysales from pos_dsalesline where date(insertdate)=date(now()) and sku='".$sku."'";
					
				    $rsa = $connec->query($sql_sales);

						foreach ($rsa as $rsa1) {
						
							$qtysales = $rsa1['qtysales'];
						}

					
					
					
			
			
			foreach($cs as $mpii){
				$m_pro_id = $mpii['m_product_id'];
				$name = $mpii['name'];
				// $price = $mpii['price'];
				
			}
			
			
			foreach($gm as $rr){
					
				
				

		
			
			
				$hasil = get_data_erp($rr['m_locator_id'], $m_pro_id, $org_key, $ss); //php curl
				
		
				$j_hasil = json_decode($hasil, true);
				
									
				$qtyon= $j_hasil['qtyon'];			
				$price= $j_hasil['price'];			
				$statuss= $j_hasil['statuss'];			
				$qtyout= $j_hasil['qtyout'];			
				$statusss= $j_hasil['statusss'];			
									
							
				
				
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
						
				
				
				$statement1 = $connec->query("insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, postdate,m_storage_id, m_product_id, sku, qtyerp, qtycount, qtysales, price, status, qtysalesout, status1) 
				VALUES ('".$rr['m_pi_key']."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".date('Y-m-d H:i:s')."','".$rr['m_locator_id']."','".$m_pro_id."', '".$sku."', '".$qtyon."', '".$qtycount."', '".$qtysales."', '".$price."', '".$statuss."', '".$qtyout."', '".$statusss."')"); 
				
				// $cekeke = "insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, m_storage_id, m_product_id, sku, qtyerp, qtysales, qtycount, price, 
				// status, qtysalesout, status1) 
				// VALUES ('".$rr['m_pi_key']."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".$rr['m_locator_id']."','".$m_pro_id."', '".$sku."', 
				// '".$qtyon."', '".$qtysales."', '".$qtycount."', '".$price."', '".$statuss."', '".$qtyout."', '".$statusss."')";
				
				// var_dump($cekeke);
				// die();
				
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
	}
	else if($_GET['act'] == 'updatecounter'){
		
		$sku = $_POST['sku'];
		$qtyon = $_POST['quan'];
		$nama = $_POST['nama'];
		
		
			$statement1 = $connec->query("update m_piline set qtycount = '".$qtyon."' where sku = '".$sku."' and date(m_piline.insertdate) = '".date('Y-m-d')."'");
			
			
			
			if($statement1){
				$json = array('result'=>'1', 'msg'=>$sku .' ('.$nama.') QUANTITY = <font style="color: red">'.$qtyon.'</font>');	
			}else{
				$json = array('result'=>'0', 'msg'=>'Gagal ,coba lagi nanti');	
				
			}				
			

		$json_string = json_encode($json);
		echo $json_string;
	}else if($_GET['act'] == 'deleteline'){
		
		$m_piline_key = $_POST['m_piline_key'];
		
		
			$statement1 = $connec->query("delete from m_piline where m_piline_key = '".$m_piline_key."'");
			
			
			
			if($statement1){
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
		
			$statement1 = $connec->query("update m_piline set qtycount = '".$qtyon."', verifiedcount = '".$totvc."' where sku = '".$sku."' and date(m_piline.insertdate) = '".date('Y-m-d')."'");
			
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
		
		
			$statement1 = $connec->query("update m_pi set status = '5', postdate = '".date('Y-m-d')."' where m_pi_key = '".$pi_key."'");
			
			if($statement1){
				
				$statement2 = $connec->query("update pos_mproduct set isactived = '1' where sku in
							(
								select sku from m_piline where m_pi_key = '".$pi_key."'
							)");
				
				if($statement2){
					$json = array('result'=>'1');
					
				}else{
					$json = array('result'=>'1');
				}
				
				
					
			}else{
				$json = array('result'=>'0');	
				
			}				
			

		$json_string = json_encode($json);
		echo $json_string;
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
		$truncate = $connec->query("TRUNCATE TABLE inv_mproduct");
		if($truncate){
			
			$json_url = "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_inv&org_id=".$org_key;
			$json = file_get_contents($json_url);

			$arr = json_decode($json, true);
			$jum = count($arr);
			// echo $jum;
			$no = 0;
			foreach($arr as $item) { //foreach element in $arr
				$aoi = $item['ad_org_id']; //etc
				$mpi = $item['m_product_id']; //etc
				$mpci = $item['m_product_category_id']; //etc
				$sku = $item['sku']; //etc
				$n = $item['name']; //etc
				$rn = $item['rack_name']; //etc
				// print_r($aoi);
				
				// $ss = "insert into inv_mproduct (insertdate, isactived, insertby, postby, postdate, ad_mclientkey, ad_morg_key, m_product_id, m_product_category_id, sku, name, rack_name) 
					// VALUES ('".date('Y-m-d H:i:s')."','1', 'SYSTEM', 'SYSTEM', '".date('Y-m-d H:i:s')."','D089DFFA729F4A22816BD8838AB0813C', '".$aoi."', '".$mpi."', '".$mpci."','".$sku."', '".$n."', '".$rn."')";
				// print_r($ss);
				$statement1 = $connec->query("insert into inv_mproduct (insertdate, insertby, postby, postdate, ad_mclient_key, ad_morg_key, m_product_id, m_product_category_id, sku, name, rack_name) 
					VALUES ('".date('Y-m-d H:i:s')."','SYSTEM', 'SYSTEM', '".date('Y-m-d H:i:s')."','D089DFFA729F4A22816BD8838AB0813C', '".$aoi."', '".$mpi."', '".$mpci."','".$sku."', '".$n."', '".$rn."')");
					
					 
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
		
	
			$cek = $connec->query("select * from m_pi where date(insertdate) = '".date('Y-m-d')."' and status = '3'");
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
		// $m_pi = '0AA0C1F01AC64ED6BC6FD7C556495255';	
		// sendWa('0AA0C1F01AC64ED6BC6FD7C556495255')
		
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
		$truncate = $connec->query("TRUNCATE TABLE m_pi_users");
		if($truncate){
			
		
			
			$json_url = "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_user&org_id=".$org_key;
			$json = file_get_contents($json_url);

			$arr = json_decode($json, true);
			$jum = count($arr);
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
				// print_r($aoi);
				
				// $ss = "insert into inv_mproduct (insertdate, isactived, insertby, postby, postdate, ad_mclientkey, ad_morg_key, m_product_id, m_product_category_id, sku, name, rack_name) 
					// VALUES ('".date('Y-m-d H:i:s')."','1', 'SYSTEM', 'SYSTEM', '".date('Y-m-d H:i:s')."','D089DFFA729F4A22816BD8838AB0813C', '".$aoi."', '".$mpi."', '".$mpci."','".$sku."', '".$n."', '".$rn."')";
				// print_r($ss);
				
				
				$sql = "insert into m_pi_users (ad_muser_key, isactived, userid, username, userpwd, ad_org_id, name) 
					VALUES ('".$ad_muser_key."', '".$isactived."','".$userid."','".$username."','".$userpwd."','".$ad_org_id."','".$name."')";
				
				$statement1 = $connec->query($sql);
					
					
					// echo $sql;
					
					 
					if($statement1){
						$no = $no+1;
						// if($no == $jum){
							// $json = array('result'=>'1', 'msg'=>'Berhasil sync '.$no.' dari '.$jum.' items');
								
						// }else{
							
							// $json = array('result'=>'0', 'msg'=>'Berhasil sync '.$no.' dari '.$jum.' items');
						// }
						
						
					}	
					
					
									
			}
			$json = array('result'=>'1', 'msg'=>'Berhasil sync '.$no.' dari '.$jum.' users');
			$json_string = json_encode($json);	
			echo $json_string;	
			
				
			
		}	

				

	}else if($_GET['act'] == 'proses_inv_temp'){
		$sku = $_POST['sku'];
		$qty = $_POST['qty'];
		$tgl = $_POST['tgl'];
		$jumlahpi = $_POST['jumlahpi'];
		
		// $sku = '8262400000064';
		// $qty = '12';
		// $tgl = '2022-06-23';
		// $jumlahpi = '1212';
		
		
		
		$cekqty = "select qtycount from m_piline where sku = '".$sku."' and date(insertdate) = '".$tgl."'";
		$result = $connec->query($cekqty);
		$count = $result->rowCount();
		
		if($count > 0){
			// $sql  = "update m_pi_line set qtycount=? where sku=? and date(insertdate)=?";
			// $stmt = $connec->prepare($sql);
			foreach ($result as $tot) {
				$qtycount = $tot['qtycount'];
				$jumqty = (int)$qtycount + (int)$qty;
				$upcount = $connec->query("update m_piline set qtycount='".$jumqty."' where sku='".$sku."' and date(insertdate)='".$tgl."'");
				if($upcount){
					
					$update = $connec->query("update inv_temp set status = 1 where sku = '".$sku."' and date(tanggal) = '".$tgl."'");
					if($sukses){
						$json = array('result'=>'1', 'sku'=>$sku);
					
					
					}else{
						$json = array('result'=>'1', 'sku'=>$sku);
					
					}
				}else{
					$json = array('result'=>'1', 'sku'=>$sku);
					
				}
				
			}
			
			
		}else{
			$json = array('result'=>'1', 'sku'=>$sku.' Tidak ada di line');
			
		}
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
				
				$upcount = $connec->query("update pos_mproduct set stockqty='".$totqty."' where sku='".$r['sku']."'");
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
		
		
		$hasil = get_data_stock_all($org_key);
		$j_hasil = json_decode($hasil, true);
		
		// $jum = count($hasil);
		
		// if($jum > 0){
		$no = 0;	
		foreach($j_hasil as $r) {
			
			
			$stock_sales = 0;
			
			$ceksales = $connec->query("select sku, sum(qty) as jj from pos_dsalesline where sku = '".$r['sku']."' and date(insertdate) = date(now()) group by sku");
			foreach ($ceksales as $rs) {
				
					$stock_sales = $rs['jj'];
				}
			
			$cekitems = $connec->query("select count(sku) as jum from pos_mproduct where sku = '".$r['sku']."'");
			foreach ($cekitems as $ra) {
				
					$haha = $ra['jum'];
				}
			
			$totqty = $r['stockqty'] - $stock_sales;
			// $totqty = $r['stockqty'];
				
			if($haha > 0){
				
				
				
				
				
				$upcount = $connec->query("update pos_mproduct set stockqty='".$totqty."' where sku='".$r['sku']."'");
				
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
				
			}else{
				
				
			}

		}
		
		$data = array("result"=>1, "msg"=>"Berhasil sync ".$no." data");
		
		$json_string = json_encode($data);	
		echo $json_string;
		// echo $sql;
		
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
		
		
	}else if($_GET['act'] == 'cetak_generic'){
		$mpi = $_POST['mpi'];
		$jj = array();
		$list_line = "select m_piline.sku ,m_piline.qtyerp, m_piline.qtysales, m_piline.qtycount, m_piline.qtysalesout, pos_mproduct.name, m_pi.status, m_piline.verifiedcount from m_pi inner join m_piline on m_pi.m_pi_key = m_piline.m_pi_key left join pos_mproduct on m_piline.sku = pos_mproduct.sku 
		where m_pi.m_pi_key = '".$mpi."' and m_pi.status = '2' and m_piline.qtycount != (m_piline.qtyerp - m_piline.qtysalesout - m_piline.qtysales) order by qtyerp desc";
		$no = 1;
		foreach ($connec->query($list_line) as $row1) {	
			$variant = ($row1['qtycount'] + $row1['qtysales']) - ($row1['qtyerp'] - $row1['qtysalesout']);
			$qtyerpreal = $row1['qtyerp'] - $row1['qtysalesout'];
			
			
			$jj[] = array(
				"sku"=> $row1['sku'],
				"name"=> $row1['name'],
				"qtyvariant"=> $variant,
				"qtycount"=> $row1['qtycount']
			);
		}
		

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
         $query = $connec->query("SELECT a.sku,a.name,a.price,a.shortcut, (coalesce(a.price,0) - coalesce(b.discount,0)) price_discount, b.discountname FROM 
		 pos_mproduct a left join (select * from pos_mproductdiscount where todate > '".date('Y-m-d')."') b on a.sku = b.sku
		 
		 order by $order $dir
                                                      LIMIT $limit
                                                      OFFSET $start");
        }
        else {
            $search = $_POST['search']['value']; 
            $query = $connec->query("SELECT a.sku,a.name,a.price,a.shortcut, (coalesce(a.price,0) - coalesce(b.discount,0)) price_discount, b.discountname FROM 
		 pos_mproduct a left join (select * from pos_mproductdiscount where todate > '".date('Y-m-d')."') b on a.sku = b.sku WHERE a.sku ILIKE  '%$search%'
                                                         or a.name ILIKE  '%$search%'
                                                         or a.shortcut ILIKE  '%$search%'
                                                         order by $order $dir
                                                         LIMIT $limit
                                                         OFFSET $start");
 
 
         $querycount = $connec->query("SELECT count(*) as jumlah FROM 
		 pos_mproduct a left join (select * from pos_mproductdiscount where todate > '".date('Y-m-d')."') b on a.sku = b.sku WHERE a.sku ILIKE  '%$search%'
                                                         or a.name ILIKE '%$search%'
                                                         or a.shortcut ILIKE  '%$search%'
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
				
				
				$nestedData['no'] = $no;
                $nestedData['sku'] = '<font style="font-weight: bold">'.$r['sku'].'</font> '.$discname.' <br> <font style="color: green; font-weight: bold">Reguler : '.rupiah($r['price']).'</font><br> <font style="color: red; font-weight: bold">Diskon : '.rupiah($r['price_discount']).'</font>';
                $nestedData['name'] = $r['name'];
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
		 
	}
	
	
	
	
	
	

}
    

?>