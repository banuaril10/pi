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
				<h4>VERIFIKASI LIST</h4>
				 <button type="button" class="btn btn-primary" onclick="loopingLine();">Sync Approval</button>
			</div>
			<div class="card-body">
			<div class="tables">		
				<div class="table-responsive bs-example widget-shadow">	
				<p id="notif" style="color: red; font-weight: bold"></p>
				
					<table class="table table-bordered" id="example">
						<thead>
							<tr>
								<th>No</th>
								<th>Document No</th>
								<th>Tanggal / Rack Name</th>
							
								<th>Total Sistem</th>
								<th>Total Fisik</th>
								
								<th>Selisih</th>
								
								
								<th>Type</th>
								<th>Status</th>
								<th>Aksi</th>
								
							</tr>
						</thead>
						<tbody>
						
						<?php 
						function rupiah($angka){
	
							$hasil_rupiah = "Rp " . number_format($angka,0,',','.');
							return $hasil_rupiah;
 
						}
 
						$sql_list = "select m_pi_key, m_pi.name ,m_pi.insertdate, m_pi.rack_name, m_pi.insertby, m_pi.status,m_pi. m_locator_id, m_pi.inventorytype
						from m_pi where m_pi.status in ('2','3') and inventorytype = '".$_SESSION['role']."' and date(insertdate) = date(now()) order by insertdate desc";
						$no = 1;
						foreach ($connec->query($sql_list) as $row) {
						
											
							
						if($row['status'] == 1){
							$stat = 'Draft';
							
						}else if($row['status'] == 2){
							$stat = 'Verifikasi';
							$color = 'blue';
							
						}else if($row['status'] == 3){
							$stat = 'Waiting Approval';
							$color = 'red';
							
						}else if($row['status'] == 4){
							$stat = 'Approved by SPV';
							$color = 'green';
							
						}else{
							
							$stat = $row['status'];
						}
						
						
						$sql_amount = "select SUM(CASE WHEN issync=1 THEN 1 ELSE 0 END) jumsync,  sum(qtysales * price) hargasales,  sum(qtysalesout * price) hargagantung,  sum(qtyerp * price) hargaerp, sum(qtycount * price) hargafisik, count(sku) jumline
						from m_piline where m_pi_key = '".$row['m_pi_key']."'";
						foreach ($connec->query($sql_amount) as $tot) {
							
							$qtyerp = $tot['hargaerp'] - $tot['hargagantung'] - $tot['hargasales'];
							$qtycount = $tot['hargafisik'];

							$jumline = $tot['jumline'];
							$jumsync = $tot['jumsync'];
							$selisih = $qtycount - $qtyerp;
							
						}
						
						if($jumsync == $jumline){
							
							$colorr = 'blue';
							$statt = 1;
						}else{
							
							$colorr = 'red';
							$statt = 0;
						}
						
						$jumrelease = '<font style="color: '.$color.'">'.$jumsync.' / '.$jumline.'</font>';
						
						?>
						
						
							<tr>
								<th scope="row"><?php echo $no; ?></th>
								<td><?php echo $row['name']; ?><br>
								<?php if($row['status'] == 2){ ?>
									<a href="invverif.php?m_pi=<?php echo $row['m_pi_key']; ?>" class="btn btn-danger">Proses</a>
								<?php }else if($row['status'] == 3){ ?>
									<a href="detail.php?m_pi=<?php echo $row['m_pi_key']; ?>" class="btn btn-warning">Detail</a>
								<?php } ?>
								</td>
								
								<td><?php echo $row['insertdate']; ?><br>RACK : <b><?php echo $row['rack_name']; ?></b></td>
					
								<td><?php echo rupiah($qtyerp); ?></td>
								<td><?php echo rupiah($qtycount); ?></td>
								
								<td><?php echo rupiah($selisih); ?></td>
								
						
								<td><?php echo $row['inventorytype']; ?></td>
								<td style="color: <?php echo $colorr; ?>; font-weight: bold">
								<?php echo $jumrelease; ?><br>
								
								<font style="color: <?php echo $color; ?>"><?php echo $stat; ?></font><br>
								
								
								
								<?php if($row['status'] == 2){ ?>
								<button type="button" onclick="cekSession();" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $row['m_pi_key']; ?>">Release</button>

								<?php } if($row['status'] == 3 && $statt == 0){ ?>
									
							    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModall<?php echo $row['m_pi_key']; ?>">Lanjutkan..</button>
								<?php } ?>
								
								
								
						
								</td>
								
								
								<td>
								<?php if($row['status'] != 3 && $row['status'] != 4){ ?>
								<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#batal<?php echo $row['m_pi_key']; ?>">Batalkan</button>
								<?php }else{ ?>
									-
								<?php } ?>
								<!--<button type="button" class="btn btn-success" onclick="sendWa('<?php echo $row['m_pi_key']; ?>')">Test WA</button>-->
								
								</td>
							
								
							</tr>
							
							
							
							<div class="modal fade" id="exampleModal<?php echo $row['m_pi_key']; ?>" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog">
							 <p id="notif_sess" style="color: red; font-weight: bold; background: #fff; padding: 10px"></p>
								<div class="modal-content">
								
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin melakukan release?</h5>
								
									<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									No. Document : <b><?php echo $row['name']; ?></b><br>
									Tanggal : <b><?php echo $row['insertdate']; ?></b>
									
									<br>RACK : <b><?php echo $row['rack_name']; ?></b>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
									<button onclick="releasePI('<?php echo $row['m_pi_key']; ?>');" class="btn btn-success">YAKIN</button>
								</div>
								</div>
							</div>
							</div>
							
							
							
							
							
							<div class="modal fade" id="exampleModall<?php echo $row['m_pi_key']; ?>" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
								
								
								
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin melanjutkan release items yg gantung?</h5>
									
									<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
									<button onclick="releasePIGantung('<?php echo $row['m_pi_key']; ?>');" class="btn btn-success">YAKIN</button>
								</div>
								</div>
							</div>
							</div>
							
							
							
							<div class="modal fade" id="batal<?php echo $row['m_pi_key']; ?>" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Yakin membatalkan PI? <br>Data Header akan menghilang dari daftar</h5>
								
									<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
									<button onclick="batalPI('<?php echo $row['m_pi_key']; ?>');" class="btn btn-success">YAKIN</button>
								</div>
								</div>
							</div>
							</div>
							
							
						<?php $no++;} ?>
   
   
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
		</div>
	</div>
