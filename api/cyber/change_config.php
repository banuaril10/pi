<?php include "../../config/koneksi.php";
$get_nama_toko = "select name from ad_morg where isactived = 'Y' ";
$resultss = $connec->query($get_nama_toko);
foreach ($resultss as $r) {
    $storename = $r["name"];
}

function get_id_location_by_name($namestore, $url)
{
    $data = array("namestore" => $namestore);

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
            CURLOPT_POSTFIELDS => $data
        )
    );

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}


$url = $base_url . '/store/profile/get_id_location.php';

$hasil = get_id_location_by_name($storename, $url);
$j_hasil = json_decode($hasil, true);

// print_r($hasil);

$id_location = $j_hasil[0]['id_master_location'];

// print_r($id_location);

if ($id_location != "") {

    $json_config = '{
        "domain": "",
        "organization": "' . $id_location . '"
    }';
    echo $json_config;
}else{
    echo "error";
}





?>