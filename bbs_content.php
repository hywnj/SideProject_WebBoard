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

    //이전글
    $sql_pri = mysqli_query($db, "SELECT no, title FROM tbl_bbs WHERE no < $bno ORDER BY reg_date DESC LIMIT 1;");
    $tbl_bbs_pri = mysqli_fetch_assoc($sql_pri);

    //다음글
    $sql_next = mysqli_query($db, "SELECT no, title FROM tbl_bbs WHERE no > $bno ORDER BY reg_date ASC LIMIT 1;");
    $tbl_bbs_next = mysqli_fetch_assoc($sql_next);

    //댓글
    $sql_reply = mysqli_query($db, "SELECT reply_no, tbl_bbs_no, reply_content, reply_id, reply_date FROM tbl_bbs_reply WHERE tbl_bbs_no=$bno");

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
        function replyCheck(){
            if(!frmReply.reply_content.value){
                alert("작성한 내용이 없습니다!");
                return false;
            }else{
                document.frmReply.submit();
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
        <!--댓글-->
        <div class="reply_view">
            <h3>댓글 목록</h3>
            <div class="dap_lo">
                <? while ($tbl_bbs_reply = mysqli_fetch_assoc($sql_reply)) { ?>
                    <div class="dap_to">
                        <p style="font-size: 15px;"><b><?= $tbl_bbs_reply['reply_id'] ?></b></p>
                        <p style="font-size: 13px;"><?= nl2br($tbl_bbs_reply['reply_content']) ?></p>
                        <p style="font-size: 11px;"><?= $tbl_bbs_reply['reply_date'] ?></p>
                        <button type="button">수정</button> <button type="button">삭제</button>
                    </div>
                <? } ?>
            </div>
            <div class="dap_ins">
                <h3>댓글 작성</h3>
                <form name="frmReply" acton="/bbs_save.php" method="post">
                    <input type="hidden" name="action_flag" value="C">
                    <textarea name="reply_content" id="re_content" placeholder="댓글을 입력해주세요."></textarea>
                </form>
            </div>
            <button type="button" onClick="replyCheck();" id=reply_btn>저장</button>
        </div>
        <table style="margin-top: 30px;">
            <tbody>
                <tr>
                    <td><b>▲ 이전글</b></td>
                    <? if (empty($tbl_bbs_pri['no'])) { ?>
                        <td>이전글이 없습니다.</td>
                    <? } else { ?>
                        <td><u><a href="/bbs_content.php?page=<?= $pno ?>&no=<?= $tbl_bbs_pri['no'] ?>&sort=<?= $sort ?>"><?= $tbl_bbs_pri['title'] ?></a></u></td>
                    <? } ?>
                </tr>
                <tr>
                    <td><b>▼ 다음글</b></td>
                    <? if (empty($tbl_bbs_next['no'])) { ?>
                        <td>다음글이 없습니다.</td>
                    <? } else { ?>
                        <td><u><a href="/bbs_content.php?page=<?= $pno ?>&no=<?= $tbl_bbs_next['no'] ?>&sort=<?= $sort ?>"><?= $tbl_bbs_next['title'] ?></a></u></td>
                    <? }
                    ?>
                </tr>
            </tbody>
        </table>
        <!-- 목록, 수정, 삭제 -->
        <div id="bo_ser">
            <a href="/bbs_list.php?page=<?= $pno ?>&sort=<?= $sort ?>"><button style="width: 50px; height: 30px;">목록</button></a>
            <a href="/bbs_modify.php?page=<?= $pno ?>&no=<?= $tbl_bbs['no']; ?>&sort=<?= $sort; ?>"><button id="mod_btn" style="width: 50px; height: 30px">수정</button></a>
            <button type="button" onClick="delCheck();" id="del_btn" style="width: 50px; height: 30px; background:dimgrey;">삭제</button>
        </div>
    </div>
</body>

</html>