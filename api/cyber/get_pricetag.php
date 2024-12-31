<?php include "../../config/koneksi.php";
//get data product

$arrproduct = $_POST['arrproduct'];
$arrcopy = $_POST['arrcopy'];
$data = array();
// $data_copy = array();

//data copy is 
// [{"sku":"8010014541","copy":"1"},{"sku":"8010014547","copy":"1"},{"sku":"8010014800","copy":"1"},{"sku":"8010014889","copy":"1"}]


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
    $copy = 1;
    //data_copy is 
    // [{"sku":"8010014541","copy":"1"},{"sku":"8010014547","copy":"1"},{"sku":"8010014800","copy":"1"},{"sku":"8010014889","copy":"1"}]

    //looping data_copy and get copy value
    foreach ($arrcopy as $rcopy) {
        if ($r['sku'] == $rcopy['sku']) {
            $copy = $rcopy['copy'];
        }
    }
   

    


    for ($i = 0; $i < $copy; $i++) {
        $products[] = $r['sku']."|".$r['name']."|".$r['price']."|".$date_now."|".$r['rack']."|".$r['shortcut']."|".$r['harga_last']."|".$r['tag']."|".$storecode."/".date('dmy')."|".$r['barcode'];
    }
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