<?php session_start();
ini_set('max_execution_time', '4000');
if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
  
}else{
	
	header("Location: ../index.php");
}


include "../config/koneksi.php";
// $ch = curl_init();
$username = $_SESSION['username'];
$org_key = $_SESSION['org_key'];
$ss = $_SESSION['status_sales'];
$kode_toko = $_SESSION['kode_toko'];
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

// var_dump($postData);	
	

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
	// if ($server_output == "OK") {$json = array('result'=>'1');	  } else {$json = array('result'=>'0');	 }
	// $json_string = json_encode($json);
	// return $json_string;
}


function get_data_erp($a,$b,$c,$d){
			
	$postData = array(
		"m_locator_id" => $a,
		"m_pro_id" => $b,
		"org_key" => $c,
		"ss" => $d
	
    );				
	

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=get_data',
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
	
	// $it = 1;
	// $sl = 1;
	// $kat = 1;
	// $rack = 1;
	// $pc = 1;
	
	
	
	if($_GET['act'] == 'input'){
		
		
				$cekrak = "select count(m_pi_key) jum from m_pi where rack_name='".$rack."' and status != '3' and status != '5'";
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
				}

				$sql1 = "select m_product_id,sku from inv_mproduct where rack_name='".$rack."'";
				$result = $connec->query($sql1);
				$count = $result->rowCount();
				
				
				
				
				$no = 0;
				foreach ($connec->query($sql1) as $row) {
					
					$sql_sales = "select case when sum(qty) is null THEN '0' ELSE sum(qty) END as qtysales from pos_dsalesline where date(insertdate)=date(now()) and sku='".$row['sku']."'";
					
				    $rsa = $connec->query($sql_sales);

						foreach ($rsa as $rsa1) {
						
							$qtysales = $rsa1['qtysales'];
						}
						
				
				

		
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
						
						
			
					// $hasil = get_data_erp($sl, $row['m_product_id'], $org_key, $ss); //php curl
					$hasil = get_data_erp($sl, $row['m_product_id'], $org_key, $ss); //php curl
				
					$j_hasil = json_decode($hasil, true);
				
				// var_dump($sl." ".$row['m_product_id']." ".$org_key." ".$ss);
				// var_dump($hasil);
				
				if($hasil){
					$qtyon= $j_hasil['qtyon'];			
					$price= $j_hasil['price'];			
					$statuss= $j_hasil['statuss'];			
					$qtyout= $j_hasil['qtyout'];			
					$statusss= $j_hasil['statusss'];
					
					$statement1 = $connec->query("insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, postdate, m_storage_id, m_product_id, sku, qtyerp, qtycount, qtysales, price, status, qtysalesout, status1) 
					VALUES ('".$lastid."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".date('Y-m-d H:i:s')."', '".$sl."','".$row['m_product_id']."', '".$row['sku']."', '".$qtyon."', '".$qtycount."', '".$qtysales."','".$price."', '".$statuss."', '".$qtyout."','".$statusss."')");
					
					// $sqll = "insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, postdate, m_storage_id, m_product_id, sku, qtyerp, qtycount, qtysales, price, status, qtysalesout, status1) 
					// VALUES ('".$lastid."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".date('Y-m-d H:i:s')."', '".$sl."','".$row['m_product_id']."', '".$row['sku']."', '".$qtyon."', '".$qtycount."', '".$qtysales."','".$price."', '".$statuss."', '".$qtyout."','".$statusss."')";
					
					// echo $sqll;
					
					if($statement1){
						
						$connec->query("update pos_mproduct set isactived = 0 where sku = '".$row['sku']."'");
						
						
						$no = $no+1;
						if($no == $count){
							$json = array('result'=>'1');
							
						}else{
							
							$json = array('result'=>'2');
						}
						
						
					}
					
				}else{
					
					$qtyon= 0;			
					$price= 0;			
					$statuss= 0;
					$qtyout= 0;			
					$statusss= 0;
					
					$statement1 = $connec->query("insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, postdate, m_storage_id, m_product_id, sku, qtyerp, qtycount, qtysales, price, status, qtysalesout, status1) 
					VALUES ('".$lastid."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".date('Y-m-d H:i:s')."', '".$sl."','".$row['m_product_id']."', '".$row['sku']."', '".$qtyon."', '".$qtycount."', '".$qtysales."','".$price."', '".$statuss."', '".$qtyout."','".$statusss."')");
					
					// $sqll = "insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, postdate, m_storage_id, m_product_id, sku, qtyerp, qtycount, qtysales, price, status, qtysalesout, status1) 
					// VALUES ('".$lastid."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".date('Y-m-d H:i:s')."', '".$sl."','".$row['m_product_id']."', '".$row['sku']."', '".$qtyon."', '".$qtycount."', '".$qtysales."','".$price."', '".$statuss."', '".$qtyout."','".$statusss."')";
					
					// echo $sqll;
					
					if($statement1){
						
						$connec->query("update pos_mproduct set isactived = 0 where sku = '".$row['sku']."'");
						
						
						$no = $no+1;
						if($no == $count){
							$json = array('result'=>'1');
							
						}else{
							
							$json = array('result'=>'2');
						}
						
						
					}
				}
				// var_dump($hasil);
									
				
					
					
					
					// if($counterp > 0){
						// foreach ($rerp as $row_erp) {
							// $qtyon = $row_erp['qtyonhand'];
							
						// }	
					// }else{
						// $qtyon = '';
						
					// }
					
				
					// $sqll = "insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, postdate, m_storage_id, m_product_id, sku, qtyerp, qtycount, qtysales, price, status, qtysalesout, status1) 
					// VALUES ('".$lastid."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".date('Y-m-d H:i:s')."', '".$sl."','".$row['m_product_id']."', '".$row['sku']."', '".$qtyon."', '".$qtycount."', '".$qtysales."','".$price."', '".$statuss."', '".$qtyout."','".$statusss."')";
					
					
					
					
					// echo $sqll;
					 // $statement1 = $connec->query("insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, m_storage_id, m_product_id, sku, qtyerp) 
					// VALUES ('".$lastid."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".$sl."','".$row['m_product_id']."', '".$row['sku']."', '".$qtyon."')");
					
					
				}

			}else{
				
				$json = array('result'=>'0', 'msg'=>'Gagal, coba lagi nanti');
			}
				
				
			}
		
		
			
        
			$json_string = json_encode($json);
			echo $json_string;
		 
		
	}else if($_GET['act'] == 'inputitems'){		
				

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
						$n = $row['isactived'];
						
						$stats = push_to_server($pi_key, $a, $b, $c, $d, $e, $f,$ff, $g, $h, $i, $j, $k, $l, $m, $n);
						
						$jsons = json_decode($stats, true);


						// var_dump($jsons);
						
						if($jsons['result'] == '1'){
							
							$connec->query("update m_pi set status = '3' where m_pi_key ='".$pi_key."'");
			
							
							$sql_line = "select m_piline.*, pos_mproduct.name from m_piline left join pos_mproduct on m_piline.sku = pos_mproduct.sku where m_piline.m_pi_key ='".$pi_key."' and m_piline.issync =0";
							
							
							
							foreach ($connec->query($sql_line) as $rline) {
								
							
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
						
								$jsons1 = json_decode($stats1, true);
								
								// var_dump($stats1);
								
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
							
							
						}
						
						
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
		
		
		$ss = $_GET['status_sales'];

		
		
			
			
			$cek = $connec->query("select * from m_pi_sales where date(tanggal) = '".date('Y-m-d')."'");
			$count = $cek->rowCount();
			
			if($count > 0){
				$statement1 = $connec->query("update m_pi_sales set status_sales = '".$ss."' where date(tanggal) = '".date('Y-m-d')."'");
				
			}else{
				
				$statement1 = $connec->query("insert into m_pi_sales (tanggal, status_sales) VALUES ('".date('Y-m-d')."', '".$ss."')");
			}
			
			if($statement1){
				$json = array('result'=>'1', 'msg'=>'Berhasil');	
				$_SESSION['status_sales'] = $ss;
			}else{
				$json = array('result'=>'0', 'msg'=>'Gagal ,coba lagi nanti');	
				
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
		
		$sql_amount = "select SUM(CASE WHEN issync=1 THEN 1 ELSE 0 END) jumsync,  sum(qtysalesout * price) hargagantung,  sum(qtyerp * price) hargaerp, sum(qtycount * price) hargafisik, count(sku) jumline from m_piline where m_pi_key = '".$m_pi."'";
		foreach ($connec->query($sql_amount) as $tot) {
			
			$qtyerp = $tot['hargaerp'] - $tot['hargagantung'];
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
				
				
				
				
				
				// $sukses = $stmt->execute([$jumqty, $sku, $tgl]);
				
			
				
				
							
						
					
					
				
				
				
				
				
				
				
				// $update_lagi = $connec->query("");
				// if(){
					
					
				// }
				
			}
			
			
		}else{
			$json = array('result'=>'1', 'sku'=>$sku.' Tidak ada di line');
			
		}
		$json_string = json_encode($json);	
		echo $json_string;	
	
	}
	
	// ,'INSERT INTO m_pi_users (ad_muser_key, isactived, userid, username, userpwd, ad_org_id, name, price) VALUES ();'

}
    

?>