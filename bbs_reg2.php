<?php  
    include_once "C:/sideProject/Board/common/db.php"; 
    include_once "C:/sideProject/Board/common/common.php"; 
    echo $_SESSION['USER_ID'];

    //url 직접접근 방지
    /*if ( !preg_match("/".$_SERVER['HTTP_HOST']."/i", $_SERVER['HTTP_REFERER'])){
        echo "<script>
        alert('No direct access allowed');
        location.href='/bbs_list.php';</script>";
        exit('No direct access allowed');
    }*/
    
    //비정상적 접근 - 비회원 접근
    if(empty($_SESSION['USER_ID'])) {
        echo "<script>
        alert('접근권한이 없습니다! 로그인을 해주세요!');
        location.href='/bbs_list.php';</script>";
    }
?>
<!doctype html>
<head>
    <meta charset="UTF-8">
    <title>게시판</title>
    <link rel="stylesheet" type="text/css" href="/css/read_style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <script>
        function fnCheck() {
            
            var title = frmReg.title.value;
            var reg_id =  "<?=$_SESSION['USER_ID']?>" ;
            //frmReg.reg_id.value;
            var email = frmReg.email.value;
            
            //필수 입력값 check
            if(title == ""||reg_id == ""){
                if(title == ""){
                    alert("제목을 입력해주세요!");
                    return false;
                }else if(reg_id == ""){
                    alert("등록 아이디를 입력해주세요!");
                    return false;
                }
            }

            if(email.trim()){
                var regExp = /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
                if(!regExp.test(email)) {
                    alert("이메일 형식이 올바르지 않습니다!");
                    return false;
                }
            }
            //form 제출
            document.frmReg.submit();     
        }
    </script>

</head>
<body>
    <div id="board_read">
    <h1>게시글 상세페이지</h1>
        <form action="/bbs_save.php" name="frmReg" method="post">
            <input type= "hidden" name="action_flag" value="r">
            <table class="list-table">
                <thead>
                    <th style="width: 200px;"><b>제목</b></th>
                    <th colspan="3" style="width: 300px;">
                    <input type="text" name="title" id="utitle"  placeholder="제목을 입력해주세요." maxlength="100" required style="height:70%; width:100%; border: 0;"></th>
                </thead>
                <tbody>
                    <tr>
                        <td><b>등록아이디</b></td>
                        <td>
                        <input name="reg_id" id="ureg_id" value = <?=$_SESSION['USER_ID'] ?> >
                        </td>
                        <td><b>이메일</b></td>
                        <td>
                        <input type="text" name="email" id="uemail"  placeholder="이메일을 입력해주세요." maxlength="100" required style="height:100%; width:100%; border: 0;">
                        </td>
                    </tr>
                </tbody>
            </table>
            <div id="bo_content">
                <textarea name="content" id="ucontent" rows="20" cols="124" placeholder="내용을 입력해주세요."></textarea>
            </div>
        </form>
        <!-- 목록, 수정, 삭제 -->
        <div id="bo_ser" >
            <a href="/bbs_list.php"><button style="width: 50px; height: 30px;">목록</button></a>
            <button type="button" onClick="fnCheck();" style="width: 80px; height: 30px; background:wheat; cursor:pointer;" >저장</button></a>
        </div>
    </div>
</body>
</html>