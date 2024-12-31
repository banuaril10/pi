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
$url = $base_url.'/store/bank/get_bank.php';

$hasil = get_category($url);
$j_hasil = json_decode($hasil, true);

$s = array();

// pos_mbank_key
// isactived
// insertdate
// insertby
// postby
// postdate
// name
// description


foreach ($j_hasil as $key => $value) {
   
    $pos_mbank_key = $value['pos_mbank_key'];
    $isactived = $value['isactived'];
    $insertdate = date('Y-m-d H:i:s');
    $insertby = $value['insertby'];
    $postby = $value['postby'];
    $postdate = date('Y-m-d H:i:s');
    $name = $value['name'];
    $description = $value['description'];

    $s[] = "('$pos_mbank_key', '$isactived', '$insertdate', '$insertby', '$postby', '$postdate', '$name', '$description')";

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
$truncate = "TRUNCATE TABLE pos_mbank";
$statement = $connec->prepare($truncate);
$statement->execute();

$values = implode(", ", $s);
$insert =  "INSERT INTO pos_mbank (pos_mbank_key, isactived, insertdate, insertby, postby, postdate, name, description) VALUES $values";

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
