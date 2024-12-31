<?php session_start();
if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
	$org_key = $_SESSION['org_key'];
	$username = $_SESSION['username'];
}else{
	
	header("Location: index.php");
}

$get_nama_toko = "select * from ad_morg where postby = 'SYSTEM'";
$resultss = $connec->query($get_nama_toko);
foreach ($resultss as $r) {
	$storecode = $r["value"];	
	$org_key = $r['ad_morg_key'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Apps</title>

    <link rel="stylesheet" href="styles/css/bootstrap.css">
    <link rel="stylesheet" href="styles/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="styles/css/selectize4.css">
    <link rel="stylesheet" href="styles/css/font-awesome.css">
	
	<style>
		.selectize {
			
			border-color: #000;
			margin-bottom: 10px;
			
		}
	</style>

    <link rel="stylesheet" href="assets/vendors/iconly/bold.css">

    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/css/app.css">
	<script src="styles/js/jquery-3.5.1.js"></script>	
</head>
<body>