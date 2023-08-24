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
				<h4>DATA PENDAFTARAN LOMBA</h4>

					<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">+</button>

				
				<font id="notif1" style="color: red; font-weight: bold"></font>	
			</div>
			<div class="card-body">
			<div class="tables">			
				<div class="table-responsive bs-example widget-shadow">	
					
				
				
				
					<table class="table table-bordered" id="example">
						<thead>
							<tr>
								
								<th>No</th>
								<th>Nomor Pendaftaran</th>
								<th>No. HP</th>
								<th>Nama</th>
								<th>Kategori</th>
								<th>Tgl Daftar</th>
		
							</tr>
						</thead>
						<tbody>
						
						<?php 
						
							$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
							$results = $connec->query($sqll);
							foreach ($results as $r) {
								$ad_morg_key = $r["ad_morg_key"];	
							}
					
							$json_url = "https://pi.idolmartidolaku.com/api/action.php?modul=lomba&act=get&ad_org_id=".$ad_morg_key;
							$json = file_get_contents($json_url);
						
					
							$arr = json_decode($json, true);
							$jum = count($arr);
							
							// var_dump($jsons);
							
							$s = array();
							if($jum > 0){
							$no = 1;
							foreach ($arr as $row1) {
	
												echo 
												"<tr>
													<td>".$no."</td>
													
													<td>".$row1['kode_pendaftaran']."</td>
													<td>".$row1['no_hp']."</td>
													<td>".$row1['nama']."</td>
													<td>".$row1['kategori']."</td>
													<td>".$row1['insertdate']."</td>
													
												</tr>";
												
												
								
								$no++;
								}
							}
							
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
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div id="overlay">
			<div class="cv-spinner">
				<span class="spinner"></span>
			</div>
		</div>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Input Data Pendaftaran Lomba</h5><br>
       
		 
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		
		
      </div>
	  
      <div class="modal-body" style="background: #6e6e6e">
	  
	   <form id="fupForm" method="post">
	    <p id="notif" style="color: red; font-weight: bold; background: #fff; padding: 10px"></p>
		
		<div class="row-info">  
	
	  
	   <div class="row">
      <div class="col-25">
        <label style="color: #fff"for="fname">Nama</label>
      </div>
      <div class="col-75">
        <input type="text" class="form-control" class="form-control" id="nama" name="nama" placeholder="Masukan nama lengkap..">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label style="color: #fff"for="fname">No HP</label>
      </div>
      <div class="col-75">
        <input type="text" class="form-control" class="form-control" id="no_hp" name="no_hp" placeholder="Masukan nomor hp kamu..">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label style="color: #fff"for="country">Kategori</label>
      </div>
      <div class="col-75">
        <select class="form-control" id="kategori" name="kategori">
          <option value="TK atau PAUD">TK atau PAUD</option>
          <option value="SD Kelas 1/3">SD Kelas 1/3</option>
        </select>
      </div>
    </div>
	
	<br>
			
		
			
		</div> 
		
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
        <button type="button" id="butsave" class="btn btn-primary">SUBMIT</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
	$('#butsave').on('click', function() {
	
		var no_hp = $('#no_hp').val();
		var nama = $('#nama').val();
		var kategori = $('#kategori').val();
	
		
		if(no_hp!="" && nama!="" && kategori!=""){
						
			$.ajax({
				url: "api/action.php?modul=lomba&act=input",
				type: "POST",
				data: {
					no_hp: no_hp,
					nama: nama,
					kategori: kategori,			
		
				},
				cache: false,
				beforeSend: function(){
					$('#notif').html("Proses input lomba..");
				},
				success: function(dataResult){
					console.log(dataResult);
					var dataResult = JSON.parse(dataResult);
					if(dataResult.result=='1'){
						$('#notif').html('<font style="color: green">'+dataResult.message+'</font>');
						$("#overlay").fadeOut(300);　
						$('#fupForm')[0].reset();
						$("#nama").focus();
					}
					else {
						$("#overlay").fadeOut(300);　
						$('#notif').html(dataResult.message);
					}
					
				}
			});
				
			}else{
				$('#notif').html("Lengkapi data dulu");
				
			}
	});
</script>
</div>
<?php include "components/fff.php"; ?>