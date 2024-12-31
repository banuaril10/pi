<!DOCTYPE HTML>
<html>
<head>
<title>Login Physical Inventory</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/css/bootstrap.css" rel='stylesheet' type='text/css' />
<link href="styles/css/style.css" rel='stylesheet' type='text/css' />
<link href="styles/css/font-awesome.css" rel="stylesheet"> 
<script src="styles/js/jquery-1.11.1.min.js"></script>
<script src="styles/js/modernizr.custom.js"></script>

<link href="styles/css/animate.css" rel="stylesheet" type="text/css" media="all">
<script src="styles/js/metisMenu.min.js"></script>
<script src="styles/js/custom.js"></script>
<link href="styles/css/custom.css" rel="stylesheet">
</head> 
<body onload="cekVersion();">
	<div class="main-content">
		<div id="page-wrapper">
			<div class="main-page login-page">
			
			
			
				<h3 class="title1">Sign in Store Apps</h3>
				
				
				
				
				<div class="widget-shadow">
					<div class="login-body">
					
					
					
					
					
					<form action="config/cek_login.php" method="POST">
						
					<font id="notif1" style="color: red; font-weight: bold"></font><br>
						
					<?php 
					include "config/koneksi.php"; 
					include "create_table.php"; 
					?> 
						
						<br>
				
							<input type="text" class="user" name="user" placeholder="username" required="" autofocus>
							<input type="password" name="pwd" class="lock" placeholder="password">
							<input type="submit" name="Sign In" id="login" value="Continue"></input>
							
						</form>
						
						<br>
						<p>Download apk inventory pada link berikut : <a style="font-weight: bold" href="https://pi.idolmartidolaku.com/api/pos/inventory-counter.apk"> Download</a></p>
						<p>Download POS Versi terbaru pada link berikut : <a style="font-weight: bold" href="https://pi.idolmartidolaku.com/api/pos/pos-winlin-v3.0.1.zip"> Download</a></p>
						
					</div>
				</div>
			</div>
		</div>
		<div class="footer">
		   <p>&copy; 2022 PT Idola Cahaya Semesta. All Rights Reserved</p>
		</div>
	</div>
	<script src="styles/js/jquery.nicescroll.js"></script>
	<script src="styles/js/scripts.js"></script>
   <script src="styles/js/bootstrap.js"> </script>
   
   
<script type="text/javascript">
  $('#btn-update').click(function(){
        var clickBtnValue = $(this).val();
        var ajaxurl = 'update.php',
        data =  {'action': clickBtnValue};
        $.post(ajaxurl, data, function (response) {
            // Response div goes here.
            
        });
    });

function cekVersion(){
	
	$.ajax({
		url: "api/cek_version.php",
		type: "GET",
		beforeSend: function(){
	
			$('#notif1').html("<font style='color: red'>Sedang mengecek version..</font>");
		},
		success: function(dataResult){
			// console.log(dataResult);
			var dataResults = JSON.parse(dataResult);
			// var btnoracle = "";
			// if(dataResults.oracle=='0'){
			// 	btnoracle = "<button type='button' id='sync' onclick='changeConfig();' class='btn btn-success'>Change Config</button>";
			// }

			if(dataResults.result=='1'){
				$('#notif1').html("<font style='color: green'>Version up to date (ver "+dataResults.version+") "+
				"<a target=_blank href='https://idolmart.co.id/live/pi/doc_pi.php'>Link update</a> &nbsp "+
				"<button type='button' onclick='updateVersion();' class='btn btn-danger'>Update</button></font> ");
				$(':input[type="submit"]').prop('disabled', false);
			}else{
				
				if(dataResults.version === null){
					var msg = "<font style='color: red'>Periksa koneksi internet dan ERP</font>";
					
				}else{
					
					var msg = "<font style='color: red'>Versi belum update, silahkan update dulu ke ver "+dataResults.version+"</font>";
					
				}
				
				$('#notif1').html(msg+" <a target=_blank href='https://idolmart.co.id/live/pi/doc_pi.php'>Link update</a> &nbsp <button type='button'"+
					"onclick='updateVersion();' class='btn btn-danger'>Update</button> ");
				// $(':input[type="submit"]').prop('disabled', true);
				$(':input[type="submit"]').prop('disabled', false);
			}
			// else {
				// $('#notif').html(dataResult.msg);
			// }
			
		}
	});
	
}


function updateVersion(){
	$.ajax({
		url: "api/update_version.php",
		type: "GET",
		beforeSend: function(){
			$('#notif1').html("<font style='color: red'>Sedang melakukan update, mohon tunggu..</font>");
		},
		success: function(dataResult){
			// console.log(dataResult);
			$('#notif1').html("<font style='color: green'>"+dataResult+"</font>");
			runPhp();
			// location.reload();
			// else {
				// $('#notif').html(dataResult.msg);
			// }
			
		}
	});
}

function changeConfig(){
	$.ajax({
		url: "api/cyber/change_config.php",
		type: "GET",
		beforeSend: function(){
			$('#notif1').html("<font style='color: red'>Sedang mengubah config, mohon tunggu..</font>");
		},
		success: function(dataResult){
			console.log(dataResult);
			$('#notif1').html("<font style='color: green'>"+dataResult+"</font>");
			// $('#notif1').html("<font style='color: green'>"+dataResult+"</font>");
		}
	});
}

function runPhp(){
	$.ajax({
		url: "update.php",
		type: "GET",
		success: function(dataResult){
			// console.log(dataResult);
			// $('#notif1').html("<font style='color: green'>"+dataResult+"</font>");
			location.reload();
			// else {
				// $('#notif').html(dataResult.msg);
			// }
			
		}
	});
	
}

function syncUser(){
	

	
	$.ajax({
		url: "api/register.php",
		type: "GET",
		beforeSend: function(){
			$('#sync').prop('disabled', true);
			$('#notif1').html("<font style='color: red'>Sedang melakukan sync, mohon tunggu..</font>");
		},
		success: function(dataResult){
			// console.log(dataResult);
			var dataResults = JSON.parse(dataResult);
			if(dataResults.result=='1'){
				$('#notif1').html("<font style='color: green'>"+dataResults.msg+"</font>");
				$("#example").load(" #example");
			}else{
				
				$('#notif1').html("<font style='color: green'>"+dataResult+"</font>");
				
			}
			// else {
				// $('#notif').html(dataResult.msg);
			// }
			
		}
	});
	
}

</script>
   
   
</body>
</html>