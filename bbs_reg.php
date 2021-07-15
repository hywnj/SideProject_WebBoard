<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>게시판 - 등록하기</title>
    <link rel="stylesheet" type="text/css" href="/css/reg_style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <script>
        function fnCheck() {
            
            var title = frmReg.title.value;
            var reg_id = frmReg.reg_id.value;
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
    <div id="board_reg">
        <h1>등록하기 페이지</h1>
        <div id="reg_area">
            <form action="/bbs_save.php" name="frmReg" method="post">
                <div id="in_title">
                    <!--보통 DB Max값의 절반으로 maxlengtg 설정-->
                    <textarea name="title" id="utitle" rows="1" cols="55" placeholder="제목을 입력해주세요." maxlength="100" required></textarea>
                </div>
                <div class="wi_line"></div>
                <div id="in_reg_id">
                    <textarea name="reg_id" id="ureg_id" rows="1" cols="55" placeholder="등록 아이디를 입력해주세요." maxlength="100" required></textarea>
                </div>
                <div class="wi_line"></div>
                <div id="in_reg_id">
                    <textarea name="email" id="uemail" rows="1" cols="55" placeholder="이메일을 입력해주세요." maxlength="100" ></textarea>
                </div>
                <div class="wi_line"></div>
                <div id="in_content">
                    <textarea name="content" id="ucontent" placeholder="내용을 작성해주세요."></textarea>
                </div>
            </form>
        </div>
        
    </div>
    <div id="bt_se">
        <div id="reg"><button type="button" onClick="fnCheck();" style="width: 80px; height: 30px; background:wheat; cursor:pointer;" >등록하기</button></div>
        <div id="list"><a href="/bbs_list.php"><button style="width: 80px; height: 30px; cursor:pointer;">목록</button></a></div>
    </div>
</body>
</html>
