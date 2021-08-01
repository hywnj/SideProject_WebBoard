<?php
include_once "C:/sideProject/Board/common/db.php";
include_once "C:/sideProject/Board/common/common.php";

$user_id = $_SESSION['USER_ID'];

//비회원
if (empty($user_id)) {
?> <style>
        #reg_btn,
        #logout_btn,
        #myboard_btn {
            display: none !important;
        }
    </style>
<? $user_id = "비회원";
} else {
?>
    <style>
        #sign_btn,
        #login_list_btn {
            display: none !important;
        }
    </style>
<?
}

//GET변수 초기화
$catagory = (isset($_GET['catagory'])) ? $_GET['catagory'] : "";
$keyword = (isset($_GET['keyword'])) ? $_GET['keyword'] : "";
$pno = (isset($_GET['page'])) ? $_GET['page'] : 1;
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : "desc";
$myboard = (isset($_GET['myboard'])) ? $_GET['myboard'] : "";

//변수
$list_cnt = 5; //한 페이지 최대 게시글 수
$page_cnt = 5; //아래 보여지는 최대 페이지 num 개수
$list_start = ($pno - 1) * $list_cnt; //현재 페이지 게시글 시작번호 = (현재 페이지 - 1) * 한 페이지 최대 게시글 수 
$page_num = ceil($pno / $page_cnt); //몇번째 페이지 집합인지 = (현재 페이지 / 아래 보여지는 최대 페이지 개수)의 올림
$page_start = ($page_num - 1) * $page_cnt + 1; // 현재 페이지 시작번호 = (페이지 집합 번호 - 1) * 아래 보여지는 최대 페이지 개수 + 1 
$page_end =  $page_start + $page_cnt - 1; //페이지 끝 번호 = 페이지 시작번호 + 페이지 최대 개수 - 1

//DB쿼리 실행
//검색여부 확인
$sql_keyword = "";
if (!empty($catagory) && ($catagory === "title" || $catagory === "content") && !empty($keyword)) {
    $sql_keyword = " AND $catagory LIKE '%$keyword%'";
}
//내 게시물 확인 버튼
$sql_myboard = "";
if ($myboard === "true") {
    $sql_myboard = " AND REG_ID ='" . $_SESSION['USER_ID'] . "'";
}

// 1) 전체 list 가져오기 
$sql_cnt = mysqli_query($db, "SELECT count(no) FROM tbl_bbs WHERE(1=1) $sql_keyword $sql_myboard");
$tbl_bbs_cnt = mysqli_fetch_assoc($sql_cnt);

//정렬
if ($sort === "desc" || $sort === "asc" || $sort === "title") {
    if ($sort === "title") {
        $sql_sort = " ORDER BY $sort ASC";
    } else {
        $sql_sort = " ORDER BY reg_date $sort";
    }
} else {
    $sql_sort = " ORDER BY reg_date DESC";
}

// 2) limit 추가 - 리스트에 표시할 것;
/*$sql_list = mysqli_query($db, "SELECT no, title, reg_id, reg_date, email 
                                FROM tbl_bbs WHERE(1=1) $sql_keyword $sql_myboard $sql_sort
                                LIMIT $list_start, $list_cnt");*/

