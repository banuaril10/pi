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
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=sync_pos_cronss',
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
				
				

				$data = array("result"=>1, "msg"=>$j_hasil['msg'], "data"=>$j_hasildata);
			
				
			}else{
				
				$data = array("result"=>0, "msg"=>$j_hasil['msg']);
				
			}
			
			
			// $jum11 = count($j_hasil);
			
			
			
		// 
		
		// $data = array("result"=>1, "msg"=>"Berhasil sync ".$no." data");
		
		$json_string = json_encode($data);	
		echo $json_string;

		
	