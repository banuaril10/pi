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
				<h4>STOCK POS LOKAL VS ERP</h4>
			</div>
			
			
			
			<div class="card-body">
			<div class="tables">	
			<p id="notif" style="color: red; font-weight: bold"></p>

			
			
			
			
				<div class="table-responsive bs-example widget-shadow">	
				
				<!--<button type="button" onclick="loadTable();" class="btn btn-danger" id="load" value="Load">Load Items</button>-->
				<p>Last update : <?php 
				
					$cekdate = "select tanggal from m_pi_stock where date(tanggal) = date(now())";
		$cds = $connec->query($cekdate);
		foreach ($cds as $rr) {
			$tgll = $rr["tanggal"];	
		}
				echo $tgll;
				?></p>
		<font id="sebentar"></font>
				
				<!--<input type="text" id="search" class="form-control" id="exampleInputName2" placeholder="Search">-->
			<!--	<input type="text" onchange="filterTable();" id="search" class="form-control" id="exampleInputName2" placeholder="Search by SKU atau Nama">-->
			<font style="color: red">* Search by sku and name</font>
			<table class="table table-bordered table-sm" id="example1" style="width: 100%">
            <thead>
              <tr>
                <th scope="col">SKU</th>
                <th scope="col">Name</th>
                <th scope="col">Stock Lokal</th>
                <th scope="col">Qty Sales</th>
                <th scope="col">Stock ERP</th>
                <th scope="col">Status</th>

                
                <!--<th scope="col">Harga</th>
                <th scope="col">Harga Diskon</th>-->
              </tr>
            </thead>
            <tbody>
			
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
function loadTable(){
	var no = 1;
	$.ajax({
		url: "api/action.php?modul=inventory&act=api_mproducts",
		type: "GET",
		beforeSend:function(){
              $('#sebentar').html("<div class='alert alert-danger mb-3' role='alert'>Proses load...</div>");
           
        },  
		success: function(dataResult){
			// console.log(dataResult);
			var dataResult = JSON.parse(dataResult);
			//console.log(dataResult);
			 $('#sebentar').html("<div class='alert alert-success mb-3' role='alert'>Selesai load...</div>");
			var total = ""; // Let this contain all the text
			
			for (var i = 0; i < dataResult.length; i++) {
			
				
				//Creating table
				var table ="<tr><td>"+no+"</td><td>"+dataResult[i].sku+"</td><td>"+dataResult[i].name+"</td><td>"+parseInt(dataResult[i].stock_lokal)+"</td><td>"+parseInt(dataResult[i].qty_sales)+"</td><td>"+dataResult[i].set+"</td></tr>";
			
				// Store the value in 'total'
				total+= table;  
				no++;
			}
			document.getElementById("myTB").innerHTML = total;
			
		}
	});
	
	
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


  $(function(){
 
           $('.table').DataTable({
              "processing": true,
              "serverSide": true,
              "ajax":{
                       "url": "api/action.php?modul=inventory&act=api_monitoring",
                       "dataType": "json",
                       "type": "POST"
                     },
              "columns": [
                  // { "data": "no" },
                  { "data": "sku" },
                  { "data": "name" },
                  { "data": "stock_lokal" },
                  { "data": "stock_sales" },
                  { "data": "stock_erp" },
                  { "data": "status" },
       
                  
                  // { "data": "price" },
                  // { "data": "price_discount" },
				  
              ]  
 
          });
        });


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