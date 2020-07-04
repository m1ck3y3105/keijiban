<?php
    $user_name = "匿名";
    session_start();
    if(!empty($_SESSION["user_name"])){
         $user_name=$_SESSION["user_name"];
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <header>
        <h2 id="top">掲示板</h2>
        <li><?php echo "{$user_name}さん" ?></li>


        <div class="headermenu">
            <?php if($user_name != "匿名"){ 
                  echo '<a class="button1" href="./index.html">トップ</a>';
                  echo '<a class="button1" href="./name.php">ニックネーム変更</a>';
                  echo '<a class="button1" href="./help.html">ヘルプ</a>';
                  echo '<a class="button1" href="./logout.php">ログアウト</a>';
              }
              else{
                  echo'<a class="button1" href="./index.html">トップ</a>';
                  echo'<a class="button1" href="./help.html">ヘルプ</a>';
                  echo '<a class="button1" href="./login.php">ログイン</a>';
                  echo '<a class="button1" href="./newaccount.php">アカウント作成</a>';
              }

            ?>
        </div>
    </header>
</body>
</html>