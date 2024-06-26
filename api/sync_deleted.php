<?php 
include "../config/koneksi.php";

// $date = date('Y-m-d');
// $prev_date = date('Y-m-d', strtotime($date .' -1 day'));



$sales = "delete from pos_dsales where date(insertdate) between current_date - 800 and current_date - 90;";
$salesline = "delete from pos_dsalesline where date(insertdate) between current_date - 800 and current_date - 90;";
$order = "delete from pos_dsalesline where date(insertdate) between current_date - 800 and current_date - 90;";
$orderline = "delete from pos_dsalesline where date(insertdate) between current_date - 800 and current_date - 90;";

$text = "";

$cp2 = $connec->query($salesline);
$cp1 = $connec->query($sales);
$cp3 = $connec->query($order);
$cp4 = $connec->query($orderline);


if($cp1){$text .= "Berhasil Clear Sales, <br>";}else{$text .= "Gagal Clear Sales, <br>";}
if($cp2){$text .= "Berhasil Clear Sales line, <br> ";}else{$text .= "Gagal Clear Sales line, <br> ";}
if($cp3){$text .= "Berhasil Clear Order,<br> ";}else{$text .= "Gagal Clear Order,<br> ";}
if($cp4){$text .= "Berhasil Clear Order line";}else{$text .= "Gagal Clear Order line";}

echo $text;
// $json = array('result'=>'1', 'msg'=>$text);

// $json_string = json_encode($json);
// echo $json_string;



