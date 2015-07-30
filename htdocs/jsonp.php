
<?php

include "functions.php";

header("content-type: application/json");

$name = $_GET['name'];
$command = "python ../cgi-bin/py.py " . $name;
//$pid = popen($command, "r");
$output = exec($command, $output);
//        while (!feof($pid)) {
//            $output .= fread($pid, 8000);
//
//            flush();
//            ob_flush();
//
////            usleep(100000);
//        }


echo $_GET['callback'] . '('. $output . ')';

//pclose($pid);

?>