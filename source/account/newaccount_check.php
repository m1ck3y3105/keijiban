<?php
  session_start();
  
  /*入力の項目が足りるか*/
  if(!empty($_POST["user_name"]) && !empty($_POST["password"]) && !empty($_POST["re_password"])){
    $user_name = $_POST["user_name"];
    $password = $_POST["password"];
    $re_password = $_POST["re_password"];

    
    /*再入力されたパスワードのチェック*/
    if($password == $re_password){
        error_reporting(0);

        $connect=pg_connect("dbname=postgres user=postgres password=KMtkm1412");
        $sql1="INSERT INTO user_admin(user_name,login_pass) VALUES('{$user_name}','{$password}')";
        $result1 = pg_query($connect,$sql1);

        /*ユーザidがかぶってないか*/
        if(!$result1){
            echo "そのユーザidはすでにつかわれています。";
        }
        else{
            echo "新規登録できました。";
        }


    }
    else{
        echo "再入力されたパスワードが違います。";
    }


  }
  else{
      echo "入力の項目が足りていません。";
  }
  echo '<a class="button1" href="./index.html">トップへ</a>';

?>