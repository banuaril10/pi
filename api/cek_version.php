<?php 

include "../config/koneksi.php";
ini_set('max_execution_time', '2000');

function get_version(){
			

	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=get_version',
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
			
			$hasil = get_version(); //php curl
				
			$j_hasil = json_decode($hasil, true);
				
				
				if($hasil){
					$cv_web = $j_hasil['version'];
				}				
				
				
			$cek_ver = "select value from m_piversion";
			$cv = $connec->query($cek_ver);
			
			
			foreach ($cv as $r){
				
				$cv_lokal = $r['value'];
			}
				
			
				if($cv_lokal == $cv_web){
					
					$json = array('result'=>'1', 'version'=>$cv_web);
				}else{
					$json = array('result'=>'0', 'version'=>$cv_web);
					
				}
			
		$json_string = json_encode($json);
		echo $json_string;