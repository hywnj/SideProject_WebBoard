<?php
include_once "C:/sideProject/Board/common/db.php";
include_once "C:/sideProject/Board/common/common.php"; ?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <script>
        //frmSign 제출
        function fnCheck() {

            //변수
            var user_nm = frmSign.user_nm.value;
            var user_id = frmSign.user_id.value;
            var user_pw = frmSign.user_pw.value;
            var user_pw_check = frmSign.user_pw_check.value;
            var email = frmSign.email.value;
            var phone = frmSign.phone.value.replace(/[\-\s]/g, '');

            /* 필수값 & Max check */
            //이름
            if (!user_nm) {
                alert("이름을 입력해주세요!");
                document.frmSign.user_nm.focus();
                return false;
            }

            //아이디
            if (!user_id) {
                alert("아이디를 입력해주세요!");
                document.frmSign.user_id.focus();
                return false;
            } else {
                if (user_id.length < 2 || user_id.length > 10) {
                    alert("아이디는 최소 2자 이상, 최대 10자 이하로 설정해주세요!");
                    document.frmSign.user_id.focus();
                    return false;
                }
            }

            //비밀번호
            if (!user_pw) {
                alert("비밀번호를 입력해주세요!");
                document.frmSign.user_pw.focus();
                return false;
            } else {
                if (user_pw.length < 10 || user_pw.length > 15) {
                    alert("비밀번호는 최소 10자 이상, 최대 15자 이하로 설정해주세요!");
                    document.frmSign.user_pw.focus();
                    return false;
                }
            }
            if (!user_pw_check) {
                alert("비밀번호를 한번더 입력해주세요!");
                document.frmSign.user_pw_check.focus();
                return false;
            }

            //이메일
            if (!email) {
                alert("이메일을 입력해주세요!");
                document.frmSign.email.focus();
                return false;
            }


            /* 정규식 check */
            var emailExp = /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
            var idExp = /[^a-zA-Z]/ig;
            var pwExp = /(?=.*[a-zA-Z])+(?=.*\d)+(?=.*[#?!@$%^&*-])+/ig;
            var numExp = /[^0-9]/g;
            var engExp = /[a-zA-Z]/ig;
            var chrExp = /[#?!@$%^&*-]/ig;

            //아이디
            if (idExp.test(user_id)) {
                alert("아이디는 영문자만 포함가능합니다!");
                document.frmSign.user_id.focus();
                return false;
            }

            //비밀번호
            //if (!numExp.test(user_pw) || !engExp.test(user_pw) || !chrExp.test(user_pw)) {
            if (!pwExp.test(user_pw)) {
                alert("비밀번호는 1개이상의 특수문자와 영문자, 숫자 조합으로 설정해주세요!");
                document.frmSign.user_pw.focus();
                return false;
            }
            //비밀번호 확인 check
            if (user_pw !== user_pw_check) {
                alert("비밀번호가 다릅니다! 설정한 비밀번호와 같은 값을 입력해주세요.");
                document.frmSign.user_pw_check.focus();
                return false;
            }

            //이메일
            if (!emailExp.test(email)) {
                alert("이메일 형식이 올바르지 않습니다!");
                document.frmSign.email.focus();
                return false;
            }

            //전화번호
            if (phone) {
                if (phone.length < 10) {
                    alert("전화번호는 10자리 이상 입력해주세요!");
                    document.frmSign.phone.focus();
                    return false;
                } else {
                    if (numExp.test(phone)) {
                        alert("전화번호는 '-'와 숫자만 포함되어야 합니다!");
                        document.frmSign.phone.focus();
                        return false;
                    }
                }
            }
            //form 제출
            document.frmSign.submit();

        }
    </script>
</head>

<body>
    <div id="sign_area">
        <h1>회원가입</h1>
        <form name="frmSign" action="/bbs_save.php" method="post">
            <input type="hidden" name="action_flag" value="S">
            <p>
                <h3>이름<span style="color: red;"> *</span></h3>
                <input type="text" name="user_nm" id="sign_input" placeholder="이름을 입력해주세요." maxlength="10" require>
            </p>
            <p>
                <h3>아이디<span style="color: red;"> *</span></h3>
                <input type="text" name="user_id" id="sign_input" placeholder="최소 2자 ~ 최대 10자의 영문자만 포함가능합니다." maxlength="10" require>
            </p>
            <p>
                <h3>비밀번호<span style="color: red;"> *</span></h3>
                <input type="text" name="user_pw" id="sign_input" placeholder="10~15자리의 1개 이상의 특수문자와 영문자와 숫자의 조합만 가능합니다." maxlength="15" require>
            </p>
            <p>
                <h3>비밀번호 확인<span style="color: red;"> *</span></h3>
                <input type="text" name="user_pw_check" id="sign_input" placeholder="설정한 비밀번호를 한 번 더 입력해주세요." maxlength="15" require>
            </p>
            <p>
                <h3>이메일<span style="color: red;"> *</span></h3>
                <input type="text" name="email" id="sign_input" placeholder="이메일을 입력해주세요." maxlength="40" require>
            </p>
            <p>
                <h3>휴대전화번호</h3>
                <input type="text" name="phone" id="sign_input" placeholder="휴대전화번호를 입력해주세요.(선택항목)" maxlength="15">
            </p>
        </form>
        <button type="button" onClick="fnCheck();" id="sign_btn">가입하기</button>
        <a href="/index.php"><button type="button" id="main_btn">메인으로</button></a>
    </div>
</body>

</html>