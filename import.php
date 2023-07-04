<?php include "config/koneksi.php";
// Memanggil Library
// require 'vendor/autoload.php';


// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Reader\Csv;
// use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

// $file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

// if(isset($_FILES['import']['name']) && in_array($_FILES['import']['type'], $file_mimes)) {
    // var_dump($_FILES['import']['name']);
 
    // $arr_file = explode('.', $_FILES['import']['name']);
    // $extension = end($arr_file);


    // if('csv' == $extension) {
        // $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    // } else {
        // $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    // }


    // $spreadsheet = $reader->load($_FILES['import']['tmp_name']);

    // $sheetData = $spreadsheet->getActiveSheet()->toArray();

    // var_dump($sheetData);
	
	
	// $cek = $connec->query("select count(*) jum from inv_temp where filename = '".$_FILES['import']['name']."'");
	// foreach($cek as $r){
		// $jum = $r['jum'];
		
	// }
	
	// if($jum > 0){
		
		
	// }else{
	
		// for($i = 1;$i < count($sheetData);$i++){
			// $sku = $sheetData[$i]['0'];
			// $qty = $sheetData[$i]['1'];
	
			// $connec->query("insert into inv_temp (tanggal, sku, qty, status, filename) VALUES ('".date('Y-m-d')."','".$sku."','".$qty."','0', '".$_FILES['import']['name']."');");
		// }
	// }
    
   
    
// }





// if (isset($_POST['submit']))
// {
	$no = 0;
    // Allowed mime types
    $fileMimes = array(
        'text/x-comma-separated-values',
        'text/comma-separated-values',
        'application/octet-stream',
        'application/vnd.ms-excel',
        'application/x-csv',
        'text/x-csv',
        'text/csv',
        'application/csv',
        'application/excel',
        'application/vnd.msexcel',
        'text/plain'
    );
 
	$json = array();
    // Validate whether selected file is a CSV file
    if (!empty($_FILES['import']['name']) && in_array($_FILES['import']['type'], $fileMimes))
    {
 
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['import']['tmp_name'], 'r');
 
            // Skip the first line
            fgetcsv($csvFile);
 
            // Parse data from CSV file line by line
             // Parse data from CSV file line by line
           
                // Get row data
               
				
 
				$cek = $connec->query("select count(*) jum from inv_temp where filename = '".$_FILES['import']['name']."' and date(tanggal) = date(now())");
				foreach($cek as $r){
					$jum = $r['jum'];
					
				}
				
				if($jum > 0){
					 // $json = array("result"=>0, "msg"=>"File sudah pernah diimport");
					// echo json_encode($json);
					
					 echo ("<script LANGUAGE='JavaScript'>
    window.alert('File sudah pernah diimport');
    window.location.href='importer.php';
    </script>");
	
				}else{
				
					 while (($getData = fgetcsv($csvFile, 10000, ",")) !== FALSE)
					{
						
					
						
					
						$sku = str_replace(' ', '', $getData[0]);
						
						if($sku != ""){
							
							$qty = $getData[1];
							$sqll = "insert into inv_temp (sku, tanggal, qty, status, filename) VALUES ('".$sku."','".date('Y-m-d')."','".$qty."','0', '".$_FILES['import']['name']."');";
							$import = $connec->query($sqll);
							
							if($import){
								$no = $no + 1;
							
								// var_dump($getData);
			
							}
						}
						

						// echo $sqll.'<br>';
		
							 
					}
					
					 
					
					
				}
            
			
            // Close opened CSV file
            fclose($csvFile);
			
			
       
         
    }
    else
    {
	  echo ("<script LANGUAGE='JavaScript'>
    window.alert('File tidak valid');
    window.location.href='importer.php';
    </script>");
		
    }
	
	
	echo ("<script LANGUAGE='JavaScript'>
    window.alert('Berhasil Import ".$no." items');
    window.location.href='importer.php';
    </script>");
	
// }
?>