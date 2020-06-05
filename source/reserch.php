<!DOCTYPE html>
<?php 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['reserchkey'])) {
            $comment = $_POST['reserchkey'];
        }
    }
?>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>スレッド検索</title>
        <link rel="stylesheet" href="../styles/style.css">
        <link rel="stylesheet" href="../styles/tiles.css">
        <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
        <script>
            $(function () {
                $("#header").load("./header.html");
                $("#footer").load("./footer.html");
            });
        </script>
    </head>
    <div id="header"></div>
    <div style="text-align: center;">
        <div class="tile2">
            <form action="" method="post" name="reserch-form">
                <body id="reserch">
                    <input id="inputkeyword" type="text" name="reserchkey" placeholder="キーワードを入力">
                    <input id="searchbtn" type="submit" value="検索">
                </body>
            </form>   
        </div>
    </div>
    <div class="tile3">

    </div>
    <div id="footer"></div>
</html>