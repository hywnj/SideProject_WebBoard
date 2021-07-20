<?php
    include_once "C:/sideProject/Board/common/db.php"; 
    include_once "C:/sideProject/Board/common/common.php"; 

    /* R,M,D 공통 check */
    //url 직접접근 방지
    if ( !preg_match("/".$_SERVER['HTTP_HOST']."/i", $_SERVER['HTTP_REFERER'])){
        echo "<script>
        alert('No direct access allowed');
        location.href='/bbs_list.php';</script>";
        exit('No direct access allowed');
    }

    // parameter check
    $action_flag = $_POST['action_flag'];
    $bno = $_GET['no'];
    $reg_id = trim($_POST['reg_id']);
    $email = trim($_POST['email']);
    
    $title = trim($_POST['title']);

    //Register, Modify 공통 check
    if($action_flag == "R" || $action_flag == "M"){
        
        //비회원 접근
        if(empty($_SESSION['USER_ID'])){ 
            echo "<script>
            alert('접근권한이 없습니다! 로그인 후 이용해주세요!');
            location.href='/bbs_list.php';</script>";
        }

        //title 글자수 제한
        if(mb_strlen($title)>60){
            echo "<script>
            alert('제목은 60자 이하로 입력해주세요!');
            history.back();</script>";
            exit;
        }
        //title 필수값 check
        if(!$title){
            echo "<script>
            alert('제목을 입력해주세요!');
            history.back();</script>";
            exit;
        }
    }

    $content = $_POST['content'];

    // Modify, Delete 계정확인
    if( $action_flag == "M" || $action_flag == "D" ){
        // $bno 필수 check
        if(!$bno){
            echo "<script>
            alert('게시글 no를 받지 못했습니다!');
            history.back();</script>";
            exit;
        }
        //Modify
        if ($action_flag == "M") {
            // Session, reg_id 비교
            if($_SESSION['USER_ID'] != $reg_id){
                echo "<script>
                alert('계정이 달라 수정권한이 없습니다!');
                history.back();</script>";
                exit;
            }
        }
        //Delete
        else if ($action_flag === "D") {
            // 등록번호 SELECT
            $sql = mysqli_query($db, "select reg_id from tbl_bbs where no=$bno");
            if(!$sql){
                echo "<script> alert('DB쿼리 실행실패!'); history.back(); </script>";
                exit;
            }else{
                $tbl_bbs = mysqli_fetch_array($sql);
            }
            // Session, reg_id 비교
            if($_SESSION['USER_ID'] != $tbl_bbs['reg_id']){
                echo "<script>
                alert('계정이 달라 삭제권한이 없습니다!');
                history.back();</script>";
                exit;
            }
        }
    }
    
    /*SQL Query문*/
    //register
    if($action_flag == "R"){
        $sql = mysqli_query($db, "INSERT INTO tbl_bbs
                                (title, content, reg_id, email) 
                                VALUES
                                ('".$title."','".$content."','".$reg_id."','".$email."')"); 
        if($sql){
        echo "<script>
        alert('저장을 성공했습니다!');
        location.href='/bbs_list.php';</script>";
        exit;
        }
    }
    //modify
    else if($action_flag == "M"){
        $sql = mysqli_query($db,"UPDATE tbl_bbs 
                                SET title='".$title."'
                                , email='".$email."'
                                , content='".$content."'
                                , mod_date=now() WHERE no=$bno");
        if($sql){
        echo "<script>
        alert('수정을 성공했습니다!');
        location.href='/bbs_list.php';</script>";
        exit;
        }
    }
    else if($action_flag == "D"){
        $sql = mysqli_query($db, "DELETE FROM tbl_bbs WHERE no=$bno");
        if($sql){
            echo "<script>
            alert('글이 삭제되었습니다!');
            location.href='/bbs_list.php';</script>";
            exit;
        }
    }
    //R,M,D가 아닐경우
    else{
        echo "<script>
        alert('잘못된 접근입니다!);
        </script>";
        echo $action_flag;
        exit;
    }

    //sql 쿼리 실패
    echo "<script> alert('DB쿼리 실행실패!'); history.back(); </script>";
