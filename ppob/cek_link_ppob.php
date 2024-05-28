<?php 
include "../config/koneksi.php";

$cek_ver = "select link_ppob from m_piversion";
$cv = $connec->query($cek_ver);

$link = "";
foreach ($cv as $r){
	
	$link = $r['link_ppob'];
}
	

echo $link;