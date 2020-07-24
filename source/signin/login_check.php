<?php
  session_start();
  
  if(!empty($_POST["user_name"]) && !empty($_POST["password"])){
    $connect=pg_connect("dbname=postgres user=postgres password=KMtkm1412");

    $user_name = $_POST["user_name"];
    $password = $_POST["password"];

    $sql1="SELECT login_pass FROM user_admin where user_name='{$user_name}'";
    $result1 = pg_query($connect,$sql1);

    $row = pg_fetch_row($result1);
    if($row[0]==$password){
        $_SESSION["user_name"] = $user_name;
        echo "ログインできました。";
    }
    else{
        echo "ユーザID、パスワードが間違っています";
    }
  }
  else{
      echo "入力されていません。";
  }
  echo '<a class="button1" href="./index.html">トップへ</a>';
?>