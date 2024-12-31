<?php include "../../config/koneksi.php";
$connec->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}
function get($url)
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


$url = $base_url.'/store/price/get_price.php?idstore='. $idstore;

$hasil = get($url);
$j_hasil = json_decode($hasil, true);

// print_r($j_hasil);

$items_updated = 0;
$s = array();


try{
    foreach ($j_hasil as $key => $value) {

        $description = $value['description'];
        $itemid = $value['itemid'];
        $unitprice = $value['unitprice'];

        //update pos_mproduct
        $update = "UPDATE pos_mproduct SET price = '" . $unitprice . "', name = '" . $description . "', postdate = '" . date('Y-m-d H:i:s') . "' 
        WHERE sku = '" . $itemid . "'";
        // echo $update;

        $result = $connec->query($update);

        if ($result) {
            $items_updated++;
        }
    }
    $json = array(
        "status" => "SUCCESS",
        "message" => $items_updated . " price updated successfully",
        "data" => $update
    );

    echo json_encode($json);
}catch(PDOException $e){
    echo $e->getMessage();
}


?>
