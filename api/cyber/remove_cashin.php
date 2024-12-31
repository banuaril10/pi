<?php include "../../config/koneksi.php";
function rupiah($angka)
{

    $hasil_rupiah = number_format($angka, 0, ',', '.');
    return $hasil_rupiah;

}
$id = $_POST['id'];
$jj = array();
$haha = array();
$list_line = "delete from cash_in where status = '0' and cashinid = '" . $id . "'";
$delete = $connec->query($list_line);

$haha = array(
    "status" => "SUCCESS",
    "message" => "Data berhasil dihapus"
);


$json_string = json_encode($haha);
echo $json_string;