</div>


<script type="text/javascript" >
loopingLine();

function cekSession(){
		$.ajax({
			url: "api/action.php?modul=inventory&act=cek_session",
			type: "GET",
			beforeSend: function(){
				$('#notif_sess').html("Cek session..");
				 $(".modal-content").hide();
				
			},
			success: function(dataResult){
				console.log(dataResult);
				var dataResult = JSON.parse(dataResult);
				
				if(dataResult.result=='1'){
					$('#notif_sess').html("<font style='color: green'>"+dataResult.msg+"</font>");
					 $(".modal-content").show();
					
				
				}else{
					
					
					$('#notif_sess').html("<font style='color: red'>"+dataResult.msg+"</font>");
					
					 $(".modal-content").hide();
				
				}
				
				
				
			}
					
				
				
				
			
		});
	
	
}

function sendWa(m_pi){
	
	$.ajax({
			url: "api/action.php?modul=inventory&act=nohp_spv&m_pi="+m_pi,
			type: "GET",
			beforeSend: function(){
				$('#notif').html("Proses kirim notif ke SPV..");
			},
			success: function(dataResult){
				console.log(dataResult);
				var dataResults = JSON.parse(dataResult);
				
				if(dataResults.result=='1'){
					$('#notif').html("<font style='color: green'>"+dataResults.msg+"</font>");
					// $("#example").load(" #example");
				
				}else{
					
					$('#notif').html("<font style='color: red'>"+dataResults.msg+"</font>");
					
				}
				
				
				
			}
					
				
				
				
			
		});
	
}

