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
				<h4>DATA MEMBER</h4>

					<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">+</button>

				
				<font id="notif1" style="color: red; font-weight: bold"></font>	
			</div>
			<div class="card-body">
			<div class="tables">			
				<div class="table-responsive bs-example widget-shadow">	
					
				
				
				
					<table class="table table-bordered" id="example">
						<thead>
							<tr>
								
								<th>Kartu Member</th>
								<th>No. HP</th>
								<th>Nama</th>
								<th>Point</th>
								<th>Post By</th>
		
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
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div id="overlay">
			<div class="cv-spinner">
				<span class="spinner"></span>
			</div>
		</div>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Input Data Member</h5><br>
       
		 
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		
		
      </div>
	  
      <div class="modal-body" style="background: #cacaca">
	  
	   <form id="fupForm" method="post">
	    <p id="notif" style="color: red; font-weight: bold; background: #fff; padding: 10px"></p>
		
		<div class="row-info"> 
			
			<div class="row">
      <div class="col-25">
        <label style="color: #fff"for="fname">Nomor Whatsapp</label>
      </div>
      <div class="col-75">
	  
	<table>
		<tr><td> <input type="number" class="form-control" id="no_hp" name="firstname" placeholder="08123456789"></td><td> <button type="button" onclick="cekmember();" class="btn btn-primary">Validasi Nomor</button></td></tr>
     </table>  
       
		
		
		
      </div>
    </div>    
	
	<div class="row">
      <div class="col-25">
        <label style="color: #fff"for="fname">Kode OTP</label>
      </div>
      <div class="col-75">
        <input type="text" class="form-control" id="kode" name="kode" autocomplete="false" placeholder="kode otp..">
      </div>
    </div>	
	
        <input type="hidden" class="form-control" value="123456" id="password" name="firstname" placeholder="Masukan password kamu..">
        <input type="hidden" class="form-control" value="<?php echo $_SESSION['org_key']; ?>" id="org_key" name="org_key" placeholder="Masukan password kamu..">
        <input type="hidden" class="form-control" value="<?php echo $_SESSION['username']; ?>" id="nama_insert" name="nama_insert" placeholder="Masukan password kamu..">
	  
	   <div class="row">
      <div class="col-25">
        <label style="color: #fff"for="fname">Nomor Kartu Member</label>
      </div>
      <div class="col-75">
        <input type="number" class="form-control" class="form-control" id="membercardno" name="membercardno" placeholder="Masukan nomor kartu..">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label style="color: #fff"for="fname">Nama Lengkap</label>
      </div>
      <div class="col-75">
        <input type="text" class="form-control" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Masukan nama lengkap kamu..">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label style="color: #fff"for="country">Jenis Kelamin</label>
      </div>
      <div class="col-75">
        <select class="form-control" id="jk" name="country">
          <option value="Male">Laki-Laki</option>
          <option value="Female">Perempuan</option>
        </select>
      </div>
    </div>
	<div class="row">
      <div class="col-25">
        <label style="color: #fff"for="fname">Tanggal Lahir</label>
      </div>
      <div class="col-75">
	  
	  <input type="hidden" class="form-control" id="from" value="1900-01-01"></input>
	  <input type="hidden" class="form-control" id="to" value="<?php echo date('Y-m-d'); ?>"></input>
	  
	  
		<input class="form-control" id="tgl_lahir" type='date'></input>
		
      </div>
    </div>
	
	<div class="row">
      <div class="col-25">
        <label style="color: #fff"for="fname" >Kota/Kabupaten</label>
      </div>
      <div class="col-75">
        <input class="form-control" type="text" id="kota" name="firstname" placeholder="Jakarta">
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
  $(function(){
 
           $('.table').DataTable({
              "processing": true,
              "serverSide": true,
              "ajax":{
                       "url": "api/action.php?modul=inventory&act=api_mmember",
                       "dataType": "json",
                       "type": "POST"
                     },
              "columns": [
                  // { "data": "no" },
                  { "data": "membercardno" },
                  { "data": "nohp" },
                  { "data": "name" },
                  { "data": "point" },
                  { "data": "insertby" },
                  // { "data": "price" },
                  // { "data": "price_discount" },
              ]  
 
          });
        });


	function saveLokal(ad_morg_key, isactived, insertdate, insertby, postby, postdate, memberid, name, dateofbirth, point, membercardno, nohp,seqno){
		
		$.ajax({
				url: "api/action.php?modul=inventory&act=input_member",
				type: "POST",
				data: {
					ad_morg_key: ad_morg_key,
					isactived: isactived,
					insertdate: insertdate,
					insertby: insertby,			
					postby: postby,			
					postdate: postdate,			
					memberid: memberid,			
					name: name,
					dateofbirth: dateofbirth,
					point: point,
					membercardno: membercardno,			
					nohp: nohp,			
					seqno: seqno,			
				},
				cache: false,
				beforeSend: function(){
					$('#notif').html("Proses input member dilokal..");
				},
				success: function(dataResult){
					console.log(dataResult);
					var dataResult = JSON.parse(dataResult);
					if(dataResult.result=='1'){
						$('#notif').html('<font style="color: green">'+dataResult.msg+'</font>');
						$("#overlay").fadeOut(300);　
						
						
						$('#fupForm')[0].reset();
						$("#no_hp").focus();
						
						// $('#fupForm')[0].reset();
						// $("#no_hp").focus();
						
						
					}
					else {
						$("#overlay").fadeOut(300);　
						$('#notif').html(dataResult.msg);
					}
					
				}
			});

	}
		






	$('#butsave').on('click', function() {
	
		var no_hp = $('#no_hp').val();
		var password = $('#password').val();
		var no_ktp = "";
		var nama_lengkap = $('#nama_lengkap').val();
		var tgl_lahir = $('#tgl_lahir').val();
		var jk = $('#jk').val();
		var kota = $('#kota').val();	
		var membercardno = $('#membercardno').val();	
		var org_key = $('#org_key').val();	
		var nama_insert = $('#nama_insert').val();	
		var from = $('#from').val();	
		var to = $('#to').val();	
		
		
		if(no_hp!="" && nama_lengkap!="" && tgl_lahir!="" && jk!="" && kota!=""){
			// var  mydate = new Date(tgl_lahir);
			// alert(mydate);
			
			const isDateInRage = (startDate, endDate) => (dateToCheck) => {
				return dateToCheck >= startDate && dateToCheck <= endDate
			}
			
			const isInRangeOne = isDateInRage(from, to)
			
			if(isInRangeOne(tgl_lahir)){
				
				
				
				if (localStorage.getItem("otp") === null) {
					$('#notif').html("Nomor belum divalidasi..");
				}else{
					
					if (localStorage.getItem("otp") == '1') {
						
										$.ajax({
				url: "https://idolmart.co.id/e-idol/action.php?modul=regis&act=input",
				type: "POST",
				data: {
					nohp: no_hp,
					password: password,
					noktp: "",
					name: nama_lengkap,			
					dateofbirth: tgl_lahir,			
					gender: jk,			
					city: kota,			
					membercardno: membercardno,			
					org_key: org_key,			
					nama_insert: nama_insert,			
				},
				cache: false,
				beforeSend: function(){
					$('#notif').html("Proses input member..");
				},
				success: function(dataResult){
					console.log(dataResult);
					var dataResult = JSON.parse(dataResult);
					if(dataResult.result=='1'){
						$('#notif').html('<font style="color: green">'+dataResult.message+'</font>');
						$("#overlay").fadeOut(300);　
						saveLokal(dataResult.ad_morg_key,
						dataResult.isactived,
						dataResult.insertdate,
						dataResult.insertby,
						dataResult.postby,
						dataResult.postdate,
						dataResult.memberid,
						dataResult.name,
						dataResult.dateofbirth,
						dataResult.point,
						dataResult.membercardno,
						dataResult.nohp,
						dataResult.seqno
						
						);
						
					}
					else {
						$("#overlay").fadeOut(300);　
						$('#notif').html(dataResult.message);
					}
					
				}
			});
						
						
						
						
					}else{
						$('#notif').html("Nomor gagal divalidasi.. pastikan nomor benar dan aktif Whatsapp");
						
					}
					
				}
				

			
			
			
			
			
			
			
			
			
		}else{
			
			
			$('#notif').html("Tgl lahir tdk valid harus diantara 01/01/1900 s.d Tgl sekarang");
		}
				
			}else{
				$('#notif').html("Lengkapi data dulu");
				
			}


			
		
			
			
		
	
	});




	var input = document.getElementById("no_hp");
	input.addEventListener("keypress", function(event) {
	if (event.key === "Enter") {
		event.preventDefault();
		cekmember();
	}
	});

	var input1 = document.getElementById("kota");
	input1.addEventListener("keypress", function(event) {
	if (event.key === "Enter") {
		event.preventDefault();
		document.getElementById("butsave").click();
	}
	});
	
	
	function cekmember(){
		var no_hp = $('#no_hp').val();
		var val = Math.floor(1000 + Math.random() * 9000);
		if(no_hp!=""){

			$.ajax({
				url: "https://idolmart.co.id/e-idol/action.php?modul=regis&act=cekmember&no_hp="+no_hp, //cek member jika ada kirim otp
				type: "GET",
				cache: false,
				beforeSend: function(){
					$('#notif').html("Cek nomor member..");
				},
				success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.result=='0'){

						$('#notif').html(dataResult.msg);
						
					}
					else {
						$('#notif').html(dataResult.msg);
						sendOtp();
					}
					
				}
			});
		}
		else{

			$('#notif').html("Masukan nomor hp..");
		}
		
		
	}

	localStorage.removeItem("otp");
	function sendOtp(){
		var no_hp = $('#no_hp').val();
		var val = Math.floor(1000 + Math.random() * 9000);
		if(no_hp!=""){
			$("#overlay").fadeIn(300);
			$.ajax({
				url: "https://idolmart.co.id/e-idol/action.php?modul=regis&act=resend&no_hp="+no_hp+"&kode_otp="+val, //cek member jika ada kirim otp
				type: "GET",
				cache: false,
				success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.result=='1'){
						$("#overlay").fadeOut(300);
						$('#notif').html(dataResult.msg);
						$('#kode').val(val);
						localStorage.setItem("otp","1");
						$("#membercardno").focus();
					}
					else {
						localStorage.setItem("otp","0");
						$("#overlay").fadeOut(300);
						$('#notif').html(dataResult.msg);
					}
					
				}
			});
		}
		else{
			alert('Masukan nomor hp..');
		}
	};


</script>
</div>
<?php include "components/fff.php"; ?>