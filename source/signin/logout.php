<!DOCTYPE html>
<html lang="ja">
<head>
    <base href="/"></base>
    <meta charset="UTF-8">
</head>
<body>
    <h1>ログアウトしました</h1>
    <a class="button1" href="source/index.html">トップへ</a>
    <?php
        session_start();
        unset($_SESSION["user_name"]);
    ?>
</body>