<?php include "config/koneksi.php"; 
function rupiah($angka){
	
	$hasil_rupiah = number_format($angka,0,',','.');
	return $hasil_rupiah;
 
}

		$price = "No Price";
		$name = "Not Found";
		$get = $connec->query("select name, price from pos_mproduct where sku = '".$_POST['sku']."'");
		foreach($get as $r){
			$price = $r['price'];
			$name = $r['name'];
		}
		
		echo $name.'|'.rupiah($price);	
		
?>


