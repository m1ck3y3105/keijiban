<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h1>ログアウトしました</h1>
    <a class="button1" href="./index.html">トップへ</a>
<?php
    session_start();
    unset($_SESSION["user_name"]);
?>