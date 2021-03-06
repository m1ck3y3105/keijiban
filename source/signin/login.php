<!DOCTYPE html>
<html lang="ja">
<head>
    <base href="/"></base>
    <title>掲示板サイト</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/style2.css">
    <link rel="stylesheet" href="styles/yota.css">
    <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
    <script>
        $(function () {
            $("#header").load("source/header.php");
            $("#footer").load("source/footer.html");
        });
    </script>
</head>
<body>
    <div id="header"></div>
    <div id="login">
        <form action="source/signin/login_check.php" method="POST">
            <div class="loginbox">
                <h3>ユーザID：<input name="user_name" type="text"></h3>
                <h3>パスワード：<input id="pass" type="text" name="pass" title="記号以外15文字以内" maxlength="15" pattern="([ぁ-んァ-ヶｦ-ﾟ一-龠０-９a-zA-Z0-9\-]{1,15})" required ></h3>
            </div>    
            <div class="submit">
                <input class="submitbtn_mv" name="login" type="submit" value="ログイン">
            </div>
        </form>
    </div>
    <div id="footer"></div>
</body>
</html>
