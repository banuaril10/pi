<?php
try {
    $dbuser = 'postgres';
    $dbpass = '123qweASD';
    $dbhost = '35.247.131.101';
    $dbname='infinite';
    $dbport='5432';
    // $dbport='5434';

    $connec_erp = new PDO("pgsql:host=$dbhost;dbname=$dbname;port=$dbport", $dbuser, $dbpass);
} catch (PDOException $e) {
   $json = array('result'=>'0', 'msg'=>'Koneksi ke ERP gagal, coba lagi nanti');
   $json_string = json_encode($json);
	echo $json_string;
    // die();
}

?>