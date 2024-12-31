<?php error_reporting(0);
include "../config/koneksi.php";
 function guid($data = null) {
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

$data = $_POST['data'];

$s = array();

$ar_imp = explode('|', $data);
							
foreach ($ar_imp as $rrr) {
	
	$ar_imp = explode(';', $rrr);
	if($ar_imp[0] != ""){
		$s[] = "('".guid()."', '".$ar_imp[0]."', '".$ar_imp[1]."', '".date('Y-m-d')."', '0', 'SYSTEM')";
	}
	
	
}

$values = implode(", ",$s);
$lastquery = "INSERT INTO inv_temp_nasional (id, sku, qty, tanggal, status, user_input) VALUES ".$values."";

$insert_bulk = $connec->query($lastquery);

if($insert_bulk){
	$json = array('result'=>'1', 'msg'=>'Data berhasil diimport');	
}else{
	$json = array('result'=>'0', 'msg'=>'Gagal ,coba lagi nanti');	
}				
			

$json_string = json_encode($json);
echo $json_string;
		
		
?>