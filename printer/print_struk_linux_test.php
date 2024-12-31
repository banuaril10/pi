<?php
	//change font size to small
	// $html = chr(27) . chr(33) . chr(1); // ESC ! 1 to change font size to small
	// $html = chr(27) . chr(33) . chr(1); // ESC ! 1 to change font size to small
	$html = 'test print';
	$html .= 'test print';
	$html .= 'test print';
	$html .= 'test print';
	$html .= 'test print';
	$html .= 'test print';
	$html .= 'test print';
	$html .= 'test print';
	$html .= 'test print';
	$html .= 'test print';
	$html .= '\r\n'; 
	$html .= '\r\n'; 
	$html .= '\r\n'; 

	// $html .= chr(29) . "V" . 0; 
	// $html .= '\r\n'; 
	
	
	$cmd='';
    $cmd='echo "'.$html.'" | lpr -o raw'; //linux

    $child = shell_exec($cmd); 
	
	$data = array("result"=>1, "msg"=>$child);
		
	$json_string = json_encode($data);	
	echo $json_string;

?>