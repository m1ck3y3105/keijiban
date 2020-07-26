<?php
    session_start();
    $user_name='';
    $thread_id=0;
    $OK=0;
  
    // ログインしているユーザ名を取得
    if(!empty($_SESSION["user_name"])){
        $user_name=$_SESSION["user_name"];
    }
    
    //スレッドIDを取得
    if(!empty($_SESSION["thread_id"])){
        $thread_id=$_SESSION["thread_id"];
    }

    if($thread_id != 0 && $user_name != ''){
      $OK=1;
      
      $connect=pg_connect("dbname=postgres user=postgres password=KMtkm1412");

      $sql1="DELETE FROM restrict_admin WHERE thread_id= {$thread_id}";
      $result1 = pg_query($connect,$sql1);

      $sql2="DELETE FROM comment_admin WHERE thread_id= {$thread_id}";
      $result2 = pg_query($connect,$sql2);

      $sql3="DELETE FROM thread_admin WHERE thread_id= {$thread_id}";
      $result3 = pg_query($connect,$sql3);

      if($result1 && $result2 && $result3){
          $OK=2;
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
    <link rel="stylesheet" href="styles/yota.css">
    <link rel="stylesheet" href="styles/tiles.css">
</head>
<body>

    <?php if($OK == 0){ ?>
    <!-- いろいろ対策 -->
    <h2>不正な実行です</h2>
    <a class="button1" href="source/index.html">トップへ</a>

    <?php }else if($OK == 1){ ?>
    <!-- 削除失敗 -->
    <h2>スレッドが削除できませんでした</h2>
    <form action="source/thread/thred_admin.php" method="get">    
        <div class="submitbtn">
            <input name="return_thread" type="submit" value="スレッド管理画面へ戻る">
        </div>
    </form>

    <?php }else if($OK == 2){ ?>
    <h2>スレッドが正常に削除されました</h2>
    <a class="button1" href="source/index.html">トップへ</a>
    <?php } ?> 
    
</body>
</html>