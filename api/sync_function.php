<?php include "../config/koneksi.php";
$connec->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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


$url = $base_url . '/store/function/get_function.php?idstore=' . $idstore;

$hasil = get($url);
$j_hasil = json_decode($hasil, true);

// print_r($j_hasil);

$items_updated = 0;
$s = array();


try {
	foreach ($j_hasil as $key => $value) {

		$name = $value['name'];
		$fungsi = $value['fungsi'];
		
		//run with prepare pdo
		$script = $fungsi;
		$run = $connec->prepare($script);
		$run->execute();

	}
	$json = array(
		"status" => "SUCCESS",
		"msg" => "function has been updated",
		"data" => $update
	);

	echo json_encode($json);
} catch (PDOException $e) {
	echo $e->getMessage();
}


?>