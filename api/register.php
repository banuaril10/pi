<?php 

include "../config/koneksi.php";
ini_set('max_execution_time', '2000');


			$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";

			$results = $connec->query($sqll);
			
			
			foreach ($results as $r) {
				$org_key = $r["ad_morg_key"];
				
			}


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