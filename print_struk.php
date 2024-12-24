<?php
$html = $_POST['html'];
$html .= chr(29) . "V" . 0; 

	$cmd='';
    $cmd='echo "'.$html.'" | lpr -o raw'; //linux
	
	
	
    $child = shell_exec($cmd); 
	
	
	
	$data = array("result"=>1, "msg"=>$child);
		
		$json_string = json_encode($data);	
		echo $json_string;

?>