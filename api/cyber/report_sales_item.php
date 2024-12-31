<?php include "../../config/koneksi.php";
//get
$query = "SELECT date(a.insertdate) date, a.sku, b.name, sum(a.qty) qty, sum(a.amount) amount, sum(b.price) price FROM pos_dsalesline a 
left join pos_mproduct b on a.sku = b.sku
group by date(a.insertdate), a.sku, b.name
order by date(a.insertdate) desc";


$json = array();
$no = 1;
$statement = $connec->query($query);
foreach ($statement as $r) {

    $date = $r['date'];
    $sku = $r['sku'];
    $name = $r['name'];
    $qty = $r['qty'];
    $amount = $r['qty'] * $r['price'];

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