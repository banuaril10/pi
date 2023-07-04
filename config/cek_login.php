<?php session_start();
include "koneksi.php";
$username = $_POST['user'];
$pwd = hash_hmac("sha256", $_POST['pwd'], 'marinuak');

$sql = "select userid, username, ad_org_id, name from m_pi_users where userid ='".$username."' 
and userpwd ='".$pwd."' and isactived = '1' group by userid,username,ad_org_id, name  limit 1";

$result = $connec->query($sql);

$rows = $result->rowCount();

$number_of_rows = $result->fetchColumn(); 
if($rows > 0){


// $cmd_cash = ['CREATE TABLE public.cash_in (
    // cashinid character varying(32) DEFAULT public.get_uuid() NOT NULL PRIMARY KEY,
    // org_key character varying(32),
    // userid character varying(32),
    // nama_insert character varying(50),
    // cash numeric DEFAULT 0 NOT NULL,
	// insertdate timestamp without time zone,
    // status character varying(10),
	// approvedby character varying(50),
	// syncnewpos numeric DEFAULT 0 NOT NULL,
	// setoran numeric DEFAULT 0 NOT NULL
// );'];

// $cmd_alter_cash = ['ALTER TABLE cash_in ADD COLUMN IF NOT EXISTS syncnewpos numeric DEFAULT 0 NOT NULL;','ALTER TABLE cash_in ADD COLUMN IF NOT EXISTS setoran numeric DEFAULT 0 NOT NULL;'];


// $cmd = ['CREATE TABLE public.m_pi (
    // m_pi_key character varying(32) DEFAULT public.get_uuid() NOT NULL,
    // ad_client_id character varying(32),
    // ad_org_id character varying(32),
    // isactived character varying(2),
    // insertdate timestamp without time zone,
    // insertby character varying(50),
    // m_locator_id character varying(32),
    // inventorytype character varying(30),
    // name character varying(50),
    // description character varying(255),
    // movementdate timestamp without time zone,
    // approvedby character varying(50),
    // status character varying(20),
    // issync boolean DEFAULT false,
    // rack_name character varying(32),
    // postby character varying(150),
    // postdate timestamp without time zone,
    // category numeric DEFAULT 1 NOT NULL,
	// insertfrommobile character varying(15),
	// insertfromweb character varying(15)
// );','CREATE INDEX m_pi_m_pi_key_idx ON public.m_pi USING btree (m_pi_key);','CREATE INDEX m_pi_name_idx ON public.m_pi USING btree (name);'];

// $cmd_alter = ['ALTER TABLE m_pi ADD COLUMN IF NOT EXISTS insertfrommobile varchar(15);','ALTER TABLE m_pi ADD COLUMN IF NOT EXISTS insertfromweb varchar(15);'];



// $cmd2 = ['CREATE TABLE public.m_piline (
    // m_piline_key character varying(32) DEFAULT public.get_uuid() NOT NULL,
    // m_pi_key character varying(32),
    // ad_client_id character varying(32),
    // ad_org_id character varying(32),
    // isactived character varying(2),
    // insertdate timestamp without time zone,
    // insertby character varying(50),
    // postby character varying(50),
    // postdate timestamp without time zone,
    // m_storage_id character varying(32),
    // m_product_id character varying(32),
    // sku character varying(50),
    // qtyerp numeric,
    // qtycount numeric DEFAULT 0,
    // issync integer DEFAULT 0,
    // status numeric DEFAULT 0,
    // verifiedcount numeric DEFAULT 0,
    // qtysales numeric DEFAULT 0,
    // price numeric DEFAULT 0,
    // status1 numeric DEFAULT 0,
    // qtysalesout numeric DEFAULT 0,
	// barcode varchar(30)
// );','CREATE INDEX m_piline_insertdate_idx ON public.m_piline USING btree (insertdate);',
// 'CREATE INDEX m_piline_m_pi_key_idx ON public.m_piline USING btree (m_pi_key);',
// 'CREATE INDEX m_piline_sku_idx ON public.m_piline USING btree (sku);'
// ];


// $cmd2_alter_piline = ['ALTER TABLE m_piline ADD COLUMN IF NOT EXISTS barcode varchar(30);'];


// $cmd3 = ['CREATE TABLE public.m_pi_sales (
    // tanggal timestamp without time zone,
    // status_sales numeric DEFAULT 0
// );'
// ];

// $inv_temp = ['CREATE TABLE public.inv_temp (
	// sku varchar NULL,
	// qty numeric NULL,
	// filename varchar NULL,
	// tanggal timestamp NULL,
	// status numeric NULL
// );'
// ];

// $cmd_stock = ['CREATE TABLE public.m_pi_stock (tanggal TIMESTAMP,
    // status_sync_stok character varying(2));'];
	
	
	// $result = $connec->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'm_pi_stock'" );
	// if($result->rowCount() == 1) {
		
	// }
	// else {
	
		
		// foreach ($cmd_stock as $r){
	
				// $connec->exec($r);
		// }
	
	
	// }


// $result_ci = $connec->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'cash_in'" );
// if($result_ci->rowCount() == 1) {
	// foreach ($cmd_alter_cash as $r){

			// $connec->exec($r);
	// }
	
// }
// else {

	
	// foreach ($cmd_cash as $r){

			// $connec->exec($r);
	// }


// }



// $result = $connec->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'm_pi'" );
// if($result->rowCount() == 1) {
	
	// foreach ($cmd_alter as $r){

			// $connec->exec($r);
	// }
	
	
// }
// else {

	
	// foreach ($cmd as $r){

			// $connec->exec($r);
	// }


// }




// $result1 = $connec->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'm_piline'" );
// if($result1->rowCount() == 1) {
	
	// foreach ($cmd2_alter_piline as $r1){

			// $connec->exec($r1);
	// }
// }
// else {

	// foreach ($cmd2 as $r1){

			// $connec->exec($r1);
	// }


// }

// $result2 = $connec->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'm_pi_sales'" );
// if($result2->rowCount() == 1) {
	
// }
// else {

	// foreach ($cmd3 as $r2){

			// $connec->exec($r2);
	// }


// }

// $result_inv = $connec->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'inv_temp'" );
// if($result_inv->rowCount() == 1) {
	
// }
// else {

	// foreach ($inv_temp as $rr){

			// $connec->exec($rr);
	// }


// }

	foreach ($connec->query($sql) as $row) {
			$_SESSION['userid'] = $row["userid"];
			$_SESSION['username'] = $row["username"];
			$_SESSION['org_key'] = $row["ad_org_id"];
			$_SESSION['name'] = $row["name"];
			
			if($row["name"] == 'Audit'){
				
				$_SESSION['role'] = "Global";
			}else{
				$_SESSION['role'] = "Daily";
			}
			
			
			$sqll = "select value from ad_morg where postby = 'SYSTEM'";

			$results = $connec->query($sqll);
			
			
			foreach ($results as $r) {
				$_SESSION['kode_toko'] = $r["value"];
				
			}

			
			// echo $row["userid"];
		
			// setcookie("userid",$row["userid"],time() + (10 * 365 * 24 * 60 * 60));
			// setcookie("username",$row["username"],time() + (10 * 365 * 24 * 60 * 60));
			// setcookie("org_key",$row["ad_morg_key"],time() + (10 * 365 * 24 * 60 * 60));
			
			if($row["ad_org_id"] == '112233445566'){
				
				header("Location: ../cek_harga.php?".$_SESSION["username"]);
			}else if($row["name"] == 'Cashier'){
				header("Location: ../cashier.php?".$_SESSION["username"]);
				
			}else if($row["name"] == 'Promo'){
				header("Location: ../cek_promo.php?".$_SESSION["username"]);
				
			}else{
				
				header("Location: ../content.php?".$_SESSION["username"]);
			}
			
			
	}
	
}else{
	
	
			// echo $sql; 
			// echo $rows; 
			header("Location: ../index.php?pesan=Username/pass salah");
		}


	

?>