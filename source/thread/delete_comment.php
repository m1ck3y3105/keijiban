<?php
     $thread_id  = 0;
     $comment_id = 0;

     if(!empty($_POST["thread_id"])){
        $thread_id=$_POST["thread_id"];
     }

     if(!empty($_POST["comment_id"])){
        $comment_id=$_POST["comment_id"];
     }
?>

<!doctype html>
<html lang="ja">
<head>
    <base href="/"></base>
    <meta charset="UTF-8">
    <title>掲示板サイト</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/style2.css">
    <link rel="stylesheet" href="styles/tiles.css">
    <link rel="stylesheet" href="styles/yota.css">
    <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
    <script>
        $(window).on('load', function() {
            $("#header").load("source/header.php");
            $("#footer").load("source/footer.html");
        });
    </script>
</head>
<div id="header"></div>
<body>
    <div id="dele_come">
    <div id="login">

        <?php if($thread_id==0 || $comment_id==0){ ?>

        <h3>不正な実行です。</h3>
        <a href='source/index.php'>トップへ</a>

        <?php }else{ ?> 

        <h3>本当に削除しますか？</h3>
        <form action="source/thread/delete_comment_check.php" method="POST">    
            <div class="submit">
                <input class="submitbtn" name="yes" type="submit" value="はい">
                <input type='hidden' name='thread_id' value= <?php echo "{$thread_id}"; ?> >
                <input type='hidden' name='comment_id' value= <?php echo "{$comment_id}"; ?> >
            </div>
        </form>
        <form action="source/thread/thread.php" method="GET">    
            <div class="submit">
                <input class="submitbtn" type="submit" value="いいえ">
                <input type='hidden' name='thread_id' value= <?php echo "{$thread_id}"; ?> >
            </div>
        </form>

        <?php } ?>

    </div>
    </div>
    
</body>
<div id="footer"></div>
</html>