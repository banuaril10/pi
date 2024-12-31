<?php include "../../config/koneksi.php";
//get
$query = "SELECT date(a.insertdate) date, c.cat_id, c.category, sum(a.price * a.qty) amount
FROM pos_dsalesline a inner join pos_mproduct b on a.sku = b.sku inner join in_master_category c 
on cast(b.idcat as varchar) = c.cat_id group by c.cat_id, c.category, date(a.insertdate) order by date(a.insertdate), c.category";

$json = array();
$no = 1;
$statement = $connec->query($query);
foreach ($statement as $r) {

    $category = $r['category'];
    $amount = $r['amount'];

    $json[] = array(
        "no" => $no,
        "date" => date_format_pos($r['date']),
        "category" => $category,
        "amount" => rupiah_pos($amount),
        "amount_num" => $amount,
    );

    $no++;
}


$json_string = json_encode($json);
echo $json_string;

?>