<?php include "config/koneksi.php"; ?>
<?php include "components/main.php"; ?>
<?php include "components/sidebar.php"; ?>
<style>
.grid-container {
  display: grid;
  grid-template-columns: auto auto auto auto;
  background-color: #2196F3;
  padding: 10px;
}
.grid-item {
  background-color: rgba(255, 255, 255, 0.8);
  border: 1px solid rgba(0, 0, 0, 0.8);
  padding: 20px;
  font-size: 30px;
  text-align: center;
}
</style>
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
				<h4>HARGA REGULER <?php echo $_GET['rak']; ?></h4> <button type="button" class="btn btn-primary" id="sync" onclick="syncMaster();">Sync Rack</button>
			</div>
			<div class="card-body">
			<div class="tables">			
				<div class="table-responsive bs-example widget-shadow">	
				<p id="notif1" style="color: red; font-weight: bold"></p>		

<input type="hidden" id="price" value="45px"  placeholder="Ukuran Font Harga">
				<!--<input type="text" id="left1" value="10px"  placeholder="Margin 1 ">
				<input type="text" id="left2" value="235px"  placeholder="Margin 2 ">
				<input type="text" id="left3" value="465px"  placeholder="Margin 3 ">
				<input type="text" id="left4" value="700px" placeholder="Margin 4 ">-->
				
				 <table>
 
				
				<tr>
					<!--<td><center><button style="width:200px; background: #22ad3a;" type="button" id="btn-load"><div class="item1">Load Data Product</div></button></td>-->
					<td style="display: none"><center><button class="btn btn-primary" id="btn-cetak"><div class="item1"><b>CETAK BESAR DOT </b></div></button></center></td>
					<td style="display: none"><center><button class="btn btn-danger" type="button" id="btn-cetak1"><div class="item1"><b>CETAK KECIL DOT </b></div></button></center></td>
					
					<td style="display: none"><center><button class="btn btn-warning" type="button" id="btn-cetak1-tinta"><div class="item1"><b>CETAK HARGA REGULER (Uk. KECIL)</b></div></button></center></td>
					<td style="display: none"><center><button class="btn btn-warning" type="button" id="btn-cetak3-tinta"><div class="item1"><b>CETAK HARGA PROMO (Uk. KECIL)</b></div></button></center></td>
					<!--<td><center><button style="width:200px" type="button" id="btn-submit"><div class="item1">Setup Data Toko</div></button></center></td>-->
					
					
					
				</tr>
				
				

				</table>
				
			
		
				<?php 
		if($_GET['rak'] && !empty($_GET['rak'])){

				$rak = $_GET['rak'];
				

        }else{

				$rak = "all";
				
			} 
			
			
			if($_GET['stock'] && !empty($_GET['stock'])){
			$stock = $_GET['stock'];
			if($stock == "all"){
				
				$value = "Semua Stock";
				
			}else{
				
				
				$value = "Stock > 0";
			}
				
				

			}else{

				$stock = "all";
				$value = "Semua Stock";
			}
			
			
			?>
			
				
			<form action="" method="GET">
				
			 <table>
 
 
				<tr>
					<td><select id="rak" name="rak" class="form-control text-search">
				<option value="">Pilih Rack</option>
					<?php 
						$rack = "select rack_name from inv_mproduct group by rack_name order by rack_name";
						$no = 1;
						foreach ($connec->query($rack) as $sr) { ?>
							<option value="<?php echo $sr['rack_name']; ?>"><?php echo $sr['rack_name']; ?></option>
							
						
						<?php } ?>
					
				</select></td>
				<td><select id="rak" name="stock" class="form-control text-search">
				
							
							<option value="<?php echo $stock; ?>"><?php echo $value; ?></option>
							<option value="ada">Stock > 0</option>
							<option value="all">Semua Stock</option>
				</select></td>
				<td>
					<button class="btn btn-success" type="submit" >Cari</button>
					<a class="btn btn-primary" href="mitems.php">Reset Filter</a>
				</td>
				</tr>
			</table>	
			</form>	
				

				<p style="color: red; font-weight: bold">Satu kertas terdiri dari 32 tag, untuk mencari items ketik CTRL + F</p>
				Pilih Brand : <select id="brand">
					<option value="IDOLMART">IDOLMART</option>
					<option value="IDOLAKU">IDOLAKU</option>
					<option value="UNIQU">UNIQU</option>
				</select>
				<button class="btn btn-danger" type="button" id="btn-cetak-tinta"><div class="item1"><b>CETAK HARGA REGULER</b></div></button>
					<button class="btn btn-success" type="button" id="btn-cetak-tinta-pdf"><div class="item1"><b>CETAK PLANOGRAM</b></div></button>
				<br>
				 <input class="btn btn-primary" type="button" id="checkall" value="Check All"/>
				 <input class="btn btn-danger" type="button" id="uncheckall" value="Uncheck All"/>
				 <br>
				 <br>
				
				<!--<input type="text" id="search" class="form-control" id="exampleInputName2" placeholder="Search">-->
					<!--<input id="myInput" type="text" placeholder="Search..">-->
				
					<table class="table table-bordered table-hover" id="example">
						<thead>
							<tr>
								<th></th>
								<th>No</th>
								<th>SKU</th>
								<th>Barcode</th>
								<th>Name</th>
								<th>Price</th>
								<th>Rack Name</th>
							</tr>
						</thead>
						<tbody>
						
						<?php 
						
						
						// $sql_list = "select date(now()) as tgl_sekarang, a.sku, a.name ,b.rack_name, a.price, a.barcode from pos_mproduct a left join inv_mproduct b on a.sku = b.sku ";
						
						// if($_GET['rak'] && !empty($_GET['rak'])){
							
							// $sql_list .= "where b.rack_name = '".$_GET['rak']."'";
							
						// }
						
						// $sql_list .= " order by a.name";
						
						
						
						// $no = 1;
						// foreach ($connec->query($sql_list) as $row) {
							// $disk = 0;
							// $cek_disc = "select discount from pos_mproductdiscount where todate > '".date('Y-m-d')."' and sku = '".$row['sku']."'";
							// foreach ($connec->query($cek_disc) as $row_dis) {
								
								// $disk = $row_dis['discount'];
							// }
							// $harga_last = $row['price'] - $disk;
							
						?>
						
						
							<!--<tr>
								<td><input type="checkbox" id="checkbox" name="checkbox[]" value="<?php echo $row['sku']; ?>|<?php echo $row['name']; ?>|<?php echo $row['price']; ?>|<?php echo $row['tgl_sekarang']; ?>|<?php echo $row['rack_name']; ?>|<?php echo $row['shortcut']; ?>|<?php echo $harga_last; ?>"></td>
								<td scope="row"><?php echo $no; ?></td>
								<td><?php echo $row['sku']; ?></td>
								<td><?php echo $row['barcode']; ?></td>
								<td><?php echo $row['name']; ?></td>
								<td><?php echo $row['price']; ?></td>
								
								<td><?php echo $row['rack_name']; ?></td>
								
							</tr>-->
							
							
							
							
						<?php 
						// $no++;} 
						?>
   
   
						</tbody>
					</table>
					
					
					
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>

