<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>掲示板サイト</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/style2.css">
    <link rel="stylesheet" href="../styles/tiles.css">
    <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
    <script>
        $(window).on('load', function() {
            $("#header").load("./header.php");
            $("#footer").load("./footer.html");
        });
    </script>
</head>
<body>
    <div id="header"></div>

    <?php
      if(!empty($_POST["thread_id"])){

        $thread_id=$_POST["thread_id"];

        $connect=pg_connect("dbname=postgres user=postgres password=KMtkm1412");

        $sql1="SELECT thread_admin.thread_name, thread_admin.thread_text, thread_admin.thread_date, user_admin.user_name 
        FROM thread_admin,user_admin where thread_id={$thread_id} and thread_userid=user_id";
        $result1 = pg_query($connect,$sql1);
    
        $row1 = pg_fetch_row($result1);
        $thread_name = $row1[0];
        $thread_text = $row1[1];
        $thread_date = $row1[2];
        $thread_username = $row1[3];

        $sql2="SELECT user_admin.user_name,comment_admin.comment_text,comment_admin.comment_date 
        FROM comment_admin,user_admin WHERE thread_id={$thread_id} and comment_userid=user_id ORDER BY comment_date ASC";
        $result2 = pg_query($connect,$sql2);

    }
    else{
        die("スレッドが存在しません");
    }
    ?>

    <div class="thred">
        <?php echo "<h2>{$thread_name}</h2>" ?>
        <h3 class="aab"><?php echo "{$thread_text}"; ?>
        <dt>作成者：<?php echo "{$thread_username}"; ?></dt>
        <dt>作成日：<?php echo "{$thread_date}"; ?></dt>
        </h3>

        <?php
        $i=0;
        while($row2 = pg_fetch_row($result2)){
            if($i%2==0){
                echo  "<div class='abc'>
                       <dt>投稿者：{$row2[0]}</dt>
                       <h4>{$row2[1]}</h4>
                       <dt>投稿日:{$row2[2]}</dt>
                       </div>";
            }
            else if($i%2==1){
                echo  "<div class='abcd'>
                       <dt>投稿者：{$row2[0]}</dt>
                       <h4>{$row2[1]}</h4>
                       <dt>投稿日:{$row2[2]}</dt>
                       </div>";
            }
           $i++;
        }
        ?>

    </div>
    <div id="footer"></div>
</body>
</html>
