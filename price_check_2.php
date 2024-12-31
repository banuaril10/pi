<?php include "config/koneksi.php"; ?>
<!DOCTYPE html>
<html>
<head>
<style>
.item1 { grid-area: header; }

.grid-container {
  border-color: #000;
  background-color: #fff;
  padding: 10px;
  margin: auto;
  font-family: "Lucida Console", "Courier New", monospace;
  font-size : 30px
}
.strikeout {
  line-height: 1em;
  position: relative;
  color: red;
}
.strikeout::after {
  border-bottom: 0.125em solid red;
  content: "";
  left: 0;
  margin-top: calc(0.125em / 2 * -1);
  position: absolute;
  right: 0;
  top: 50%;
}

</style>
</head>
<body id="appin">


<div class="grid-container">
 
 <center><img src="images/idolmart.png" style="width:120px"> </img><br>
 
 
 <form action="price_check_2.php" method="GET">
 
	<input type="text" name="barcode" autofocus >
 
 </form>
 
<?php
include "config/koneksi.php"; 
function rupiah($angka){
	$hasil_rupiah = number_format($angka,0,',','.');
	return $hasil_rupiah;
}

		$price = "No Price";
		$name = "Not Found";
		
		if($_GET['barcode'] != ""){
			$get = $connec->query("select sku, name, price from pos_mproduct where sku = '".$_GET['barcode']."' or barcode = '".$_GET['barcode']."'");
			foreach($get as $r){
				$hardisk = 0;
				$diskon = $connec->query("select discount from pos_mproductdiscount where sku = '".$r['sku']."' and date(now()) between fromdate and todate ");
				foreach($diskon as $rd){
					$hardisk = $rd['discount'];
				}
				$pricenormal = $r['price'];
				$pricedisk = "";
				$priceend = $r['price'] - $hardisk;
				
				if($hardisk > 0){
					$pricedisk = '<font class="strikeout">Rp. '.rupiah($pricenormal).'</font><br>';
				}
				
				$name = $r['name'];
			}
			
	
			if($name != "Not Found"){
				echo '<center>
				<font id="barang"> '.$name.' </font>
				<br>
				
				'.$pricedisk.'
				
				<font style="font-size: 30px" id="price"> Rp. '.rupiah($priceend).'</font>
				</center>';
			}else{
				echo "Items tidak ditemukan";
			}
		}else{
			
			echo "Scan items dibawah";
			echo "<br><img src='images/down.gif' style='width: 100px'>";
		}
		
		
		
 
 ?>
 
 </center>

</div>


<script src="styles/js/jquery-3.6.0.js"></script>
<script>
var elem = document.getElementById("appin");
function openFullscreen() {
  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.webkitRequestFullscreen) { /* Safari */
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) { /* IE11 */
    elem.msRequestFullscreen();
  }
}

openFullscreen();

function search(ele) {
    if(event.key === 'Enter') {
        getData();        
    }
}

function getData(){
	var sku = $("#sku").val();
	$.ajax({
		url: "price_check_sku.php",
		type: "POST",
		data: {sku: sku},
		beforeSend: function(){
			$('#notif').html("Proses cek harga..");
		},
		success: function(dataResult){
			const myArray = dataResult.split("|");
			$("#barang").html(myArray[0]);
			$("#price").html(myArray[1]);
			$("#sku").val("");
		
		}
	});
}


</script>

</body>
</html>


