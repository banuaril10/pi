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
								<th>Aksi</th>
		
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
	
												echo "<tr>
													<td>".$no."</td>
													
													<td>".$row1['kode_pendaftaran']."</td>
													<td>".$row1['no_hp']."</td>
													<td>".$row1['nama']."</td>
													<td>".$row1['kategori']."</td>
													<td>".$row1['insertdate']."</td>
													<td><button class='btn btn-primary' onclick='cetakGeneric(\"".$row1['id']."\");'>Reprint</button></td>
													
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
        <input type="text" class="form-control" class="form-control" id="nama" name="nama" placeholder="Masukan nama lengkap pendaftar..">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label style="color: #fff"for="fname">No HP</label>
      </div>
      <div class="col-75">
        <input type="text" class="form-control" class="form-control" id="no_hp" name="no_hp" placeholder="Masukan nomor hp pendaftar..">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label style="color: #fff"for="country">Kategori</label>
      </div>
      <div class="col-75">
        <select class="form-control" id="kategori" name="kategori">
          <option value="A. TK atau PAUD">A. TK atau PAUD</option>
          <option value="B. SD Kelas 1/3">B. SD Kelas 1 s.d. 3</option>
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
$(document).ready( function () {
    $('#example').DataTable({
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
        ],
    });
} );


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
						cetakGeneric(dataResult.id);
						
						
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
	
	
function textbyline(str,intmax,stralign){
    var strresult='';
  if (stralign=='right'){
    strresult=str.padStart(intmax);
  } else if (stralign=='center'){
    var l = str.length;
    var w2 = Math.floor(intmax / 2);
    var l2 = Math.floor(l / 2);
    var s = new Array(w2 - l2 + 1).join(" ");
    str = s + str + s;
    if (str.length < intmax)
    {
        str += new Array(intmax - str.length + 1).join(" ");
    }
    strresult=str;
  } else {
    strresult=str;
  }
  return strresult;
};


function print_text(html){
	// console.log(html);
	$.ajax({
		url: "print.php",
		type: "POST",
		data : {html: html},
		success: function(dataResult){
			var dataResult = JSON.parse(dataResult);

			$('#notif').html("Proses print");
			
			
		}
	});
}
	
	
	
	
	
	
function cetakGeneric(id){
		
		// alert(mpi+'<br>'+rn+'<br>'+dn);		
		var number = 0;	
		var no = 1;	

		$.ajax({
			url: "api/action.php?modul=lomba&act=cetak_generic",
			type: "POST",
			data : {id: id},
			success: function(dataResult){
				var html = '';
				var dataResult = JSON.parse(dataResult);
				
				var panjang = dataResult.length;
				$('#notif').html("Proses print");
				
				for(let i = 0; i < dataResult.length; i++) {
						let data = dataResult[i];

						var kode_struk = data.kode_struk;
						var kode_pendaftaran = data.kode_pendaftaran;
						var nomor_urut = data.nomor_urut;
						var nama = data.nama;
						var no_hp = data.no_hp;
						var kategori = data.kategori;
						var ad_org_id = data.ad_org_id;
						var nama_toko = data.nama_toko;
						var insertdate = data.insertdate;
						var datenow = data.datenow;
						var brand = data.brand;
							
							html += textbyline(brand, 38, 'center')+'\n\r';
							html += textbyline('STRUK PENDAFTARAN LOMBA', 38, 'center')+'\n\r';
							html += textbyline('KODE DFTR.	:' + kode_pendaftaran, 24, 'left')+ '\r\n';
							html += textbyline('TOKO   		:' + nama_toko, 24, 'left')+ '\r\n';
							html += textbyline('NAMA   		:' + nama, 24, 'left') + '\r\n';
							html += textbyline('NO HP  		:' + no_hp, 24, 'left') + '\r\n';
							html += textbyline('KATEGORI  	:' + kategori, 24, 'left') + '\r\n';
							html += textbyline('TGL DAFTAR	:' + insertdate, 24, 'left')+ '\r\n';
							html += textbyline('TGL CETAK	:' + datenow, 24, 'left')+ '\r\n';
							html += '\n\r';
							number++;
							no++;
							
							
							if(number == panjang){
							
								html+='\n\r';
								html+='\n\r';
								html+='\n\r';
								html+='\n\r';
								html+='\n\r';
								html+='\n\r';
								print_text(html);
								console.log(html);
							}
					
				
				}
			}
		});

				
}
	
</script>
</div>
<?php include "components/fff.php"; ?>