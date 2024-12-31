<?php include "../../config/koneksi.php";
//get
$query = "select b.category, sum(stockqty) qty, sum(stockqty*price) total from pos_mproduct a
inner join in_master_category b on cast(a.idcat as varchar) = b.cat_id 
group by b.category order by b.category asc";

$json = array();
$no = 1;
$statement = $connec->query($query);
foreach ($statement as $r) {

    $category = $r['category'];
    $qty = $r['qty'];
    $total = $r['total'];

    $json[] = array(
        "no" => $no,
        "category" => $category,
        "qty" => $qty,
        "total" => rupiah_pos($total),
        "total_norp" => $total

    );

    $no++;
}


$json_string = json_encode($json);
echo $json_string;

?>