<script>
// $(document).ready(function(){
  // $("#myInput").on("keyup", function() {
    // var value = $(this).val().toLowerCase();
    // $("#example tr").filter(function() {
      // $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    // });
  // });
// });
</script>
<script type="text/javascript">
			// $('#checkall').click(function(e){
				// var table= $(e.target).closest('table');
				// $('td input:checkbox',table).prop('checked',this.checked);
			// });

// var $rows = $('#example tr');
// $('#search').keyup(function() {
    // var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
    
    // $rows.show().filter(function() {
        // var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        // return !~text.indexOf(val);
    // }).hide();
// });



function syncMaster(){
	

	
	$.ajax({
		url: "api/action.php?modul=inventory&act=sync_inv",
		type: "GET",
		beforeSend: function(){
			 // $('#sync').prop('disabled', true);
			$('#notif1').html("<font style='color: red'>Sedang melakukan sync, mohon tunggu..</font>");
			$("#overlay").fadeIn(300);
		},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);
			if(dataResult.result=='1'){
				location.reload();
			}
			
		}
	});
	
}


$(document).ready( function () {


	 $('.table').DataTable({
              "processing": true,
              "serverSide": true,
			  "lengthMenu": [1000, 2000, 3000, 4000, 5000, 10000],
			  "searching": false,
              "ajax":{
                       "url": "api/action.php?modul=inventory&act=api_pricetag",
                       "dataType": "json",
                       "type": "POST",
					   "data": {
						   "rak":"<?php echo $rak; ?>",
						   "stock":"<?php echo $stock; ?>",
					   }
                     },
              "columns": [
                  { "data": "check" },
                  { "data": "no" },
                  { "data": "sku" },
                  { "data": "barcode" },
                  { "data": "name" },
                  { "data": "price" },
                  { "data": "rack_name" },
              ]  
 
          });
} );

