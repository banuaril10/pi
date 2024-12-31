<?php include "../../config/koneksi.php";

$org_key = $_POST['ad_org_id'];
$nama_insert = $_POST['username'];
$userid = $_POST['userid'];
$tanggal = $_POST['tanggal'];

//show tbody format return

$sql = "SELECT * FROM cash_in WHERE org_key = '$org_key' AND userid = '$userid' 
and date(insertdate) = '$tanggal' ORDER BY insertdate DESC";
$statement1 = $connec->query($sql);

$total = 0;
$sql_total = "SELECT sum(cash) total_cashin FROM cash_in WHERE org_key = '$org_key' AND userid = '$userid' 
and date(insertdate) = '$tanggal' and status = '1'";
$statement1_total = $connec->query($sql_total);

foreach ($statement1_total as $row) {
    $total = rupiah_pos($row['total_cashin']);
}

$data = array();
foreach($statement1 as $row){
    $data[] = array(
        'cashinid' => $row['cashinid'],
        'org_key' => $row['org_key'],
        'userid' => $row['userid'],
        'nama_insert' => $row['nama_insert'],
        'cash' => rupiah_pos($row['cash']),
        'insertdate' => $row['insertdate'],
        'status' => $row['status'],
        'approvedby' => $row['approvedby'],
        'syncnewpos' => $row['syncnewpos'],
        'setoran' => $row['setoran']
    );
}


if ($statement1) {
    $json = array('result' => '1', 'msg' => 'Berhasil menampilkan cash in', 'data' => $data, 'total' => $total);
} else {
    $json = array('result' => '0', 'msg' => 'Gagal menampilkan cash in');
}

$json_string = json_encode($json);
echo $json_string;