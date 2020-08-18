 <?php
    session_start();
    $OK = 0;

    if(!empty($_SESSION["user_name"])){
        $OK = 1;
        $user_name=$_SESSION["user_name"];

        if(!empty($_POST["title"]) && !empty($_POST["password"]) && !empty($_POST["comment"])){
            $OK = 2;
            $title=$_POST["title"];
            $password=$_POST["password"];
            $comment=$_POST["comment"];
    
            $datetime=date("Y-m-d H:i:s");
    
            $connect=pg_connect("dbname=postgres user=postgres password=msh2570");
    
            $sql1="SELECT user_id FROM user_admin where user_name='{$user_name}'";
            $result1 = pg_query($connect,$sql1);
        
            $row1 = pg_fetch_row($result1);
            $user_id = $row1[0];
            
            $sql2="INSERT INTO thread_admin
            (thread_name,thread_text,thread_date,
            thread_userid,thread_pass,comment_count,good_count)
            values($1,$2,'{$datetime}',{$user_id},$3,0,0)";
            $array2 = array("title" => "{$title}","comment" => "{$comment}","password" => "{$password}");
            $result2 = pg_query_params($connect,$sql2,$array2);
    
            if($result2){
                $OK = 3;
            }
        }
    }


    
?>
<!doctype html>
<html lang="ja">
<head>
    <base href="/"></base>
    <meta charset="UTF-8">
    <title>掲示板サイト</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/style2.css">
    <link rel="stylesheet" href="styles/yota.css">
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

    <div id="loginch">
    <?php if($OK == 0){ ?>
    <!-- 直接遷移されたときの表示 -->
    <h2>不正な遷移です</h2>
    <a class="submitbtn" href="source/index.php">トップへ</a>

    <?php }else if($OK == 1){ ?>
    <!-- 入力が足りない場合の表示 -->
    <h2>入力が足りていません</h2>
    <a class="submitbtn" href="source/thread/create_thread.php">スレッド作成画面へ</a>
    

    <?php }else if($OK == 2){ ?>
    <!-- スレッド作成に失敗した時の表示 -->
    <h2>スレッド新規登録に失敗しました</h2>
    <a class="submitbtn" href="source/thread/create_thread.php">スレッド作成画面へ</a>

    <?php }else if($OK == 3){ ?>
    <!-- スレッド作成に成功した時の表示 -->
    <h2>スレッド新規登録できました</h2>
    <a class="submitbtn" href="source/index.php">トップへ</a>

    <?php } ?>
    </div>

   
    
</body>
<div id="footer"></div>
</html>
