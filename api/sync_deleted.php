<?php 
include "../config/koneksi.php";

// $date = date('Y-m-d');
// $prev_date = date('Y-m-d', strtotime($date .' -1 day'));



$sales = "delete from pos_dsales where date(insertdate) between current_date - 800 and current_date - 60;";
$salesline = "delete from pos_dsalesline where date(insertdate) between current_date - 800 and current_date - 60;";
$order = "delete from pos_dsalesline where date(insertdate) between current_date - 800 and current_date - 60;";
$orderline = "delete from pos_dsalesline where date(insertdate) between current_date - 800 and current_date - 60;";

$cp1 = $connec->query($sales);
$cp2 = $connec->query($salesline);
$cp3 = $connec->query($order);
$cp4 = $connec->query($orderline);


if($cp1 && $cp2 && $cp3 && $cp4){
	$json = array('result'=>'1', 'msg'=>'Berhasil sync');
}else{

	$json = array('result'=>'0', 'msg'=>'Gagal sync');	
}

$json_string = json_encode($json);
echo $json_string;



