<?php include "config/koneksi.php"; ?>
<?php include "components/main.php"; ?>
<?php include "components/sidebar.php"; ?>
<div id="app">
<div id="overlay">
			<div class="cv-spinner">
				<span class="spinner"></span>
			</div>
		</div>
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
				

				<h4>SYNC SKU</h4>
			
			</div>
			<div class="card-body">
			<div class="tables">
						
				<div class="table-responsive bs-example widget-shadow">				
				
					<div class="form-group">
					<p id="notif" style="color: red; font-weight: bold"></p>
					</div>
					<div class="form-inline"> 

					<div class="form-group"> 
					
					<button type="button" onclick="sync_sku();" class="btn btn-primary">Sync SKU</button>
					
					<button type="button" onclick="load_product();" class="btn btn-danger">Load Data
					</button>
					<br>
					<br>
					<input type="text" id="search" class="form-control" id="exampleInputName2" placeholder="Search" onchange="filterTable();">
					<input type="hidden" id="search1">
					</div> 

					</div>
					  
			
					<table class="table table-bordered" id="example1">
						<thead>
							<tr>
								<th>No</th>
								<th>SKU / Nama</th>
								<th>Barcode</th>

							</tr>
						</thead>
						<tbody>
			
					
			
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
// $(document).ready( function () {
    // $('#example1').DataTable();
// } );


document.getElementById("search").addEventListener("keyup", function() {
var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search");
  filter = input.value.toUpperCase();
  table = document.getElementById("example1");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    td1 = tr[i].getElementsByTagName("td")[2];
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


function sync_sku(){
	$("#overlay").fadeIn(300);

		$.ajax({
		url: "api/action.php?modul=inventory&act=sync_sku",
		type: "POST",
		beforeSend: function(){
			$('#notif').html("Proses sync barcode..");
		},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);

			$('#notif').html("<font style='color: green'>"+dataResult.msg+"</font>");
			$("#overlay").fadeOut(300);
			
		}
		});
		
}


function load_product(){
	$("#overlay").fadeIn(300);
	$.ajax({
		url: "api/action.php?modul=inventory&act=load_product_barcode",
		type: "GET",
		success: function(dataResult){
			document.querySelector('#example1 > tbody').innerHTML = dataResult;
			$("#overlay").fadeOut(300);
		}
	});
	
	
	
}



function filterTable(){
var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search1");
  filter = input.value.toUpperCase();
  table = document.getElementById("example1");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    td1 = tr[i].getElementsByTagName("td")[2];
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
	
}

</script>
</div>
<?php include "components/fff.php"; ?>