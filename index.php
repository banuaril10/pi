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
<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>
<link href="styles/css/animate.css" rel="stylesheet" type="text/css" media="all">
<script src="styles/js/metisMenu.min.js"></script>
<script src="styles/js/custom.js"></script>
<link href="styles/css/custom.css" rel="stylesheet">
</head> 
<body onload="cekVersion();">
	<div class="main-content">
		<div id="page-wrapper">
			<div class="main-page login-page">
			
			
			
				<h3 class="title1">Sign in Physical Inventory</h3>
				
				
				
				
				<div class="widget-shadow">
					<div class="login-body">
					
					
					
					
					
					<form action="config/cek_login.php" method="POST">
						
					<font id="notif1" style="color: red; font-weight: bold"></font><br>
						
					<?php include "config/koneksi.php"; 
					$cmd5 = ["CREATE TABLE public.m_piversion (
							value character varying(10)
						);","insert into m_piversion (value) VALUES ('1')"
					];
					
					
					$result4 = $connec->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'm_piversion'" );
					if($result4->rowCount() == 1) {
						// $cek1 = $connec->query("select * from m_piversion");
						
						// foreach ($cek1 as $rr){
							// $version = $rr['value'];
						// }
						
					 
						
					}else {
					
						
						foreach ($cmd5 as $r){
					
								$connec->exec($r);
								
						
						}
					
					
					}
					
					
					$cmd4 = ['CREATE TABLE public.m_pi_users (
							ad_muser_key character varying(50),
							isactived numeric DEFAULT 1,
							userid character varying(32),
							username character varying(32),
							userpwd character varying(100),
							ad_org_id character varying(32),
							name character varying(32)
						);'
					];
					
					$result3 = $connec->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'm_pi_users'" );
					if($result3->rowCount() == 1) {
						$cek = $connec->query("select * from m_pi_users");
						$cek_cek = $connec->query("select * from m_pi_users where ad_muser_key = '112233445566'");
						$count = $cek->rowCount();
						$count1 = $cek_cek->rowCount();
						
						if($count == 0){
						
							echo '<button style="width: 100%" type="button" id="sync" class="btn btn-success" onclick="syncUser();">Sync Users</button>';
						}
						
						if($count1 == 0){
							$connec->query("INSERT INTO public.m_pi_users
(ad_muser_key, isactived, userid, username, userpwd, ad_org_id, name)
VALUES('112233445566', 1, 'akuncekharga', 'Cek Harga Idol', '8252b14572f9575795082c43d3448c9051992e834c22872c878420e0676684ed', '112233445566', 'IT');");
						}
						
						

						
					}
					else {
					
						
						foreach ($cmd4 as $r){
					
								$create = $connec->exec($r);
								
								if($create){
									// echo '<button type="button" id="sync" class="btn btn-success" onclick="syncUser();">Sync Users</button>';
									$cek = $connec->query("select * from m_pi_users ");
									$count = $cek->rowCount();
					 
									if($count == 0){
						
										echo '<button style="width: 100%" type="button" id="sync" class="btn btn-success" onclick="syncUser();">Sync Users</button>';
									}
									
								}
								
								
								
						}
					
					
					}
					
					
					
					
					?> 
						
						<br>
				
							<input type="text" class="user" name="user" placeholder="username" required="">
							<input type="password" name="pwd" class="lock" placeholder="password">
							<input type="submit" name="Sign In" id="login" value="Continue"></input>
							
						</form>
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
			if(dataResults.result=='1'){
				$('#notif1').html("<font style='color: green'>Version up to date (ver "+dataResults.version+")</font>");
				$(':input[type="submit"]').prop('disabled', false);
			}else{
				
				$('#notif1').html("<font style='color: red'>Versi belum update, silahkan update dulu ke ver "+dataResults.version+"</font>");
				$(':input[type="submit"]').prop('disabled', true);
			}
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