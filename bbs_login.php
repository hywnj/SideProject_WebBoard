<?php
include_once "C:/Project/SideProject_WebBoard/common/db.php";
include_once "C:/Project/SideProject_WebBoard/common/common.php";

//변수
$user_id = $_POST['user_id'];
$user_pw = hash('sha256', $_POST['user_pw']);

$sql_id = mysqli_query($db, "SELECT user_id, user_pw, pw_cnt, email FROM tbl_user WHERE user_id='" . $user_id . "'");
$tbl_user = mysqli_fetch_assoc($sql_id);
$pw_cnt = $tbl_user['pw_cnt'];
$email = $tbl_user['email'];

//등록회원인지 확인
if (!$tbl_user['user_id']) {
?>
    <script>
        alert('일치하는 아이디가 없습니다!');
        history.back();
    </script>
    <?
} else {
    //회원정보 불일치
    if ( $user_pw !== $tbl_user['user_pw']) {
        ++$pw_cnt;
        //비밀번호 불일치 횟수 update
        $sql_pwcnt = mysqli_query($db, "UPDATE tbl_user SET pw_cnt=" . $pw_cnt . " WHERE user_id='" . $user_id . "'");
    ?> <script>
            alert('비밀번호가 일치하지 않습니다!( ' + <?= $pw_cnt ?> + '회)');
            history.back();
        </script>
    <?/*//비밀번호 5회이상 오류시 
        if ($pw_cnt > 5) {
        ?> <script>
                alert('비밀번호 불일치 5회이상');
                location.href = '/index.php';
            </script>
    <?        } */
    } else {
        //회원정보 일치 - 세션시작 및 정보저장
        session_start();
        $_SESSION['USER_ID'] = $user_id;
        $_SESSION['USER_PW'] = $user_pw;
        $_SESSION['EMAIL'] = $email;
        

        //비밀번호 불일치 횟수 초기화
        $sql_pwcnt_reset = mysqli_query($db, "UPDATE tbl_user SET pw_cnt=0 WHERE user_id='" . $user_id . "'");

    ?> <script>
            alert('환영합니다!');
            location.href = '/bbs_list.php';
        </script>

    <? exit;
    }
}
