<?php
include "../config/koneksi.php";
function kirim_data($a){
		
	$postData = array(
		"data" => $a,
	
    );		
	$curl = curl_init();

	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://pi.idolmartidolaku.com/api/action.php?modul=noc&act=monitoring_cache',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => $postData,
	));
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	return $response;	
					
					
}



function getUsers() {
    $result = [];
    /** @see http://php.net/manual/en/function.posix-getpwnam.php */
    $keys = ['name', 'passwd', 'uid', 'gid', 'gecos', 'dir', 'shell'];
    $handle = fopen('/etc/passwd', 'r');
    if(!$handle){
        throw new \RuntimeException("failed to open /etc/passwd for reading! ".print_r(error_get_last(),true));
    }
    while ( ($values = fgetcsv($handle, 1000, ':')) !== false ) {
        $result[] = array_combine($keys, $values);
    }
    fclose($handle);
    return $result[41]['name'];
}




ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
exec('cd /var/www/html/pi/api && sudo chown $USER cache.php');
$userpc = get_current_user();
// $userhost = gethostname();
// $userpc2 = exec('id -u -n');
// $userpc3 = exec('eval echo "~$different_user"');
$processUser = posix_getpwuid(posix_geteuid());


$now = date("Y-m-d");


// echo $userpc;
// echo $userpc2;
// echo $userpc3;

unlink('monitoring/History');
// if(!copy('/home/'.getUsers().'/.config/google-chrome/Default/History', 'monitoring/History')){
	
	if ( !is_dir('/home/'.getUsers().'/.config/google-chrome') ) {
		copy('/home/'.getUsers().'/.config/chromium/Default/History', 'monitoring/History');
	}else{
		copy('/home/'.getUsers().'/.config/google-chrome/Default/History', 'monitoring/History');
		
		
	}
	
	
	
	
// }
 //linux
$dir_linux = 'sqlite:monitoring/History';
$dir_win = 'sqlite:C:\Users\banua\AppData\Local\Google\Chrome\User Data\Default\History';


try {
   $db = new PDO($dir_linux);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
}   catch (Exception $e) {
    echo "Unable to connect";
    echo $e->getMessage();
    exit;
}


$sqll = "select ad_morg_key from ad_morg where postby = 'SYSTEM'";
		$results = $connec->query($sqll);
		foreach ($results as $r) {
			$org = $r["ad_morg_key"];	
		
		}

$query =  "SELECT url, title, ((last_visit_time/1000000)-11644473600) dateformat FROM urls order by last_visit_time desc limit 1000";
var_dump($db->query($query));
foreach ($db->query($query) as $row)
{ 
	echo $row[0].'<br>';
	
								$items[] = array(
									'org_id'	=>$org, 
									'link' 		=>$row[0], 
									'title' 	=>$row[1], 
									'access_date' 	=>$row[2], 
								);
	
}


	
								$items_json = json_encode($items);
								kirim_data($items_json);





?>
