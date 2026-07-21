<?php 
include "config/koneksi.php";
$cmd_alter_pos_medc_urutan = [
	'ALTER TABLE public.pos_medc ADD COLUMN IF NOT EXISTS urutan int4 NULL DEFAULT 0;'
];

foreach ($cmd_alter_pos_medc_urutan as $r) {
	$connec->exec($r);
}
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
		$link_ppob = $j_hasil['link_ppob'];
	}
	
	
	if($cv_web != ''){
		
		$connec->query("update m_piversion set value = '".$cv_web."', link_ppob = '".$link_ppob."'"); //klo udah ada update
	}
	
			
	


?>