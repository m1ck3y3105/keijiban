<!doctype html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>掲示板サイト</title>
        <link rel="stylesheet" href="../styles/style.css">
        <link rel="stylesheet" href="../styles/tiles.css">
        <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
        <script>
            $(window).on('load', function() {
                $("#header").load("./header.php");
                $("#footer").load("./footer.html");
            });
        </script>
    </head>
    <body>
        <div id="header"></div>
        <?php
          if(!empty($_POST["thread_id"])){
            $thread_id=$_POST["thread_id"];

            $connect=pg_connect("dbname=postgres user=postgres password=KMtkm1412");

            $sql1="SELECT thread_name FROM thread_admin where thread_id={$thread_id}";
            $result1 = pg_query($connect,$sql1);
    
            $row = pg_fetch_row($result1);
            echo "<h1>スレッド名：{$row[0]}</h1>";

            $sql2="SELECT comment_text,comment_date FROM comment_admin where thread_id={$thread_id} ORDER BY comment_date ASC";
            $result2 = pg_query($connect,$sql2);
        
            while($row = pg_fetch_row($result2)){
               echo "<li>{$row[0]}　投稿日：{$row[1]}</li>";
               echo "<br>";
            }
        }
        ?>
        <div class="thred">
            <h2>スレッド</h2>
            <h3 class="aab">[郎報]　吉川匠、斎藤工に間違えられる！？
            <dt>作成者：guuu</dt>
            </h3>
            <div class="abc">
                <dt>投稿者：yu</dt>
                <h4>え～～～～！</h4>
                <dt>投稿日</dt>
            </div>
            <div class="abcd">
                <dt>投稿者：su</dt>
                <h4>わあ～～～～～！</h4>                <dt>投稿日</dt>
            </div>
            <div class="abc">
                <dt>投稿者：ku</dt>
                <h4>嘘～～～</h4>
                <dt>投稿日</dt>
            </div>
            <div class="abcd">
                <dt>投稿者：fu</dt>
                <h4>おおおおお！</h4>
                <dt>投稿日</dt>
            </div>

        </div>
        <div id="footer"></div>
    </body>
</html>