<?php 
include "../config/koneksi.php";

// $date = date('Y-m-d');
// $prev_date = date('Y-m-d', strtotime($date .' -1 day'));



$sales = "delete from pos_dsales where date(insertdate) between current_date - 800 and current_date - 60;";
$salesline = "delete from pos_dsalesline where date(insertdate) between current_date - 800 and current_date - 60;";
$order = "delete from pos_dsalesline where date(insertdate) between current_date - 800 and current_date - 60;";
$orderline = "delete from pos_dsalesline where date(insertdate) between current_date - 800 and current_date - 60;";

$text = "";

$cp2 = $connec->query($salesline);
$cp1 = $connec->query($sales);
$cp3 = $connec->query($order);
$cp4 = $connec->query($orderline);


if($cp1){$text .= "Berhasil Sales, <br>";}else{$text .= "Gagal Sales, <br>";}
if($cp2){$text .= "Berhasil Sales line, <br> ";}else{$text .= "Gagal Sales line, <br> ";}
if($cp3){$text .= "Berhasil Order,<br> ";}else{$text .= "Gagal Order,<br> ";}
if($cp4){$text .= "Berhasil Order line";}else{$text .= "Gagal Order line";}

echo $text;
// $json = array('result'=>'1', 'msg'=>$text);

// $json_string = json_encode($json);
// echo $json_string;



