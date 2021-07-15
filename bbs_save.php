<?php
    include $_SERVER['DOCUMENT_ROOT']."/db.php";

    $title = trim($_POST['title']);

    if(mb_strlen($title)>60){
        echo "<script>
        alert('제목은 60자 이하로 입력해주세요!');
        history.back();</script>";
    }
    if(!$title){
        echo "<script>
        alert('제목을 입력해주세요!');
        history.back();</script>";
    }

    $reg_id = trim($_POST['reg_id']);
    if(!$reg_id){
        echo "<script>
        alert('등록 아이디를 입력해주세요!');
        history.back();</script>";
    }

    $content = $_POST['content'];
    $email = strtolower(trim($_POST['email']));
    $reg_ip = $_SERVER['REMOTE_ADDR'];
    
    
    $sql = mysqli_query($db, "insert into tbl_bbs(title, content, reg_id, email, reg_ip ) values('".$title."','".$content."','".$reg_id."','".$email."','".$reg_ip."')"); 
    if($sql){
        echo "<script>
        alert('저장을 성공했습니다!');
        location.href='/bbs_list.php';</script>";
    }else{
        echo "<script>
        alert('필수값(제목, 등록아이디)을 모두 입력해주세요!');
        history.back();</script>";
    }
?>