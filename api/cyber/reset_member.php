<?php 
include "../../config/koneksi.php";


if($_GET['id'] == '9aa0f83d-f03a-4650-91ae-c07fac5613fa'){
	$ll = "truncate table pos_mmember";
	$query = $connec->query($ll);


	$json = array(
		"status" => "OK",
		"message" => 'Berhasil truncate'
	);

	echo json_encode($json);
}


?>
