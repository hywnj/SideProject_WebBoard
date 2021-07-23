<?php
    include_once "C:/sideProject/Board/common/common.php";
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css" />

    <script>
        function userCheck(){
            
            var user_id = frmLogin.user_id.value;
            var user_pw = frmLogin.user_pw.value;
            
            //입력 check
            if(!user_id){
                alert('아이디를 입력해주세요!');
                return false;
            }
            if(!user_pw){
                alert('비밀번호를 입력해주세요!');
                return false;
            }

            //login form submit
            document.frmLogin.submit();

        }
    </script>
</head>

<body>
    <div id="main_area">
        <h1 style="text-align: center;">게시판에 오신걸 환영합니다!</h1>
        <div id="login_box">
            <!--로그인-->
            <h2 style="margin-left: 20px;">로그인</h2>
            <form name="frmLogin" action="/bbs_login.php" method="post">
                <h4 id="login_text">아이디</h4>
                <input type="text" name="user_id" id="login_input" placeholder="아이디를 입력해주세요." maxlength="10" require>
                <h4 id="login_text">비밀번호</h4>
                <input type="password" name="user_pw" id="login_input" placeholder="비밀번호를 입력해주세요." maxlength="15" require>
            </form>
            <button type="button" onClick="userCheck();" id="login_btn">로그인</button>
        </div>
        <!--회원가입-->
        <h4 style="margin-top: 50px;">회원이 아니시라면, 회원가입을 먼저 해주세요!</h4>
        <a href="/bbs_sign.php">
            <h4 style="color: blue; margin-top:-5px"><u>회원가입하기↗</u></h4>
        </a>
        <!--게시글 리스트 바로가기-->
        <p style="color:gray; margin-top:30px">비회원으로는 게시글 보기만 가능해요!</p>
        <a href="/bbs_list.php"><button id="list_btn"><b>자유게시판 바로가기</b></button></a>
    </div>
</body>

</html>