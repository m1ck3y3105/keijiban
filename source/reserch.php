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
        <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
        <script>
            $(function () {
                $("#header").load("./header.html");
                $("#footer").load("./footer.html");
            });
        </script>
    </head>
    <div id="header"></div>
    <div class="researchForm">
        <form action="" method="post" name="reserch-form">
            <body id="reserch">
                <h3><label for="reserch">スレッド検索</label></h3>
                <input type="text"　placeholder="スレッド名を入力してください。" name="reserchkey">
                <input type="submit" value="検索">
            </body>
        </form>   
    </div>
    <div id="footer"></div>
</html>