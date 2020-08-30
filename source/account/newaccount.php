<!DOCTYPE html>
<html lang="ja">
<head>
    <base href="/~group02/"></base>
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
    <!-- <script type="text/javascript">
        function sliceMaxLength(elem, maxlength){
            elem.value = elem.value.slice(0,maxLength);
        }
    </script> -->
</head>
<body>
    <div id="header"></div>
    <div id="newac">
        <form action="source/account/newaccount_check.php" method="POST">
            <div class="loginbox">
                <h3>ユーザID：<input name="user_name" type="text" maxlength='15'></h3>
                <h3>パスワード：<input id="pass" type="password" name="pass" title="記号以外15文字以内" minlength="6" maxlength="15" pattern="([ぁ-んァ-ヶｦ-ﾟ一-龠０-９a-zA-Z0-9\-]{1,15})" required ></h3>
                <h3>パスワード再入力：<input id="pass_check" type="password" name="pass_check" title="記号以外15文字以内" minlength="6" maxlength="15" pattern="([ぁ-んァ-ヶｦ-ﾟ一-龠０-９a-zA-Z0-9\-]{1,15})" required ></h3>
                <div class="submit">
                    <input  class="submitbtn_mv" name="create_account" type="submit" value="アカウント作成">
                </div>
            </div>
        </form>
    </div>

</body>
<div id="footer"></div>
</html>
