





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
<body>
	<div class="main-content">
		<div id="page-wrapper">
			<div class="main-page login-page ">
			
				<h3 class="title1">Sign in Physical Inventory 1.0</h3>
				<div class="widget-shadow">
					<div class="login-body">
					
					
					<button style="width: 100%" type="button" id="btn-update" class="btn btn-danger">Cek Version</button>
					
					<form action="config/cek_login.php" method="POST">
						
					<p id="notif1" style="color: red; font-weight: bold"></p>
						
					<?php include "config/koneksi.php"; 
					
					
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
						$count = $cek->rowCount();
					 
						if($count == 0){
						
							echo '<button style="width: 100%" type="button" id="sync" class="btn btn-success" onclick="syncUser();">Sync Users</button>';
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
						<br>
							<input type="text" class="user" name="user" placeholder="username" required="">
							<input type="password" name="pwd" class="lock" placeholder="password">
							<input type="submit" name="Sign In" value="Continue"></input>
							
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