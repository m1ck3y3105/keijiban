<?php
    session_start();
    $user_name=$_SESSION["user_name"];
    /*if($user_name != '匿名')*/
        if(!empty($_POST["newname"]) ){
            error_reporting(0);
            $newname = $_POST["newname"];
          
           $connect=pg_connect("dbname=group02 user=group02 password=Re_zero_1109 host=localhost");

            $sql1="UPDATE user_admin SET user_name = '{$newname}' WHERE user_name='{$user_name}'";
            $result1 = pg_query($connect,$sql1);

            if(!$result1){
                echo "そのユーザidはすでにつかわれています。";
            }
            else{
                echo "ニックネーム変更できました。";
                $_SESSION["user_name"]=$newname;
            }
        }
    echo '<a class="button1" href="../index.html">トップへ</a>';
?>