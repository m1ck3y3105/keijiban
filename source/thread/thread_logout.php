<!DOCTYPE html>
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
    <h1>スレッド管理画面からログアウトしました</h1>
    <a class="button1" href="source/index.html">トップへ</a>
</body>
</html>

<?php
    session_start();
    unset($_SESSION["thread_id"]);
?>