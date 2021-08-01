<?php
include  "C:/Project/SideProject_WebBoard/common/db.php";
include_once "C:/Project/SideProject_WebBoard/common/common.php";
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html;" />
    <title>게시판</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css" />

</head>

<body>
    <?php
    //해당 게시글 데이터 가져오기
    $bno = $_GET['no'];
    $pno = (isset($_GET['page'])) ? $_GET['page'] : 1;
    $sort = $_GET['sort'];
    $sql = mysqli_query($db, "SELECT 
                                    no
                                    , title
                                    , content
                                    , email
                                    , reg_id
                                    , reg_date
                                    , mod_date
                                FROM tbl_bbs WHERE no=$bno");

    if ($sql) {
        $tbl_bbs = mysqli_fetch_array($sql);
    } else {
    ?> <script>
            alert('DB 쿼리문 실행실패!');
            history.back();
        </script>
    <? }

    //게시글이 없는 no일때
    if (empty($bno)) {
    ?> <script>
            alert('게시글이 존재하지 않습니다!');
            history.back();
        </script>
    <? }

    //작성자가 아닐때
    if ($_SESSION['USER_ID'] != $tbl_bbs['reg_id']) {
    ?>
        <style>
            #board_mod_btn,
            #board_del_btn {
                display: none !important;
            }
        </style>
    <? }

    //비회원
    if (empty($_SESSION['USER_ID'])) {
    ?> <style>
            #dap_ins,
            #reply_btn {
                display: none !important;
            }
        </style>
    <?
    }

    //이전글
    $sql_pri = mysqli_query($db, "SELECT no, title FROM tbl_bbs WHERE no < $bno ORDER BY reg_date DESC LIMIT 1;");
    $tbl_bbs_pri = mysqli_fetch_assoc($sql_pri);

    //다음글
    $sql_next = mysqli_query($db, "SELECT no, title FROM tbl_bbs WHERE no > $bno ORDER BY reg_date ASC LIMIT 1;");
    $tbl_bbs_next = mysqli_fetch_assoc($sql_next);

    //댓글
    $sql_reply = mysqli_query($db, "SELECT reply_no, tbl_bbs_no, reply_content, reply_id, reg_date, mod_date FROM tbl_bbs_reply WHERE tbl_bbs_no=$bno");

    ?>
    <!--게시글 삭제-->

    <script type="text/javascript">
        function bbsDelCheck() {
            var bbsDelConfrim = confirm("정말 삭제하시겠습니까?");
            if (bbsDelConfrim) {
                //Form 전송
                document.write(
                    '<form id="frmDel" action="/bbs_save.php?page=<?= $pno ?>&no=<?= $bno ?>" method="post"><input type="hidden" name="action_flag" value="D"></form>'
                );
                document.getElementById("frmDel").submit();
            } else {
                return false;
            }
        }

        function replyCheck() {
            if (!frmReply.reply_content_textarea.value) {
                alert("작성한 내용이 없습니다!");
                frmReply.reply_content.focus();
                return false;
            }
            document.frmReply.submit();
        }

        function replyDelCheck(reno) {
            var replyDelConfrim = confirm("정말 삭제하시겠습니까?");
            if (replyDelConfrim) {
                //Form 전송
                document.write(
                    '<form id="frmReDel" action="/bbs_save.php?page=<?= $pno ?>&no=<?= $bno ?>&sort=<?= $sort ?>&reno=' + reno + '" method="post"><input type="hidden" name="action_flag" value="D"></form>'
                );
                document.getElementById("frmReDel").submit();
            } else {
                return false;
            }
        }

        function replyModiChange(reno) {
            document.getElementById("reply_content_modify_box_" + reno).style.display = "block";
            document.getElementById("reply_content_box_" + reno).style.display = "none";
            document.getElementById("re_modi_btn_" + reno).style.display = "none";
            document.getElementById("re_del_btn_" + reno).style.display = "none";
        }

        /*function replyModiCheck(reno) {
            //Form 전송
            document.frmReMod.submit();
            //$("#frmReMod").submit();
            // UI 변경
            document.getElementById("reply_content_modify_box_" + reno).style.display = "none";
            document.getElementById("reply_content_box_" + reno).style.display = "block";
            document.getElementById("re_modi_btn_" + reno).style.display = "block";
            document.getElementById("re_del_btn_" + reno).style.display = "block";
        }*/
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
                    <td><b>등록일시</b></td>
                    <td><?= $tbl_bbs['reg_date']; ?></td>
                </tr>
                <tr>
                    <td><b>등록이메일</b></td>
                    <td><?= $tbl_bbs['email']; ?></td>
                    <td><b>수정일시</b></td>
                    <td>
                        <? if ($tbl_bbs['reg_date'] != $tbl_bbs['mod_date']) {
                            echo $tbl_bbs['mod_date'];
                        } else { ?>
                            -
                        <? } ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <div id="bo_content">
            <?php echo nl2br($tbl_bbs['content']); ?>
        </div>
        <!--댓글 목록-->
        <div class="reply_view" id="reply_view">
            <h3>댓글 목록</h3>
            <div class="dap_lo">
                <? while ($tbl_bbs_reply = mysqli_fetch_assoc($sql_reply)) { ?>
                    <div class="dap_to">
                        <div style="font-size: 15px;"><b><?= $tbl_bbs_reply['reply_id'] ?></b>
                            <span style="font-size: 11px; margin-left:10px; color:gray;"><?= $tbl_bbs_reply['reg_date'] ?></span>
                            <? if ($tbl_bbs_reply['reg_date'] === $tbl_bbs_reply['mod_date']) { ?>
                            <?  } else { ?>
                                <span style="font-size: 11px; color: #09C"> | 수정 : <?= $tbl_bbs_reply['mod_date'] ?></span>
                            <? } ?>
                            <!--댓글 작성자에게만 수정, 삭제 버튼 노출 -->
                            <? if ($_SESSION['USER_ID'] === $tbl_bbs_reply['reply_id']) { ?>
                                <button type="button" id="re_del_btn_<?= $tbl_bbs_reply['reply_no'] ?>" onClick="replyDelCheck(<?= $tbl_bbs_reply['reply_no'] ?>);" style="float: right; margin-left:10px;">
                                    삭제
                                </button>
                                <button type="button" id="re_modi_btn_<?= $tbl_bbs_reply['reply_no'] ?>" onClick="replyModiChange(<?= $tbl_bbs_reply['reply_no'] ?>);" style="float: right;">
                                    수정
                                </button>
                            <? } ?>
                        </div>
                        <!--댓글 수정 이건 왜안될까 . 같은 페이지에서 댓글이 여러개일때 submit이 여러개인셈이되어서??
                        <div id="reply_content_modify_box_<?= $tbl_bbs_reply['reply_no'] ?>" style="display: none;">
                            <form name="frmReMod" action="/bbs_save.php?page=<?= $pno ?>&no=<?= $bno ?>&sort=<?= $sort ?>&reno=<?= $tbl_bbs_reply['reply_no'] ?>" method="post">
                                <input type="hidden" name="action_flag" value="M">
                                <textarea name="reply_content" cols=155><?= nl2br($tbl_bbs_reply['reply_content']) ?></textarea>
                                <button type="button" id="re_modi_save_btn" onClick="replyModiCheck(<?= $tbl_bbs_reply['reply_no'] ?>);" style="float: right;">
                                    저장
                                </button>
                            </form>
                        </div>-->
                        <!--댓글 수정-->
                        <div id="reply_content_modify_box_<?= $tbl_bbs_reply['reply_no'] ?>" style="display: none;">
                            <form name="frmReMod" action="/bbs_save.php?page=<?= $pno ?>&no=<?= $bno ?>&sort=<?= $sort ?>&reno=<?= $tbl_bbs_reply['reply_no'] ?>" method="post">
                                <input type="hidden" name="action_flag" value="M">
                                <textarea name="reply_content" cols=155><?= nl2br($tbl_bbs_reply['reply_content']) ?></textarea>
                                <button type="submit" id="re_modi_save_btn" style="float: right;">
                                    저장
                                </button>
                            </form>
                        </div>

                        <div id="reply_content_box_<?= $tbl_bbs_reply['reply_no'] ?>" style="font-size: 13px;"><?= nl2br($tbl_bbs_reply['reply_content']) ?></div>
                    </div>
                <? } ?>
            </div>
            <!--댓글 작성-->
            <div class="dap_ins" id="dap_ins">
                <h3 style="color: rgb(0, 153, 204);">댓글 작성</h3>
                <form action="/bbs_save.php?page=<?= $pno ?>&no=<?= $bno; ?>&sort=<?= $sort; ?>" name="frmReply" method="post">
                    <input type="hidden" name="action_flag" value="C">
                    <textarea name="reply_content" id="reply_content_textarea" placeholder="댓글을 입력해주세요."></textarea>
                </form>
                <button type="button" id="reply_btn" onClick="replyCheck();">저장</button>
            </div>
        </div>
        <!--이전글 다음글-->
        <div id="pri_next">
            <p><b>▲ 이전글</b>
                <? if (empty($tbl_bbs_pri['no'])) { ?>
                    이전글이 없습니다.
                <? } else { ?>
                    <u><a href="/bbs_content.php?page=<?= $pno ?>&no=<?= $tbl_bbs_pri['no'] ?>&sort=<?= $sort ?>"><?= $tbl_bbs_pri['title'] ?></a></u>
                <? } ?>
            </p>
            <p><b>▼ 다음글</b>
                <? if (empty($tbl_bbs_next['no'])) { ?>
                    다음글이 없습니다.
                <? } else { ?>
                    <u><a href="/bbs_content.php?page=<?= $pno ?>&no=<?= $tbl_bbs_next['no'] ?>&sort=<?= $sort ?>"><?= $tbl_bbs_next['title'] ?></a></u>
                <? } ?>
            </p>
        </div>
        <!-- 목록, 수정, 삭제 -->
        <div id="bo_ser">
            <a href="/bbs_list.php?page=<?= $pno ?>&sort=<?= $sort ?>"><button style="width: 50px; height: 30px;">목록</button></a>
            <a href="/bbs_modify.php?page=<?= $pno ?>&no=<?= $tbl_bbs['no']; ?>&sort=<?= $sort; ?>"><button id="board_mod_btn" style="width: 50px; height: 30px">수정</button></a>
            <button type="button" onClick="bbsDelCheck();" id="board_del_btn" style="width: 50px; height: 30px; background:tomato;">삭제</button>
        </div>
    </div>
</body>

</html>