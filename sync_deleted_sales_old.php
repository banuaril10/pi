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
				

				<h4>RESET PENJUALAN MENYISAKAN 60 HARI TERAKHIR</h4>
			
			</div>
			<div class="card-body">
			<div class="tables">
						
				<div class="table-responsive bs-example widget-shadow">				
				
					<div class="form-group">
					<p id="notif" style="color: red; font-weight: bold"></p>
					</div>
					<div class="form-inline"> 

					<div class="form-group"> 
					
					<button type="button" onclick="sync_deleted();" class="btn btn-danger">Reset Penjualan</button>
					<br>
					<br>
					
					</div> 

					</div>
					
			
					
					
					
				</div>
			</div>
		</div>
	</div>
		</div>
	</div>
</div>




<script type="text/javascript">
function sync_deleted(){
	

		$.ajax({
		url: "api/sync_deleted.php",
		type: "POST",
		beforeSend: function(){
			$("#overlay").fadeIn(300);
			$('#notif').html("Proses sync..");
		},
		success: function(dataResult){
			console.log(dataResult);
			$('#notif').html("<font style='color: green'>"+dataResult+"</font>");
			$("#overlay").fadeOut(300);
			// location.reload();
			
		}
		});
		
}

</script>
</div>
<?php include "components/fff.php"; ?>