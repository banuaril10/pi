<?php session_start();


ini_set('max_execution_time', '2000');

include "config/koneksi.php";
	$it = 'Daily';
	$sl = '4DC01BB67AB148C9A02C4F5DB39AF969';
	$kat = '3';
	$rack = 'R-223';
	
function get_data_erp($a,$b,$c,$d){
			
	$postData = array(
		"m_locator_id" => $a,
		"m_pro_id" => $b,
		"org_key" => $c,
		"ss" => $d
	
    );				

// var_dump($postData);	
	

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
// $ch = curl_init();
$username = $_SESSION['username'];
$org_key = $_SESSION['org_key'];
$ss = $_SESSION['status_sales'];
				
				
				$cekrak = "select count(m_pi_key) jum from m_pi where rack_name='".$rack."' and status != '3'";
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
			) VALUES ('','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".$sl."', '".$it."','BOSOL-".date('YmdHis')."','PI-".$rack."', 
			'".date('Y-m-d H:i:s')."','user spv','1','".$rack."','".$username."','".date('Y-m-d H:i:s')."', '1') RETURNING m_pi_key");
			
			foreach ($statement as $rr) {
				
				$lastid = $rr['m_pi_key'];
			}

			if($statement){

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
						
				$hasil = get_data_erp($sl, $row['m_product_id'], $org_key, $ss); //php curl
				
		
				$j_hasil = json_decode($hasil, true);
				
									
				$qtyon= $j_hasil['qtyon'];			
				$price= $j_hasil['price'];			
				$statuss= $j_hasil['statuss'];			
				$qtyout= $j_hasil['qtyout'];			
				$statusss= $j_hasil['statusss'];


		
						$cek_count = "select qtycount from m_piline where sku = '".$row['sku']."' and date(insertdate) = '".date('Y-m-d')."'";
						$rsac = $connec->query($cek_count);
						$ccc = $rsac->rowCount();
						
						if($ccc > 0){
							foreach ($rsac as $rrr) {
						
								$qtycount = $rrr['qtycount'];
							}
							
						}else{
							$qtycount = 0;
							
						}
						
						
			
					
					
					
					
					// if($counterp > 0){
						// foreach ($rerp as $row_erp) {
							// $qtyon = $row_erp['qtyonhand'];
							
						// }	
					// }else{
						// $qtyon = '';
						
					// }
					
					
					
					
					$statement1 = $connec->query("insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, postdate, m_storage_id, m_product_id, sku, qtyerp, qtycount, qtysales, price, status, qtysalesout, status1) 
					VALUES ('".$lastid."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".date('Y-m-d H:i:s')."', '".$sl."','".$row['m_product_id']."', '".$row['sku']."', '".$qtyon."', '".$qtycount."', '".$qtysales."','".$price."', '".$statuss."', '".$qtyout."','".$statusss."')");
					
					
					$echo = "insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, postdate, m_storage_id, m_product_id, sku, qtyerp, qtycount, qtysales, price, status, qtysalesout, status1) 
					VALUES ('".$lastid."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".date('Y-m-d H:i:s')."', '".$sl."','".$row['m_product_id']."', '".$row['sku']."', '".$qtyon."', '".$qtycount."', '".$qtysales."','".$price."', '".$statuss."', '".$qtyout."','".$statusss."')";
					 // $statement1 = $connec->query("insert into m_piline (m_pi_key, ad_org_id, isactived, insertdate, insertby, m_storage_id, m_product_id, sku, qtyerp) 
					// VALUES ('".$lastid."','".$org_key."','1','".date('Y-m-d H:i:s')."','".$username."', '".$sl."','".$row['m_product_id']."', '".$row['sku']."', '".$qtyon."')");
					
					echo $echo;
					
					
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

			}else{
				
				$json = array('result'=>'0', 'msg'=>'Gagal, coba lagi nanti');
			}
				
				
			}
		
		
			
        
			$json_string = json_encode($json);
			echo $json_string;