<?php include "../../config/koneksi.php";
//get
$stock = $_GET['stock'];
$rack = $_GET['rack'];

$query = "SELECT * FROM pos_mproductbuyget where date(now()) between fromdate and todate;";
$json = array();
$no = 1;
$statement = $connec->query($query);
foreach ($statement as $r) {


    //get name skubuy and skuget from pos_mproduct table 

    $query2 = "SELECT name FROM pos_mproduct where sku = '".$r['skubuy']."'";
    $statement2 = $connec->query($query2);
    foreach ($statement2 as $r2) {
        $namebuy = $r2['name'];
    }


    $query3 = "SELECT name FROM pos_mproduct where sku = '".$r['skuget']."'";
    $statement3 = $connec->query($query3);

    foreach ($statement3 as $r3) {
        $nameget = $r3['name'];
    }

    $r['skubuy'] = $r['skubuy']."<br>".$namebuy;
    $r['skuget'] = $r['skuget']."<br>".$nameget;

    $json[] = array(
        "no" => $no,
        "skubuy" => $r['skubuy'],
        "qtybuy" => $r['qtybuy'],
        "skuget" => $r['skuget'],
        "qtyget" => $r['qtyget'],
        "priceget" => rupiah_pos($r['priceget']),
        "pricediscount" => rupiah_pos($r['discount']),
        "priceafter" => rupiah_pos($r['priceget'] - $r['discount']),
    );

    $no++;
}


$json_string = json_encode($json);
//return json

echo $json_string;

?>