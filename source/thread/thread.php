<?php
  session_start();

  $thread_id=0;
  //現在見ているスレッドのIDを保存
  if(!empty($_GET["thread_id"])){
    $thread_id=$_GET["thread_id"];
  }

  $user_name='匿名';
  // ログインしているユーザ名を取得 + スレッド作成者による制限情報をチェック
  if(!empty($_SESSION["user_name"])){
      $user_name=$_SESSION["user_name"];
  }

  $connect=pg_connect("dbname=postgres user=postgres password=KMtkm1412");

  // コメントが入力された時の処理
  if(!empty($_POST["your-message"])){
      $your_message=$_POST["your-message"];

      //ユーザネームからユーザIDを取得
      $sql1="SELECT user_id FROM user_admin WHERE user_name=$1";
      $array1 = array("user_name" => "{$user_name}");
      $result1 = pg_query_params($connect,$sql1,$array1);
      $row1 = pg_fetch_row($result1);
      $user_id = $row1[0];

      $datetime=date("Y-m-d H:i:s");
      
      //コメントをテーブルに挿入
      $sql2="INSERT INTO comment_admin
            (thread_id,comment_text,comment_date,comment_userid)
            values({$thread_id},$1,'{$datetime}',{$user_id})";
      $array2 = array("your_message" => "{$your_message}");
      $result2 = pg_query_params($connect,$sql2,$array2);


      //投稿者制限テーブルにユーザ情報を保存
      //(初期値は解除、もうデータが入っている場合は挿入しない)
      $sql3="INSERT INTO restrict_admin
            (thread_id,user_id,restrict_status)
            SELECT {$thread_id},{$user_id},false
            WHERE NOT EXISTS
            (SELECT restrict_id FROM restrict_admin
            WHERE thread_id={$thread_id} and user_id={$user_id})";
      $result3 = pg_query($connect,$sql3);
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

      if($thread_id!=0){

        //GETしたスレッドIDからスレッド名、内容、作成日時、作成者を取得
        $sql1="SELECT thread_admin.thread_name, thread_admin.thread_text, thread_admin.thread_date, user_admin.user_name 
        FROM thread_admin,user_admin where thread_id={$thread_id} and thread_userid=user_id";
        $result1 = pg_query($connect,$sql1);
    
        $row1 = pg_fetch_row($result1);
        $thread_name = $row1[0];
        $thread_text = $row1[1];
        $thread_date = $row1[2];
        $thread_username = $row1[3];

        //GETしたスレッドIDのコメント情報（作成者、内容、作成日時）を取得し、時間の順番で並び替えて保存
        $sql2="SELECT user_admin.user_name, comment_admin.comment_text, comment_admin.comment_date ,comment_admin.comment_id
        FROM comment_admin,user_admin WHERE thread_id={$thread_id} and comment_userid=user_id ORDER BY comment_date ASC";
        $result2 = pg_query($connect,$sql2);

        //スレッド内のコメントの数を数える
        $sql3="SELECT COUNT(comment_id) FROM comment_admin WHERE thread_id={$thread_id}";
        $result3 = pg_query($connect,$sql3);
        $row3 = pg_fetch_row($result3);
        $comment_count = $row3[0];
        
        //数えたコメントの数をスレッドテーブルに挿入する
        $sql4="UPDATE thread_admin SET comment_count = {$comment_count} WHERE thread_id = {$thread_id}";
        $result4 = pg_query($connect,$sql4);
      }
      else{
        die("スレッドが存在しません");
      }
    ?>

    <div class="thread">
        <?php echo "<h2>{$thread_name}</h2>" ?>
        <form action="source/thread/thread_login.php" method="POST">    
            <div class="submitbtn">
                <input name="no" type="submit" value="スレッド管理">
                <input type='hidden' name='thread_id' value= <?php echo "{$thread_id}"; ?> >
            </div>
        </form>
        <h3 class="aab"><?php echo "{$thread_text}"; ?>
        <dt>作成者：<?php echo "{$thread_username}"; ?></dt>
        <dt>作成日：<?php echo "{$thread_date}"; ?></dt>
        </h3>

        <?php
        $i=1;
        while($row2 = pg_fetch_row($result2)){
            if($i%2==1){
                echo  "<div class='abc'>
                       <dt>#{$i}  　投稿者：{$row2[0]}</dt>
                       <h4>{$row2[1]}</h4>
                       <dt>投稿日:{$row2[2]}</dt>";
            }
            else if($i%2==0){
                echo  "<div class='abcd'>
                       <dt>#{$i}　  投稿者：{$row2[0]}</dt>
                       <h4>{$row2[1]}</h4>
                       <dt>投稿日:{$row2[2]}</dt>";
            }
            //自分のコメントにだけ「コメント削除」ボタンが表示される
            if($user_name==$row2[0]){
                echo "<form action='source/thread/delete_comment.php' method='POST'>   
                        <div class='submitbtn'>
                          <input name='delete_comment' type='submit' value='コメント削除'>
                          <input type='hidden' name='comment_id' value='{$row2[3]}'>
                          <input type='hidden' name='thread_id' value='$thread_id'>
                        </div>
                      </form>";
            }
            echo  "</div>";
           $i++;
        }
        
        echo "</div> ";
      
      ?> 
 
    
    <?php if($user_name!='匿名'){ ?>
    <form action="" method="POST">
      <textarea id="message" name="your-message" placeholder="コメント入力" value=""></textarea>
      <input type="submit" name="message-button" class="button" value="投稿">
    </form>

    <?php }else{ echo "<br>スレッドにコメントをする場合はログインしてください"; } ?>
    
    <div id="footer"></div>
</body>
</html>
