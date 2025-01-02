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

// Check if directory D exists, if not use directory E
if (file_exists('D:\\')) {
    $drive = 'D:';
} else {
    $drive = 'E:';
}

// Execute commands in the selected drive
execPrint("$drive && cd /xampp/htdocs/pi && git config --global --add safe.directory '*' && git config --global user.email 'banuaril100@gmail.com' && git stash && git pull");