// document.getElementById("search").addEventListener("keyup", function() {
// var input, filter, table, tr, td, i, txtValue;
  // input = document.getElementById("search");
  // filter = input.value.toUpperCase();
  // table = document.getElementById("example");
  // tr = table.getElementsByTagName("tr");
  // for (i = 0; i < tr.length; i++) {
    // td = tr[i].getElementsByTagName("td")[3];
    // td1 = tr[i].getElementsByTagName("td")[4];
    // td2 = tr[i].getElementsByTagName("td")[5];
    // td3 = tr[i].getElementsByTagName("td")[7];
    // if (td) {
      // txtValue = td.textContent || td.innerText;
      // txtValue1 = td1.textContent || td1.innerText;
      // txtValue2 = td2.textContent || td2.innerText;
      // txtValue3 = td3.textContent || td3.innerText;
      // if (txtValue.toUpperCase().indexOf(filter) > -1) {
        // tr[i].style.display = "";
      // }else if(txtValue1.toUpperCase().indexOf(filter) > -1){
		// tr[i].style.display = "";  
	  // }else if(txtValue2.toUpperCase().indexOf(filter) > -1){
		// tr[i].style.display = "";  
	  // }else if(txtValue3.toUpperCase().indexOf(filter) > -1){
		// tr[i].style.display = "";  
	  // } else {
        // tr[i].style.display = "none";
      // }
    // }       
  // }
	
	
	
// });


