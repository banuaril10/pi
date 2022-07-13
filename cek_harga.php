<?php include "config/koneksi.php"; ?>
<?php include "components/main.php"; ?>
<?php include "components/sidebar.php"; ?>
<div id="overlay">
			<div class="cv-spinner">
				<span class="spinner"></span>
			</div>
		</div>
<div id="app">
<div id="main">



<header class="mb-3">
	<a href="#" class="burger-btn d-block d-xl-none">
		<i class="bi bi-justify fs-3"></i>
	</a>
</header>
<?php include "components/hhh.php"; ?>

<!------ CONTENT AREA ------->
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4>CEK HARGA</h4>
			</div>
			
			
			
			<div class="card-body">
			<div class="tables">	
			<p id="notif" style="color: red; font-weight: bold"></p>
			<div id="qr-reader" style="width: 100%"></div>
			
				<div class="table-responsive bs-example widget-shadow">	
				


		
				
				<!--<input type="text" id="search" class="form-control" id="exampleInputName2" placeholder="Search">-->
				
			<table class="table table-striped table-bordered table-sm">
            <thead>
              <tr>
               <!-- <th scope="col">No</th>-->
                <th scope="col">SKU</th>
                <th scope="col">Nama</th>
                <!--<th scope="col">Harga</th>
                <th scope="col">Harga Diskon</th>-->
              </tr>
            </thead>
            <tbody>
              <!-- List Data Menggunakan DataTable -->             
            </tbody>
          </table>
					
					
					
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
<script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>

<script type="text/javascript">
function onScanSuccess(decodedText, decodedResult) {
    console.log(`Code scanned = ${decodedText}`, decodedResult);
	// document.querySelector('input[type="search"]').value = decodedText;
	getItems(decodedText);
}
var html5QrcodeScanner = new Html5QrcodeScanner(
	"qr-reader", { fps: 10, qrbox: 250 });
html5QrcodeScanner.render(onScanSuccess);


function getItems(sku){
	
	$.ajax({
		url: "api/action.php?modul=inventory&act=get_items",
		type: "POST",
		data : {sku: sku},
		beforeSend: function(){
			$('#notif').html("Proses mencari items..");
		},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);
			if(dataResult.result == 0){
				
				$('#notif').html("<font style='color: red'>Items tidak ditemukan</font>");
			}else{
				
				$('#notif').html("<font style='color: green'>"+dataResult.sku+" ("+dataResult.name+") Harga : <font style='color: red'>"+dataResult.price_discount+"</font></font>");
			}

				

			
		}
	});
	
}


  $(function(){
 
           $('.table').DataTable({
              "processing": true,
              "serverSide": true,
              "ajax":{
                       "url": "api/action.php?modul=inventory&act=api_datatable",
                       "dataType": "json",
                       "type": "POST"
                     },
              "columns": [
                  // { "data": "no" },
                  { "data": "sku" },
                  { "data": "name" },
                  // { "data": "price" },
                  // { "data": "price_discount" },
              ]  
 
          });
        });
</script>
</div>
<?php include "components/fff.php"; ?>