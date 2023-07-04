<?php 
// if($_SERVER["HTTPS"] != "on")
// {
    // header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    // exit();
// } 
?>
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
				<h4>RECEIVING ITEMS</h4>
			</div>
			
			
			
			<div class="card-body">
			<div class="tables">	
			<p id="notif" style="color: red; font-weight: bold"></p>

			<input type="doc_no" style="width: 500px" id="doc_no" placeholder="Document Number">
			<input type="button" onclick="getDataDoc();" value="Get Data">
			<input type="button" id="sync" onclick="SyncData();" value="Terima Barang">
			
			
			
				<div class="table-responsive bs-example widget-shadow">	
				
				<!--<button type="button" onclick="loadTable();" class="btn btn-danger" id="load" value="Load">Load Items</button>-->
				
		<font id="sebentar"></font>
				
				<!--<input type="text" id="search" class="form-control" id="exampleInputName2" placeholder="Search">-->
			<!--	<input type="text" onchange="filterTable();" id="search" class="form-control" id="exampleInputName2" placeholder="Search by SKU atau Nama">-->

			<table class="table table-bordered table-sm" id="example1">
            <thead>
              <tr>
                <th scope="col">No</th>
          
                <th scope="col">SKU</th>
                <th scope="col">Nama Items</th>
                <th scope="col">Qty</th>
                <th scope="col">Stock ERP</th>
                <!--<th scope="col">Harga</th>
                <th scope="col">Harga Diskon</th>-->
              </tr>
            </thead>
            <tbody id="myTB">
			
			<?php
			// $no = 1;
			// $query = $connec->query("select sku, name, coalesce(stockqty,0) stockqty, 
					
					// CASE WHEN description IS NOT NULL 
       // THEN description
       // ELSE '0'
// END AS descriptions from pos_mproduct");
					// foreach($query as $rr){
						// $stock_lokal = 0;
						
						
					// $ceksales = $connec->query("select sku, sum(qty) as jj from pos_dsalesline where sku = '".$rr['sku']."' and date(insertdate) = date(now()) group by sku");
					
					// $stock_sales = 0;
					// foreach ($ceksales as $rs) {
				
						// $stock_sales = $rs['jj'];
					// }
					
				
						
						// $stock_lokal = $rr['stockqty'];
						// $stockk = $rr['descriptions'];
						
						
						// $lok = $stock_lokal + $stock_sales;
					
					// if($lok == $stockk){
						
						// $set = "Sesuai";
					// }else{
						
						// $set = "Belum Sesuai";
					// }
					

						
						
						// echo '<tr><td>'.$no.'</td><td>'.$rr['sku'].'</td><td>'.$rr['name'].'</td><td>'.$stock_lokal.'</td><td>'.$stock_sales.'</td><td>'.$stockk.'</td><td>'.$set.'</td></tr>';
						// $no++;
					// }
			
			
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

<script type="text/javascript">
function SyncData(){
	var doc_no = document.getElementById("doc_no").value;
	
	if(doc_no != ''){
		
		var no = 1;
		$.ajax({
		url: "api/sync_perdoc.php?doc_no="+doc_no,
		type: "GET",
		beforeSend:function(){
              $('#sebentar').html("<div class='alert alert-danger mb-3' role='alert'>Proses sync...</div>");
           
        },  
		success: function(dataResult){
			console.log(dataResult);
			var dataResult = JSON.parse(dataResult);
			//console.log(dataResult);
			if(dataResult.result == 1){
				
				
				$("#sync").hide();
			}
			
			 $('#sebentar').html("<div class='alert alert-success mb-3' role='alert'>"+dataResult.msg+"</div>");
		

			
		}
		});
		
	}
}
$("#sync").hide();

function getDataDoc(){
	
	var doc_no = document.getElementById("doc_no").value;
	if(doc_no != ""){
		var no = 1;
	$.ajax({
		url: "api/get_perdoc.php?doc_no="+doc_no,
		type: "GET",
		beforeSend:function(){
              $('#sebentar').html("<div class='alert alert-danger mb-3' role='alert'>Proses load...</div>");
           
        },  
		success: function(dataResult){
			
			// console.log(dataResult);
			var dataResults = JSON.parse(dataResult);
			if(dataResults.result == 1){
				
				// console.log(dataResults.data);
				console.log(dataResults['data']);
				 $('#sebentar').html("<div class='alert alert-success mb-3' role='alert'>Selesai load...</div>");
				 
				 
				 
			var total = ""; // Let this contain all the text
			
			for (var i = 0; i < dataResults['data'].length; i++) {
			
				
				//Creating table
			var table ="<tr><td>"+no+"</td><td>"+dataResults['data'][i].sku+"</td><td>"+dataResults['data'][i].namaitem+"</td><td>"+parseInt(dataResults['data'][i].qty)+"</td><td>"+parseInt(dataResults['data'][i].stockqty)+"</td></tr>";
			
				// Store the value in 'total'
				total+= table;  
				no++;
			}
			document.getElementById("myTB").innerHTML = total;
				 
			$("#sync").show();	 
				 
			}else{
				
				 $('#sebentar').html("<div class='alert alert-success mb-3' role='alert'>Tidak ada data...</div>");
			}
			
			
			

			
		}
	});
		
	}else{
		 $('#sebentar').html("<div class='alert alert-success mb-3' role='alert'>Document number tidak boleh kosong...</div>");
		
	}
	
	
	
	
	
}


function filterTable(){
var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search");
  filter = input.value.toUpperCase();
  table = document.getElementById("example1");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    td1 = tr[i].getElementsByTagName("td")[2];
    td2 = tr[i].getElementsByTagName("td")[6];
    if (td) {
      txtValue = td.textContent || td.innerText;
      txtValue1 = td1.textContent || td1.innerText;
      txtValue2 = td2.textContent || td2.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if(txtValue1.toUpperCase().indexOf(filter) > -1){
		tr[i].style.display = "";  
	  }else if(txtValue2.toUpperCase().indexOf(filter) > -1){
		tr[i].style.display = "";  
	  } else {
        tr[i].style.display = "none";
      }
    }       
  }
	
}

  // $(function(){
 
           // $('.table').DataTable({
              // "processing": true,
              // "serverSide": true,
              // "ajax":{
                       // "url": "api/action.php?modul=inventory&act=monitoring",
                       // "dataType": "json",
                       // "type": "POST"
                     // },
              // "columns": [
 
                  // { "data": "sku" },
                  // { "data": "name" },
                  // { "data": "stockqty" },
                  // { "data": "stockqty1" },

              // ]  
 
          // });
        // });
</script>
</div>
<?php include "components/fff.php"; ?>