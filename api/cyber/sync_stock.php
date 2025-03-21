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
$url = $base_url.'/store/items/get_stock.php?idstore='. $idstore;

$hasil = get($url);
$j_hasil = json_decode($hasil, true);
$items_updated = 0;
$s = array();

$arr_qty_sales = array();

$sql = "select sku, sum(qty) as qty from pos_dsalesline where date(insertdate) = date(now()) group by sku";
$query = $connec->query($sql);

foreach ($query as $row) {
    $arr_qty_sales[$row['sku']] = $row['qty'];
}



foreach ($j_hasil as $key => $value) {
    $itemsid = $value['itemsid'];
    $id = $value['id'];
    $location_id = $value['location_id'];
    $bin_id = $value['bin_id'];
    $stock = $value['stock'];
    $insertdate = $value['insertdate'];
    $updatedate = $value['updatedate'];
    $userin = $value['userin'];
    $userup = $value['userup'];
    $isactived = $value['isactived'];
    
    //update pos_product

    //dikurang sales hari ini dari table pos_salesline
    $qty = 0;
    if (isset($arr_qty_sales[$itemsid])) {
        $qty = $arr_qty_sales[$itemsid];
    }
    
    $stock = $stock - $qty;

    
    $update = "UPDATE pos_mproduct SET stockqty = '".$stock."', postdate = '".date('Y-m-d H:i:s')."' WHERE sku = '".$itemsid."'";
    $result = $connec->query($update);
   
    if ($result) {
        $items_updated++;
    }
}

$json = array(
    "status" => "SUCCESS",
    "message" => $items_updated. " Stock updated successfully",
    "data" => $update
);

echo json_encode($json);
?>