function formatRupiah(angka, prefix){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
		}
		
		
		
		
			document.getElementById("btn-cetak").addEventListener("click", function() { //cetak besar
				var array = [];
				var checkboxes = document.querySelectorAll('input[type=checkbox]:checked');
				

				
				for (var i = 0; i < checkboxes.length; i++) {
					array.push(checkboxes[i].value)
				}
				let text = "<div style='position: relative; width: 920px; height: 151px; padding-top: 20px; '>";
				var x = 1;
				for (let i = 0; i < array.length; i++) {
						var res = array[i].split("|");
						
						if(res[5] === 'undefined' || res[5] === 'null'){
							
							var sc = '';
						}else{
							var sc = res[5];
						}
						
						var price = document.getElementById("price").value;


						text += "<div style='text-align:left; position: absolute; display: inline-block; border: 1px; color: black; width: 202px; height: 151px; font-family: Calibri'><label style='text-align: right; font-size: 14px'><b>"+res[1].toUpperCase()+"</b></label><br><label style='text-align: left; font-size: 14px'><b>Rp </b></label><label style='text-align: left; font-size: "+price+"'><b>"+formatRupiah(res[2], '')+"</b></label><br><label style='text-align: left; font-size: 14px'>"+res[0]+" | "+sc+"</label><br><label style='text-align: left; font-size: 14px'>"+res[3]+"</label> | <label style='text-align: left; font-size: 14px'>"+res[4].toUpperCase()+"</label></div>";
						
						if((i+1)%4==0 && i!==0){
							
							text += "</div><div style='position: relative;width: 920px; height: 151px; padding-top: 0'>";
						}
						x++;

					}
			
				text += "</table>";
					

				  var mywindow = window.open('', 'my div', 'height=600,width=800');
							/*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
							mywindow.document.write('<style>@media print{@page {size: potrait; width: 216mm;height: 280mm;}}</style>');
							mywindow.document.write(text);

					
							mywindow.print();
							// mywindow.close();
					
							return true;
			});

			document.getElementById("btn-cetak1").addEventListener("click", function() { //cetak besar
				var array = [];
				var checkboxes = document.querySelectorAll('input[type=checkbox]:checked');
				
				for (var i = 0; i < checkboxes.length; i++) {
					array.push(checkboxes[i].value)
				}
				let text = "<div style='position: relative; width: 920px; height: 151px; padding-top: 15px;  border: 1px solid #000;'>";
				var x = 1;
				for (let i = 0; i < array.length; i++) {
						var res = array[i].split("|");
						
						if(res[5] === 'undefined' || res[5] === 'null'){
							
							var sc = '-';
						}else{
							var sc = res[5];
						}
						
						// if(i == 0){
							// text += "";
						// }
						if(x==5){
							var x = 1;
						}
					
				

						text += "<div style='text-align:left; position: absolute; display: inline-block; color: black; width: 202px; height: 151px; font-family: Calibri'><label style='text-align: right; font-size: 12px'>"+res[1].toUpperCase()+"</label><br><label style='text-align: left; font-size: 10px'><b>Rp </b></label><label style='text-align: left; font-size: 18px'><b>"+formatRupiah(res[2], '')+"</b></label><br><label style='text-align: left; font-size: 10px'>"+res[0]+"<br>"+sc+"</label><br><label style='text-align: left; font-size: 10px'>"+res[3]+"</label><br><label style='text-align: left; font-size: 10px'>"+res[4].toUpperCase()+"</label> </div>";
						
						if((i+1)%4==0 && i!==0){
							
							text += "</div><div style='position: relative;width: 920px; height: 151px; padding-top: 0; border: 1px solid #000;'>";
						}
						x++;

					}
			
				text += "</table>";
					

				  var mywindow = window.open('', 'my div', 'height=600,width=800');
							/*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
							mywindow.document.write('<style>@media print{@page {size: potrait; width: 216mm;height: 280mm;}}</style>');
							mywindow.document.write(text);

					
							mywindow.print();
							// mywindow.close();
					
							return true;
			});
			
			
			
			
			
			
			
			
			
			
			
			document.getElementById("btn-cetak-tinta-pdf").addEventListener("click", function() { //cetak besar
				var array = [];
				var checkboxes = document.querySelectorAll('input[type=checkbox]:checked');
				var brand = document.getElementById('brand').value;
				
				
			
				
			
				$.ajax({
					url: "api/action.php?modul=inventory&act=get_type",
					type: "GET",
					success: function(dataResult){
						// console.log(dataResult);
						var dataResult = JSON.parse(dataResult);
						if(dataResult.result=='1'){
											if(brand == 'IDOLMART'){
					
					brand = brand+" UNIK ";
					
				}
				
				
				for (var i = 0; i < checkboxes.length; i++) {
					array.push(checkboxes[i].value)
				}
				let text = "PLANOGRAM<br>";
			    text += "TYPE TOKO : "+dataResult.type+"<br>";
				text += "RACK : <?php echo $_GET['rak']; ?>";
				text += "<table border='1'>";
				text += "<tr><td>No</td><td>SKU</td><td>Name</td><td>Price</td><td>Rack Name</td></tr>";
				// let text = "<div style='position: relative; width: 920px; height: 135px; padding-top: 25px; border: 1px solid #000;'>";
				
				var x = 1;
				for (let i = 0; i < array.length; i++) {
						var res = array[i].split("|");
						
						if(res[5] === 'undefined' || res[5] === 'null' || res[5] === ''){
							
							var sc = '';
						}else{
							var sc = ' / '+res[5];
						}
					
					
						text += "<tr><td>"+x+"</td><td>"+res[0]+"</td><td>"+res[1]+"</td><td>Rp. "+formatRupiah(res[2], '')+"</td><td>"+res[4].toUpperCase()+"</td></tr>";
					
						

							// text += "<td style='border: 0.5px solid #000;'><div style='margin:5px 5px 0 10px; color: black; width: 171px; height: 121px; font-family: Calibri'><label style='text-align: right; font-size: 10px'><b>"+res[1].toUpperCase()+"</b></label><br><label style='float: right; font-size: "+sizeprice+"'><label style='font-size: 10px'><b>Rp </b></label><b>"+formatRupiah(res[2], '')+"</b></label><br><label style='text-align: left; font-size: 10px'>"+res[0]+"/"+res[4].toUpperCase()+"</label><br><center ><hr><label style='text-align: center; font-size: 10px'>"+brand+" MURAH DAN LENGKAP</label></center></div></td>";
						
						
						x++;

					}
			
				text += "</table>";
					

				  var mywindow = window.open('', 'my div', 'height=600,width=800');
							/*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
							mywindow.document.write('<style>@media print{@page {size: potrait; width: 216mm;height: 280mm;margin-top: 15;margin-right: 2;margin-left: 2; padding: 0;} margin: 0; padding: 0;} table { page-break-inside:auto }tr{ page-break-inside:avoid; page-break-after:auto }</style>');
							mywindow.document.write(text);

					
							mywindow.print();
							// mywindow.close();
					
							return true;
						}
						
					}
					
					
				});
				

			});
			
			
			
			
			document.getElementById("btn-cetak-tinta").addEventListener("click", function() { //cetak besar ini yg dipake
				var array = [];
				var checkboxes = document.querySelectorAll('input[type=checkbox]:checked');
				var brand = document.getElementById('brand').value;
				
				
				if(brand == 'IDOLMART'){
					
					brand = brand+" UNIK ";
					
				}
				
				
				for (var i = 0; i < checkboxes.length; i++) {
					array.push(checkboxes[i].value)
				}
				let text = "<table><tr>";
				// let text = "<div style='position: relative; width: 920px; height: 135px; padding-top: 25px; border: 1px solid #000;'>";
				
				var x = 1;
				for (let i = 0; i < array.length; i++) {
						var res = array[i].split("|");
						
						if(res[5] === 'undefined' || res[5] === 'null' || res[5] === ''){
							
							var sc = '';
						}else{
							var sc = ' / '+res[5];
						}
						
						if(x==5){
							var x = 1;
						}

						var lengthh = res[1].length;
						var panjangharga = parseInt(res[2]);
	
						
						var sizeprice = "47px";
						if(lengthh > 33){
							
							 sizeprice = "47px";
						}
						
						if(panjangharga > 999999){
							sizeprice = "43px";
							
						}
						
						if(res[4] != ""){
							var rack = res[0]+"/"+res[4];
							
							
						}else{
							
							var rack = res[0]+"/NO_RACK";
						}
						
						// <br style='line-height: 70%;'>
						
						var newStr = rack.replace('-', '_');
						
						
							text += "<td style='border: 0.5px solid #000'><div style='margin:5px 5px 0 5px; color: black; width: 177px; height: 121px; font-family: Calibri; '><div style='height:30px; text-align: left; font-size: 10px'><b>"+res[1].toUpperCase()+"</b></div><label style='margin: -10px 0 0 0; float: right; font-size: "+sizeprice+"'><label style='font-size: 10px'><b>Rp </b></label><b>"+formatRupiah(res[2], '')+"</b></label><br><label style='text-align: left; font-size: 10px; width: 100%'>"+newStr+"</label><center><hr style='border-top: solid 1px #000 !important; background-color:black; border:none; height:1px; margin:5px 0 5px 0;'><label style='text-align: center; font-size: 10px; margin-top: 10px'>"+brand+" MURAH DAN LENGKAP</label></center></div></td>";
							
						
						
							if((i+1)%4==0 && i!==0){
							
								text += "</tr><tr>";
							}
							x++;

					}
			
				text += "</table>";
					

				  var mywindow = window.open('', 'my div', 'height=600,width=800');
							/*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
							mywindow.document.write('<style>@media print{@page {size: potrait; width: 216mm;height: 280mm;margin-top: 15;margin-right: 2;margin-left: 2; padding: 0;} margin: 0; padding: 0;} table { page-break-inside:auto }tr{ page-break-inside:avoid; page-break-after:auto }</style>');
							mywindow.document.write(text);

					
							mywindow.print();
							// mywindow.close();
					
							return true;
			});
			
			document.getElementById("btn-cetak1-tinta").addEventListener("click", function() { //cetak besar
				var array = [];
				var checkboxes = document.querySelectorAll('input[type=checkbox]:checked');
				
				for (var i = 0; i < checkboxes.length; i++) {
					array.push(checkboxes[i].value)
				}
				let text = "<table><tr>";
				var x = 1;
				for (let i = 0; i < array.length; i++) {
						var res = array[i].split("|");
						
						if(res[5] === 'undefined' || res[5] === 'null'){
							
							var sc = '-';
						}else{
							var sc = res[5];
						}
						
						// if(i == 0){
							// text += "";
						// }
						if(x==5){
							var x = 1;
						}
						
						
						
						// var left1 = document.getElementById("left1").value;
						// var left2 = document.getElementById("left2").value;
						// var left3 = document.getElementById("left3").value;
						// var left4 = document.getElementById("left4").value;
						
						// if(x==1){
							// var left = left1;
						// }else if(x==2){
							// var left = left2;
							
						// }else if(x==3){
							// var left = left3;
							
						// }else if(x==4){
							// var left = left4;
						// }


						
						text += "<td style='border: 1px solid #000;'><div style='margin:15px 0 0 20px; text-align:left; display: inline-block; color: black; width: 226px; height: 151px; font-family: Calibri'><label style='text-align: right; font-size: 10px'><b>"+res[1].toUpperCase()+"</b></label><br><label style='text-align: left; font-size: 10px'><b>Rp </b></label><label style='text-align: left; font-size: 18px'><b>"+formatRupiah(res[2], '')+"</b></label><br><label style='text-align: left; font-size: 10px'>"+res[0]+" | "+sc+"</label><br><label style='text-align: left; font-size: 10px'>"+res[3]+"</label> | <label style='text-align: left; font-size: 10px'>"+res[4].toUpperCase()+"</label></div></td>";
						
						if((i+1)%4==0 && i!==0){
							
							text += "</tr><tr>";
						}
						x++;
				

						// text += "<div style='text-align:left; left:"+left+"; position: absolute; display: inline-block; border: 1px; color: black; width: 202px; height: 151px; font-family: Calibri'><label style='text-align: right; font-size: 12px'>"+res[1].toUpperCase()+"</label><br><label style='text-align: left; font-size: 10px'><b>Rp </b></label><label style='text-align: left; font-size: 18px'><b>"+formatRupiah(res[2], '')+"</b></label><br><label style='text-align: left; font-size: 10px'>"+res[0]+"<br>"+sc+"</label><br><label style='text-align: left; font-size: 10px'>"+res[3]+"</label><br><label style='text-align: left; font-size: 10px'>"+res[4].toUpperCase()+"</label> </div>";
						
						// if((i+1)%4==0 && i!==0){
							
							// text += "</div><div style='position: relative;width: 920px; height: 151px; padding-top: 0'>";
						// }
						// x++;

					}
			
				text += "</table>";
					

				  var mywindow = window.open('', 'my div', 'height=600,width=800');
							/*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
							mywindow.document.write('<style>@media print{@page {size: potrait; width: 216mm;height: 280mm;}}table { page-break-inside:auto }tr{ page-break-inside:avoid; page-break-after:auto }</style>');
							mywindow.document.write(text);

					
							mywindow.print();
							// mywindow.close();
					
							return true;
			});
			


			document.getElementById("checkall").addEventListener("click", function() {	

				var checkboxes = document.querySelectorAll('input[type="checkbox"]');
				
		
				for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox'){
					if(checkboxes[i].checked == true){
						
						checkboxes[i].checked = false;
					}else if(checkboxes[i].checked == false){
						checkboxes[i].checked = true;
						
					}
					
					
					}	
				}
				

			});
			
			
			document.getElementById("uncheckall").addEventListener("click", function() {	

				var checkboxes = document.querySelectorAll('input[type="checkbox"]');
				
		
				for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox'){
			
						
						checkboxes[i].checked = false;
	
				}
				}
				

			});


			document.getElementById("btn-cetak").addEventListener("click", function() { //cetak besar
				var array = [];
				var checkboxes = document.querySelectorAll('input[type=checkbox]:checked');
				

				
				for (var i = 0; i < checkboxes.length; i++) {
					array.push(checkboxes[i].value)
				}
				let text = "<div style='position: relative; width: 920px; height: 151px; padding-top: 20px; '>";
				var x = 1;
				for (let i = 0; i < array.length; i++) {
						var res = array[i].split("|");
						
						if(res[5] === 'undefined' || res[5] === 'null'){
							
							var sc = '';
						}else{
							var sc = res[5];
						}
						
						// if(i == 0){
							// text += "";
						// }
						if(x==5){
							var x = 1;
						}
						
						// var left1 = document.getElementById("left1").value;
						// var left2 = document.getElementById("left2").value;
						// var left3 = document.getElementById("left3").value;
						// var left4 = document.getElementById("left4").value;
						var price = document.getElementById("price").value;
						
						
						if(x==1){
							var left = left1;
						}else if(x==2){
							var left = left2;
							
						}else if(x==3){
							var left = left3;
							
						}else if(x==4){
							var left = left4;
						}
							
						// var ptop = top;

						text += "<div style='text-align:left; left:"+left+"; position: absolute; display: inline-block; border: 1px; color: black; width: 202px; height: 151px; font-family: Calibri'><label style='text-align: right; font-size: 14px'><b>"+res[1].toUpperCase()+"</b></label><br><label style='text-align: left; font-size: 14px'><b>Rp </b></label><label style='text-align: left; font-size: "+price+"'><b>"+formatRupiah(res[2], '')+"</b></label><br><label style='text-align: left; font-size: 14px'>"+res[0]+" | "+sc+"</label><br><label style='text-align: left; font-size: 14px'>"+res[3]+"</label> | <label style='text-align: left; font-size: 14px'>"+res[4].toUpperCase()+"</label></div>";
						
						if((i+1)%4==0 && i!==0){
							
							text += "</div><div style='position: relative;width: 920px; height: 151px; padding-top: 0'>";
						}
						x++;

					}
			
				text += "</table>";
					
				const data = [
				{
					type: "text", // 'text' | 'barCode' | 'qrCode' | 'image' | 'table
					value: text,
					style: 'text-align:left;',
					css: { "font-size": "14px"},
				},
				
				];
			print_tag(data);	
			});


document.getElementById("search").addEventListener("keyup", function() {
var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search");
  filter = input.value.toUpperCase();
  table = document.getElementById("example");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[2];
    td1 = tr[i].getElementsByTagName("td")[3];
    if (td) {
      txtValue = td.textContent || td.innerText;
      txtValue1 = td1.textContent || td1.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if(txtValue1.toUpperCase().indexOf(filter) > -1){
		tr[i].style.display = "";  
	  } else {
        tr[i].style.display = "none";
      }
    }       
  }
	
	
	
});


function selectKat(){
	var kat = document.getElementById( 'kat' ).value;
	
	if(kat == '1'){
		 $("#pc").show();
		 $("#rack").hide();
		
	}else if(kat == '2'){
		
		 $("#pc").hide();
		 $("#rack").show();
	}else if(kat == '3'){
		
		 $("#pc").hide();
		 $("#rack").hide();
	}
	
	
}

function syncMaster(){
	

	
	$.ajax({
		url: "api/action.php?modul=inventory&act=sync_inv",
		type: "GET",
		beforeSend: function(){
			 $('#sync').prop('disabled', true);
			$('#notif1').html("<font style='color: red'>Sedang melakukan sync, sabar ya..</font>");
			$("#overlay").fadeIn(300);
		},
		success: function(dataResult){
		
			var dataResult = JSON.parse(dataResult);
			if(dataResult.result=='1'){
				$('#notif1').html("<font style='color: green'>"+dataResult.msg+"</font>");
				$("#example").load(" #example");
				$("#overlay").fadeOut(300);
			}
			else {
				$('#notif').html(dataResult.msg);
			}
			
		}
	});
	
}

function getType(){
	

	var type = "";
	$.ajax({
		url: "api/action.php?modul=inventory&act=get_type",
		type: "GET",
		success: function(dataResult){
			// console.log(dataResult);
			var dataResult = JSON.parse(dataResult);
			if(dataResult.result=='1'){
				localStorage.setItem("type",dataResult.type);
				type = dataResult.type;
			}
			
		}
		
		
	});
	return type;
}

function ubahStatus(m_pi_key){
	// alert(m_pi_key);
	var formData = new FormData();
		
	formData.append('m_pi', m_pi_key);
	
	$.ajax({
		url: "api/action.php?modul=inventory&act=verifikasi",
		type: "POST",
		data : formData,
		processData: false,
		contentType: false,
		success: function(dataResult){
			console.log(dataResult);
			var dataResult = JSON.parse(dataResult);
			if(dataResult.result=='1'){
				$('#notif1').html("<font style='color: green'>Berhasil verifikasi!</font>");
				$("#example").load(" #example");
				$(".modal").modal('hide');
			}
			// else {
				// $('#notif').html(dataResult.msg);
			// }
			
		}
	});
	
}

$('#butsave').on('click', function() {
		
		var it = $('#it').val();
		var sl = $('#sl').val();
		var kat = $('select[id=kat] option').filter(':selected').val();
		var rack = $('select[id=rack] option').filter(':selected').val();
		var pc = $('select[id=pc] option').filter(':selected').val();
		// var image = $('#image')[0].files[0];
		
		
		var formData = new FormData();
		
		formData.append('it', it);
		formData.append('sl', sl);
		formData.append('kat', kat);
		formData.append('rack', rack);
		formData.append('pc', pc);
		
		if(it!="" || sl!="" || kat!=""){
			$( "#butsave" ).prop( "disabled", true );
			// $('#notif').html("Sistem sedang melakukan input, jangan refresh halaman..");
			
			if(kat == '1'){
				
				if(pc!=""){
					
					
					$.ajax({
						url: "api/action.php?modul=inventory&act=input",
						type: "POST",
						data : formData,
						processData: false,
						contentType: false,
						beforeSend: function(){
							$('#notif').html("Proses input header dan line..");
						},
						success: function(dataResult){
							var dataResult = JSON.parse(dataResult);
							if(dataResult.result=='2'){
								$('#notif').html("Proses input ke inventory line");
								// $("#example").load(" #example");
							}else if(dataResult.result=='1'){
								$('#notif').html("<font style='color: green'>Berhasil input dengan product category!</font>");
								location.reload();
								$( "#butsave" ).prop( "disabled", false );
							}
							else {
								$('#notif').html(dataResult.msg);
								$( "#butsave" ).prop( "disabled", false );
							}
							
						}
					});
					
					
					
					
				}else{
					
					$('#notif').html("Product category tidak boleh kosong!");
					$( "#butsave" ).prop( "disabled", false );
				}
			}else if(kat == '2'){
				if(rack!=""){
					
					$.ajax({
						url: "api/action.php?modul=inventory&act=input",
						type: "POST",
						data : formData,
						processData: false,
						contentType: false,
						beforeSend: function(){
							$('#notif').html("Proses input header dan line..");
						},
						success: function(dataResult){
							var dataResult = JSON.parse(dataResult);
							if(dataResult.result=='2'){
								$('#notif').html("Proses input ke inventory line");
								$( "#butsave" ).prop( "disabled", false );
								// $("#example").load(" #example");
							}else if(dataResult.result=='1'){
								$('#notif').html("<font style='color: green'>Berhasil input dengan rack!</font>");
								location.reload();
								$( "#butsave" ).prop( "disabled", false );
							}
							else {
								$('#notif').html(dataResult.msg);
								$( "#butsave" ).prop( "disabled", false );
							}
							
						}
					});
				}else{
					
					$('#notif').html("Rack tidak boleh kosong!");
					$( "#butsave" ).prop( "disabled", false );
				}
				
			}else if(kat == '3'){
					
					$.ajax({
						url: "api/action.php?modul=inventory&act=inputitems",
						type: "POST",
						data : formData,
						processData: false,
						contentType: false,
						beforeSend: function(){
							$('#notif').html("Proses input header dan line..");
						},
						success: function(dataResult){
							var dataResult = JSON.parse(dataResult);
							if(dataResult.result=='2'){
								$('#notif').html("Proses input ke inventory line");
								$( "#butsave" ).prop( "disabled", false );
								// $("#example").load(" #example");
							}else if(dataResult.result=='1'){
								$('#notif').html("<font style='color: green'>Berhasil input dengan rack!</font>");
								location.reload();
								$( "#butsave" ).prop( "disabled", false );
							}
							else {
								$('#notif').html(dataResult.msg);
								$( "#butsave" ).prop( "disabled", false );
							}
							
						}
					});
				
			}
			
			
			// $("#overlay").fadeIn(300);
			// $.ajax({
				// url: "action.php?modul=inventory&act=input",
				// type: "POST",
				// data : formData,
				// processData: false,
				// contentType: false,
				// success: function(dataResult){
					// var dataResult = JSON.parse(dataResult);
					// if(dataResult.result=='1'){
						// $('#notif').html("Maaf, nomor/password salah, coba dicek lagi");
					// }
					// else {
						// alert("Gagal input");
					// }
					
				// }
			// });
		}
		else{
			$('#notif').html("Lengkapi isian dulu!");
		}
	});
	

	
</script>
</div>
<?php include "components/fff.php"; ?>