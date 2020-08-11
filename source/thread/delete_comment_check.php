<?php
    
    $string="";
    if(!empty($_POST["thread_id"]) && !empty($_POST["comment_id"])){
      $connect=pg_connect("dbname=postgres user=postgres password=KMtkm1412");

      $thread_id = $_POST["thread_id"];
      $comment_id = $_POST["comment_id"];

    $sql1="DELETE FROM comment_admin WHERE comment_id= {$comment_id}";
      $result1 = pg_query($connect,$sql1);

      if(!$result1){
          $string="コメントを削除できませんでした";
      }
      else{
        $string="コメントを削除できました";

        //スレッド内のコメント数の値を１減らす
        $sql2="UPDATE thread_admin SET comment_count = comment_count - 1  WHERE thread_id = {$thread_id}";
        $result2 = pg_query($connect,$sql2);
      }
      

    }
    else{
        //delete.comment.php以外からの遷移対策
      $string="エラーが発生しました";
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
    <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
    <script>
        $(window).on('load', function() {
            $("#header").load("source/header.php");
            $("#footer").load("source/footer.html");
        });
    </script>
</head>
<body>
    <div id="header"></div>
    <h3> <?php echo $string; ?> </h3>
    <form action="source/thread/thread.php" method="get">    
            <div class="submitbtn">
                <input type="submit" value="スレッド画面へ戻る">
                <input type='hidden' name='thread_id' value= <?php echo "{$thread_id}"; ?> >
            </div>
        </form>
</body>
<div id="footer"></div>
</html>