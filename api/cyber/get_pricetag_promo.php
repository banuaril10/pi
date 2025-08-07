<?php include "../../config/koneksi.php";
//get data product

$arrproduct = $_POST['arrproduct'];
$arrcopy = $_POST['arrcopy'];
$data = array();
// $data_copy = array();

// $arr = json_encode($arrproduct);

foreach ($arrproduct as $r) {
    $data[] = $r;
}

// foreach ($arrcopy as $r) {
//     $data_copy[] = $r;
// }

// print_r($data);

$get_store_code = $connec->query("select value from ad_morg");
foreach ($get_store_code as $r) {
    $storecode = $r['value'];
}


$implode = implode("','", $data);

$qq = "select a.*, (a.price - b.discount) afterdiscount, b.fromdate, b.todate from pos_mproduct a
inner join (select * from pos_mproductdiscount where date(now()) between fromdate and todate) b on a.sku = b.sku
where a.sku in ('" . $implode . "')";
$statement = $connec->query($qq);

$date_now = date('d/m/Y');

$products = array();
$noarr = 0;
foreach ($statement as $r) {

    $copy = 1;

    foreach ($arrcopy as $rcopy) {
        if ($r['sku'] == $rcopy['sku']) {
            $copy = $rcopy['copy'];
        }
    }

    for ($i = 0; $i < $copy; $i++) {
        $products[] = $r['sku'] . "|" . $r['name'] . "|" . $r['price'] . "|" . $date_now . "|" . $r['rack'] . "|" . $r['afterdiscount'] . "|" . $r['todate'] . "|" . $r['barcode'] . "|" . $r['fromdate'];
    }


    $noarr++;
}

$json = array(
    "status" => "SUCCESS",
    "products" => $products,
);
echo json_encode($json);




// print_r($qq);



// $query = "select * from pos_mproduct ";


?>