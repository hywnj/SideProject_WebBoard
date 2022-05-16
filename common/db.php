<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/common/config.php';

#echo "MySqli <br>" . date("Y-m-d H:i:s", time());
global $db;
$db = mysqli_connect($DB_USER, $DB_ID, $DB_PW, $DB_NAME, $DB_PORT);
if($db){
    echo "connect : success<br>";
    error_log (date("Y-m-d H:i:s", time()) . " - mysqli_connect : success\n", 3, "C:/APM/Apache24/htdocs/debug.log");
}
else{
    echo "disconnect : fail<br>";
    error_log (date("Y-m-d H:i:s", time()) . " - mysqli_connect : fail\n", 3, "C:/APM/Apache24/htdocs/debug.log");
}
