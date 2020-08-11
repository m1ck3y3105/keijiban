<?php
    session_start();
    $OK = 0;

    if(!empty($_POST["user_name"]) && !empty($_POST["password"])){
      $OK = 1;

      $user_name = $_POST["user_name"];
      $password = $_POST["password"];

      $connect=pg_connect("dbname=postgres user=postgres password=KMtkm1412");

      $sql1="SELECT login_pass FROM user_admin where user_name= $1";
      $array1 = array("user_name" => "{$user_name}");
      $result1 = pg_query_params($connect,$sql1,$array1);

      $row = pg_fetch_row($result1);
      if($row[0]==$password){
          $OK = 2;
          $_SESSION["user_name"] = $user_name;
      }
    }

?>
<!doctype html>
<html lang="ja">
<head>
    <base href="/"></base>
    <title>掲示板サイト</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/yota.css">
    <link rel="stylesheet" href="styles/login.css">
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
    <!-- 何も入力されてないとき＋直接遷移されたときの表示 -->
    <h2>入力してください</h2>
    <a class="button1" href="source/signin/login.php">ログイン画面へ</a>

    <?php }else if($OK == 1){ ?>
    <!-- パスワードが違った場合の表示 -->
    <h2>パスワードが違います</h2>
    <a class="button1" href="source/signin/login.php">ログイン画面へ</a>
    

    <?php }else if($OK == 2){ ?>
    <!-- ログインに成功した時の表示 -->
    <h3>ログインできました</h3>
    <a class="button1" href="source/index.php">トップへ</a>
    <?php } ?>
    </div>

    <div id="footer"></div>
</body>
</html>
