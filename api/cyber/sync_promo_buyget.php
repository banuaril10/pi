<?php include "../../config/koneksi.php";
$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}
function get_category($url)
{
    $curl = curl_init();
    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        )
    );

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
$url = $base_url.'/store/promo/get_promo_buyget.php?idstore='.$idstore;

$hasil = get_category($url);
$j_hasil = json_decode($hasil, true);

// print_r($j_hasil);

$s = array();
foreach ($j_hasil as $key => $value) {

    $ad_morg_key = $value['ad_morg_key']; //etc
    $isactived = $value['isactived']; //etc
    $insertdate = $value['insertdate']; //etc
    $insertby = $value['insertby']; //etc
    $postby = $value['postby']; //etc
    $postdate = $value['postdate']; //etc
    $discountname = 'Buy & Get'; //etc
    $typepromo = $value['typepromo']; //etc
    $skubuy = $value['skubuy']; //etc
    $qtybuy = $value['qtybuy']; //etc
    $skuget = $value['skuget']; //etc
    $qtyget = $value['qtyget']; //etc
    $priceget = $value['priceget']; //etc
    $fromdate = $value['fromdate']; //etc
    $todate = $value['todate']; //etc
    $discount = $value['discount']; //etc
    $jenis_promo = $value['jenis_promo']; //etc

    $s[] = "('" . $isactived . "','" . $ad_mclient_key . "', '" . $ad_morg_key . "', '" . date("Y-m-d H:i:s") . "','" . $insertby . "', 
    '" . $insertby . "', '" . date("Y-m-d H:i:s") . "', '" . $discountname . "','" . $typepromo . "', '" . $fromdate . "', '" . $todate . "', '" . $skubuy . "', 
    '" . $qtybuy . "', '" . $skuget . "', '" . $qtyget . "', '" . $priceget . "', '" . $discount . "', '" . $jenis_promo . "')";

}

if($s == null){
    $json = array(
        "status" => "FAILED",
        "message" => "Data Not Found",
    );
    echo json_encode($json);
    die();
}

//truncate
$truncate = "TRUNCATE pos_mproductbuyget";
$statement = $connec->prepare($truncate);
$statement->execute();

$values = implode(", ", $s);
$insert = "insert into pos_mproductbuyget (isactived, ad_mclient_key, ad_morg_key, insertdate, insertby, postby, postdate, discountname, typepromo, 
fromdate, todate, skubuy, qtybuy, skuget, qtyget, priceget, discount, jenis_promo) VALUES " . $values . ";";

$statement = $connec->prepare($insert);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($result) {
    $json = array(
        "status" => "OK",
        "message" => "Data Inserted",
    );
} else {
    $json = array(
        "status" => "FAILED",
        "message" => "Data Not Inserted",
        "q" => $insert,
    );
}

echo json_encode($json);
?>
