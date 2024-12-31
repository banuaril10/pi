<?php include "../../config/koneksi.php";
function rupiah($angka)
{

    $hasil_rupiah = number_format($angka, 0, ',', '.');
    return $hasil_rupiah;

}
$id = $_POST['id'];
$jj = array();
$haha = array();
$list_line = "select * from cash_in where status = '1' and cashinid = '" . $id . "'";
$no = 1;
foreach ($connec->query($list_line) as $row1) {
    $approvedby = $row1['approvedby'];
    $setoran = $row1['setoran'];
    $nama_insert = $row1['nama_insert'];
    $jam = date('H:i:s', strtotime($row1['insertdate']));
    $date = date('Y-m-d', strtotime($row1['insertdate']));

    $jj[] = array(
        "jam" => $jam,
        "cash" => "Rp " . rupiah($row1['cash'])
    );
}

$haha = array(
    "tanggal" => $date,
    "username" => $nama_insert,
    "approvedby" => $approvedby,
    "setoran" => $setoran,
    "data" => $jj

);


$json_string = json_encode($haha);
echo $json_string;