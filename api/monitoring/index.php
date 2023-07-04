<?php include "../../config/koneksi.php";
//error_reporting(0);
require_once('api.php');


function kirim_data($a,$b,$c,$d,$e,$f,$g,$h){
		
	$postData = array(
		"ip" => $a,
		"signal" => $b,
		"tx_ccq" => $c,
		"rx_ccq" => $d,
		"troughput" => $e,
		"channel" => $f,
		"upload" => $g,
		"download" => $h,
	
    );		
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=noc&act=monitoring',
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



$API = new routeros_api();
$API->debug = false;


$username = 'noc';
$password = 'idol0617noc';
echo 'Jumlah Toko';
echo '<form action=""><input type="text" name="jumlahtoko"><input type="submit"></form>';

echo '<table border="1"><tr><td>IP</td><td>Signal Status</td><td>Frequency</td><td>Tx-ccq</td><td>Rx-ccq</td><td>Troughput</td><td>Upload</td><td>Download</td></tr>';
$people = array("BOS01","BOS02","BOS03","BOS04","BOS05","BOS06","BOS07","BOS08","BOS09");
		$x = 0;
		// $sqll = "select value from ad_morg where postby = 'SYSTEM'";
		// $results = $connec->query($sqll);
		// foreach ($results as $r) {
			// $kode_toko = $r["value"];	
			
			// if (in_array($kode_toko, $people)){
				
				// $x = str_replace("BOS0","",$kode_toko);
			// }else{
				// $x = str_replace("BOS","",$kode_toko);
				
			// }
		// }



// for($x=2; $x <= $_GET['jumlahtoko']; $x++){
//$x=$_GET['kodetoko'];
	// $ip = '10.0.'.$x.'.1';
	$ip = exec('hostname -I');
	
	// $ip = '10.0.'.$x.'.1';
	
	$ip_exp = explode('.', $ip);
	
	$ip_new = $ip_exp[0].'.'.$ip_exp[1].'.'.$ip_exp[2].'.1';
	// $ip_new = '10.0.5.1';
	if ($API->connect($ip_new,$username,$password)) {
	$upload = 0;
	$download = 0;


  // $getup = $API->comm("/tool/bandwidth-test", array(
      // "address" => "114.141.55.178",
      // "user" => "noc",
      // "password" => "idol0617noc",
      // "direction" => "transmit",
      // "protocol" => "tcp",
      // "duration" => "5",
      // ));
	  
	  // $upload = $getup[4]['tx-total-average']/100000;
	  
	  // if($upload != ""){
		   // $getdown = $API->comm("/tool/bandwidth-test", array(
			// "address" => "114.141.55.178",
			// "user" => "noc",
			// "password" => "idol0617noc",
			// "direction" => "receive",
			// "protocol" => "tcp",
			// "duration" => "5",
			// ));  
		   // $download = $getdown[6]['rx-total-average']/100000;
	  // }
	 
	  
	  
	  
	  var_dump($getup);
	  // var_dump($upload);
	  
	  
	 

  
    $getinterfacetraffic = $API->comm("/interface/wireless/monitor", array(
      "numbers" => "0",
      "once" => "",
      ));
// /	var_dump($getinterfacetraffic);
	//$tes .= $API->('=stats=');
	if($getinterfacetraffic[0]["signal-strength"] == ""){

		$signal = "Menggunakan FO";
	}else{
		$signal = $getinterfacetraffic[0]["signal-strength"];
		$tx_ccq = $getinterfacetraffic[0]["tx-ccq"];
		$rx_ccq = $getinterfacetraffic[0]["rx-ccq"];
		$troughput = $getinterfacetraffic[0]["p-throughput"];
		$channel = $getinterfacetraffic[0]["channel"];
		
		
		kirim_data($ip_new, $signal, $tx_ccq, $rx_ccq, $troughput, $channel, $upload, $download);
		
		$getlog = $API->comm("/log/print", array(
			"?topics" => "hotspot,info,debug"
		));
		
		var_dump($getlog);

		
	}
	
	echo '<tr><td>'.$ip_new.'</td><td>'.$signal.'</td><td>'.$channel.'</td><td>'.$tx_ccq.'%</td><td>'.$rx_ccq.'%</td><td>'.$troughput.' MB</td><td>'.$upload.'</td><td>'.$download.'</td></tr>';

		// Command Jam Training dimulai
		
	}else{
	
	echo '<tr><td>'.$ip_new.'</td><td><font class="notif-red"> BOS '.$x.' tidak terkoneksi.</font></td><td>-</td></tr>';
	

	}

// }

echo '</table>';

?>