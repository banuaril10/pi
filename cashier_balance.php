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
				<h4>SYNC BALANCE</h4>
				
				<font id="notif1" style="color: red; font-weight: bold"></font>	
			</div>
			<div class="card-body">
			<div class="tables">			
				<div class="table-responsive bs-example widget-shadow">	
					
					
		<?php 	if($_GET['tgl_awal'] && !empty($_GET['tgl_awal']) ){

				$tgl_awal = $_GET['tgl_awal'];
				

        }else{

				$tgl_awal = date('Y-m-d');
				
			}
	
	
		
		if($_GET['tgl_akhir'] && !empty($_GET['tgl_akhir']) ){

				$tgl_akhir = $_GET['tgl_akhir'];
				

        }else{

				$tgl_akhir = date('Y-m-d');
				
			}
			
			?>
				
				<!--<form action="api/action.php?modul=inventory&act=send_cashier1" method="POST">
					<button type="submit" class="btn btn-primary" name="reset">Sync 17-19</button>
				</form>-->
				
				<form action="">

						<div class="row mb-3">
						 
                                <div class="col mb-0">
                                  <label for="emailExLarge" class="form-label">Tanggal Mulai</label>
		
									<input type="date" name="tgl_awal" class="form-control" value="<?php echo $tgl_awal; ?>" />
                                </div>
								 <div class="col mb-0">
                                  <label for="dobExLarge" class="form-label">Tanggal Selesai</label>
		
									<input type="date" name="tgl_akhir" class="form-control" value="<?php echo $tgl_akhir; ?>" />
                                </div>
								
                            
                        </div>
			  
		<input type="submit" class="btn btn-primary" value="Filter">				  
		</form>				  
	
					<table class="table table-bordered" id="example">
						<thead>
							<tr>
								<th>No</th>
								<th>Tanggal</th>
								<th>Status</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
						
						<?php 
						
						function get_data_balance($org, $date){
	
							$curl = curl_init();
						
							curl_setopt_array($curl, array(
							CURLOPT_URL => "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=get_data_balance&org_id=".$org."&tgl=".$date,
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_ENCODING => '',
							CURLOPT_MAXREDIRS => 10,
							CURLOPT_TIMEOUT => 0,
							CURLOPT_FOLLOWLOCATION => true,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST => 'GET',
							));
							
							$response = curl_exec($curl);
							
							curl_close($curl);
							return $response;
							
							
						}
						
						
						
						$sql_list = "select date(insertdate) as dt from pos_dcashierbalance where date(insertdate) between '".$tgl_awal."' and '".$tgl_akhir."' group by date(insertdate) order by date(insertdate) desc";
						$no = 1;
						
						$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
						$results = $connec->query($sqll);
						foreach ($results as $r) {
							$org_keys = $r["ad_morg_key"];	
						}
						
						foreach ($connec->query($sql_list) as $row) {
		
						$jsons = get_data_balance($org_keys, $row['dt']);
						$arr = json_decode($jsons, true);
						$jum = count($arr);
						$s = array();
						$status = "<font style='background-color: red; color: #fff'>Not Yet</font>";
						$st = 0;
						if($jum > 0){
							
							foreach($arr as $j){
								
								if($j['jum'] > 0){
									
									$status = "<font style='background-color: green; color: #fff'>Done</font>";
									$st = 1;
								}
								
							}
							
							
						}
						
						
						?>
						
						
							<tr>
								<th scope="row"><?php echo $no; ?></th>
								<td><?php echo $row['dt']; ?></td>
								<td><?php echo $status; ?></td>
								<td>
								<button class="btn btn-primary" onclick="sendSync('<?php echo $row['dt']; ?>');">Sync Ulang</button>
								</td>
			
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
function sendSync(tgl){ 

	
	
	$.ajax({
		url: "api/action.php?modul=inventory&act=send_cashier&tgl="+tgl,
		type: "GET",
		processData: false,
		contentType: false,
		success: function(dataResult){

			$('#notif1').html("<font style='color: green'>Berhasil sync</font>");
			
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
		var sso = $('#status_sales').val();
		// var image = $('#image')[0].files[0];
		
		
		var formData = new FormData();
		
		formData.append('it', it);
		formData.append('sl', sl);
		formData.append('kat', kat);
		formData.append('rack', rack);
		formData.append('pc', pc);
		formData.append('sso', sso);
		
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
							$("#overlay").fadeIn(300);
						},
						success: function(dataResult){
							var dataResult = JSON.parse(dataResult);
							if(dataResult.result=='2'){
								$('#notif').html("Proses input ke inventory line");
								$("#overlay").fadeOut(300);
								// $("#example").load(" #example");
							}else if(dataResult.result=='1'){
								$('#notif').html("<font style='color: green'>Berhasil input dengan product category!</font>");
								$("#overlay").fadeOut(300);
								location.reload();
								$( "#butsave" ).prop( "disabled", false );
							}
							else {
								$('#notif').html(dataResult.msg);
								$( "#butsave" ).prop( "disabled", false );
								$("#overlay").fadeOut(300);
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
							$("#overlay").fadeIn(300);
						},
						success: function(dataResult){
							var dataResult = JSON.parse(dataResult);
							if(dataResult.result=='2'){
								$('#notif').html("Proses input ke inventory line");
								$( "#butsave" ).prop( "disabled", false );
								$("#overlay").fadeOut(300);
								// $("#example").load(" #example");
							}else if(dataResult.result=='1'){
								$('#notif').html("<font style='color: green'>Berhasil input dengan rack!</font>");
								$("#overlay").fadeOut(300);
								location.reload();
								$( "#butsave" ).prop( "disabled", false );
							}
							else {
								$('#notif').html(dataResult.msg);
								$( "#butsave" ).prop( "disabled", false );
								$("#overlay").fadeOut(300);
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
							$("#overlay").fadeIn(300);
						},
						success: function(dataResult){
							var dataResult = JSON.parse(dataResult);
							if(dataResult.result=='2'){
								$('#notif').html("Proses input ke inventory line");
								$( "#butsave" ).prop( "disabled", false );
								$("#overlay").fadeOut(300);
								// $("#example").load(" #example");
							}else if(dataResult.result=='1'){
								$('#notif').html("<font style='color: green'>Berhasil input dengan rack!</font>");
								$("#overlay").fadeOut(300);
								location.reload();
								$( "#butsave" ).prop( "disabled", false );
							}
							else {
								$('#notif').html(dataResult.msg);
								$("#overlay").fadeOut(300);
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
	
	
	
	function cekSalesOrder(org_id){
		
		$.ajax({
			url: "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=cek_sales&org_id="+org_id,
			type: "GET",
			beforeSend: function(){
				$("#overlay").fadeIn(300);
				$('#notif').html("Proses cek sales order gantung..");
			},
			success: function(dataResult){
				var dataResult = JSON.parse(dataResult);
				if(dataResult.result=='1'){
					$('#notif').html("<font style='color: green'>"+dataResult.msg+"</font>");
					$("#overlay").fadeOut(300);
					updateStatusSales(dataResult.stats);
					
					
				}else if(dataResult.result=='0'){
					$("#overlay").fadeOut(300);
					$('#notif').html(dataResult.msg);
					
					
				}
				
				
			}
		});
		
	}
	
	function updateStatusSales(stats){
		// alert(stats);
		$.ajax({
			url: "api/action.php?modul=inventory&act=update_sales&status_sales="+stats,
			type: "GET",
			beforeSend: function(){
				$('#notif').html("Proses cek sales order gantung..");
			},
			success: function(dataResult){
				
				console.log(dataResult);
				var dataResult = JSON.parse(dataResult);
				if(dataResult.result=='1'){
					$('#notif').html("<font style='color: green'>"+dataResult.msg+"</font>");
					$('#stats_sales_order').val(stats);
					
					// location.reload();
				}else if(dataResult.result=='0'){
					$('#notif').html(dataResult.msg);
					
					
				}
				
				
			}
		});
		
	}
	
	
	
	
	
</script>
</div>
<?php include "components/fff.php"; ?>