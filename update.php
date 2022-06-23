<?php 
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
execPrint("sudo git pull");
execPrint("git status");

?>