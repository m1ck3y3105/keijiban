<?php
    $OK=0;
    $string="";
    $thread_id=0;

    if(!empty($_POST["thread_id"]) && !empty($_POST["comment_id"])){
      $OK=1;
      $connect=pg_connect("dbname=group02 user=group02 password=msh2570 host=localhost");

      $thread_id = $_POST["thread_id"];
      $comment_id = $_POST["comment_id"];

      $sql1="DELETE FROM comment_admin WHERE comment_id= {$comment_id}";
      $result1 = pg_query($connect,$sql1);

      if($result1){
          $OK=2;

          $string="コメントを削除できました";

          //スレッド内のコメント数の値を１減らす
          $sql2="UPDATE thread_admin SET comment_count = comment_count - 1  WHERE thread_id = {$thread_id}";
          $result2 = pg_query($connect,$sql2);
          
      }
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

    <div id="login">

    <?php if($OK == 0){ ?>
    <!-- いろいろ対策 (delete.comment.php以外からの遷移など)-->

    <h2>エラーが発生しました</h2>
    <form action="source/thread/thread.php" method="GET">    
        <div class="submit">
            <input class="submitbtn" type="submit" value="スレッド画面へ戻る">
            <input type='hidden' name='thread_id' value= <?php echo "{$thread_id}"; ?> >
        </div>
    </form>

    <?php }else if($OK == 1){ ?>
    <!-- 削除失敗 -->

    <h2>コメントを削除できませんでした</h2>
    <form action="source/thread/thread.php" method="GET">    
        <div class="submit">
            <input class="submitbtn" type="submit" value="スレッド画面へ戻る">
            <input type='hidden' name='thread_id' value= <?php echo "{$thread_id}"; ?> >
        </div>
    </form>

    <?php }else if($OK == 2){ ?>
    <!-- 削除成功 -->

    <h2>コメントを削除できました</h2>
    <form action="source/thread/thread.php" method="GET">    
        <div class="submit">
            <input class="submitbtn" type="submit" value="スレッド画面へ戻る">
            <input type='hidden' name='thread_id' value= <?php echo "{$thread_id}"; ?> >
        </div>
    </form>    

    <?php } ?> 

    </div>  

</body>
<div id="footer"></div>
</html>