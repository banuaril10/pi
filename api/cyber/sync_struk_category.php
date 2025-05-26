<?php
include "../../config/koneksi.php";

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

// Ganti URL ke endpoint yang sesuai
$url = $base_url . '/store/struk/category.php';

$hasil = get_category($url);
$j_hasil = json_decode($hasil, true);

$s = array();

// Data field: in_struk_category_header_id, fromdate, todate, category, minprice, insertdate
foreach ($j_hasil as $key => $value) {
    $id = $value['in_struk_category_header_id'];
    $fromdate = $value['fromdate'];
    $todate = $value['todate'];
    $category = $value['category'];
    $minprice = $value['minprice'] ?: 0;
    $template = $value['template'];
    $insertdate = date('Y-m-d H:i:s');

    $s[] = "('$id', '$fromdate', '$todate', '$category', '$minprice', '$insertdate','$template')";
}

if ($s == null) {
    $json = array(
        "status" => "FAILED",
        "message" => "Data Not Found",
        "endpoint" => $url,
    );
    echo json_encode($json);
    die();
}

// Truncate table dulu
$truncate = "TRUNCATE TABLE in_struk_category_header";
$statement = $connec->prepare($truncate);
$statement->execute();

// Insert ulang
$values = implode(", ", $s);
$insert = "INSERT INTO in_struk_category_header 
(in_struk_category_header_id, fromdate, todate, category, minprice, insertdate, template) 
VALUES $values";

$statement = $connec->prepare($insert);
$execute = $statement->execute();

if ($execute) {
    $json = array(
        "status" => "OK",
        "message" => "Data Inserted",
    );
} else {
    $json = array(
        "status" => "FAILED",
        "message" => "Data Not Inserted, Query = " . $insert,
    );
}

echo json_encode($json);
?>