<?php include "../../config/koneksi.php";

$has = $_POST['has'];
$domain = $_POST['domain'];
$locationid = $_POST['locationid'];
$name = $_POST['name'];
$nohp = $_POST['nohp'];

function send_otp($url, $has, $domain, $locationid, $name, $nohp)
{
   
    $postData = array(
        'has' => $has,
        'domain' => $domain,
        'locationid' => $locationid,
        'nohp' => $nohp
    );
    $fields_string = http_build_query($postData);

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
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fields_string,
        )
    );

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;

}

$url = $base_url.'/store/register/send_otp.php';

$hasil = send_otp($url, $has, $domain, $locationid, $name, $nohp);
print_r($hasil);


// print_r("masook");

$j_hasil = json_decode($hasil, true);



$json = array(
    "status" => "OK",
    "message" => $j_hasil['message'],
);

// echo json_encode($json);
?>
