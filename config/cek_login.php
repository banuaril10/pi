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
    category numeric DEFAULT 1 NOT NULL
);','CREATE INDEX m_pi_m_pi_key_idx ON public.m_pi USING btree (m_pi_key);','CREATE INDEX m_pi_name_idx ON public.m_pi USING btree (name);'];

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
    qtysalesout numeric DEFAULT 0
);','CREATE INDEX m_piline_insertdate_idx ON public.m_piline USING btree (insertdate);',
'CREATE INDEX m_piline_m_pi_key_idx ON public.m_piline USING btree (m_pi_key);',
'CREATE INDEX m_piline_sku_idx ON public.m_piline USING btree (sku);'
];

$cmd3 = ['CREATE TABLE public.m_pi_sales (
    tanggal timestamp without time zone,
    status_sales numeric DEFAULT 0
);'
];


//alter table 
$result = $connec->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'm_pi'" );
if($result->rowCount() == 1) {
	
}
else {

	
	foreach ($cmd as $r){

			$connec->exec($r);
	}


}


$result1 = $connec->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'm_piline'" );
if($result1->rowCount() == 1) {
	
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



	foreach ($connec->query($sql) as $row) {
			$_SESSION['userid'] = $row["userid"];
			$_SESSION['username'] = $row["username"];
			$_SESSION['org_key'] = $row["ad_org_id"];
			
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
			header("Location: ../content.php?".$_SESSION["username"]);
	}
	
}else{
	
	
			// echo $sql; 
			// echo $rows; 
			header("Location: ../index.php?pesan=Username/pass salah");
		}


	

?>