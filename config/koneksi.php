<?php
date_default_timezone_set('Asia/Jakarta');
error_reporting(0);
try {
    // $dbuser = 'postgres';
    // $dbpass = 'idm$$&!@#141';
    // $dbhost = '114.141.55.178';
    // $dbname='postgres';
    // $dbport='5432';
    // $dbport='5434';

 $dbuser = 'postgres';
    $dbpass = 'pos';
    $dbhost = 'localhost';
    $dbname='dbinfinitepos';
    $dbport='5434';


    $connec = new PDO("pgsql:host=$dbhost;dbname=$dbname;port=$dbport", $dbuser, $dbpass);
  
} catch (PDOException $e) {
    // echo "Error : " . $e->getMessage() . "<br/>";
    // die();

}

?>