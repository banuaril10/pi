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
				<h4>LIST USERS</h4>
			</div>
			<div class="card-body">
			<div class="tables">			
				<div class="table-responsive bs-example widget-shadow">	
				<button type="button" class="btn btn-primary" id="sync" onclick="syncMaster();">Sync</button>
				<p id="notif1" style="color: red; font-weight: bold"></p>	
				<input type="text" id="search" class="form-control" id="exampleInputName2" placeholder="Search">
					<table class="table table-bordered" id="example">
						<thead>
							<tr>
								<th>No</th>
								<th>Username</th>
								<th>Name</th>
							</tr>
						</thead>
						<tbody>
					
						
						<?php 
						$sql_list = "select * from m_pi_users order by username asc";
						$no = 1;
						foreach ($connec->query($sql_list) as $row) {
						
						?>
						
						
							<tr>
								<th scope="row"><?php echo $no; ?></th>
								<td><?php echo $row['username']; ?></td>
								<td><?php echo $row['name']; ?> </td>
								
				
								
							</tr>
							
							
							
							
						<?php unset($elements); $no++;} ?>
   
   
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

document.getElementById("search").addEventListener("keyup", function() {
var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search");
  filter = input.value.toUpperCase();
  table = document.getElementById("example");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    td1 = tr[i].getElementsByTagName("td")[1];
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

function syncMaster(){
	

	
	$.ajax({
		url: "api/action.php?modul=inventory&act=sync_user",
		type: "GET",
		beforeSend: function(){
			 $('#sync').prop('disabled', true);
			$('#notif1').html("<font style='color: red'>Sedang melakukan sync, sabar ya..</font>");
			$("#overlay").fadeIn(300);
		},
		success: function(dataResult){
			// console.log(dataResult);
			var dataResult = JSON.parse(dataResult);
			if(dataResult.result=='1'){
				$('#notif1').html("<font style='color: green'>"+dataResult.msg+"</font>");
				$("#example").load(" #example");
				 $('#sync').prop('disabled', false);
				 $("#overlay").fadeOut(300);
			}
			// else {
				// $('#notif').html(dataResult.msg);
			// }
			
		}
	});
	
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