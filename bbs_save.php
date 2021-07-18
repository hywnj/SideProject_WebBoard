<?php
    include_once "C:/sideProject/Board/common/db.php"; 
    include_once "C:/sideProject/Board/common/common.php"; 

    /*r,m,d 공통사항*/
    //url 직접접근 방지
    if ( !preg_match("/".$_SERVER['HTTP_HOST']."/i", $_SERVER['HTTP_REFERER'])){
        echo "<script>
        alert('No direct access allowed');
        location.href='/bbs_list.php';</script>";
        exit('No direct access allowed');
    }

    //비회원 접근
    if(empty($_SESSION['USER_ID'])){
        echo "<script>
        alert('접근권한이 없습니다!');
        location.href='/bbs_list.php';</script>";
    }

    $action_flag = $_POST['action_flag'];
    $bno = $_GET['no'];
    $reg_id = trim($_POST['reg_id']);

    //reg_id 필수값 check -> session user id값으로 고정
    if(!$reg_id){
        echo "<script>
        alert('등록 아이디를 입력해주세요!');
        history.back();</script>";
        exit();
    }

    $title = trim($_POST['title']);

    //title 글자수 제한
    if(mb_strlen($title)>60){
        echo "<script>
        alert('제목은 60자 이하로 입력해주세요!');
        history.back();</script>";
        exit();
    }
    //title 필수값 check
    if(!$title){
        echo "<script>
        alert('제목을 입력해주세요!');
        history.back();</script>";
        exit();
    }

    $content = $_POST['content'];
    $email = trim($_POST['email']);
        
    //register
    if($action_flag=="r"){
        $sql = mysqli_query($db, "insert into tbl_bbs(title, content, reg_id, email) values('".$title."','".$content."','".$reg_id."','".$email."')"); 
    }
    else{
        //계정 확인
        if($_SESSION['USER_ID'] == $reg_id ){
        }else{
            echo "<script>
            alert('계정이 달라 수정권한이 없습니다! 등록 아이디로 저장해주세요');
            history.back();</script>";
            exit();
        }
        //modify
        if($action_flag=="m"){
            $sql = mysqli_query($db,"update tbl_bbs 
                                set title='".$title."'
                                , email='".$email."'
                                , content='".$content."'
                                , mod_date=now() where no=$bno");
        }
    }

    if($sql){
        echo "<script>
        alert('저장을 성공했습니다!');
        location.href='/bbs_list.php';</script>";
    }else{
        echo "<script>
        alert('DB쿼리 실행실패!');
        history.back();</script>";
    }

    ?>