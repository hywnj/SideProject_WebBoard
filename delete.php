<?php  
    include_once "C:/sideProject/Board/common/db.php"; 
    include_once "C:/sideProject/Board/common/common.php"; 
    if(empty($_SESSION['USER_ID'])){
        echo "<script>alert('접근권한이 없습니다!');location.href='/bbs_list.php';</script>";
    }


    