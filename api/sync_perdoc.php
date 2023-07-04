<?php session_start();
include "../config/koneksi.php";
$username = $_SESSION['username'];
function get_data_stock_all($a, $b, $c, $d){
			
	$postData = array(
		"org_id" => $a,
		"sdate" => $b,
		"doc_no" => $c,
		"username" => $d,
	
    );				    
	// $fields_string = http_build_query($postData);
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_pos_crons',
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


		
		$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$org_key = $r["ad_morg_key"];	
		}

			$date = '1'; //sudah ada
			$doc_no = $_GET['doc_no']; //sudah ada
			$hasil = get_data_stock_all($org_key, $date, $doc_no, $username);
			$j_hasil = json_decode($hasil, true);
			$j_hasildata = json_decode($j_hasil['data'], TRUE);
			
			if($j_hasil['result'] == 1){
				
				$no = 0;	
				foreach($j_hasildata as $r) {
					$haha = 0;
					$stock_sales = 0;
					$stoc_lok = 0;
					$ceksales = $connec->query("select sku, sum(qty) as jj from pos_dsalesline where sku = '".$r['sku']."' and date(insertdate) = date(now()) group by sku");
					foreach ($ceksales as $rs) {
						
							$stock_sales = $rs['jj'];
						}
					
					$cekitems = $connec->query("select count(m_product_id) as jum, stockqty from pos_mproduct where sku = '".$r['sku']."' group by sku, stockqty");
					foreach ($cekitems as $ra) {
						
							$haha = $ra['jum'];
							$stoc_lok = $ra['stockqty'];
						}
					
					$totqty = $r['stockqty'] - $stock_sales;
					if($haha > 0){
						$upcount = $connec->query("update pos_mproduct set stockqty='".$totqty."', description = '".$r['stockqty']."' where sku='".$r['sku']."'");
						
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
				'".$r['org_id']."',
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
				
				$data = array("result"=>1, "msg"=>$j_hasil['msg']);
				
			}else{
				
				$data = array("result"=>0, "msg"=>$j_hasil['msg']);
				
			}
			
			
			// $jum11 = count($j_hasil);
			
			
			
		// 
		
		// $data = array("result"=>1, "msg"=>"Berhasil sync ".$no." data");
		
		$json_string = json_encode($data);	
		echo $json_string;
		
		// echo $sql;

	