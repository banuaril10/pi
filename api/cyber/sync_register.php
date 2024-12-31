<?php include "../../config/koneksi.php";

$has = $_POST['has'];
$domain = $_POST['domain'];
$locationid = $_POST['locationid'];
$name = $_POST['name'];
$kode_otp = $_POST['kode_otp'];

function push_register($url, $has, $domain, $locationid, $name, $kode_otp)
{
   
    $postData = array(
        'has' => $has,
        'domain' => $domain,
        'locationid' => $locationid,
        'name' => $name,
        'kode_otp' => $kode_otp
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

$url = $base_url.'/store/register/sync_register.php';

$hasil = push_register($url, $has, $domain, $locationid, $name, $kode_otp);

$j_hasil = json_decode($hasil, true);



if($j_hasil['status'] == 'FAILED'){
    $json = array(
        "status" => "FAILED",
        "message" => $j_hasil['message'],
    );
}else{

    $s = array();

    $has = $j_hasil['data']['has'];
    $domain = $j_hasil['data']['domain'];
    $locationid = $j_hasil['data']['locationid'];
    $name = $j_hasil['data']['name'];

    $values = implode(", ", $s);

    $jum = 0;
    $check = $connec->query("select count(pos_mcashier_key) jum from pos_mcashier where code = '" . $has . "'");
    foreach ($check as $row) {
        $jum = $row["jum"];
    }

    if ($jum > 0) {
        $qqq = " UPDATE pos_mcashier SET isactived = '1', insertdate = '" . date('Y-m-d H:i:s') . "', insertby = 'SYSTEM', 
    postby = 'SYSTEM', postdate = '" . date('Y-m-d H:i:s') . "', name = '" . $name . "', description = '', 
    ad_morg_key = '" . $locationid . "' WHERE code = '" . $has . "'";
    } else {
        $qqq = "INSERT INTO pos_mcashier (ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, name, 
    description, code) VALUES ('". $ad_mclient_key."', '" . $locationid . "', '1', '" . date('Y-m-d H:i:s') . "', 'SYSTEM', 'SYSTEM', '" . date('Y-m-d H:i:s') . "', 
    '" . $name . "', '', '" . $has . "')";
    }


    $statement = $connec->prepare($qqq);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        $json = array(
            "status" => "OK",
            "message" => "Registrasi Berhasil",
        );
    } else {
        $json = array(
            "status" => "FAILED",
            "message" => "Data Not Inserted, Query = " . $qqq,
        );
    }

}



echo json_encode($json);
?>
