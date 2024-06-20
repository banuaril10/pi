<?php session_start();
include "koneksi.php";
$username = $_POST['user'];
$pwd = hash_hmac("sha256", $_POST['pwd'], 'marinuak');

$sql = "select userid, username, ad_org_id, name from m_pi_users where userid ='".$username."' 
and userpwd ='".$pwd."' and isactived = '1' group by userid,username,ad_org_id, name  limit 1";

$result = $connec->query($sql);

$rows = $result->rowCount();

$number_of_rows = $result->fetchColumn(); 
if($rows > 0){

	foreach ($connec->query($sql) as $row) {
			$_SESSION['userid'] = $row["userid"];
			$_SESSION['username'] = $row["username"];
			$_SESSION['org_key'] = $row["ad_org_id"];
			$_SESSION['name'] = $row["name"];
			
			if($row["name"] == 'Audit' || $row["name"] == 'IC'){
				
				$_SESSION['role'] = "Global";
			}else{
				$_SESSION['role'] = "Daily";
			}
			
			
			$sqll = "select value from ad_morg where postby = 'SYSTEM'";

			$results = $connec->query($sqll);
			
			
			foreach ($results as $r) {
				$_SESSION['kode_toko'] = $r["value"];
				
			}

			
			// echo $row["userid"];
		
			// setcookie("userid",$row["userid"],time() + (10 * 365 * 24 * 60 * 60));
			// setcookie("username",$row["username"],time() + (10 * 365 * 24 * 60 * 60));
			// setcookie("org_key",$row["ad_morg_key"],time() + (10 * 365 * 24 * 60 * 60));
			
			if($row["ad_org_id"] == '112233445566'){
				
				header("Location: ../cek_harga.php?".$_SESSION["username"]);
			}else if($row["name"] == 'Cashier'){
				header("Location: ../cashier.php?".$_SESSION["username"]);
				
			}else if($row["name"] == 'Promo'){
				header("Location: ../cek_promo.php?".$_SESSION["username"]);
				
			}else{
				
				header("Location: ../content.php?".$_SESSION["username"]);
			}
			
			
	}
	
}else{
	
	
			// echo $sql; 
			// echo $rows; 
			header("Location: ../index.php?pesan=Username/pass salah");
		}


	

?>