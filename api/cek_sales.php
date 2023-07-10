<?php
include "../config/koneksi.php";

$date_now = $_GET['tgl'];
foreach ($connec->query("select count(*) jum, sum(amount) amount from pos_dsalesline where date(insertdate) = '".$date_now."'") as $row) {

	$jum = $row['jum'];
	$amount = $row['amount'];
}
	

	
$json = array('result'=>'1', 'jum'=>$jum, 'amount'=>$amount);
$json_string = json_encode($json);
echo $json_string;