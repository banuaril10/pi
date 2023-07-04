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
				<h4>SYNC MASTER CATEGORY</h4>
			</div>
			<div class="card-body">
			<div class="tables">			
				<div class="table-responsive bs-example widget-shadow">	
				<button type="button" class="btn btn-primary" id="sync" onclick="syncMaster();">Sync</button>
				<p id="notif1" style="color: red; font-weight: bold"></p>				
				
					<table class="table table-bordered" id="example">
						<thead>
							<tr>
								
								<th>Value</th>
								<th>Name</th>
							
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
 
		


function syncMaster(){
	

	
	$.ajax({
		url: "api/action.php?modul=inventory&act=sync_cat",
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
				location.reload();
				$("#overlay").fadeOut(300);
			}
			else {
				$('#notif').html(dataResult.msg);
			}
			
		}
	});
	
}
 $(function(){
 
           $('.table').DataTable({
              "processing": true,
              "serverSide": true,
              "ajax":{
                       "url": "api/action.php?modul=inventory&act=api_mcategory",
                       "dataType": "json",
                       "type": "POST"
                     },
              "columns": [
                   // { "data": "no" },
                  { "data": "value" },
                  { "data": "name" },
                  // { "data": "price" },
                  // { "data": "price_discount" },
              ]  
 
          });
        });

</script>
</div>
<?php include "components/fff.php"; ?>