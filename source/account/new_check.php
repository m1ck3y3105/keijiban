<!doctype html>
<html lang="ja">
<head>
    <base></base>
    <meta charset="UTF-8">
    <title>掲示板サイト</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/style2.css">
    <link rel="stylesheet" href="styles/tiles.css">
    <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
    <script>
        $(window).on('load', function() {
            $("#header").load("source/header.php");
            $("#footer").load("source/footer.html");
        });
    </script>
</head>
<body>
    <div id="header"></div>

    <?php
    session_start();
    if(!empty($_SESSION["user_name"])){
        $user_name=$_SESSION["user_name"];
    }
    else{
        die("ログインしてください");
    }

    if(!empty($_POST["title"]) && !empty($_POST["password"]) && !empty($_POST["comment"])){
        $title=$_POST["title"];
        $password=$_POST["password"];
        $comment=$_POST["comment"];

        $datetime=date("Y-m-d H:i:s");

        $connect=pg_connect("dbname=postgres user=postgres password=KMtkm1412");

        $sql1="SELECT user_id FROM user_admin where user_name='{$user_name}'";
        $result1 = pg_query($connect,$sql1);
    
        $row1 = pg_fetch_row($result1);
        $user_id = $row1[0];
        
        $sql2="INSERT INTO thread_admin
        (thread_name,thread_text,thread_date,
        thread_userid,thread_pass,comment_count,restrict_count)
        values('{$title}','{$comment}','{$datetime}',{$user_id},'{$password}',0,0)";
        $result2 = pg_query($connect,$sql2);

        if(!$result2){
            echo "スレッド新規登録に失敗しました。";
        }
        else{
            echo "スレッド新規登録できました。";
        }
    }
    else{
        echo "入力してください。";
    }
    echo  "<a href='index.html'>トップへ</a>";

    ?>
    <div id="footer"></div>
</body>
</html>
