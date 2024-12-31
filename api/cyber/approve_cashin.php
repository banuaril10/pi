<?php include "../../config/koneksi.php";

$id = $_POST['id'];
$userspv = $_POST['userspv'];
//get name by userspv

$sql = "SELECT username FROM ad_muser WHERE ad_muser_key = '$userspv'";
$statement1 = $connec->query($sql);

if ($statement1) {
    $row = $statement1->fetch(PDO::FETCH_ASSOC);
    $userspv = $row['username'];
}

//get userid by cash_in
$sql = "SELECT userid FROM cash_in WHERE cashinid = '$id'";
$statement1 = $connec->query($sql);

if ($statement1) {
    $row = $statement1->fetch(PDO::FETCH_ASSOC);
    $user_kasir = $row['userid'];
}


$setoran = 0;
$cek_setoran = "select setoran from cash_in where status = '1' and userid = '" . $user_kasir . "' and date(insertdate) = date(now()) order by setoran desc limit 1";
$cs = $connec->query($cek_setoran);

foreach ($cs as $rrr) {
    $setoran = $rrr['setoran'];
}

$setoran = $setoran + 1;


$sql = "UPDATE cash_in SET approvedby = '$userspv', setoran='$setoran', status = '1' WHERE cashinid = '$id'";
$statement1 = $connec->query($sql);
if ($statement1) {
    $json = array('result' => '1', 'msg' => 'Berhasil approve cash in', 'query' => $sql);
} else {
    $json = array('result' => '0', 'msg' => 'Gagal approve cash in');
}

$json_string = json_encode($json);

echo $json_string;



?>
