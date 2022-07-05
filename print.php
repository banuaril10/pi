<?php 
$html = $_POST['html'];
// $html = "TEST PRINNNTT";


// $myfile = fopen("print.txt", "w") or die("Unable to open file!");
// $txt = $html;
// fwrite($myfile, $txt);
// fclose($myfile);
// $cmd='print.bat';
	  
	  

	$cmd='';
    $cmd='echo "'.$html.'" | lpr -o raw';
	
	
	
    $child = shell_exec($cmd);  