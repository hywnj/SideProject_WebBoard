<?php
include_once "C:/sideProject/Board/common/db.php"; 
include_once "C:/sideProject/Board/common/common.php";

    //url 직접접근 방지
    if ( !preg_match("/".$_SERVER['HTTP_HOST']."/i", $_SERVER['HTTP_REFERER'])){
        echo "<script>
        alert('No direct access allowed');
        location.href='/bbs_list.php';</script>";
        exit('No direct access allowed');
    }

    //비정상적 접근 - 비회원 접근
    if(empty($_SESSION['USER_ID'])) {
        echo "<script>
        alert('접근권한이 없습니다!');
        location.href='/bbs_list.php';</script>";
    }

$bno = $_GET['no'];
$title = trim($_POST['title']);
$email = trim($_POST['email']);
$content = $_POST['content'];
$reg_id = $_POST['reg_id'];
$mod_ip = $_SERVER['REMOTE_ADDR'];

//계정 확인
if( $_SESSION['USER_ID'] == $reg_id){
}else{
    echo "<script>
    alert('계정이 달라 수정권한이 없습니다! 등록 아이디로 저장해주세요');
    history.back();</script>";
    exit();
}


$sql = mysqli_query($db,"update tbl_bbs 
                            set title='".$title."'
                            , email='".$email."'
                            , content='".$content."'
                            , reg_id='".$reg_id."'
                            , mod_date=now() 
                            where no=$bno");




if(!$sql){
    echo "<script>
    alert('DB쿼리문 실행실패!');
    history.back();</script>";
}else{
    echo "<script>
    alert('수정되었습니다!');
    location.href='/bbs_list.php';</script>";
}

?>
