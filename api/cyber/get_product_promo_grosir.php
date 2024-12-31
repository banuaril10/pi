<?php include "../../config/koneksi.php";
//get
$stock = $_GET['stock'];

$query = "SELECT sku, name, price, barcode, tag, stockqty FROM pos_mproduct where sku in 
(select sku from pos_mproductdiscountgrosir_new where date(now()) 
between fromdate and todate) ";

if($stock > 0){
    $query = "SELECT sku, name, price, barcode, tag, stockqty FROM pos_mproduct where sku in 
(select sku from pos_mproductdiscountgrosir_new where date(now()) 
between fromdate and todate)";
}


$json = array();
$no = 1; 
$statement = $connec->query($query);
foreach ($statement as $r) {

    $grosir_price = array();
    $get_grosir = "SELECT * FROM pos_mproductdiscountgrosir_new where sku = '".$r['sku']."' and date(now()) between fromdate and todate";
    $statement_grosir = $connec->query($get_grosir);
    foreach ($statement_grosir as $r_grosir) {
        $grosir_price[] = array(
            'minbuy' => $r_grosir['minbuy'],
            "discount" => $r_grosir['discount'],
            "afterdiscount" => $r['price'] - $r_grosir['discount']
        );
    }


    $text_grosir_price = "";
    foreach ($grosir_price as $gp) {
        $text_grosir_price .= "Beli ".$gp['minbuy'].", Harga Satuan Menjadi ".rupiah_pos($gp['afterdiscount'])."<br>";
    }



    $json[] = array(
        "no" => $no,
        "sku" => $r['sku'],
        "name" => $r['name'],
        "price" => $r['price'],
        "discountname" => 'Promo Grosir',
        "stock" => $r['stockqty'],
        "grosir_price" => $text_grosir_price,
    );

    $no++;
}


$json_string = json_encode($json);
//return json

echo $json_string;

?>