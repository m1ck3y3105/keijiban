<!DOCTYPE html>
<?php 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        //code
    }
?>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ニックネーム変更</title>
        <link rel="stylesheet" href="../styles/style.css">
        <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
        <script>
            $(function () {
                $("#header").load("./header.html");
                $("#footer").load("./footer.html");
            });
        </script>
    </head>
    <div id="header"></div>
    <div id="chenge"><h3>ニックネーム変更</h3></div>
    <form class="nameForm" action="" method="post" name="name-form">
        <body id="name">
            <div class="namae">
                <h4>新しいニックネームを入力してください</h4>
                <h5>（全角、半角15文字以内）</h5>
                <div class="nic">
                    <h3><label for="title">ニックネーム : </label></h3>
                    <input id="newname" type="text" name="newname" title="記号以外15文字以内" maxlength="15" pattern="([ぁ-んァ-ヶｦ-ﾟ一-龠０-９a-zA-Z0-9\-]{1,15})" required >
                </div>
                <h1><input type="submit" value="更新"></h1>
            </div>
        </body>
    </form>   
    <div id="footer"></div>
</html>