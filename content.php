<?php include "config/koneksi.php"; ?>
<?php include "components/main.php"; ?>
<?php include "components/sidebar.php"; ?>


<?php
		$sql_sales = "select count(tanggal) as jums from m_pi_sales where date(tanggal) = '".date('Y-m-d')."'";
		
		$rsa = $connec->query($sql_sales);
			
		foreach ($rsa as $h){ 
			
		
			if($h['jums'] > 0){ 
			
				$sql_sales1 = "select status_sales from m_pi_sales where date(tanggal) = '".date('Y-m-d')."'";
		
				$rsa1 = $connec->query($sql_sales1);
					foreach ($rsa1 as $h1){
					$_SESSION['status_sales'] = $h1['status_sales'];
						
						if($_SESSION['status_sales'] == '1'){
							
							$status_gantung = 1;
						}else{
							$status_gantung = 0;
						}
					}	
			?>
				
				
			<?php }else{ 
				$status_gantung = 2;
			
			?>
				
				
			<?php }
			
		}	
		
?>



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
				<h4>INVENTORY LIST</h4>
				<p>Note : Proses input header sekaligus sync dari ERP, sedikit memakan waktu</p>
				<?php if($status_gantung == 1){ ?>
					
					<!--<font style="color: red; font-weight: bold">Ada sales order gantung, tetap bisa melakukan PI tapi proses agak lambat</font><br>-->
					<button type="button" onclick="cekSalesOrder('<?php echo $org_key; ?>');" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">+</button>
					<!--<button type="button" class="btn btn-success" onclick="cekSalesOrder('<?php echo $org_key; ?>');">Cek Sales Gantung</button>-->
				<?php }else if($status_gantung == 0){ ?>
					<!--<font style="color: green; font-weight: bold">Sales order sudah komplit</font><br>-->
					<button type="button" onclick="cekSalesOrder('<?php echo $org_key; ?>');" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">+</button>
				<?php }else if($status_gantung == 2){ ?>
					<!--<button type="button" class="btn btn-success" onclick="cekSalesOrder('<?php echo $org_key; ?>');">Cek Sales Gantung</button>-->
					<button type="button" onclick="cekSalesOrder('<?php echo $org_key; ?>');" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">+</button>
				<?php } ?>
				
				<font id="notif1" style="color: red; font-weight: bold"></font>	
			</div>
			<div class="card-body">
			<div class="tables">			
				<div class="table-responsive bs-example widget-shadow">	
					
				
				<input type="hidden" id="stats_sales_order" value="<?php echo $_SESSION['status_sales']; ?>">
				
				
					<table class="table table-bordered" id="example">
						<thead>
							<tr>
								<th>No</th>
								<th>Document No</th>
								<th>Tanggal / Description</th>
								<th>Post By</th>
								<th>Type</th>
								<th>Status</th>
								<th>Status Sync ERP</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
						
						<?php 
						$sql_list = "select m_pi_key, name ,insertdate, rack_name, insertby, status, m_locator_id, inventorytype, category from m_pi 
						where status = '1' and inventorytype = '".$_SESSION['role']."' and date(insertdate) = date(now()) order by insertdate desc";
						$no = 1;
						foreach ($connec->query($sql_list) as $row) {
						if($row['status'] == 1){
							$stat = 'Draft';
							
						}else if($row['status'] == 2){
							$stat = 'Verifikasi';
							
						}else{
							
							$stat = $row['status'];
						}
						
						if($row['category'] == 1){
							$cat = 'RACK';
							
						}else if($row['category'] == 3){
							$cat = 'ITEMS';
							
						}else if($row['category'] == 2){
							$cat = 'CATEGORY';
							
						}
						
						
						
						$sql_amount = "select coalesce(SUM(CASE WHEN status=1 THEN 1 ELSE 0 END),0) jumsync, count(sku) jumline
						from m_piline where m_pi_key = '".$row['m_pi_key']."'";
						foreach ($connec->query($sql_amount) as $tot) {
							
							$jumsync = $tot['jumsync'];
							$jumline = $tot['jumline'];
							
						}
						
						if($jumsync == $jumline){
							
							$color = 'blue';
							$statt = 1;
						}else{
							
							$color = 'red';
							$statt = 0;
						}
						
						$jumrelease = '<font style="color: '.$color.'; font-weight: bold">'.$jumsync.' / '.$jumline.' Items</font>';
						
						?>
						
						
							<tr>
								<th scope="row"><?php echo $no; ?></th>
								<td><?php echo $row['name']; ?><br>
								
								
									<a href="invlist.php?m_pi=<?php echo $row['m_pi_key']; ?>&kat=<?php echo $row['category']; ?>" class="btn btn-primary">Counting</a>
						
								
								</td>
								
								<td><?php echo $row['insertdate']; ?><br><?php echo $cat; ?> : <b><?php echo $row['rack_name']; ?></b></td>
					
					
								
								<td><?php echo $row['insertby']; ?></td>
								<td><?php echo $row['inventorytype']; ?></td>
								<td><?php echo $stat; ?><br>
								
					
								

								</td>
								<td><?php echo $jumrelease; ?><br>
								<?php if($statt == 0){ ?>
								<button type="button" onclick="syncErp('<?php echo $row['m_pi_key']; ?>');" class="btn btn-primary">Sync Ulang</button>	
									
								<?php }else if($statt == 1){ ?>
									
								<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $row['m_pi_key']; ?>">Verifikasi</button>	
									
								<?php } ?>
								
								</td>
								
								<td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#batal<?php echo $row['m_pi_key']; ?>">Batalkan</button></td>
								
							</tr>
							
							
							
							<div class="modal fade" id="exampleModal<?php echo $row['m_pi_key']; ?>" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin melakukan verifikasi?</h5>
								
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
									<button onclick="ubahStatus('<?php echo $row['m_pi_key']; ?>');" class="btn btn-success">YAKIN</button>
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
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div id="overlay">
			<div class="cv-spinner">
				<span class="spinner"></span>
			</div>
		</div>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Description</h5><br>
       
		 
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		
		
      </div>
	  
      <div class="modal-body" style="background: #cacaca">
	  
	  
	    <p id="notif" style="color: red; font-weight: bold; background: #fff; padding: 10px"></p>
		
		<div class="row-info"> 
			
			<select name="it" id="it" class="selectize" required>
				
				<option value="<?php echo $_SESSION['role']; ?>"><?php echo $_SESSION['role']; ?></option>
			</select>
			
			<select name="sl" id="sl" class="selectize">
				<?php 
				
				
				
				$sql2 = "select m_locator_id ,locator_name from pos_mproduct WHERE locator_name ilike '%GR AREA BOS%' group by m_locator_id ,locator_name";
	
				foreach ($connec->query($sql2) as $row) {
					echo '<option value="'.$row['m_locator_id'].'">'.$row['locator_name'].'</option>';	    
				}
				?>
			</select>
			<select name="kat" id="kat" onchange="selectKat();" class="selectize">
				<option value="">Kategori PI</option>
				
				
			<?php if($_SESSION['role'] == 'Global'){ ?>	
				<option value="1">Product Category</option>
				
			<?php } ?>	
				
				<option value="2">Rack</option>
				<option value="3">Items</option>
			</select>
		<div id="pc" style="display: none">
			<select name="pc" id="pc"class="selectize" >
				<option value="">Product Category</option>			
				<?php 
				$sql = "select * from inv_mproductcategory";
	
				foreach ($connec->query($sql) as $row) {
					echo '<option value="'.$row['m_product_category_id'].'">'.$row['value'].'</option>';	    
				}
				?>
			</select>
		</div>
		<div id="rack" style="display: none">
			<select name="rack" id="rack" class="selectize">
				<option value="">Rack Name</option>
				<?php 
				$sql1 = "select rack_name from inv_mproduct where not rack_name isnull group by rack_name order by rack_name";
	
				foreach ($connec->query($sql1) as $row) {
					echo '<option value="'.$row['rack_name'].'">'.$row['rack_name'].'</option>';	    
				}
				?>
				
				
				
				
			</select>
			
		</div>	
			
		</div> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
        <button type="button" id="butsave" class="btn btn-primary">SUBMIT</button>
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

function syncErp(m_pi){
	
	// alert("cu");
	var formData = new FormData();
		
	formData.append('m_pi', m_pi);
	
	$.ajax({
		url: "api/action.php?modul=inventory&act=sync_erp",
		type: "POST",
		data : formData,
		processData: false,
		contentType: false,
		beforeSend: function(){
			$('#notif1').html("<font style='color: red'>Sedang melakukan sync..</font>");
		},
		success: function(dataResult){
			console.log(dataResult);
			var dataResult = JSON.parse(dataResult);
			if(dataResult.result=='1'){
				$('#notif1').html("<font style='color: green'>"+dataResult.msg+"</font>");
				$("#example").load(" #example");
				$(".modal").modal('hide');
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
		var sso = $('#stats_sales_order').val();
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
						url: "api/action.php?modul=inventory&act=input_kat",
						type: "POST",
						data : formData,
						processData: false,
						contentType: false,
						beforeSend: function(){
							$('#notif').html("Proses input header dan line..");
							$("#overlay").fadeIn(300);
						},
						success: function(dataResult){
							console.log(dataResult);
							
							
							// if (!$.trim(dataResult)){   
								
								if(dataResult){
									var dataResult = JSON.parse(dataResult);
									if(dataResult.result=='2'){
										$('#notif').html("Proses input ke inventory line");
										$( "#butsave" ).prop( "disabled", false );
										$("#overlay").fadeOut(300);
										location.reload();
									}else if(dataResult.result=='1'){
										$('#notif').html("<font style='color: green'>Berhasil input dengan category!</font>");
										$("#overlay").fadeOut(300);
										location.reload();
										$( "#butsave" ).prop( "disabled", false );
									}
									else {
										$('#notif').html(dataResult.msg);
										$( "#butsave" ).prop( "disabled", false );
										$("#overlay").fadeOut(300);
									}
									
								}else{
									
										$('#notif').html("Items tidak ditemukan");
										$( "#butsave" ).prop( "disabled", false );
										$("#overlay").fadeOut(300);
									
								}
								
								
								
								
							// }
							// else{   
								// $('#notif').html("Items tidak ditemukan");
								// $( "#butsave" ).prop( "disabled", false );
								// $("#overlay").fadeOut(300);
							// }
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
							console.log(dataResult);
							var dataResult = JSON.parse(dataResult);
							if(dataResult.result=='2'){
								$('#notif').html("Proses input ke inventory line");
								$( "#butsave" ).prop( "disabled", false );
								$("#overlay").fadeOut(300);
								location.reload();
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
							console.log(dataResult);
							var dataResult = JSON.parse(dataResult);
							if(dataResult.result=='2'){
								$('#notif').html("Proses input ke inventory line");
								$( "#butsave" ).prop( "disabled", false );
								$("#overlay").fadeOut(300);
								location.reload();
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
		// alert(org_id);
		$.ajax({
			url: "https://pi.idolmartidolaku.com/api/action.php?modul=inventory&act=cek_sales&org_id="+org_id,
			type: "GET",
			beforeSend: function(){
				$("#overlay").fadeIn(300);
				$('#notif').html("Proses cek sales order gantung..");
				$(".row-info").hide();
				$(".modal-footer").hide();
				
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
					$(".row-info").show();
					$(".modal-footer").show();
					// location.reload();
				}else if(dataResult.result=='0'){
					$('#notif').html(dataResult.msg);
					
					
				}else {
					$('#notif').html(dataResult.msg);
					
					
				}
				
				
			}
		});
		
	}
	
	
	
	
	
</script>
</div>
<?php include "components/fff.php"; ?>