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


$url = $base_url . '/store/version/get_version.php?idstore=' . $idstore;

$hasil = get($url);
$j_hasil = json_decode($hasil, true);
$items_updated = 0;
$s = array();
foreach ($j_hasil as $key => $value) {

    $version = $value['version'];
    $updatedate = $value['updatedate'];
    $link = $value['link_win'];
    $link_linux = $value['link_linux'];
    //update pos_mproduc
}

$json = array(
    "version" => $version,
    "updatedate" => $updatedate,
    "link" => $link,
    "link_linux" => $link_linux,
    "items_updated" => $items_updated,
);

echo json_encode($json);
?>