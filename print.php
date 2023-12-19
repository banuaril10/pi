<?php 
$html = $_POST['html'];
// $html = "TEST PRINNNTT";


// $myfile = fopen("print_cashin.txt", "w") or die("Unable to open file!");
// $txt = $html;
// fwrite($myfile, $txt);
// fclose($myfile);
// $cmd='print_cash.bat'; //windows
	  
	  

	$cmd='';
    $cmd='echo "'.$html.'" | lpr -o raw'; //linux
	
	
	
    $child = shell_exec($cmd); 
	
	
	
	$data = array("result"=>1, "msg"=>$child);
		
		$json_string = json_encode($data);	
		echo $json_string;