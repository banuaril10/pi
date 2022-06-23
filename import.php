<?php include "config/koneksi.php";
// Memanggil Library
require 'vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

if(isset($_FILES['import']['name']) && in_array($_FILES['import']['type'], $file_mimes)) {
    var_dump($_FILES['import']['name']);
    // Memisahkan nama file dengan formatnya
    $arr_file = explode('.', $_FILES['import']['name']);
    $extension = end($arr_file);

    // Mengecek format file
    if('csv' == $extension) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    } else {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    }

    // Membaca isi file
    $spreadsheet = $reader->load($_FILES['import']['tmp_name']);

    // Mengubah isi file kebentuk array
    $sheetData = $spreadsheet->getActiveSheet()->toArray();

    var_dump($sheetData);
	
	
	$cek = $connec->query("select count(*) jum from inv_temp where filename = '".$_FILES['import']['name']."'");
	foreach($cek as $r){
		$jum = $r['jum'];
		
	}
	
	if($jum > 0){
		
		
	}else{
	
		for($i = 1;$i < count($sheetData);$i++){
			$sku = $sheetData[$i]['0'];
			$qty = $sheetData[$i]['1'];
	
			$connec->query("insert into inv_temp (tanggal, sku, qty, status, filename) VALUES ('".date('Y-m-d')."','".$sku."','".$qty."','0', '".$_FILES['import']['name']."');");
		}
	}
    
   
    
}
?>