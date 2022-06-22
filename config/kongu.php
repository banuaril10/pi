<?php error_reporting(0);
 
// $koneksi = mysqli_connect("localhost","root","","ticket");
$koneksi =  mysqli_connect("localhost","outeruser","%^$#^7434Gadw..","idolmart_idmticket");
 
// Check connection
if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}
 
 
if($_COOKIE['nik'] == ''){
	
	header("Location: login.php");
	
} 
 
 
?>