<?php
include_once "C:/sideProject/Board/common/db.php";
include_once "C:/sideProject/Board/common/common.php";

/* R(글 등록), M(수정), D(삭제), S(회원가입) 공통 check */
//url 직접접근 방지
if (!preg_match("/" . $_SERVER['HTTP_HOST'] . "/i", $_SERVER['HTTP_REFERER'])) {
    echo "<script>
        alert('No direct access allowed');
        location.href='/index.php';</script>";
    exit('No direct access allowed');
}

// Parameter check
$action_flag = $_POST['action_flag'];

//게시글 관련 변수
$bno = $_GET['no'];
$pno = $_GET['page'];
$sort = $_GET['sort'];
$reg_id = trim($_POST['reg_id']);
$title = trim($_POST['title']);
$content = $_POST['content'];

//회원가입 관련 변수
$user_nm = trim($_POST['user_nm']);
$user_id = trim($_POST['user_id']);
//비밀번호 암호화
$user_pw = hash('sha256', trim($_POST['user_pw']));
$phone = str_replace("-", "", trim($_POST['phone']));

//공통 변수
$email = trim($_POST['email']);

/* action_flag 별 check */
//Register, Modify 공통 check
if ($action_flag == "R" || $action_flag == "M") {

    //비회원 접근
    if (empty($_SESSION['USER_ID'])) {
        echo "<script>
            alert('접근권한이 없습니다! 로그인 후 이용해주세요!');
            location.href='/bbs_list.php';</script>";
    }

    //title 글자수 제한
    if (mb_strlen($title) > 60) {
        echo "<script>
            alert('제목은 60자 이하로 입력해주세요!');
            history.back();</script>";
        exit;
    }
    //title 필수값 check
    if (!$title) {
        echo "<script>
            alert('제목을 입력해주세요!');
            history.back();</script>";
        exit;
    }
}

// Modify, Delete 계정확인
if ($action_flag == "M" || $action_flag == "D") {
    // $bno 필수 check
    if (!$bno) {
        echo "<script>
            alert('게시글 no를 받지 못했습니다!');
            history.back();</script>";
        exit;
    }
    //Modify
    if ($action_flag == "M") {
        // Session, reg_id 비교
        if ($_SESSION['USER_ID'] != $reg_id) {
            echo "<script>
                alert('계정이 달라 수정권한이 없습니다!');
                history.back();</script>";
            exit;
        }
    }
    //Delete
    else if ($action_flag === "D") {
        // 등록번호 SELECT
        $sql = mysqli_query($db, "SELECT reg_id FROM tbl_bbs WHERE no=$bno");
        if (!$sql) {
            echo "<script> alert('DB쿼리 실행실패!'); history.back(); </script>";
            exit;
        } else {
            $tbl_bbs = mysqli_fetch_array($sql);
        }
        // Session, reg_id 비교
        if ($_SESSION['USER_ID'] != $tbl_bbs['reg_id']) {
            echo "<script>
                alert('계정이 달라 삭제권한이 없습니다!');
                history.back();</script>";
            exit;
        }
    }
}

//Sign - 회원가입
if ($action_flag === "S") {
    if (!$user_nm || !$user_id || !$user_pw || !$email) {
        echo "<script>
                alert('필수값을 모두 입력해주세요!');
                history.back();</script>";
        exit;
    } else {
        if (mb_strlen($user_nm) > 10) {
            echo "<script>
                alert('이름은 10자 이하로 입력해주세요!');
                history.back();</script>";
            exit;
        }
        if (mb_strlen($user_id) < 2 || mb_strlen($user_id) > 10) {
            echo "<script>
            alert('아이디는 최소 2자 이상, 최대 10자 이하로 설정해주세요!');
            history.back();</script>";
            exit;
        }
        if (mb_strlen($email) > 40) {
            echo "<script>
            alert('이메일은 40자 이하로 입력해주세요!');
            history.back();</script>";
            exit;
        }
        if (mb_strlen($phone) > 15) {
            echo "<script>
            alert('이메일은 15자 이하로 입력해주세요!');
            history.back();</script>";
            exit;
        }
    }
}

/*SQL Query문*/
//register
if ($action_flag == "R") {
    $sql = mysqli_query($db, "INSERT INTO tbl_bbs
                                (title, content, reg_id, email) 
                                VALUES
                                ('" . $title . "','" . $content . "','" . $reg_id . "','" . $email . "')");

    $sql_read = mysqli_query($db, "SELECT no FROM tbl_bbs 
                                    WHERE title='" . $title . "' AND content='" . $content . "' AND reg_id='" . $reg_id . "' AND email='" . $email . "' 
                                    ORDER BY reg_date DESC 
                                    LIMIT 1");
    $tbl_bbs = mysqli_fetch_assoc($sql_read);

    if ($sql) {
        echo "<script>
            alert('저장을 성공했습니다!');
            location.href='/bbs_content.php?page=1&no=" . $tbl_bbs['no'] . "';</script>";
        exit;
    }
}
//modify
else if ($action_flag == "M") {
    $sql = mysqli_query($db, "UPDATE tbl_bbs 
                                SET title='" . $title . "'
                                , email='" . $email . "'
                                , content='" . $content . "'
                                , mod_date=now() WHERE no=$bno");
    if ($sql) {
        echo "<script>
        alert('수정을 성공했습니다!');
        location.href='/bbs_content.php?page=" . $pno . "&no=" . $bno . "&sort=" . $sort . "';</script>";
        exit;
    }
    //delete
} else if ($action_flag == "D") {
    $sql = mysqli_query($db, "DELETE FROM tbl_bbs WHERE no=$bno");
    if ($sql) {
        echo "<script>
            alert('글이 삭제되었습니다!');
            location.href='/bbs_list.php?page=" . $pno . "';</script>";
        exit;
    }
    //sign
} else if ($action_flag === "S") {
    $sql = mysqli_query($db, "INSERT INTO tbl_user 
                                (user_nm, user_id, user_pw, email, phone)
                                VALUES 
                                ('" . $user_nm . "', '" . $user_id . "', '" . $user_pw . "', '" . $email . "', '" . $phone . "')");
    if ($sql) {
        echo "<script>
        alert('회원가입이 완료되었습니다!');
        location.href='/index.php';</script>";
        exit;
    }
}
//R,M,D,S가 아닐경우
else {
    echo "<script>
        alert('잘못된 접근입니다!);
        </script>";
    echo $action_flag;
    exit;
}

//sql 쿼리 실패
if (!$sql) {
    echo "<script> alert('DB쿼리 실행실패!'); history.back(); </script>";
}
