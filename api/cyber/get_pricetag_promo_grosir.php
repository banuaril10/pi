<?php include "../../config/koneksi.php";
//get data product

$arrproduct = $_POST['arrproduct'];
$arrcopy = $_POST['arrcopy'];
$data = array();
$data_copy = array();

$arr = json_encode($arrproduct);

foreach ($arrproduct as $r) {
    $data[] = $r;
}

foreach ($arrcopy as $r) {
    $data_copy[] = $r;
}

// print_r($data);

$get_store_code = $connec->query("select value from ad_morg");
foreach ($get_store_code as $r) {
    $storecode = $r['value'];
}


$implode = implode("','", $data);

$qq = "SELECT sku, name, price, barcode, tag, stockqty, rack FROM pos_mproduct where sku in 
(select sku from pos_mproductdiscountgrosir_new where date(now()) 
between fromdate and todate) and sku in ('" . $implode . "')";
$statement = $connec->query($qq);

$date_now = date('d/m/Y');

$products = array();
$noarr = 0;
foreach ($statement as $r) {

    $grosir_price = array();
    $get_grosir = "SELECT * FROM pos_mproductdiscountgrosir_new where sku = '".$r['sku']."' and date(now()) between fromdate and todate and minbuy > 1";
    // echo $get_grosir;

    $statement_grosir = $connec->query($get_grosir);
    $todate = "";
    foreach ($statement_grosir as $r_grosir) {
        $grosir_price[] = array(
            'minbuy' => $r_grosir['minbuy'],
            "discount" => $r_grosir['discount'],
            "afterdiscount" => $r['price'] - $r_grosir['discount'],
            "todate" => $r_grosir['todate']
        );
        $todate = $r_grosir['todate'];
    }

    // print_r($grosir_price);

    $harga_grosir = "<table border='0' cellspacing='0' cellpadding='0' style='width: 100%;border-spacing: 0;border-collapse: collapse; margin-top:10px'>";
    // echo $harga_grosir;

    $jumlah_grosir = count($grosir_price);

    if($jumlah_grosir == 1){
        $font_size = "font-size: 20px";
        $font_size_rp = "font-size: 7px";
        $font_size_price = "font-size: 25px";
    }else if($jumlah_grosir == 2){
        $font_size = "font-size: 18px";
        $font_size_rp = "font-size: 12px";
        $font_size_price = "font-size: 25px";
    }else if($jumlah_grosir == 3){
        $font_size = "font-size: 10px";
        $font_size_rp = "font-size: 8px";
        $font_size_price = "font-size: 17px";
    }

	foreach ($grosir_price as $gp) {
        // echo $gp['minbuy'];
        $harga_grosir .= "<tr style='".$font_size."'><td>Beli ".$gp['minbuy']." </td>   
        <td style='text-align: right'><label style='". $font_size_rp."'><b>Rp </b></label> <b style='". $font_size_price."'>
        ".rupiah_pos($gp['afterdiscount'])."</b>/pcs</td></tr>";

        if($jumlah_grosir == 1){
            $harga_grosir .= "<br>";
        }
    }						
 					
    $harga_grosir .= "</table>";

    // echo $harga_grosir;

    // if($jumlah_grosir > 1){
        for ($i = 0; $i < $data_copy[$noarr]; $i++) {
            $products[] = $r['sku'] . "|" . $r['name'] . "|" . $r['price'] . "|" . $date_now . "|" . $r['rack'] . "|" . $harga_grosir . "|" . $todate . "|" . $r['barcode'] . "|" . $jumlah_grosir;
        }
    // }
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