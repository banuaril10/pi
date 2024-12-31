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
execPrint('D: && cd /xampp/htdocs/pi && git config --global --add safe.directory "*" && git config --global user.email "banuaril100@gmail.com" && git stash && git pull');
// execPrint('php update.php');