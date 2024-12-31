<?php include "../../config/koneksi.php";

$ll = "select * from ad_morg where isactived = 'Y'";
$query = $connec->query($ll);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $idstore = $row['ad_morg_key'];
}
function get($url)
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


$url = $base_url.'/store/profile/get_profile.php?idstore='. $idstore;

$hasil = get($url);
$j_hasil = json_decode($hasil, true);


foreach ($j_hasil as $key => $value) {
    $ad_mclient_key = '';
    $isactived = 'Y';
    $insertdate = date('Y-m-d H:i:s');
    $insertby = 'SYSTEM';
    $postby = 'SYSTEM';
    $postdate = date('Y-m-d H:i:s');
    $name = $j_hasil[0]['location_name'];
    $description = 'PKP';
    $value = $j_hasil[0]['location'];
    $ppn = '1';
    $address1 = $j_hasil[0]['address1'];
    $address2 = $j_hasil[0]['address2'];
    $address3 = $j_hasil[0]['address3'];
    $addressdonasi = $j_hasil[0]['addressdonasi'];
    $isqty = $j_hasil[0]['isqty'];
    $note1 = $j_hasil[0]['note1'];
    $note2 = $j_hasil[0]['note2'];
    $note3 = $j_hasil[0]['note3'];

    if ($isqty == 0) {
        $update = "UPDATE ad_morg
        SET name='".$name."', value='". $value."', address1='". $address1."', 
        address2='" . $address2 . "', address3='" . $address3 . "', addressdonasi='" . $addressdonasi . "', isqty=false, note1='" . $note1 . "', 
        note2='" . $note2 . "', note3='" . $note3 . "'";
    } else {
        $update = "UPDATE ad_morg
        SET name='" . $name . "', value='" . $value . "', address1='" . $address1 . "', 
        address2='" . $address2 . "', address3='" . $address3 . "', addressdonasi='" . $addressdonasi . "', isqty=true, note1='" . $note1 . "', 
        note2='" . $note2 . "', note3='" . $note3 . "'";
    }
}

$statement = $connec->prepare($update);
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
        "message" => "Data Not Inserted, Query = ". $update,
    );
}

echo json_encode($json);
?>
