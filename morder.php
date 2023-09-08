<?php include "config/koneksi.php"; ?>
<?php include "components/main.php"; ?>
<?php include "components/sidebar.php"; ?>
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
				<h4>LIST D ORDER</h4>
			</div>
			<div class="card-body">
			<div class="tables">			
				<div class="table-responsive bs-example widget-shadow">	
				<p id="notif1" style="color: red; font-weight: bold"></p>


			<form action="" method="GET">
				
			 <table>
 
 
				<tr>
					<td><input type="date" id="tgl" name="tgl" class="form-control text-search">
			
				</select></td>
				<td>
					<button class="btn btn-success" type="submit" >Cari</button>
				</td>
				</tr>
			</table>	
			</form>	


				
					<table class="table table-bordered" id="example">
						<thead>
							<tr>
								<th>No</th>
								<th>insertdate</th>
								<th>insertby</th>
								<th>postby</th>
								<th>postdate</th>
								<th>documentno</th>
								<th>orderamount</th>
								<th>orderamountwebpos</th>
								<th>issync</th>
								<th>orderdate</th>
								<th>paymentmethodname</th>
								<th>cashamount</th>
								<th>bankname</th>
								<th>edcname</th>
								<th>pointamount</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						
						function get_data_amount($docno){
							$postData = array(
								"docno" => $docno
							);				    
							$fields_string = http_build_query($postData);		    
							$curl = curl_init();
						
							curl_setopt_array($curl, array(
							CURLOPT_URL => "https://pi.idolmartidolaku.com/api/action.php?modul=d_order_web_pos",
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_ENCODING => '',
							CURLOPT_MAXREDIRS => 10,
							CURLOPT_TIMEOUT => 0,
							CURLOPT_FOLLOWLOCATION => true,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST => 'POST',
							CURLOPT_POSTFIELDS => $fields_string,
							));
							
							$response = curl_exec($curl);
							
							curl_close($curl);
							return $response;
						
						
						}
						
						function rupiah($angka){
	
							$hasil_rupiah = number_format($angka,0,',','.');
							return $hasil_rupiah;
 
						}
						$sql_list = "select * from pos_dorder pd where date(insertdate) = '".$_GET['tgl']."'";
						$no = 1;
						foreach ($connec->query($sql_list) as $row) {
							$orderamount = 0;
							
							$jsons = get_data_amount($row['documentno']);
							$arrs = json_decode($jsons, true);
							
							foreach ($arrs as $rows) { 
									$orderamount = $rows['orderamount'];
							}
							
							// $link = "https://pi.idolmartidolaku.com/api/action.php?modul=d_order_web_pos&documentno=".$row['documentno'];
							// $jsons = file_get_contents($link, false);
							// echo $link;
							// echo $jsons;
						
						?>
						
						
							<tr>
								<th scope="row"><?php echo $no; ?></th>
								<!--<td><?php echo $row['pos_dorder_key']; ?></td>
								<td><?php echo $row['ad_mclient_key']; ?> </td>
								<td><?php echo $row['ad_morg_key']; ?> </td>
								<td><?php echo $row['isactived']; ?> </td>-->
								<td><?php echo $row['insertdate']; ?> </td>
								<td><?php echo $row['insertby']; ?> </td>
								<td><?php echo $row['postby']; ?> </td>
								<td><?php echo $row['postdate']; ?> </td>
								<td><?php echo $row['documentno']; ?> </td>
								<td><?php echo rupiah($row['orderamount']); ?> </td>
								<td><?php echo rupiah($orderamount); ?> </td>
								<td><?php echo $row['issync']; ?> </td>
								<td><?php echo $row['orderdate']; ?> </td>
								<td><?php echo $row['paymentmethodname']; ?> </td>
								<td><?php echo rupiah($row['cashamount']); ?> </td>
								<td><?php echo $row['bankname']; ?> </td>
								<td><?php echo $row['edcname']; ?> </td>
								<td><?php echo $row['pointamount']; ?> </td>
								
				
								
							</tr>
							
							
							
							
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

function syncMaster(){
	

	
	$.ajax({
		url: "api/action.php?modul=inventory&act=sync_inv",
		type: "GET",
		beforeSend: function(){
			 $('#sync').prop('disabled', true);
			$('#notif1').html("<font style='color: red'>Sedang melakukan sync, sabar ya..</font>");
		},
		success: function(dataResult){
			// console.log(dataResult);
			var dataResult = JSON.parse(dataResult);
			if(dataResult.result=='1'){
				$('#notif1').html("<font style='color: green'>"+dataResult.msg+"</font>");
				$("#example").load(" #example");
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