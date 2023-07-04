<?php session_start();
include "../config/koneksi.php";

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



		$tanggal = date('Y-m-d');
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
		
		
		$json_string = json_encode($data);	
		echo $json_string;

	