<?php
include "../config/koneksi.php";
//bos online
$pi_key = '03C79C28EA5841AAA22D6E9DC27C2039';
$sql_line = "select m_piline.*, pos_mproduct.name from m_piline left join pos_mproduct on m_piline.sku = pos_mproduct.sku where m_piline.m_pi_key ='".$pi_key."' and m_piline.issync =1";
							
							
							
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
					
								var_dump($items_json);

    

?>