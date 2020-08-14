<!DOCTYPE html>
<?php 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        //code
    }
?>
<html lang="ja">
    <head>
        <base href="/"></base>
        <meta charset="UTF-8">
        <title>ニックネーム変更</title>
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
        <div id="name">
            <div id="chenge"><h3>ニックネーム変更</h3></div>
            <form class="nameForm" action="source/account/change_name_check.php" method="post" name="name-form">
                <div class="namae">
                    <h4>新しいニックネームを入力してください</h4>
                    <h5>（全角、半角15文字以内）</h5>
                    <div class="nic">
                        <h3><label for="title">ニックネーム : </label></h3>
                        <input id="newname" type="text" name="newname" title="記号以外15文字以内" maxlength="15" pattern="([ぁ-んァ-ヶｦ-ﾟ一-龠０-９a-zA-Z0-9\-]{1,15})" required >
                    </div>
                    <h1><input class="submitbtn_mv" type="submit" value="更新"></h1>
                </div>
            </form>   
        </div>
        <div id="footer"></div>
    </body>
</html>