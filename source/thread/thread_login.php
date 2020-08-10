<?php
  session_start();
  $thread_id=0;
  $OK = 0;

  $connect=pg_connect("dbname=postgres user=postgres password=KMtkm1412");

  // ログインしているユーザ名を取得
  if(!empty($_GET["thread_id"])){
      $OK = 1;
      $thread_id=$_GET["thread_id"];

      if(!empty($_SESSION["user_name"])){
          $OK = 2;

          $user_name=$_SESSION["user_name"];

          //ユーザネームからユーザIDを取得
          $sql1="SELECT user_id FROM user_admin WHERE user_name=$1";
          $array1 = array("user_name" => "{$user_name}");
          $result1 = pg_query_params($connect,$sql1,$array1);
          $row1 = pg_fetch_row($result1);
          $user_id = $row1[0];
  
          //スレッドIDからスレッド作成者を取得
          $sql2="SELECT thread_userid FROM thread_admin WHERE thread_id={$thread_id}";
          $result2 = pg_query($connect,$sql2);
          $row2 = pg_fetch_row($result2);
          $thread_userid = $row2[0];

          if($user_id == $thread_userid){
              $OK = 3;
          }
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

    <div id="login">

    <?php if($OK == 0){ ?>
    <!-- 直接遷移されたときの表示 -->
    <h2>不正な実行です</h2>
    <a class="button1" href="source/index.html">トップへ</a>

    <?php }else if($OK == 1){ ?>
    <!-- ログインしていないユーザに対する表示 -->
    <h2>ログインしてください</h2>
    <form action="source/thread/thread.php" method="GET">    
        <div class="submitbtn">
            <input type="submit" value="スレッド画面へ戻る">
            <input type='hidden' name='thread_id' value= <?php echo "{$thread_id}"; ?> >
        </div>
    </form>
    

    <?php }else if($OK == 2){ ?>
    <!-- スレッド作成者以外が管理ボタンを押した時 -->
    <h2>スレッド管理はスレッド作成者のみができる機能です</h2>
    <form action="source/thread/thread.php" method="GET">    
        <div class="submitbtn">
            <input type="submit" value="スレッド画面へ戻る">
            <input type='hidden' name='thread_id' value= <?php echo "{$thread_id}"; ?> >
        </div>
    </form>

    <?php }else if($OK == 3){ ?>
    <!-- 管理者ログインページを表示 -->
    <h5>スレッド管理画面に遷移するためにはスレッド新規作成時に設定した管理用パスワードを入力してください</h5>
    <form action="source/thread/thread_login_check.php" method="POST">
        <div class="loginbox">
            <h3>管理用パスワード：<input name="password" type="password"></h3>
        </div>    
        <div class="submitbtn">
            <input type='hidden' name='thread_id' value= <?php echo "{$thread_id}"; ?> >
            <input name="login" type="submit" value="ログイン">
        </div>
    </form>

    <?php } ?>

    </div>

</body>
<div id="footer"></div>
</html>


