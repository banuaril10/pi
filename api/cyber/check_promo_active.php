<?php include "../../config/koneksi.php";
//get total product

$jum_mulai = 0;
$jum_berakhir = 0;

//besok akan mulai

$tommorow = date('Y-m-d', strtotime('+1 day'));
$now = date('Y-m-d');

$discountmul_arr = array();
$discountber_arr = array();

$list_discountmul_arr = array();
$list_discountber_arr = array();

$text_mulai = "";
$text_berakhir = "";

$nama_promo_mulai = "";
$nama_promo_berakhir = "";

$no_mulai = 1;
$no_berakhir = 1;


$promo_mulai = $connec->query("SELECT fromdate, todate, discountname, COUNT(sku) AS jum
FROM pos_mproductdiscount WHERE fromdate = '".$tommorow."' group by fromdate, todate, discountname");
foreach ($promo_mulai as $row) {
    $discountmul_arr[] = array('discountname'=>$row["discountname"], 'jum'=>$row["jum"]);
    $jum_mulai += $row["jum"];

    $nama_promo_mulai .= '-'.$row["discountname"];


    //format date d F Y
    $text_periode = date("d F Y", strtotime($row['fromdate']))." s.d. ".date("d F Y", strtotime($row['todate']));

    $text_mulai .= $no_mulai.'. '.$row["discountname"]." Periode ". $text_periode." <br> ";
    $list_promo =  $connec->query("select * from pos_mproductdiscount where discountname = '".$row["discountname"]."' and fromdate = '".$tommorow."'");
    foreach ($list_promo as $row_list) {


        $name = "";
        $rack = "";
        $price = 0;
        //get name product
        $product = $connec->query("select price, rack, name from pos_mproduct where sku = '" . $row_list["sku"] . "'");
        foreach ($product as $row_product) {
            $name = $row_product["name"];
            $rack = $row_product["rack"];
            $price = $row_product["price"];
        }

        $after_discount = $price - $row_list["discount"];


        $list_discountmul_arr[] = array(
            'sku'=>$row_list["sku"], 
            'name'=>$name,
            'rack' => $rack,
            'price' => $price,
            'afterdiscount' => $after_discount,
            'discountname'=>$row_list["discountname"], 
            'fromdate'=>$row_list["fromdate"], 
            'todate'=>$row_list["todate"]);
    }

    $no_mulai++;

}

//besok akan berakhir
$promo_berakhir = $connec->query("SELECT fromdate, todate, discountname, COUNT(sku) AS jum
FROM pos_mproductdiscount
WHERE todate = '" . $tommorow . "' group by fromdate, todate, discountname");
foreach ($promo_berakhir as $row) {
    $discountber_arr[] = array('discountname'=>$row["discountname"], 'jum'=>$row["jum"]);
    $jum_berakhir += $row["jum"];

    $nama_promo_berakhir .= '-' . $row["discountname"];

    //format date d F Y
    $text_periode = date("d F Y", strtotime($row['fromdate']))." s.d. ".date("d F Y", strtotime($row['todate']));
    $text_berakhir .= $no_berakhir.'. '.$row["discountname"]." Periode " . $text_periode . " <br> ";
    $list_promo =  $connec->query("select * from pos_mproductdiscount where discountname = '".$row["discountname"]."' and todate = '".$tommorow."'");
    foreach ($list_promo as $row_list) {

        $name = "";
        $rack = "";
        $price = 0;
        //get name product
        $product = $connec->query("select price, rack, name from pos_mproduct where sku = '" . $row_list["sku"] . "'");
        foreach ($product as $row_product) {
            $name = $row_product["name"];
            $rack = $row_product["rack"];
            $price = $row_product["price"];
        }

        $after_discount = $price - $row_list["discount"];

        $list_discountber_arr[] = array(
            'sku'=>$row_list["sku"], 
            'name'=>$name,
            'rack'=>$rack,
            'price'=>$price,
            'afterdiscount'=>$after_discount,
            'discountname'=>$row_list["discountname"], 
            'fromdate'=>$row_list["fromdate"], 
            'todate'=>$row_list["todate"]);
    }
    $no_berakhir++;
}

$json = array(
    'mulai' => $discountmul_arr,
    'berakhir' => $discountber_arr,
    'promo_mulai' => $jum_mulai,
    'promo_berakhir' => $jum_berakhir,
    'text_mulai' => $text_mulai,
    'text_berakhir' => $text_berakhir,
    'list_discountmul_arr' => $list_discountmul_arr,
    'list_discountber_arr' => $list_discountber_arr,
    'nama_promo_mulai' => $nama_promo_mulai,
    'nama_promo_berakhir' => $nama_promo_berakhir,
    'tgl_cetak' => date('d-m-Y H-i-s')
);

echo json_encode($json);
?>