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
				

				<h4>MANAGE STOCK AWAL</h4>
			<p style="color: red; font-weight: bold">Note : Manage stock awal akan menarik semua data dri ERP, jangan lakukan manage stock disini jika ada PI yg sedang berjalan</p>
			</div>
			<div class="card-body">
			<div class="tables">
						
				<div class="table-responsive bs-example widget-shadow">				
				
					<div class="form-group">
					<p id="notif" style="color: red; font-weight: bold"></p>
					</div>
					<div class="form-inline"> 

					<div class="form-group"> 
					
					<button type="button" onclick="manage_stock('<?php echo $org_key; ?>');" class="btn btn-primary">Manage Stock
					</button>
					
					<button type="button" onclick="load_product();" class="btn btn-danger">Load Product
					</button>
					<br>
					<br>
					<input type="text" id="search" class="form-control" id="exampleInputName2" placeholder="Search">
					<input type="hidden" id="search1">
					</div> 

					</div>
					  
			
					<table class="table table-bordered" id="example1">
						<thead>
							<tr>
								<th>No</th>
								<th>SKU</th>
								<th>Stock</th>
								<th>Price</th>
								<th>Price Discount</th>

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
$(document).ready( function () {
    $('#example1').DataTable();
} );


// document.getElementById("search").addEventListener("keyup", function() {
// var input, filter, table, tr, td, i, txtValue;
  // input = document.getElementById("search");
  // filter = input.value.toUpperCase();
  // table = document.getElementById("example1");
  // tr = table.getElementsByTagName("tr");
  // for (i = 0; i < tr.length; i++) {
    // td = tr[i].getElementsByTagName("td")[1];
    // td1 = tr[i].getElementsByTagName("td")[2];
    // if (td) {
      // txtValue = td.textContent || td.innerText;
      // txtValue1 = td1.textContent || td1.innerText;
      // if (txtValue.toUpperCase().indexOf(filter) > -1) {
        // tr[i].style.display = "";
      // }else if(txtValue1.toUpperCase().indexOf(filter) > -1){
		// tr[i].style.display = "";  
	  // } else {
        // tr[i].style.display = "none";
      // }
    // }       
  // }
	
	
	
// });

var input = document.getElementById("sku");

input.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
	
	var sku = input.value;

	$.ajax({
		url: "api/action.php?modul=inventory&act=sync_pos_peritems",
		type: "POST",
		data : {sku: sku},
		beforeSend: function(){
			$('#notif').html("Proses mencari items..");
		},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);
			console.log(dataResult);
			if(dataResult.result=='1'){
				input.value = '';
				$('#notif').html("<font style='color: green'>"+dataResult.msg+"</font>");
				
				load_product(sku);
				// $('#example1').load(' #example1', function() {
					// $('#search1').val(sku);
				
					// filterTable();
				// });
			}else if(dataResult.result=='0'){
				input.value = '';
				$('#notif').html("<font style='color: red'>"+dataResult.msg+"</font>");
				// $('#example1').load(' #example1', function() {
					// $('#search1').val(sku);
				
				
				// });
				
				
				
			}
			
		}
	});
	
  }
});

function manage_stock(org_id){
	$("#overlay").fadeIn(300);
	if(org_id == ''){
		
		$('#notif').html("<font style='color: red'>Session telah habis, reload dulu</font>");
	}else{
		$.ajax({
		url: "api/action.php?modul=inventory&act=sync_pos_awal",
		type: "POST",
		data : {org_id: org_id},
		beforeSend: function(){
			$('#notif').html("Proses sync items..");
		},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);

			$('#notif').html("<font style='color: green'>"+dataResult.msg+"</font>");
			$("#overlay").fadeOut(300);
			
		}
		});
		
	}
	
	
	
}


function load_product(){
	$("#overlay").fadeIn(300);
	$.ajax({
		url: "api/action.php?modul=inventory&act=load_product_all",
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