<?php
include_once "C:/sideProject/Board/common/db.php";
include_once "C:/sideProject/Board/common/common.php";
$_SESSION['USER_ID'] = 'ju04';
print_r($_SESSION);

//비회원 - 버튼 숨기기
if (empty($_SESSION['USER_ID'])) {
    echo '<style>#reg_btn{display:none !important;}</style>';
}

//GET변수 초기화
$catagory = (isset($_GET['catagory'])) ? $_GET['catagory'] : "";
$keyword = (isset($_GET['keyword'])) ? $_GET['keyword'] : "";
$pno = (isset($_GET['page'])) ? $_GET['page'] : 1;
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : "";

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
// 1) 전체 list 가져오기 
$sql_cnt = mysqli_query($db, "SELECT count(no) FROM tbl_bbs WHERE(1=1) $sql_keyword");
$tbl_bbs_cnt = mysqli_fetch_assoc($sql_cnt);

//sort
if(!empty($sort) && ($sort === "desc" || $sort === "asc" || $sort === "title")){
    if($sort === "desc"){
        $sql_sort = " ORDER BY no DESC";
    }else if($sort === "asc"){
        $sql_sort = " ";
    }
    else{
        $sql_sort = " ORDER BY title ASC";
    }
    
}
// 2) limit 추가 - 리스트에 표시할 것;
$sql_list = mysqli_query($db, "SELECT no, title, reg_id, reg_date, email 
                                FROM tbl_bbs WHERE(1=1) $sql_sort
                                LIMIT $list_start, $list_cnt");



//DB쿼리에서 불러온 값으로 초기화하는 변수 선언
$total_no = $tbl_bbs_cnt['count(no)']; // 총 게시글 수
$total_page = ceil($total_no / $list_cnt); //총 페이지 수 = (총 게시글 수 / 한 페이지 최대 게시글 수)의 올림

if ($total_page < $page_end) { //총 페이지 수보다 페이지 끝 번호가 작으면 page_end를 총 페이지 수로 설정
    $page_end = $total_page;
}


?>

<!doctype html>

<head>
    <meta charset="UTF-8">
    <title>게시판</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
</head>

<body>
    <div id="board_area">
        <h1>자유게시판</h1>
        <? if (!empty($catagory) && !empty($keyword)) { ?>
            <div id="reset_btn"><a href="/bbs_list.php"><button style="width: 80px; height: 30px;">처음으로</button></a></div>
        <? } ?>
        <div id="search_box">
            <form name="frmSrch" method="get">
                <input type="hidden" name="page" value="1">
                <select name="catagory" style="width:50px; height: 30px">
                    <option value="title"<? if($catagory === 'title') echo ' SELECTED' ?>>제목</option>
                    <option value="content"<? if($catagory === 'content') echo ' SELECTED' ?>>내용</option>
                </select>
                <input type="text" name="keyword" value="<?=$keyword?>" size="40" style="height: 30px" required="required" />
                <button style="width: 60px; height: 30px">검색</button>
            </form>
        </div>
        <!--정렬 셀렉트박스-->
        <div id="sort_box">
            <form name="frmSrt" method="get">
                <select name="sort" style="width: 90px; height:30px; margin-top:0;" onchange="changeSort()">
                    <option value="none">==정렬==</option>
                    <option value="desc"<? if($sort === 'desc') echo ' SELECTED' ?>>내림차순</option>
                    <option value="asc"<? if($sort === 'asc') echo ' SELECTED' ?>>오름차순</option>
                    <option value="title"<? if($sort === 'title') echo ' SELECTED' ?>>제목순</option>
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
                    <th width="100">이메일</th>
                </tr>
            </thead>
            <? while ($tbl_bbs = mysqli_fetch_array($sql_list)) { ?>
                <tbody>
                    <tr>
                        <td><?=$tbl_bbs['no'] ?></td>
                        <td><a href="/bbs_content.php?no=<?= $tbl_bbs['no'] ?>"><?= $tbl_bbs['title'] ?></a></td>
                        <td><?=$tbl_bbs['reg_id'] ?></td>
                        <td><?=date("Y-m-d", strtotime($tbl_bbs['reg_date']))?></td>
                        <td><?=$tbl_bbs['email'] ?></td>
                    </tr>
                </tbody>
            <? } ?>
        </table>
        <div id="paging_area">
            <table>
                <?
                //이전 - 시작 페이지 집합일때를 제외하고는 모두 표시
                if ($page_start != 1) {
                ?>
                    <td><a href="/bbs_list.php?page=<?= $page_start - 1 ?>&catagory=<?= $catagory ?>&keyword=<?= $keyword ?>">
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
                        <td><a href="/bbs_list.php?page=<?= $i ?>&catagory=<?= $catagory ?>&keyword=<?= $keyword ?>">[<?= $i ?>]</a></td>

                    <? }
                }
                //다음 - 맨 마지막 페이지 집합일때를 제외하고는 모두 표시
                if ($total_page != $page_end) { ?>
                    <td><a href="/bbs_list.php?page=<?= $page_end + 1 ?>&catagory=<?= $catagory ?>&keyword=<?= $keyword ?>">다음 ></a></td>
                <?
                }
                ?>

            </table>
        </div>
        <div id="write_btn">
            <a href="/bbs_regist.php"><button id="reg_btn" style="width: 80px; height: 30px;">등록하기</button></a>
        </div>
    </div>
</body>

</html>