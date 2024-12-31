<?php include "../../config/koneksi.php";

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
$url = $base_url.'/store/items/get_barcode.php?idstore='. $idstore;

$hasil = get($url);
$j_hasil = json_decode($hasil, true);

$s = array();
$items_updated = 0;
foreach ($j_hasil as $key => $value) {
    $sku = $value['sku'];
    $barcode = $value['barcode'];
    
    $update = "UPDATE pos_mproduct SET barcode = '".$barcode."', postdate = '".date('Y-m-d H:i:s')."' WHERE sku = '".$sku."'";
    $result = $connec->exec($update);
    if ($result) {
        $items_updated++;
    }
}

$json = array(
    "status" => "SUCCESS",
    "message" => $items_updated. " Barcode updated successfully",
    "data" => $update
);

echo json_encode($json);
?>
