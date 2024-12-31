<?php 
include "../../config/koneksi.php";

$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}
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


$jum = 0;
$check = $connec->query("select count(ad_morg_key) jum from ad_morg where ad_morg_key = '" . $locationid . "'");
foreach ($check as $row) {
    $jum = $row["jum"];
}

if ($jum == 0) {
    $url = $base_url . '/store/users/get_users_it.php';

    // echo $idstore;

    $hasil = get_category($url);
    $j_hasil = json_decode($hasil, true);

    $json = array(
        "status" => "OK",
        "data" => $j_hasil
    );

    echo json_encode($json);
}else{

    $id_user = "pos";
    $id_location = $idstore;
    $insertdate = date('Y-m-d H:i:s');
    $insertby = "pos";
    $postby = "pos";
    $postdate = date('Y-m-d H:i:s');
    $id_role = "2f9f5a6a-d183-4887-a1a0-8efca0800e9e";
    $fullname = "POS";
    $username = "pos";
    $avatar = "pos";
    $password = "pos";
    $email = "pos";
    $phone = "pos";
    $description = "pos";
    $is_active = "Y";
    $accesscode = "pos";
    $unicode = "pos";
    $auth_key = "pos";
    $access_token = "pos";


    $myarray = array();
    $myarray[] = array(
        'id_user' => $id_user,
        'id_location' => $idstore,
        'insertdate' => $insertdate,
        'insertby' => $insertby,
        'postby' => $postby,
        'postdate' => $postdate,
        'id_role' => $id_role,
        'fullname' => $fullname,
        'username' => $username,
        'avatar' => $avatar,
        'password' => $password,
        'email' => $email,
        'phone' => $phone,
        'description' => $description,
        'is_active' => $is_active,
        'accesscode' => $accesscode,
        'unicode' => $unicode,
        'auth_key' => $auth_key,
        'access_token' => $access_token
    );

    $jsonData = json_encode($myarray);
    $j_hasil = json_decode($jsonData, true);

    $json = array(
        "status" => "OK",
        "message" => $j_hasil,
    );
    echo json_encode($json);
}





?>
