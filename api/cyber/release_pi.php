<?php include "../../config/koneksi.php";
$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}
function piline_semua($url, $a)
{
    $postData = array(
        "data_line" => $a,
    );
    $fields_string = http_build_query($postData);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $fields_string,
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}

$url = $base_url . '/store/pi/release.php?idstore=' . $idstore;

$no = 0;
$items = array();
$pi_key = $_POST['m_pi'];
$sql = "select * from m_pi where m_pi_key ='" . $pi_key . "'";
$result = $connec->query($sql);
foreach ($result as $row) {

    $a = $row['ad_client_id'];
    $b = $row['ad_org_id'];
    $c = $row['insertdate'];
    $d = $row['insertby'];
    $e = $row['m_locator_id'];
    $f = $row['inventorytype'];
    $ff = $row['name'];
    $g = $row['description'];
    $h = $row['movementdate'];
    $i = $row['approvedby'];
    $j = $row['status'];
    $k = $row['rack_name'];
    $l = $row['postby'];
    $m = $row['postdate'];
    $cat = $row['category'];
    $n = $row['isactived'];
    $o = $row['insertfrommobile'];
    $p = $row['insertfromweb'];



    $pi_cuy = array(
        "pi_key" => $pi_key,
        "ad_client_id" => $a,
        "ad_org_id" => $b,
        "insertdate" => $c,
        "insertby" => $d,
        "m_locator_id" => $e,
        "inventorytype" => $f,
        "name" => $ff,
        "description" => $g,
        "movementdate" => $h,
        "approvedby" => $i,
        "status" => $j,
        "rack_name" => $k,
        "postby" => $l,
        "postdate" => $m,
        "category" => $cat,
        "isactived" => $n,
        "insertfrommobile" => $o,
        "insertfromweb" => $p,

    );

    $sql_line = "select m_piline.*, pos_mproduct.name from m_piline left join pos_mproduct on m_piline.sku = pos_mproduct.sku where m_piline.m_pi_key ='" . $pi_key . "' and m_piline.issync =0";



    foreach ($connec->query($sql_line) as $rline) {
        $connec->query("update pos_mproduct set isactived = 1 where sku = '" . $rline['sku'] . "'");
        $items[] = array(
            'm_piline_key' => $rline['m_piline_key'],
            'm_pi_key' => $rline['m_pi_key'],
            'ad_client_id' => $rline['ad_client_id'],
            'ad_org_id' => $rline['ad_org_id'],
            'isactived' => $rline['isactived'],
            'insertdate' => $rline['insertdate'],
            'insertby' => $rline['insertby'],
            'postby' => $rline['postby'],
            'postdate' => $rline['postdate'],
            'm_storage_id' => $rline['m_storage_id'],
            'm_product_id' => $rline['m_product_id'],
            'sku' => $rline['sku'],
            'name' => $rline['name'],
            'qtyerp' => $rline['qtyerp'],
            'qtycount' => $rline['qtycount'],
            'issync' => $rline['issync'],
            'status' => $rline['status'],
            'verifiedcount' => $rline['verifiedcount'],
            'qtysales' => $rline['qtysales'],
            'price' => $rline['price'],
            'qtysalesout' => $rline['qtysalesout'],
            'hargabeli' => $rline['hargabeli']
        );

    }

    $allarray = array("pi" => $pi_cuy, "piline" => $items);

    
    $items_json = json_encode($allarray);



    $hasil = piline_semua($url, $items_json);
    // var_dump($hasil);




    $j_hasil = json_decode($hasil, true);

    // print_r($j_hasil['data']);

    if (!empty($j_hasil['data'])) {
        $connec->query("update m_pi set status = '3' where m_pi_key ='" . $pi_key . "'");
    }

    foreach ($j_hasil['data'] as $r) {
        $statement1 = $connec->query("update m_piline set issync = '" . $r['status'] . "' where m_piline_key = '" . $r['m_piline_key'] . "' 
									and m_pi_key ='" . $r['m_pi_key'] . "'");
        if ($statement1) {
            $up = $connec->query("update pos_mproduct set isactived = 1 where sku = '" . $r['sku'] . "'");
            if ($up) {

                $no = $no + 1;
            }
        } else {
            $no = $no + 1;

        }

    }
}

// var_dump($hasil);

$json = array('result' => '1', 'msg' => 'Berhasil mengirim ' . $no . ' data');
$json_string = json_encode($json);
echo $json_string;