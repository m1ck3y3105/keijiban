<?php
    $thread_id=$_POST["thread_id"];
    $comment_id=$_POST["comment_id"];
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>掲示板サイト</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/style2.css">
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
    <div id="dele_come">

        <?php if(empty($_POST["comment_id"])){
            echo "<h3>不正な実行です。</h3>";
            die("<a href='index.html'>トップへ</a>");
        } ?>
        <h3>本当に削除しますか？</h3>
        <form action="delete_comment_check.php" method="POST">    
            <div class="submitbtn">
                <input name="yes" type="submit" value="はい">
                <input type='hidden' name='thread_id' value= <?php echo "{$thread_id}"; ?> >
                <input type='hidden' name='comment_id' value= <?php echo "{$comment_id}"; ?> >
            </div>
        </form>
        <form action="thread.php" method="POST">    
            <div class="submitbtn">
                <input name="no" type="submit" value="いいえ">
                <input type='hidden' name='thread_id' value= <?php echo "{$thread_id}"; ?> >
            </div>
        </form>
    </div>
    
    <div id="footer"></div>
</body>
</html>