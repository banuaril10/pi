<?php include "../../config/koneksi.php";
//get

$date_awal = $_GET['date_awal'];
$date_akhir = $_GET['date_akhir'];


$query = "SELECT * FROM m_pi where date(insertdate) between '".$date_awal."' and '".$date_akhir."' order by date(insertdate) desc limit 100";

$json = array();
$no = 1;
$statement = $connec->query($query);
foreach ($statement as $r) {

    $get_sum_line = "SELECT sum((qtycount+qtysales)*price) as fisik, sum(qtyerp*price) sistem, (sum(qtycount*price) - sum(qtyerp*price)) selisih
    FROM m_piline WHERE m_pi_key = '".$r['m_pi_key']."' ";

    $statement_line = $connec->query($get_sum_line);
    foreach ($statement_line as $r_line) {
        $sistem = $r_line['sistem'];
        $fisik = $r_line['fisik'];
        $selisih = $r_line['selisih'];
    }

    $json[] = array(
        "no" => $no,
        "tanggal" => $r['insertdate'],
        "pi_mode" => $r['inventorytype'],
        "description" => $r['description'],
        "no_doc_pi" => $r['name'],
        "sistem" => rupiah_pos($sistem),
        "fisik" => rupiah_pos($fisik),
        "selisih" => rupiah_pos($selisih),
        "created_by" => $r['insertby'],
        "approval_by" => $r['approvedby'],
    );
    $no++;
}


$json_string = json_encode($json);
echo $json_string;

?>