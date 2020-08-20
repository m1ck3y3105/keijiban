<?php
    session_start();
    unset($_SESSION["user_name"]);
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
    <h3>ログアウトしました</h3>
        <div class="submit">
            <a class="submitbtn" href="source/index.php">トップへ</a>
        </div>  
    </div>

</body>
<div id="footer"></div>
</html>