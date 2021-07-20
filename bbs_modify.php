<?php
include_once "C:/sideProject/Board/common/db.php";
include_once "C:/sideProject/Board/common/common.php";

//url 직접접근 방지
/*if ( !preg_match("/".$_SERVER['HTTP_HOST']."/i", $_SERVER['HTTP_REFERER'])){
        echo "<script>
        alert('No direct access allowed');
        location.href='/bbs_list.php';</script>";
        exit('No direct access allowed');
    }*/

//비정상적 접근 - 비회원 접근
if (empty($_SESSION['USER_ID'])) {
    echo "<script>
        alert('접근권한이 없습니다!');
        location.href='/bbs_list.php';</script>";
}

$bno = $_GET['no'];
$sql = mysqli_query($db, "SELECT * FROM tbl_bbs WHERE no='$bno'");
if (!$sql) {
    echo "<script>
        alert('DB쿼리문 실행실패!');
        history.back();</script>";
}
$tbl_bbs = mysqli_fetch_array($sql);

//작성자가 아닌경우
if ($_SESSION['USER_ID'] != $tbl_bbs['reg_id']) {
    echo "<script>
        alert('접근권한이 없습니다! 게시글 작성자만 수정할 수 있습니다!');
        history.back();</script>";
}


?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>게시판</title>
    <link rel="stylesheet" type="text/css" href="/css/reg_style.css" />

    <script>
        function fnCheck() {

            var title = frmMod.title.value;
            var email = frmMod.email.value;
            var content = frmMod.content.value;

            //필수 입력값 - title check
            if (title == "") {
                alert("제목을 입력해주세요!");
                return false;
            }

            //변경사항 여부 check
            if (title == "<?= $tbl_bbs['title'] ?>" && email == "<?= $tbl_bbs['email'] ?>" && content == "<?= $tbl_bbs['content'] ?>") {
                alert("수정사항이 없습니다!");
                return false;
            }

            //email form check
            if (email.trim()) {
                var regExp = /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
                if (!regExp.test(email)) {
                    alert("이메일 형식이 올바르지 않습니다!");
                    return false;
                }
            }
            //form 제출
            document.frmMod.submit();
        }
    </script>
</head>

<body>
    <div id="board_reg">
        <h1>수정하기 페이지</h1>
        <div id="reg_area">
            <form action="/bbs_save.php?no=<?= $tbl_bbs['no'] ?>" name="frmMod" method="post">
                <input type="hidden" name="action_flag" value="M">
                <div id="in_title">
                    <h2 style="color:gray;">제목</h2>
                    <textarea name="title" id="utitle" rows="1" cols="55" placeholder="제목" maxlength="100" required><?= $tbl_bbs['title']; ?></textarea>
                </div>
                <div class="wi_line"></div>
                <div id="in_reg_id">
                    <h2 style="color:gray;">이메일</h2>
                    <textarea name="email" id="uemail" rows="1" cols="55" placeholder="이메일" maxlength="100"><?= $tbl_bbs['email']; ?></textarea>
                </div>
                <div class="wi_line"></div>
                <div id="in_content">
                    <h2 style="color:gray;">내용</h2>
                    <textarea name="content" id="ucontent" placeholder="내용" style="margin-top: 6px;"><?= $tbl_bbs['content']; ?></textarea>
                </div>
                <div>
                    <input type="hidden" name="reg_id" value="<?= $tbl_bbs['reg_id']; ?>">
                </div>
            </form>
        </div>
    </div>
    <div id="bt_se">
        <div id="reg">
            <button type="button" onClick="fnCheck();" style="width: 80px; height: 30px; background:wheat; cursor:pointer;">수정하기</button>
        </div>
        <div id="list"><a href="/bbs_list.php"><button style="width: 80px; height: 30px; cursor:pointer;">목록</button></a></div>
    </div>
</body>

</html>