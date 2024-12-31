<?php include "../../config/koneksi.php";
//get total product

$statement = $connec->query("select count(*) as total from pos_mproduct where isactived = '1'");
foreach ($statement as $r) {
    $total_product = $r['total'];
}

//get total product discount
$statement = $connec->query("select count(*) as total from pos_mproductdiscount where isactived = '1' and '" . date("Y-m-d") . "' between fromdate and todate");
foreach ($statement as $r) {
    $total_product_discount = $r['total'];
}


//return json
$json = array(
    "status" => "SUCCESS",
    "total_product" => $total_product,
    "total_product_discount" => $total_product_discount,
);
echo json_encode($json);

?>