<?php  
    include_once "C:/sideProject/Board/common/db.php"; 
    include_once "C:/sideProject/Board/common/common.php"; 
    $_SESSION['USER_ID'] = 'ju04';
    print_r($_SESSION);

    //비회원 - 버튼 숨기기
    if(empty($_SESSION['USER_ID'])){
        echo '<style>#reg_btn{display:none !important;}</style>';
    }

    if(!empty($_GET['search'])){
        $catagory = $_GET['catgo'];
        $search_con = $_GET['search'];
        $sql_srch = mysqli_query($db,"select * from tbl_bbs 
                                where $catagory like '%$search_con%'");
    }else{
        #$sql = mysqli_query($db,"select no,title,reg_id,DATE_FORMAT(reg_date, '%Y-%m-%d') as reg_date, email from tbl_bbs order by no desc"); 
        $sql = mysqli_query($db,"select * from tbl_bbs"); 
    }

    //검색했다면
    /*if(!empty($_GET['search'])){

    }*/

    //페이징
    if(empty($_GET['page'])){
        $pno = 1; //처음 접속시 page 번호는 1
    }else{
        $pno = $_GET['page']; //현재 페이지
    }
    $total_no = mysqli_num_rows($sql); // 총 게시글 수

    $list_cnt = 5; //한 페이지 최대 게시글 수
    $page_cnt = 5; //아래 보여지는 최대 페이지 num 개수
    $list_start = ($pno - 1) * 5 ; //현재 페이지 게시글 시작번호

    $sql2 = mysqli_query($db, "select no,title,reg_id,DATE_FORMAT(reg_date, '%Y-%m-%d') as reg_date, email 
                                from tbl_bbs 
                                order by no desc
                                limit $list_start, $list_cnt");


    $total_page = ceil($total_no / $list_cnt); //총 페이지 수
    $page_num = ceil($pno / $list_cnt); //몇번째 페이지 집합인지
    $page_start = ($page_num - 1 ) * $page_cnt + 1 ; // 현재 페이지 시작번호
    $page_end =  $page_start + $list_cnt - 1 ;//페이지 끝 번호
    if($total_page < $page_end){ //총 페이지 수보다 페이지 끝 번호가 작으면 page_end를 총 페이지 수로 설정
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
    <? if(!empty($_GET['search'])){ ?>
    <div id="reset_btn"><a href="/bbs_list.php"><button style="width: 80px; height: 30px;">처음으로</button></a></div>
    <? } ?>
    <div id="search_box">
        <form name="frmSrch" method="get">
        <select name="catgo" style="width:50px; height: 30px">
            <option value="title">제목</option>
            <option value="content">내용</option>
        </select>
        <input type="text" name="search" size="40" style="height: 30px" required="required" /> <button  style="width: 60px; height: 30px">검색</button>
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
        <? while($tbl_bbs = mysqli_fetch_array($sql2)) { ?>
        <tbody>
            <tr>
                <td><?=$tbl_bbs['no']?></td>
                <td><a href="/bbs_content.php?no=<?=$tbl_bbs['no']?>"><?=$tbl_bbs['title']?></a></td>
                <td><?=$tbl_bbs['reg_id']?></td>
                <td><?=$tbl_bbs['reg_date']?></td>
                <td><?=$tbl_bbs['email']?></td>
            </tr>
        </tbody>
        <? } ?>
    </table>
    <div id="paging_area">
        <table>
            <?  
                //이전
                if($page_start != 1){
            ?>
                <td><a href="/bbs_list.php?page=<?=$page_start - 1?>"> < 이전 </a></td>
            <?
                }
                //현재 총 페이지 수만큼 반복
                for($i = $page_start; $i <= $page_end; $i++){
                    if($pno == $i){ //현재 페이지일때 활성화 표시
            ?>
                    <td style="color:blue; "><u><b>[<?=$i?>]</b></u></td>
            <?
                    } 
                    else { //현재 페이지 아닐때
            ?>
                    <td><a href="/bbs_list.php?page=<?=$i?>">[<?=$i?>]</a></td>
            <?
                    }
                }
                //다음
                if($total_page != $page_end){ ?>
                <td><a href="/bbs_list.php?page=<?=$page_end + 1?>">다음 ></a></td>
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