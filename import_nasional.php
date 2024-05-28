<?php include "config/koneksi.php";
session_start();
$useridcuy = $_SESSION['userid'];
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
 
 function guid($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
 
	$json = array();
    // Validate whether selected file is a CSV file
    if (!empty($_FILES['import']['name']) && in_array($_FILES['import']['type'], $fileMimes))
    {
            $csvFile = fopen($_FILES['import']['tmp_name'], 'r');
            //fgetcsv($csvFile);
					$jum_file = 0;
					$cek_file = $connec->query("select count(*) jum from inv_temp_nasional where filename = '".$_FILES['import']['name']."' ");
					foreach($cek_file as $rc){
						$jum_file = $rc['jum'];
					}
					
					if($jum_file == 0){
						while (($getData = fgetcsv($csvFile, 100000, ";")) !== FALSE)
						{
							$sku = str_replace(' ', '', $getData[0]);
							if($sku != ""){
								
								$qty = $getData[1];
								$sqll = "INSERT INTO inv_temp_nasional
								(id, sku, qty, tanggal, status, user_input,filename)
								VALUES('".guid()."', '".$sku."', '".$qty."', '".date('Y-m-d H:i:s')."', '0', '".$useridcuy."','".$_FILES['import']['name']."');";
								$import = $connec->query($sqll);
								
								if($import){
									$no = $no + 1;
								}
							}	 
						}
					}else{
						
						echo ("<script LANGUAGE='JavaScript'>
						window.alert('File sudah pernah diimport');
						window.location.href='invscan.php';
						</script>");
						
					}
			
			

            fclose($csvFile);
    }
    else
    {
	  echo ("<script LANGUAGE='JavaScript'>
    window.alert('File tidak valid');
    window.location.href='invscan.php';
    </script>");
		
    }
	
	
	echo ("<script LANGUAGE='JavaScript'>
    window.alert('Berhasil Import ".$no." items');
    window.location.href='invscan.php';
    </script>");
	
// }
?>