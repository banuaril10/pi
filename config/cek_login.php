<?php session_start();
include "koneksi.php";
$username = $_POST['user'];
$pwd = hash_hmac("sha256", $_POST['pwd'], 'marinuak');

$sql = "select * from ad_muser where userid ='" . $username . "' and userpwd ='" . $pwd . "' limit 1";

$result = $connec->query($sql);

$rows = $result->rowCount();

$number_of_rows = $result->fetchColumn();

//select ad_morg_key, value from ad_morg limit 1
$getOrg = "select ad_morg_key, value from ad_morg limit 1";
$orgResult = $connec->query($getOrg);
foreach ($orgResult as $orgRow) {
	$org_key = $orgRow["ad_morg_key"];
	$kode_toko = $orgRow["value"];
}





//tambahin password user static untuk admin : user : edp , pass : edpidolmart2026
if ($username === 'edp' && $_POST['pwd'] === 'edpidolmart2026') {
	$_SESSION['userid'] = 'edp';
	$_SESSION['username'] = 'edp';
	$_SESSION['org_key'] = $org_key; // Ganti dengan org_key yang sesuai
	$_SESSION['name'] = 'Administrator';
	$_SESSION['role'] = 'admin';
	$_SESSION['kode_toko'] = $kode_toko; // Ganti dengan kode_toko yang sesuai

	header("Location: ../content.php?edp");
	exit();
}



if ($rows > 0) {
	foreach ($connec->query($sql) as $row) {
		$_SESSION['userid'] = $row["userid"];
		$_SESSION['username'] = $row["username"];
		$_SESSION['org_key'] = $row["ad_morg_key"];
		$_SESSION['name'] = $row["ad_mrole_key"];
		$_SESSION['role'] = $row["ad_mrole_key"];


		$sqll = "select value from ad_morg ";

		$results = $connec->query($sqll);

		foreach ($results as $r) {
			$_SESSION['kode_toko'] = $r["value"];
		}

		header("Location: ../content.php?" . $_SESSION["username"]);
	}
} else {

	header("Location: ../index.php?pesan=Username/pass salah");
}




?>