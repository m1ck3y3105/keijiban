<?php
  session_start();
  $thread_id=0;
  $OK=0;

  // ログインしているユーザ名を取得
  if(!empty($_SESSION["user_name"])){
      $user_name=$_SESSION["user_name"];
  }
  else{
      $OK = -1;
  }

  if(!empty($_POST["thread_id"])){
      $OK = 1;
      $thread_id=$_POST["thread_id"];

      if(!empty($_POST["password"])){
          $OK = 2 ;
          $password=$_POST["password"];

          $connect=pg_connect("dbname=postgres user=postgres password=KMtkm1412");

          //スレッドIDからスレッドパスワードを取得
          $sql1="SELECT thread_pass FROM thread_admin WHERE thread_id=$1";
          $array1 = array("thread_id" => "{$thread_id}");
          $result1 = pg_query_params($connect,$sql1,$array1);
          $row1 = pg_fetch_row($result1);
          $thread_password = $row1[0];

          if($password==$thread_password){
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

    <?php if($OK == -1 || $OK == 0){ ?>

    <!-- -1 : ログインしていないユーザに対する表示 -->
    <!--  0 : 直接遷移されたときの表示 -->
    <h2>不正な実行です</h2>
    <form action="index.html" method="POST">
        <div class="submitbtn">
            <input name="login" type="submit" value="トップへ">
        </div>
    </form>

    <?php }else if($OK == 1){ ?>

    <!-- パスワードを入力してないとき -->
    <h2>入力してください</h2>
    <form action="source/thread/thread_login.php" method="POST">    
        <div class="submitbtn">
            <input name="no" type="submit" value="スレッド管理ログインページへ">
            <input type='hidden' name='thread_id' value= <?php echo "{$thread_id}"; ?> >
        </div>
    </form>

    <?php }else if($OK == 2){ ?>

    <!-- 入力されたパスワードが違うとき -->
    <h2>入力されたパスワードが違います</h2>
    <form action="source/thread/thread_login.php" method="POST">    
        <div class="submitbtn">
            <input name="no" type="submit" value="スレッド管理ログインページへ">
            <input type='hidden' name='thread_id' value= <?php echo "{$thread_id}"; ?> >
        </div>
    </form>

    <?php }else if($OK == 3){ ?>

    <!-- 入力したパスワードが正しい時 -->
    <h5>スレッド管理者ログインができました</h5>
    <form action="source/thread/thread_admin.php" method="POST">    
        <div class="submitbtn">
            <input type='hidden' name='thread_id' value= <?php echo "{$thread_id}"; ?> >
            <input name="login" type="submit" value="スレッド管理ページへ">
        </div>
    </form>

    <?php } ?>

    </div>

    <div id="footer"></div>
</body>
</html>
