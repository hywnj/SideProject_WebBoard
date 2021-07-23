<?php
include  "C:/sideProject/Board/common/db.php";
include_once "C:/sideProject/Board/common/common.php";


?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>게시판</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
</head>

<body>
    <?php
    //해당 게시글 데이터 가져오기
    $bno = $_GET['no'];
    $pno = (isset($_GET['page'])) ? $_GET['page'] : 1;
    $sort = $_GET['sort'];
    $sql = mysqli_query($db, "SELECT * FROM tbl_bbs WHERE no=$bno");

    if ($sql) {
        $tbl_bbs = mysqli_fetch_array($sql);
    } else {
        echo "<script>
            alert('DB 쿼리문 실행실패!');
            history.back();</script>";
    }

    //게시글이 없는 no일때
    if (empty($bno)) {
        echo "<script>
            alert('게시글이 존재하지 않습니다!');
            history.back();</script>";
    }

    //작성자가 아닐때
    if (empty($_SESSION['USER_ID']) || $_SESSION['USER_ID'] != $tbl_bbs['reg_id']) {
        echo '<style>#mod_btn, #del_btn{display:none !important;}</style>';
    }
    ?>
    <!--Delete-->
    <script>
        function delCheck() {

            var delConfrim = confirm("정말 삭제하시겠습니까?");

            if (delConfrim) {
                //Form 전송
                document.write(
                    '<form id="frmDel" action="/bbs_save.php?page=<?= $pno ?>&no=<?= $bno; ?>" method="post"><input type="hidden" name="action_flag" value="D"></form>'
                );
                document.getElementById("frmDel").submit();
            } else {
                return false;
            }
        }
    </script>

    <!-- 글 불러오기 -->
    <div id="board_read">
        <h1>게시글 상세페이지</h1>
        <table class="list-table-content">
            <thead>
                <th style="width: 200px;"><b>제목</b></th>
                <th colspan="3" style="width: 300px;"><?= $tbl_bbs['title'] ?></th>
            </thead>
            <tbody>
                <tr>
                    <td><b>등록아이디</b></td>
                    <td><?= $tbl_bbs['reg_id']; ?></td>
                    <td><b>등록일자</b></td>
                    <?php echo '<td>' . date("Y-m-d", strtotime($tbl_bbs['reg_date'])) . '</td>' ?>
                </tr>
            </tbody>
        </table>
        <div id="bo_content">
            <?php echo nl2br("$tbl_bbs[content]"); ?>
        </div>
        <!-- 목록, 수정, 삭제 -->
        <div id="bo_ser">
            <a href="/bbs_list.php?page=<?= $pno ?>&sort=<?= $sort ?>"><button style="width: 50px; height: 30px;">목록</button></a>
            <a href="/bbs_modify.php?page=<?= $pno ?>&no=<?= $tbl_bbs['no']; ?>&sort=<?= $sort; ?>"><button id="mod_btn" style="width: 50px; height: 30px">수정</button></a>
            <button type="button" onClick="delCheck();" id="del_btn" style="width: 50px; height: 30px; background:dimgrey;">삭제</button>
        </div>
    </div>
</body>

</html>