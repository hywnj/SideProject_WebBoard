<?php  
    include_once "C:/sideProject/Board/common/db.php"; 
    include_once "C:/sideProject/Board/common/common.php"; 
    $_SESSION['USER_ID'] = 'ju04';
    print_r($_SESSION);
?>

<!doctype html>
<head>
<meta charset="UTF-8">
<title>게시판</title>
<link rel="stylesheet" type="text/css" href="/css/style.css" />
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body>
<div id="board_area"> 
  <h1>자유게시판</h1>
  <?php 
        if($_GET){
            echo '<div id="reset_btn"><a href="/bbs_list.php"><button style="width: 80px; height: 30px;">처음으로</button></a></div>';
        }
      ?>
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
        <?php
            //비회원 - 버튼 숨기기
            if(empty($_SESSION['USER_ID'])){
                echo '<style>#reg_btn{display:none !important;}</style>';
            }

            if($_GET){
                $catagory = $_GET['catgo'];
                $search_con = $_GET['search'];
                $sql = mysqli_query($db,"select no,title,reg_id,DATE_FORMAT(reg_date, '%Y-%m-%d') as reg_date, email from tbl_bbs where $catagory like '%$search_con%' order by no desc"); 
            }else{
                $sql = mysqli_query($db,"select no,title,reg_id,DATE_FORMAT(reg_date, '%Y-%m-%d') as reg_date, email from tbl_bbs order by no desc"); 
            }
            
            while($tbl_bbs = mysqli_fetch_array($sql)){
                echo '<tbody><tr>
                <td>'.$tbl_bbs['no'].'</td>
                <td><a href="/bbs_read.php?no='.$tbl_bbs['no'].'">'.$tbl_bbs['title'].'</a></td>
                <td>'.$tbl_bbs['reg_id'].'</td>
                <td>'.$tbl_bbs['reg_date'].'</td>
                <td>'.$tbl_bbs['email'].'</td></tr>
                </tbody>';
            }
        ?>
    </table>
    <div id="write_btn">
      <a href="/bbs_reg2.php"><button id="reg_btn" style="width: 80px; height: 30px;">등록하기</button></a>
    </div>
  </div>
</body>
</html>