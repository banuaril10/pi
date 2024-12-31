<?php include "../../config/koneksi.php";
//get
$stock = $_GET['stock'];
$rack = $_GET['rack'];

$query = "SELECT * FROM pos_mproduct where sku != '' and price > 0";
if($stock > 0){
    $query .= " and stockqty > 0 order by sku asc";
}

if($rack != ""){
    $query .= " and rack = '$rack'";
}

$query .= " order by sku asc";


$json = array();
$no = 1;
$statement = $connec->query($query);
foreach ($statement as $r) {
    $json[] = array(
        "no" => $no,
        "sku" => $r['sku'],
        "barcode" => $r['barcode'],
        "name" => $r['name'],
        "price" => $r['price'],
        "rack" => $r['rack'],
        "stock" => $r['stockqty']
    );

    $no++;
}


$json_string = json_encode($json);
//return json

echo $json_string;

?>