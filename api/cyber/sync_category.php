<?php include "../../config/koneksi.php";

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
$url = $base_url.'/store/category/get_category.php';

$hasil = get_category($url);
$j_hasil = json_decode($hasil, true);

$s = array();
foreach ($j_hasil as $key => $value) {
    $cat_id = $value['cat_id'];
    $category = $value['category'];
    $s[] = "('" . $cat_id . "', '" . $category . "')";
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
$truncate = "TRUNCATE in_master_category";
$statement = $connec->prepare($truncate);
$statement->execute();

$values = implode(", ", $s);
$insert = "INSERT INTO in_master_category (cat_id, category) VALUES " . $values . "";

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
    );
}

echo json_encode($json);
?>
