<?php
$html = $_POST['html'];
	$cmd='';
    $cmd='echo "'.$html.'" | lpr -o raw'; //linux
	
	
	
    $child = shell_exec($cmd); 
	
	
	
	$data = array("result"=>1, "msg"=>$child);
		
		$json_string = json_encode($data);	
		echo $json_string;

?>