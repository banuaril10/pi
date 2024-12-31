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
$url = $base_url.'/store/promo/get_promo_reguler.php?idstore='. $idstore;

$hasil = get_category($url);
$j_hasil = json_decode($hasil, true);

$s = array();
foreach ($j_hasil as $key => $value) {
    $amk = $value['ad_morg_key']; 
    $isactived = $value['isactived']; 
    $insertdate = $value['insertdate']; 
    $insertby = $value['insertby']; 
    $discountname = str_replace("'", "\'", $value['discountname']); 
    $discounttype = $value['discounttype']; 
    $sku = $value['sku']; 
    $discount = $value['discount']; 
    $fromdate = $value['fromdate']; 
    $todate = $value['todate']; 
    $typepromo = $value['typepromo']; 
    $maxqty = $value['maxqty'];
    $jenis_promo = $value['jenis_promo'];


    $s[] = "('" . $ad_mclient_key . "', 
    '" . $amk . "', 
    '" . $isactived . "', 
    '" . date("Y-m-d H:i:s") . "',
    '" . date("Y-m-d H:i:s") . "', 
    '" . $insertby . "',
     '" . $discountname . "', 
     '" . $discounttype . "',
     '" . $sku . "', 
     '" . $discount . "', 
     '" . $fromdate . "', 
     '" . $todate . "', 
     '" . $typepromo . "', 
     '" . $maxqty . "',
     '" . $jenis_promo . "'
     
     )";

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
$truncate = "TRUNCATE pos_mproductdiscount";
$statement = $connec->prepare($truncate);
$statement->execute();

$values = implode(", ", $s);
$insert = "insert into pos_mproductdiscount (ad_mclient_key, ad_morg_key, isactived, postdate, insertdate, insertby, discountname, discounttype, sku, discount, 
fromdate, todate, typepromo, maxqty, jenis_promo) 
						VALUES " . $values . ";";

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
        "message" => "Data Not Inserted, Query = ". $insert,
    );
}

echo json_encode($json);
?>
