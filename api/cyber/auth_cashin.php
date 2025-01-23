<?php include "../../config/koneksi.php";

$userid = $_POST['userid'];
//get name by userspv
$password = hash_hmac("sha256", $_POST['password'], 'marinuak');


$sql = "select count(*) exist, ad_muser_key from ad_muser where userid ='" . $userid . "' and userpwd ='" . $password . "' and ad_mrole_key= '2' 
group by ad_muser_key limit 1";
$statement1 = $connec->query($sql);
$isauth = 0;
$ad_muser_key = '';
foreach ($statement1 as $row) {
    $isauth = $row['exist'];
    $ad_muser_key = $row['ad_muser_key'];
}


if ($isauth > 0) {
    $json = array('result' => '1', 'msg' => 'Berhasil auth', 'query' => $sql,'ad_muser_key'=>$ad_muser_key);
} else {
    $json = array('result' => '0', 'msg' => 'Gagal auth, cek kembali user & password, atau sync user dulu', 'query' => $sql);
}

$json_string = json_encode($json);

echo $json_string;



?>
