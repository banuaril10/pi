<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
function execPrint($command) {
	$result = array();
	exec($command, $result);
	print("<pre>");
	foreach ($result as $line) {
		print($line . "\n");
	}
	print("</pre>");
}
// Print the exec output inside of a pre element
execPrint("git pull");
// execPrint("123456");
execPrint("git status");

?>