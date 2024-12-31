<?php header('Content-Type: application/json; charset=utf-8');

include "../../config/koneksi.php";

$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$items = array();


foreach ($data as $row) {

    //update stock
    $sku = $row['sku'];
    $stock = $row['stock'];
    $location_id = $row['location_id'];

    $update = "UPDATE pos_mproduct SET stockqty = stockqty + ".$stock.", postdate = '" . date('Y-m-d H:i:s') . "' WHERE sku = '" . $sku . "'";

    $que .= $update;

    $result = $connec->exec($update);
    if ($result) {
        $items[] = $sku;
    }

    // print_r($update);
}

$json = array(
    "status" => "SUCCESS",
    "message" => count($items) . " Stock updated successfully",
    "items" => $items
);

echo json_encode($json);
?>
