<?php include "../../config/koneksi.php";
ini_set('max_execution_time', '300');
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
$url = $base_url . '/store/items/get_items_nonactive.php?idstore=' . $idstore;

$hasil = get($url);
$j_hasil = json_decode($hasil, true);

try {

    foreach ($j_hasil as $key => $value) {

        $sku = $value['sku'];

        //delete 
        $delete = "delete from pos_mproduct where sku = '$sku'";
        $connec->query($delete);
    }

    $json = array(
        "status" => "OK",
        "message" => "Data Inserted",
    );

    echo json_encode($json);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}



?>