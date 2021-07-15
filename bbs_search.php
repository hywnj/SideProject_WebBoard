<?php include $_SERVER['DOCUMENT_ROOT']."/db.php";?>
<!doctype html>
<head>
    <meta charset="UTF-8">
    <title>게시판</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
</head>
<body>
    <div id="board_area"> 
    <?php
        $catagory = $_GET['catgo'];
        $search_con = $_GET['search'];
    ?>
    <h1><?php echo $catagory; ?>에서 '<?php echo $search_con; ?>'검색결과</h1>
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
            $sql = mysqli_query($db,"select * from tbl_bbs where $catagory like '%$search_con%' order by no desc");
            while($tbl_bbs = mysqli_fetch_array($sql)){
                echo '<tbody>
                <td>'.$tbl_bbs['no'].'</td>
                <td><a href="/bbs_read.php?no='.$tbl_bbs['no'].'">'.$tbl_bbs['title'].'</a></td>
                <td>'.$tbl_bbs['reg_id'].'</td>
                <td>'.$tbl_bbs['reg_date'].'</td>
                <td>'.$tbl_bbs['email'].'</td>
                </tbody>';
            }
            ?>
        </table>
        <div id="search_box2">
            <form action="/bbs_search.php" method="get">
            <select name="catgo">
                <option value="title">제목</option>
                <option value="content">내용</option>
            </select>
            <input type="text" name="search" size="40" required="required"/> <button>검색</button>
            </form>
        </div>
        <div id="write_btn">
            <a href="/bbs_list.php"><button>처음으로</button></a>
        </div>
    </div>
</body>
</html>