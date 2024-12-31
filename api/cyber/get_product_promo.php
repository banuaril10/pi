<?php include "../../config/koneksi.php";
//get
$stock = $_GET['stock'];
$rack = $_GET['rack'];

$query = "SELECT a.*, b.discountname, coalesce(b.discount,0) discount, b.fromdate, b.todate, coalesce((a.price - b.discount),0) afterdiscount FROM pos_mproduct a inner join 
(select * from pos_mproductdiscount where date(now()) between fromdate and todate) b on a.sku = b.sku where a.sku != '' ";

if($stock > 0){
    $query = " and a.stockqty > 0 ";
}

if($rack != ""){
    $query .= " and a.rack = '$rack'";
}


$query .= " order by a.sku asc";


$json = array();
$no = 1;
$statement = $connec->query($query);
foreach ($statement as $r) {
    $json[] = array(
        "no" => $no,
        "sku" => $r['sku'],
        "name" => $r['name'],
        "price" => $r['price'],
        "priceafter" => $r['afterdiscount'],
        "rack" => $r['rack'],
        "discountname" => $r['discountname'],
        "stock" => $r['stockqty']
    );

    $no++;
}


$json_string = json_encode($json);
//return json

echo $json_string;

?>