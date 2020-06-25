<!DOCTYPE html>
<?php 
    $hit = 0;
    $comment = "";
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
    <div class="search_res">
        <?php echo $comment."の検索結果 : ".$hit."件" ?>
    </div>
    <div class="time">
                <div class="time2">
                <label>時間指定 </label>
                    <input type="radio"  name="time" onclick="func1()"checked id="r1"><label for="r1">なし</label>
                    <input type="radio" name="time"onclick="func2()" id="r2"><label for="r2"> あり</label>
                    <input type="datetime-local"id="not"disabled="disabled">～<input type="datetime-local"id="no"disabled="disabled">
                </div>
                <script>
                function func1() {
                    document.getElementById("not").disabled = true;
                    document.getElementById("no").disabled = true;
                }/*アリとなしの選択*/
                function func2() {
                    document.getElementById("not").disabled = false;
                    document.getElementById("no").disabled = false;
                }
                </script>
            </div>
    <div class="tile3">
        <div class="sortmenu">
            <div class="tile4">
                <input type="radio" name="sort" checked="checked">50音順
            </div>
            <div class="tile4">
                <input type="radio" name="sort">人気順
            </div>
            <div class="tile4">
                <input type="radio" name="sort">新着順
            </div>
        </div>
    </div>
    <div id="footer"></div>
</html>