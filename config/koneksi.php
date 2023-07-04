<?php
date_default_timezone_set('Asia/Jakarta');
error_reporting(0);

$options = array(
    PDO::ATTR_ERRMODE    => PDO::ERRMODE_SILENT
);
try {

 $dbuser = 'postgres';
    $dbpass = 'pos';
    $dbhost = 'localhost';
    $dbname='dbinfinitepos';
    $dbport='5432';


    $connec = new PDO("pgsql:host=$dbhost;dbname=$dbname;port=$dbport", $dbuser, $dbpass, $options);
  
} catch (PDOException $e) {
    // echo "Error : " . $e->getMessage() . "<br/>";
    // die();

}

?>