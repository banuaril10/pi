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
$url = $base_url.'/store/users/get_users_hris.php?idstore='.$idstore;

// echo $idstore;

$hasil = get_category($url);
$j_hasil = json_decode($hasil, true);

$s_user = array();
$s_spv = array();
foreach ($j_hasil as $key => $value) {
    
    $nik = $value['nik'];
    $nama = $value['nama'];

    $s_user[] = "('".$nik."', '".$nama."')";
}

$msg = "Data User Failed";
$msg_spv = "Data SPV Failed";

if(count($s_user) > 0){
    $truncate = "TRUNCATE m_pi_hris";
    $statement = $connec->prepare($truncate);
    $statement->execute();

    $values = implode(", ", $s_user);
    $insert = "insert into m_pi_hris (nik, nama) VALUES " . $values . ";";
                                                                                        
    $statement = $connec->prepare($insert);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        $msg = "Data User Inserted";
    } else {
        $msg = "Data User Failed, Q = ".$insert;
    }
}

$json = array(
    "status" => "OK",
    "message" => $msg.', '. $msg_spv
);

echo json_encode($json);
?>
