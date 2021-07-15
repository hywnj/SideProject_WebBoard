<?php
    #echo "MySqli <br>" . date("Y-m-d H:i:s", time());
    global $db;
    $db = mysqli_connect("localhost", "root", "970418", "hyewon",  3307);
    if($db){
        echo "connect : success<br>";
        error_log (date("Y-m-d H:i:s", time()) . " - mysqli_connect : success\n", 3, "C:/Payple/APM/Apache24/htdocs/debug.log");
    }
    else{
        echo "disconnect : fail<br>";
        error_log (date("Y-m-d H:i:s", time()) . " - mysqli_connect : fail\n", 3, "C:/Payple/APM/Apache24/htdocs/debug.log");
    }
?>