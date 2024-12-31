<?php session_start();
include "koneksi.php";
$username = $_POST['user'];
$pwd = hash_hmac("sha256", $_POST['pwd'], 'marinuak');

$sql = "select * from ad_muser where userid ='".$username."' and userpwd ='".$pwd."' limit 1";

$result = $connec->query($sql);

$rows = $result->rowCount();

$number_of_rows = $result->fetchColumn(); 
if($rows > 0){
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

			header("Location: ../content.php?".$_SESSION["username"]);
	}
}else{

			header("Location: ../index.php?pesan=Username/pass salah");
}


	

?>