<?php
include "../../config/koneksi.php";
$connec->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// get idstore
$q = $connec->query("SELECT ad_morg_key FROM ad_morg WHERE isactived='Y'");
$idstore = $q->fetchColumn();

function get($url)
{
    $c = curl_init();
    curl_setopt_array($c, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true
    ]);
    $r = curl_exec($c);
    curl_close($c);
    return $r;
}

function post($url, $payload)
{
    $c = curl_init($url);
    curl_setopt_array($c, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);
    $r = curl_exec($c);
    curl_close($c);
    return $r;
}

/* STEP 1: GET DATA PERUBAHAN HARGA */
$url = $base_url . '/store/price/get_price_change_agung.php?idstore=' . $idstore;
$hasil = get($url);
$j_hasil = json_decode($hasil, true);

// load harga lama dari lokal
$oldPrices = [];
$dataOld = $connec->query("SELECT sku, price FROM pos_mproduct");

while ($r = $dataOld->fetch(PDO::FETCH_ASSOC)) {
    $oldPrices[$r['sku']] = floatval($r['price']);
}

$items_updated = 0;


//get sales qty from  pos_dsalesline 
$get_qty_sales = "select m.sku, sum(d.qty) as qty_sales from pos_dsalesline d
inner join pos_mproduct m on d.sku = m.sku
where date(d.insertdate) = date(now())
group by m.sku";
$statement_qty_sales = $connec->query($get_qty_sales);
$arr_qty_sales = array();
while ($r = $statement_qty_sales->fetch(PDO::FETCH_ASSOC)) {
    $arr_qty_sales[$r['sku']] = $r['qty_sales'];
}



try {

    foreach ($j_hasil as $item) {

        $sku = $item['itemid'];
        $new_price = floatval($item['unitprice']);
        $name = $item['description'];

        $old_price = $oldPrices[$sku] ?? 0;

        // HANYA bila ada perubahan harga
        if ($old_price != $new_price) {

            /* STEP 2: HIT API SAVE_PRICE_CHANGE SEBELUM UPDATE LOKAL */
            $urlSave = $base_url . '/store/price/save_price_change.php?idstore=' . $idstore;

            $payload = [
                "sku" => $sku,
                "old_price" => $old_price,
                "new_price" => $new_price,
                "name" => $name,
                "sales_qty" => $arr_qty_sales[$sku] ?? 0
            ];

            $saveResponse = post($urlSave, $payload);
            $saveJson = json_decode($saveResponse, true);

            // jika cloud return gagal → skip update lokal
            if (!$saveJson || (isset($saveJson['status']) && $saveJson['status'] != "SUCCESS")) {
                continue;
            }
        }

        /* STEP 3: UPDATE HARGA LOKAL (SETELAH CLOUD DIHIT) */
        $update = "
            UPDATE pos_mproduct 
            SET price = :price, 
                name = :name, 
                postdate = :postdate
            WHERE sku = :sku
        ";

        $stmt = $connec->prepare($update);
        $stmt->execute([
            ':price' => $new_price,
            ':name' => $name,
            ':postdate' => date('Y-m-d H:i:s'),
            ':sku' => $sku
        ]);

        if ($stmt->rowCount() > 0) {
            $items_updated++;
        }
    }

    echo json_encode([
        "status" => "SUCCESS",
        "message" => $items_updated . " price updated",
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "status" => "ERROR",
        "message" => $e->getMessage()
    ]);
}

?>