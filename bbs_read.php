<?php include  $_SERVER['DOCUMENT_ROOT']."/db.php"; ?>
<!doctype html>
<head>
    <meta charset="UTF-8">
    <title>게시판</title>
    <link rel="stylesheet" type="text/css" href="/css/read_style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body>
	<?php
		$bno = $_GET['no']; 
		$sql = mysqli_query($db,"select * from tbl_bbs where no='".$bno."'"); 
		if($sql){
            $tbl_bbs = mysqli_fetch_array($sql);
        }else{
            echo "<script>
            alert('DB 쿼리문 실행실패!');
            history.back();</script>";
        }        
	?>
    <!-- 글 불러오기 -->
    <div id="board_read">
    <h1>게시글 상세페이지</h1>
        <table class="list-table">
            <thead>
                <th style="width: 200px;"><b>제목</b></th>
                <th colspan="3" style="width: 300px;"><?php echo $tbl_bbs['title'] ?></th>
            </thead>
            <tbody>
                <tr>
                    <td><b>등록아이디</b></td>
                    <?php echo '<td>'.$tbl_bbs['reg_id'].'</td>'?>
                    <td><b>등록일자</b></td>
                    <?php echo '<td>'.date("Y-m-d",strtotime($tbl_bbs['reg_date'])).'</td>'?>
                </tr>
            </tbody>
        </table>
        <div id="bo_content">
                <?php echo nl2br("$tbl_bbs[content]"); ?>
        </div>
        <!-- 목록, 수정, 삭제 -->
        <div id="bo_ser" >
            <a href="/bbs_list.php"><button style="width: 50px; height: 30px;">목록</button></a>
            <a href="/bbs_list.php"><button style="width: 50px; height: 30px;">수정</button></a>
            <a href="/bbs_list.php"><button style="width: 50px; height: 30px; color:grey;">삭제</button></a>
        </div>
    </div>
</body>
</html>