function loopingLine(){
	
	$.ajax({
			url: "api/action.php?modul=inventory&act=cek_approval",
			type: "GET",
			beforeSend: function(){
				$('#notif').html("Proses sync approval..");
			},
			success: function(dataResult){
				console.log(dataResult);
				var dataResult = JSON.parse(dataResult);
				
				if(dataResult.result=='1'){
					$('#notif').html("<font style='color: green'>"+dataResult.msg+"</font>");
					$("#example").load(" #example");
				
				}
				
				
				
			}
					
				
				
				
			
		});
	
}



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
				// $('#notif').html("<font style='color: green'>Berhasil input dengan product category!</font>");
				$("#example").load(" #example");
				
			}
			// else {
				// $('#notif').html(dataResult.msg);
			// }
			
		}
	});
	
}

function batalPI(m_pi_key){ 

	var formData = new FormData();
		
	formData.append('m_pi', m_pi_key);
	
	$.ajax({
		url: "api/action.php?modul=inventory&act=batal",
		type: "POST",
		data : formData,
		processData: false,
		contentType: false,
		success: function(dataResult){
			console.log(dataResult);
			var dataResult = JSON.parse(dataResult);
			if(dataResult.result=='1'){
				$('#notif1').html("<font style='color: red'>Berhasil membatalkan PI!</font>");
				$("#example").load(" #example");
				$(".modal").modal('hide');
			}
			// else {
				// $('#notif').html(dataResult.msg);
			// }
			
		}
	});
}

function releasePI(m_pi){
	var formData = new FormData();
	formData.append('m_pi', m_pi);
	$.ajax({
		url: "api/action.php?modul=inventory&act=release_all",
		type: "POST",
		data : formData,
		processData: false,
		contentType: false,
		beforeSend: function(){
			$('#notif').html("Proses release, jangan close halaman ini sampai selesai..");
			$(".modal").modal('hide');
			$("#overlay").fadeIn(300);
		},
		success: function(dataResult){
			console.log(dataResult);
			var dataResult = JSON.parse(dataResult);
			if(dataResult.result=='1'){
				// $('#notif').html("<font style='color: green'>"+dataResult.msg+"</font>");
				updateStatusRelease(m_pi);
				// $("#example").load(" #example");
				
			}
			// else {
				// $('#notif').html(dataResult.msg);
			// }
			
		}
	});
	
	
}


function updateStatusRelease(m_pi){
	var formData = new FormData();
	formData.append('m_pi', m_pi);
	$.ajax({
		url: "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=updatepi",
		type: "POST",
		data : formData,
		processData: false,
		contentType: false,
		beforeSend: function(){
			$('#notif').html("Proses update status di server, jangan close halaman ini sampai selesai..");
			// $(".modal").modal('hide');
		},
		success: function(dataResult){
			console.log(dataResult);
			var dataResult = JSON.parse(dataResult);
			if(dataResult.result=='1'){
				
				sendWa(m_pi);
				
				$('#notif').html("<font style='color: green'>"+dataResult.msg+"</font>");
				$("#example").load(" #example");
				$("#overlay").fadeOut(300);
				
			}
			// else {
				// $('#notif').html(dataResult.msg);
			// }
			
		}
	});
}


function releasePIGantung(m_pi){
	var formData = new FormData();
	formData.append('m_pi', m_pi);
	$.ajax({
		url: "api/action.php?modul=inventory&act=releasegantung",
		type: "POST",
		data : formData,
		processData: false,
		contentType: false,
		beforeSend: function(){
			$('#notif').html("Proses release, jangan close halaman ini sampai selesai..");
			$(".modal").modal('hide');
			$("#overlay").fadeIn(300);
		},
		success: function(dataResult){
			console.log(dataResult);
			var dataResult = JSON.parse(dataResult);
			if(dataResult.result=='1'){
				
				$('#notif').html("<font style='color: green'>"+dataResult.msg+"</font>");
				updateStatusRelease(m_pi);
				
				// $("#example").load(" #example");
				
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
			$('#notif').html("Sistem sedang melakukan input, jangan refresh halaman..");
			
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