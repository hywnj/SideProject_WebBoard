<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
</head>
<body>
    <div id="main_area">
    <h1>게시판에 오신걸 환영합니다!</h1>
    <a href="/bbs_list.php"><button id="list_btn"><b>자유게시판 바로가기</b></button></a>
        <div class="login-box">
            <!--로그인-->
            <form name="frmLogin">
                <h4 style="margin-right: 130px; margin-top:50px">아이디</h4>
                <input type="text" name="user_id" placeholder="아이디를 입력해주세요." style="width: 180px; height:30px;">
                <h4 style="margin-right: 110px; margin-top: 20px">비밀번호</h4>
                <input type="password" name="user_pw" placeholder="비밀번호를 입력해주세요." style="width: 180px; height:30px;">
            </form>
            <!--회원가입-->
            <h4 style="margin-top: 50px;">회원이 아니시라면, 회원가입을 먼저 해주세요!</h4>
            <a href="/bbs_sign.php"><h4 style="color: blue;"><u>회원가입하기</u></h4></a>
        </div>
    </div>
</body>
</html>