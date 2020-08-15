<?php
  session_start();
  $OK = 0;
  
  /*入力の項目が足りるか*/
  if(!empty($_POST["user_name"]) && !empty($_POST["password"]) && !empty($_POST["re_password"])){
      $OK = 1;
      $user_name = $_POST["user_name"];
      $password = $_POST["password"];
      $re_password = $_POST["re_password"];

    
      /*再入力されたパスワードのチェック*/
      if($password == $re_password){
          $OK = 2;
          error_reporting(0);
 
          $connect=pg_connect("dbname=postgres user=postgres password=msh2570");
          $sql1="INSERT INTO user_admin(user_name,login_pass) VALUES($1,$2)";
          $array1 = array("user_name" => "{$user_name}", "password" => "{$password}");
          $result1 = pg_query_params($connect,$sql1,$array1);
 
          /*ユーザidがかぶってないか*/
          if($result1){
              $OK = 3;
          }
      }
  }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <base href="/"></base>
    <title>掲示板サイト</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/yota.css">
    <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
    <script>
        $(function () {
            $("#header").load("source/header.php");
            $("#footer").load("source/footer.html");
        });
    </script>
</head>
<body>
    <div id="header"></div>

    <div id="loginch">

    <?php if($OK == 0){ ?>
    <!-- 入力が足りていないとき＋直接遷移されたときの表示 -->
    <h2>入力が足りていません</h2>
    <a class="button1" href="source/account/newaccount.php">アカウント作成画面へ</a>

    <?php }else if($OK == 1){ ?>
    <!-- 再入力されたパスワードが違った場合の表示 -->
    <h2>再入力されたパスワードが違います</h2>
    <a class="button1" href="source/account/newaccount.php">アカウント作成画面へ</a>
    

    <?php }else if($OK == 2){ ?>
    <!-- ユーザIDが既に使われていたものだった時の表示 -->
    <h2>そのユーザIDは既に使われています</h2>
    <a class="button1" href="source/account/newaccount.php">アカウント作成画面へ</a>

    <?php }else if($OK == 3){ ?>
    <!-- 正常にアカウント作成できた時の表示 -->
    <h2>新規アカウント登録ができました</h2>
    <a class="button1" href="source/index.php">トップへ</a>
    <?php } ?>

    </div>
  
    
</body>
<div id="footer"></div>
</html>