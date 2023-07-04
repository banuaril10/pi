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
			
			
			
				<h3 class="title1">Sign in Store App</h3>
				
				
				
				
				<div class="widget-shadow">
					<div class="login-body">
					
					
					
					
					
					<form action="config/cek_login.php" method="POST">
						
					<font id="notif1" style="color: red; font-weight: bold"></font><br>
						
					<?php include "config/koneksi.php"; 
					
					
					
					$cmd_cash = ['CREATE TABLE public.cash_in (
						cashinid character varying(32) DEFAULT public.get_uuid() NOT NULL PRIMARY KEY,
						org_key character varying(32),
						userid character varying(32),
						nama_insert character varying(50),
						cash numeric DEFAULT 0 NOT NULL,
						insertdate timestamp without time zone,
						status character varying(10),
						approvedby character varying(50),
						syncnewpos numeric DEFAULT 0 NOT NULL,
						setoran numeric DEFAULT 0 NOT NULL
					);'];
					
					$cmd_alter_cash = ['ALTER TABLE cash_in ADD COLUMN IF NOT EXISTS syncnewpos numeric DEFAULT 0 NOT NULL;','ALTER TABLE cash_in ADD COLUMN IF NOT EXISTS setoran numeric DEFAULT 0 NOT NULL;'];
					
					
					$cmd = ['CREATE TABLE public.m_pi (
						m_pi_key character varying(32) DEFAULT public.get_uuid() NOT NULL,
						ad_client_id character varying(32),
						ad_org_id character varying(32),
						isactived character varying(2),
						insertdate timestamp without time zone,
						insertby character varying(50),
						m_locator_id character varying(32),
						inventorytype character varying(30),
						name character varying(50),
						description character varying(255),
						movementdate timestamp without time zone,
						approvedby character varying(50),
						status character varying(20),
						issync boolean DEFAULT false,
						rack_name character varying(32),
						postby character varying(150),
						postdate timestamp without time zone,
						category numeric DEFAULT 1 NOT NULL,
						insertfrommobile character varying(15),
						insertfromweb character varying(15)
					);','CREATE INDEX m_pi_m_pi_key_idx ON public.m_pi USING btree (m_pi_key);','CREATE INDEX m_pi_name_idx ON public.m_pi USING btree (name);'];
					
					$cmd_alter = ['ALTER TABLE m_pi ADD COLUMN IF NOT EXISTS insertfrommobile varchar(15);','ALTER TABLE m_pi ADD COLUMN IF NOT EXISTS insertfromweb varchar(15);'];
					
					
					
					$cmd2 = ['CREATE TABLE public.m_piline (
						m_piline_key character varying(32) DEFAULT public.get_uuid() NOT NULL,
						m_pi_key character varying(32),
						ad_client_id character varying(32),
						ad_org_id character varying(32),
						isactived character varying(2),
						insertdate timestamp without time zone,
						insertby character varying(50),
						postby character varying(50),
						postdate timestamp without time zone,
						m_storage_id character varying(32),
						m_product_id character varying(32),
						sku character varying(50),
						qtyerp numeric,
						qtycount numeric DEFAULT 0,
						issync integer DEFAULT 0,
						status numeric DEFAULT 0,
						verifiedcount numeric DEFAULT 0,
						qtysales numeric DEFAULT 0,
						price numeric DEFAULT 0,
						status1 numeric DEFAULT 0,
						qtysalesout numeric DEFAULT 0,
						barcode varchar(30)
					);','CREATE INDEX m_piline_insertdate_idx ON public.m_piline USING btree (insertdate);',
					'CREATE INDEX m_piline_m_pi_key_idx ON public.m_piline USING btree (m_pi_key);',
					'CREATE INDEX m_piline_sku_idx ON public.m_piline USING btree (sku);'
					];
					
					
					$cmd2_alter_piline = ['ALTER TABLE m_piline ADD COLUMN IF NOT EXISTS barcode varchar(30);'];
					$cmd2_alter_piline1 = ['ALTER TABLE m_piline ADD COLUMN IF NOT EXISTS hargabeli numeric;'];
					
					$cmd_alter_salesline = ['ALTER TABLE pos_dsalesline ADD COLUMN IF NOT EXISTS status_sales numeric DEFAULT 0 NOT NULL;'];

					foreach ($cmd_alter_salesline as $r){
						$connec->exec($r);
					}
					
					$cmd3 = ['CREATE TABLE public.m_pi_sales (
						tanggal timestamp without time zone,
						status_sales numeric DEFAULT 0
					);'
					];
					
					$inv_temp = ['CREATE TABLE public.inv_temp (
						sku varchar NULL,
						qty numeric NULL,
						filename varchar NULL,
						tanggal timestamp NULL,
						status numeric NULL
					);'
					];
					
					$cmd_stock = ['CREATE TABLE public.m_pi_stock (tanggal TIMESTAMP,
						status_sync_stok character varying(2));'];
						
						
						$result = $connec->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'm_pi_stock'" );
						if($result->rowCount() == 1) {
							
						}
						else {
						
							
							foreach ($cmd_stock as $r){
						
									$connec->exec($r);
							}
						
						
						}
					
					
					$result_ci = $connec->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'cash_in'" );
					if($result_ci->rowCount() == 1) {
						foreach ($cmd_alter_cash as $r){
					
								$connec->exec($r);
						}
						
					}
					else {
					
						
						foreach ($cmd_cash as $r){
					
								$connec->exec($r);
						}
					
					
					}
					
					
					
					$result = $connec->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'm_pi'" );
					if($result->rowCount() == 1) {
						
						foreach ($cmd_alter as $r){
					
								$connec->exec($r);
						}
						
						
					}
					else {
					
						
						foreach ($cmd as $r){
					
								$connec->exec($r);
						}
					
					
					}
					
					
					
					
					$result1 = $connec->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'm_piline'" );
					if($result1->rowCount() == 1) {
						
						foreach ($cmd2_alter_piline as $r1){
					
								$connec->exec($r1);
						}
						
						foreach ($cmd2_alter_piline1 as $r1){
					
								$connec->exec($r1);
						}
					}
					else {
					
						foreach ($cmd2 as $r1){
					
								$connec->exec($r1);
						}
					
					
					}
					
					$result2 = $connec->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'm_pi_sales'" );
					if($result2->rowCount() == 1) {
						
					}
					else {
					
						foreach ($cmd3 as $r2){
					
								$connec->exec($r2);
						}
					
					
					}
					
					$result_inv = $connec->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'inv_temp'" );
					if($result_inv->rowCount() == 1) {
						
					}
					else {
					
						foreach ($inv_temp as $rr){
					
								$connec->exec($rr);
						}
					
					
					}
					
					
					
					
					
					
					
					
					
					
					$cmd5 = ["CREATE TABLE public.m_piversion (
							value character varying(10)
						);","insert into m_piversion (value) VALUES ('1')"
					];
					
					
					$result4 = $connec->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'm_piversion'" );
					if($result4->rowCount() == 1) {

					}else {
						foreach ($cmd5 as $r){
								$connec->exec($r);
						}
					}
					
					
					$cmd_grab = ["CREATE TABLE m_grab_sku (sku character varying(25) primary key, stock character varying(10));"];
					
					
					$result_grab = $connec->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'm_grab_sku'" );
					if($result_grab->rowCount() == 1) {

					}else {
						foreach ($cmd_grab as $r){
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
						$cekuserglobal = $connec->query("select * from m_pi_users where userid = 'akunglobalit'");
						$cekuserpromo = $connec->query("select * from m_pi_users where userid = 'adminpromo'");
						$cekusermkt = $connec->query("select * from m_pi_users where userid = 'akunmarketing'");
						$count = $cek->rowCount();
						$count1 = $cek_cek->rowCount();
						$count2 = $cekuserglobal->rowCount();
						$count3 = $cekuserpromo->rowCount();
						$count4 = $cekusermkt->rowCount();
						
						if($count4 == 0){
							$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
							$results = $connec->query($sqll);
							
							foreach ($results as $r) {
								
				$connec->query("INSERT INTO public.m_pi_users
				(ad_muser_key, isactived, userid, username, userpwd, ad_org_id, name)
				VALUES ('MKT123', 1, 'akunmarketing', 'Akun Marketing', '8252b14572f9575795082c43d3448c9051992e834c22872c878420e0676684ed', '".$r['ad_morg_key']."', 'Marketing')");
							}
							
							
							
							
						}
						
						if($count2 == 0){
							$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
							$results = $connec->query($sqll);
							
							foreach ($results as $r) {
								
				$connec->query("INSERT INTO public.m_pi_users
				(ad_muser_key, isactived, userid, username, userpwd, ad_org_id, name)
				VALUES ('11223344556677', 1, 'akunglobalit', 'Akun Global IT', '8252b14572f9575795082c43d3448c9051992e834c22872c878420e0676684ed', '".$r['ad_morg_key']."', 'Ka. Toko')
				");
							}
							
							
							
							
						}
						
						
						if($count3 == 0){
							
						$sqll1 = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
							$results1 = $connec->query($sqll1);
							
							foreach ($results1 as $r1) {
								
				$connec->query("INSERT INTO public.m_pi_users
				(ad_muser_key, isactived, userid, username, userpwd, ad_org_id, name)
				VALUES('adminpromo', 1, 'adminpromo', 'Admin Promo', '8252b14572f9575795082c43d3448c9051992e834c22872c878420e0676684ed', '".$r1['ad_morg_key']."', 'Promo'),
				('112233445566', 1, 'akuncekharga', 'Cek Harga Idol', '8252b14572f9575795082c43d3448c9051992e834c22872c878420e0676684ed', '".$r1['ad_morg_key']."', 'Ka. Toko'),
				('1122334455667788', 1, 'akunglobalauditnihbos', 'Akun Global Audit', '8252b14572f9575795082c43d3448c9051992e834c22872c878420e0676684ed', '".$r1['ad_morg_key']."', 'Audit');");
							}
							
						}
							
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
				
							<input type="text" class="user" name="user" placeholder="username" required="" autofocus>
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
				$('#notif1').html("<font style='color: green'>Version up to date (ver "+dataResults.version+") <a target=_blank href='https://idolmart.co.id/live/pi/doc_pi.php'>Link update</a></font>");
				$(':input[type="submit"]').prop('disabled', false);
			}else{
				
				if(dataResults.version === null){
					var msg = "<font style='color: red'>Periksa koneksi internet dan ERP</font>";
					
				}else{
					
					var msg = "<font style='color: red'>Versi belum update, silahkan update dulu ke ver "+dataResults.version+"</font>";
					
				}
				
				$('#notif1').html(msg+" <a target=_blank href='https://idolmart.co.id/live/pi/doc_pi.php'>Link update</a>");
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