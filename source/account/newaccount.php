<!DOCTYPE html>
<html lang="ja">
<head>
    <base href="/"></base>
    <title>掲示板サイト</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/style.css">
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
    <div id="newac">
        <form action="source/account/newaccount_check.php" method="POST">
            <div class="loginbox">
                <h3>ユーザID：<input name="user_name" type="text"></h3>
                <h3>パスワード：<input name="password" type="password"></h3>
                <h3>パスワード再入力：<input name="re_password" type="password"></h3>
                <div class="submitbtn">
                    <input name="create_account" type="submit" value="アカウント作成">
                </div>
            </div>
        </form>
    </div>

</body>
<div id="footer"></div>
</html>
