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
    <link rel="stylesheet" href="styles/block-dis.css">
</head>
</head>

<body>

    <div id="sample">
            <h2>スレッド管理画面からログアウトしました</h2>
            <br>
            <a class="button8" href="source/index.php">トップへ</a>
    </div>
</body>
</html>

<?php
    session_start();
    unset($_SESSION["thread_id"]);
?>