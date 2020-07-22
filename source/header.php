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
    <base href="/"></base>
    <meta charset="UTF-8">
</head>
<body>
    <header>
        <a href="source/index.html"><img src="images/S__1517240371.jpg" alt="次へ"></a>
        <li class="login_name"><?php echo "{$user_name}さん" ?></li>


        <div class="headermenu">
            <?php if($user_name != "匿名"){ 
                  echo '<a class="button1" href="source/account/change_name.php">ニックネーム変更</a>';
                  echo '<a class="button1" href="source/signin/logout.php">ログアウト</a>';
                  echo '<a class="button1" href="source/help.html">ヘルプ</a>';
              }
              else{
                  echo '<a class="button1" href="source/account/newaccount.php">アカウント作成</a>';
                  echo '<a class="button1" href="source/signin/login.php">ログイン</a>';
                  echo '<a class="button1" href="source/help.html">ヘルプ</a>';
              }

            ?>
        </div>
    </header>
</body>
</html>
