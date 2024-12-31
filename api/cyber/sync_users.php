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
$url = $base_url.'/store/users/get_users.php?idstore='.$idstore;

// echo $idstore;

$hasil = get_category($url);
$j_hasil = json_decode($hasil, true);

$s_user = array();
$s_spv = array();
foreach ($j_hasil as $key => $value) {
    
    $id_user = $value['id_user'];
    $id_role = $value['id_role'];
    $fullname = $value['fullname'];
    $username = $value['username'];
    $password = $value['password'];
    $accesscode = $value['accesscode'];
    $unicode = $value['unicode'];

    $s_user[] = "('".$id_user."', '".$ad_mclient_key."', '".$idstore."', '1', '".date('Y-m-d H:i:s')."', 'SYSTEM', 'SYSTEM', '".date('Y-m-d H:i:s')."', '".$id_role."', '".$username."', 
    '".$fullname."', '".$password."', '1')";

    if($accesscode != "" && $unicode != ""){
        $s_spv[] = "('" . $ad_mclient_key . "', '" . $idstore . "', '1', '" . date('Y-m-d H:i:s') . "', 'SYSTEM', 'SYSTEM', '" . date('Y-m-d H:i:s') . "', '" . $id_user . "','" . $accesscode . "', '" . $unicode . "')";
    }
}

$msg = "Data User Failed";
$msg_spv = "Data SPV Failed";

if(count($s_user) > 0){
    $truncate = "TRUNCATE ad_muser";
    $statement = $connec->prepare($truncate);
    $statement->execute();

    $values = implode(", ", $s_user);
    $insert = "insert into ad_muser (ad_muser_key, ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, ad_mrole_key, userid, 
    username, userpwd, status) VALUES " . $values . ";";

    $statement = $connec->prepare($insert);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        $msg = "Data User Inserted";
    } else {
        $msg = "Data User Failed, Q = ".$insert;
    }
}


if(count($s_spv) > 0){
    $truncate = "TRUNCATE pos_msupervisor";
    $statement = $connec->prepare($truncate);
    $statement->execute();

    $values = implode(", ", $s_spv);
    $insert = "insert into pos_msupervisor (ad_mclient_key, ad_morg_key, isactived, insertdate, insertby, postby, postdate, ad_muser_key, accesscode, accessuniq) VALUES " . $values . ";";

    $statement = $connec->prepare($insert);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    if ($result) {
        $msg_spv = "Data SPV Inserted";
    } else {
        $msg_spv = "Data SPV Failed, Q = " . $insert;
    }
}

$json = array(
    "status" => "OK",
    "message" => $msg.', '. $msg_spv
);

echo json_encode($json);
?>
