<?php include "../../config/koneksi.php";
//get

$date_awal = $_GET['date_awal'];
$date_akhir = $_GET['date_akhir'];


$query = "SELECT date(a.insertdate) date, a.sku, b.name, sum(a.qty) qty, sum(a.price * a.qty) amount FROM pos_dsalesline a 
left join pos_mproduct b on a.sku = b.sku where a.sku != '' ";


if ($date_awal != '' && $date_akhir != '' ) {
    $query .= " and date(a.insertdate) between '$date_awal' and '$date_akhir' ";
}else{
    $query .= " and date(a.insertdate) = date(now()) ";
}


$query .= ' group by a.sku, b.name
order by b.name asc';

// echo $query;


$json = array();
$no = 1;
$statement = $connec->query($query);
foreach ($statement as $r) {

    $date = $r['date'];
    $sku = $r['sku'];
    $name = $r['name'];
    $qty = $r['qty'];
    $amount = $r['amount'];

    $json[] = array(
        "no" => $no,
        "date" => $date,
        "sku" => $sku,
        "name" => $name,
        "qty" => $qty,
        "amount" => rupiah_pos($amount),
        "amount_num" => $amount,
    );

    $no++;
}


$json_string = json_encode($json);
echo $json_string;

?>