//2) 리스트에 표시할 것 - 댓글 추가
$sql_list = mysqli_query($db, "SELECT
                                tb.no AS no,
                                tb.title AS title,
                                tb.reg_id AS reg_id,
                                tb.email AS email,
                                tb.reg_date AS reg_date,
                                count(tbr.tbl_bbs_no) AS reply_cnt
                            FROM
                                tbl_bbs AS tb
                            left outer join tbl_bbs_reply AS tbr ON
                                tb.no = tbr.tbl_bbs_no
                            WHERE
                                (1 = 1) $sql_keyword $sql_myboard 
                                GROUP BY tb.no
                                $sql_sort
                                LIMIT $list_start, $list_cnt");


//DB쿼리에서 불러온 값으로 초기화하는 변수 선언
$total_no = $tbl_bbs_cnt['count(no)']; // 총 게시글 수
$total_page = ceil($total_no / $list_cnt); //총 페이지 수 = (총 게시글 수 / 한 페이지 최대 게시글 수)의 올림

if ($total_page < $page_end) { //총 페이지 수보다 페이지 끝 번호가 작으면 page_end를 총 페이지 수로 설정
    $page_end = $total_page;
}



?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>게시판</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <script>
        function changeSort(option) {
            //정렬 option 선택시, 1번째 page로 세팅
            location.replace("/bbs_list.php?page=1&catagory=<?= $catagory ?>&keyword=<?= $keyword ?>&sort=" + option + "&myboard=<?= $myboard ?>");
        }

        function logoutCheck() {
            var outConfirm = confirm('로그아웃 하시겠습니까?');

            if (outConfirm) {
                location.href = '/bbs_logout.php';
            } else {
                return false;
            }
        }
    </script>
</head>

<body>
    <div id="user_box">
        <p><b><?= $user_id ?></b>님 환영합니다!</p>
        <!--회원-->
        <button type="button" onClick="logoutCheck();" id="logout_btn" style="width: 80px; height:30px;">
            로그아웃
        </button>
        <a href="/bbs_list.php?page=1&catagory=<?= $catagory ?>&keyword=<?= $keyword ?>&sort=<?= $sort ?>&myboard=true">
            <button type="button" id="myboard_btn" style="width: 100px; height:30px;">내 게시물 보기</button>
        </a>
        <!--비회원-->
        <a href="/bbs_sign.php">
            <button type="button" id="sign_btn" style="width: 100px; height:30px;">
                회원가입
            </button>
        </a>
        <a href="/index.php">
            <button type="button" id="login_list_btn" style="width: 80px; height:30px;">
                로그인
            </button>
        </a>
    </div>
    <div id="board_area">
        <h1><a href="/bbs_list.php">자유게시판</a></h1>
        <? if (!empty($catagory) && !empty($keyword) || $myboard === "true") { ?>
            <div id="reset_btn"><a href="/bbs_list.php"><button style="width: 80px; height: 30px;">처음으로</button></a></div>
        <? } ?>
        <div id="search_box">
            <form name="frmSrch" method="get">
                <select name="catagory" style="width:50px; height: 30px">
                    <option value="title" <? if ($catagory === 'title') echo ' SELECTED' ?>>제목</option>
                    <option value="content" <? if ($catagory === 'content') echo ' SELECTED' ?>>내용</option>
                </select>
                <input type="text" name="keyword" value="<?= $keyword ?>" size="40" style="height: 30px" required="required" />
                <button style="width: 60px; height: 30px">검색</button>
            </form>
        </div>
        <!--정렬 셀렉트박스-->
        <div id="sort_box">
            <form name="frmSrt">
                <select name="sort" style="width: 90px; height:30px; margin-top:0;" onChange="changeSort(this.value)">
                    <option value="desc" <? if ($sort === 'desc') echo ' SELECTED' ?>>내림차순</option>
                    <option value="asc" <? if ($sort === 'asc') echo ' SELECTED' ?>>오름차순</option>
                    <option value="title" <? if ($sort === 'title') echo ' SELECTED' ?>>제목순</option>
                </select>
                <h4 style="margin-left: 7px; ">총 <?= $total_no ?>개</h4>
            </form>
        </div>
        <table class="list-table">
            <thead>
                <tr>
                    <th width="70">번호</th>
                    <th width="500">제목</th>
                    <th width="120">등록아이디</th>
                    <th width="100">등록일</th>
                    <th width="150">이메일</th>
                </tr>
            </thead>
            <? while ($tbl_bbs = mysqli_fetch_array($sql_list)) { ?>
                <tbody>
                    <tr>
                        <td><?= $tbl_bbs['no'] ?></td>
                        <td>
                            <a href="/bbs_content.php?page=<?= $pno ?>&no=<?= $tbl_bbs['no'] ?>&sort=<?= $sort ?>">
                                <?= $tbl_bbs['title']; ?>
                            </a>
                            <!-- 댓글 수 -->
                            <?
                            if (!empty($tbl_bbs['reply_cnt'])) { ?>
                                <a href="/bbs_content.php?page=<?= $pno ?>&no=<?= $tbl_bbs['no'] ?>&sort=<?= $sort ?>#reply_view">
                                    <span style="color:orangered; font-weight:bold">
                                        &nbsp[<?= $tbl_bbs['reply_cnt'] ?>]
                                    </span>
                                </a>
                            <?    }
                            ?>
                        </td>
                        <td><?= $tbl_bbs['reg_id'] ?></td>
                        <td><?= date("Y-m-d", strtotime($tbl_bbs['reg_date'])) ?></td>
                        <td><?= $tbl_bbs['email'] ?></td>
                    </tr>
                </tbody>
            <? } 
                $db->close();
            ?>
        </table>
        <div id="paging_area">
            <table>
                <?
                //이전 - 시작 페이지 집합일때를 제외하고는 모두 표시
                if ($page_start != 1) {
                ?>
                    <td><a href="/bbs_list.php?page=<?= $page_start - 1 ?>&catagory=<?= $catagory ?>&keyword=<?= $keyword ?>&sort=<?= $sort ?>&myboard=<?= $myboard ?>">
                            < 이전 </a>
                    </td>
                    <?
                }
                //페이지 표시 - 현재 총 페이지 수만큼 반복
                for ($i = $page_start; $i <= $page_end; $i++) {
                    if ($pno == $i) { //현재 페이지일때 활성화 표시
                    ?>
                        <td style="color:blue; "><u><b>[<?= $i ?>]</b></u></td>
                    <?
                    } else { //현재 페이지 아닐때
                    ?>
                        <td><a href="/bbs_list.php?page=<?= $i ?>&catagory=<?= $catagory ?>&keyword=<?= $keyword ?>&sort=<?= $sort ?>&myboard=<?= $myboard ?>">[<?= $i ?>]</a></td>

                    <? }
                }
                //다음 - 맨 마지막 페이지 집합일때를 제외하고는 모두 표시
                if ($total_page != $page_end) { ?>
                    <td><a href="/bbs_list.php?page=<?= $page_end + 1 ?>&catagory=<?= $catagory ?>&keyword=<?= $keyword ?>&sort=<?= $sort ?>&myboard=<?= $myboard ?>">다음 ></a></td>
                <?
                }
                ?>

            </table>
        </div>
        <div id="write_btn">
            <a href="/bbs_regist.php"><button id="reg_btn" style="width: 80px; height: 30px; background-color:#09C; color:white;">등록하기</button></a>
        </div>
    </div>
</body>

</html>