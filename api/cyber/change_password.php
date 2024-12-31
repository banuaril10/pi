<?php include "../../config/koneksi.php";

$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}

$oldpwd = $_GET['oldpwd'];
$newpwd = $_GET['newpwd'];
$userid = $_GET['userid'];
function change_password($url)
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
$url = $base_url.'/store/users/change_password.php?oldpwd=' . $oldpwd . '&newpwd=' . $newpwd . '&userid=' . $userid;

$hasil = change_password($url);
$j_hasil = json_decode($hasil, true);

// print_r($j_hasil);
// die();

if ($j_hasil['status'] == 'OK') {
    $newpwd_hash = hash_hmac("sha256", $newpwd, 'marinuak');
    $sql = "update ad_muser set userpwd = :newpwd where userid = :userid ";
    $stmt = $connec->prepare($sql);
    $stmt->bindParam(':newpwd', $newpwd_hash);
    $stmt->bindParam(':userid', $userid);
    $stmt->execute();
    
    $json = array(
        'status' => 'OK',
        'message' => $j_hasil['message']
    );
} else {
    $json = array(
        'status' => 'FAILED',
        'message' => $j_hasil['message']
    );
}


echo json_encode($json);
?>
