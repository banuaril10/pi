<?php include "../../config/koneksi.php";

$org_key = $_POST['ad_org_id'];
$nama_insert = $_POST['username'];
$cash = $_POST['cash'];
//str replace
$cash = str_replace(".", "", $cash);


$userid = $_POST['userid'];

$date = date("Y-m-d H:i:s");

    $sql = "INSERT INTO cash_in (org_key, userid, nama_insert, cash, insertdate, status)
				VALUES('" . $org_key . "', '" . $userid . "', '" . $nama_insert . "', '" . $cash . "', '" . $date . "','0')";


    $statement1 = $connec->query($sql);
    if ($statement1) {
        $json = array('result' => '1', 'msg' => 'Berhasil insert cash in');

    } else {

        $json = array('result' => '0', 'msg' => 'Gagal input cash in');
    }

$json_string = json_encode($json);
echo $json_string;
?>
