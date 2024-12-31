<?php include "../../config/koneksi.php";
//get data product

$arrproduct = $_POST['arrproduct'];
// $arrcopy = $_POST['arrcopy'];
$data = array();
// $data_copy = array();


// $arr = json_encode($arrproduct);
// $arr_copy = json_encode($arrcopy);

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

$qq = "select * from pos_mproduct where isactived = '1' and sku in ('" . $implode . "') ";
$statement = $connec->query($qq);

$date_now = date('d/m/Y');

$products = array();
$noarr = 0;
foreach ($statement as $r) {
    // looping for 
    // for ($i = 0; $i < $data_copy[$noarr]; $i++) {
        $products[] = $r['sku']."|".$r['name']."|".rupiah_pos($r['price'])."|".$date_now."|".$r['rack']."|".$r['shortcut']."|".rupiah_pos($r['harga_last'])."|".$r['tag']."|".$storecode."/".date('dmy')."|".$r['barcode'];
    // }
    // $products[] = $r['sku']."|".$r['name']."|".$r['price']."|".$date_now."|".$r['rack']."|".$r['shortcut']."|".$r['harga_last']."|".$r['tag']."|".$storecode."/".date('dmy')."|".$r['barcode'];

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