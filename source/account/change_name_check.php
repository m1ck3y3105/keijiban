<?php
    session_start();
    $OK = 0;

    if(!empty($_SESSION["user_name"]) ){
        $OK = 1;
        $user_name=$_SESSION["user_name"];

        if(!empty($_POST["newname"]) ){
            $OK = 2;
            error_reporting(0);
            $newname = $_POST["newname"];

            $connect=pg_connect("dbname=postgres user=postgres password=msh2570 ");

            $sql1="UPDATE user_admin SET user_name = '{$newname}' WHERE user_name='{$user_name}'";
            $result1 = pg_query($connect,$sql1);

            if($result1){
                $OK = 3;
                $_SESSION["user_name"]=$newname;
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
    <link rel="stylesheet" href="styles/style2.css">
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
    <!-- ログインされてないとき＋直接遷移されたときの表示 -->
    <h2>ログインされていません</h2>
    <a class="submitbtn" href="source/signin/login.php">ログイン画面へ</a>

    <?php }else if($OK == 1){ ?>
    <!-- 入力されていなかった場合の表示 -->
    <h2>入力されていません</h2>
    <a class="submitbtn" href="source/account/change_name.php">ニックネーム変更画面へ</a>
    

    <?php }else if($OK == 2){ ?>
    <!-- ユーザIDが既に使われていたものだった時の表示 -->
    <h2>そのユーザIDは既に使われています</h2>
    <a class="submitbtn" href="source/account/change_name.php">ニックネーム変更画面へ</a>

    <?php }else if($OK == 3){ ?>
    <!-- 正常にニックネーム変更できた時の表示 -->
    <h2>ニックネーム変更ができました</h2>
    <a class="submitbtn" href="source/index.php">トップへ</a>

    <?php } ?>

    </div>
  
    <div id="footer"></div>
</body>
</html>
