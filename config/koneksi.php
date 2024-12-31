<?php
date_default_timezone_set('Asia/Jakarta');
error_reporting(0);

$base_url = 'https://intransit.idolmartidolaku.com/apiidolmart';
$ad_mclient_key = 'D089DFFA729F4A22816BD8838AB0813C';
$key = '5ea1a9b9-f403-4175-bc49-9daa896125b3'; // Previously used in encryption 
$c = base64_decode('T6EAJJoY5Ru0NNcX8Uls7aRLDR6r8NKyqUqqZCApv9KAh4kwUTOAG4xlDVIWqp+4x6RpweWFHNDEycWFexu9VQ=='); 
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC"); 
$iv = substr($c, 0, $ivlen); 
$hmac = substr($c, $ivlen, $sha2len=32); 
$ciphertext_raw = substr($c, $ivlen+$sha2len); 
$op = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 
$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true); 

$options = array(
    PDO::ATTR_ERRMODE    => PDO::ERRMODE_SILENT
);
try {

	if(hash_equals($hmac, $calcmac)){
		$dbuser = 'postgres';
		$dbpass = $op;
		$dbhost = 'localhost';
		$dbname='dbinfinitepos';
		$dbport='5432';
	}
	
    $connec = new PDO("pgsql:host=$dbhost;dbname=$dbname;port=$dbport", $dbuser, $dbpass, $options);
  
} catch (PDOException $e) {
    // echo "Error : " . $e->getMessage() . "<br/>";
    // die();

}

function check_null($var){
	if($var == ''){
		return 'NULL';
	}else{
		return "'".$var."'";
	}
}

function rupiah_pos($angka){
	$hasil_rupiah = number_format($angka,0,',','.');
	return $hasil_rupiah;
}

function date_format_pos($date){
	$hasil_date = date('d-m-Y', strtotime($date));
	return $hasil_date;
}

function time_format_pos($date){
	$hasil_date = date('H:i', strtotime($date));
	return $hasil_date;
}
?>