<?php
  session_start();

  $thread_id=0;
  $user_id=0;
  $restrict_user=0;
  $search = 0;
  $user_name='';
  $good_status = '';
  $good_count = 0;

  $connect=pg_connect("dbname=postgres user=postgres password=msh2570 ");

  //現在見ているスレッドのIDを保取得
  if(!empty($_GET["thread_id"])){
    $thread_id=$_GET["thread_id"];
  }

  // ログインしているユーザ名を取得 + スレッド作成者による制限情報をチェック
  if(!empty($_SESSION["user_name"]) && $thread_id!=0 ){
      $user_name=$_SESSION["user_name"];

      //ユーザ名からユーザIDを取得
      $sql1="SELECT user_id FROM user_admin WHERE user_name=$1";
      $array1 = array("user_name" => "{$user_name}");
      $result1 = pg_query_params($connect,$sql1,$array1);
      $row1 = pg_fetch_row($result1);
      $user_id = $row1[0];

      //ユーザIDから制限情報を取得
      $sql2="SELECT restrict_status FROM restrict_admin WHERE user_id={$user_id} and thread_id={$thread_id}";
      $result2 = pg_query($connect,$sql2);
      $row2 = pg_fetch_row($result2);
      $restrict_status = $row2[0];
      
      if($restrict_status=='t'){
          $restrict_user=1;
      }
  }

  // いいねボタンといいね解除ボタンがクリックされた時の処理
  if(!empty($_POST["good"]) || !empty($_POST["good_release"])){
      if(!empty($_POST["good"])){
          $sql0="INSERT INTO good_admin
                 (thread_id,user_id,good_status)
                 SELECT {$thread_id},{$user_id},true
                 WHERE NOT EXISTS
                 (SELECT good_id FROM good_admin
                 WHERE thread_id={$thread_id} and user_id={$user_id})";
          $result0 = pg_query($connect,$sql0);

          $sql0="UPDATE good_admin SET good_status='true'
                 WHERE thread_id={$thread_id} and user_id={$user_id}";
          $result0 = pg_query($connect,$sql0);
      }
      else if(!empty($_POST["good_release"])){
          $sql0="UPDATE good_admin SET good_status='false'
                 WHERE thread_id={$thread_id} and user_id={$user_id}";
          $result0 = pg_query($connect,$sql0);
      }
  }

  //いいねの情報をチェック
  if($thread_id != 0 && $user_id != 0){
      $sql0="SELECT good_status FROM good_admin WHERE user_id={$user_id} and thread_id={$thread_id}";
      $result0 = pg_query($connect,$sql0);
      $row0 = pg_fetch_row($result0);
      $good_status = $row0[0];
  }


  // コメントが入力された時の処理
  if(!empty($_POST["your-message"])){
      $your_message=$_POST["your-message"];
      $datetime=date("Y-m-d H:i:s");
      
      //コメントをテーブルに挿入
      $sql1="INSERT INTO comment_admin
            (thread_id,comment_text,comment_date,comment_userid)
            values({$thread_id},$1,'{$datetime}',{$user_id})";
      $array1 = array("your_message" => "{$your_message}");
      $result1 = pg_query_params($connect,$sql1,$array1);


      //投稿者制限テーブルにユーザ情報を保存
      //(初期値は解除、もうデータが入っている場合は挿入しない)
      $sql2="INSERT INTO restrict_admin
            (thread_id,user_id,restrict_status)
            SELECT {$thread_id},{$user_id},false
            WHERE NOT EXISTS
            (SELECT restrict_id FROM restrict_admin
            WHERE thread_id={$thread_id} and user_id={$user_id})";
      $result2 = pg_query($connect,$sql2);

  }

  # スレッド内検索ボタンが押されたときの処理
  if(!empty($_GET["key"]) || !empty($_GET["from"]) || !empty($_GET["to"])){
      if(!empty($_GET["key"])){
          $search = 1;
      }
      else{
          $search = 2;
      }
      $key = $_GET["key"];
      $menu = $_GET["menu"];
      $from="";
      $to="";
      $from_db="";
      $to_db="";

      if(!empty($_GET["from"])){
          $from = $_GET["from"];
          $from_front=substr($from,0,10);
          $from_back=substr($from,11,5);
          $from_db = "{$from_front}" . " " . "{$from_back}" . ":00";
      }

      if(!empty($_GET["to"])){
          $to = $_GET["to"];
          $to_front=substr($to,0,10);
          $to_back=substr($to,11,5);
          $to_db = "{$to_front}" . " " . "{$to_back}" . ":00";
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

        //search=0　→　通常のコメント表示
        //search=1　→　スレッド内検索結果の表示
        //search=2　→　時間範囲指定の表示

        #GETしたスレッドIDのコメント情報（作成者、内容、作成日時）を取得し、時間の順番で並び替えて保存
        if($search==0){
            
            $sql2="SELECT user_admin.user_name, comment_admin.comment_text, comment_admin.comment_date ,comment_admin.comment_id
            FROM comment_admin,user_admin WHERE thread_id={$thread_id} and comment_userid=user_id ORDER BY comment_date ASC";
            $result2 = pg_query($connect,$sql2);
        }

        #キーワード、時間、設定をもとにDBからコメントを検索
        else if($search==1){
           
            if($menu=="main"){
                $main_key="%"."{$key}"."%";
                $sql2="SELECT user_admin.user_name, comment_admin.comment_text, comment_admin.comment_date ,comment_admin.comment_id
                       FROM comment_admin,user_admin WHERE thread_id={$thread_id} and comment_userid=user_id 
                       and comment_admin.comment_text LIKE $1 ";
                $array2 = array("main_key" => "{$main_key}");
            }
            else if($menu=="user"){
                $sql2="SELECT user_admin.user_name, comment_admin.comment_text, comment_admin.comment_date ,comment_admin.comment_id
                       FROM comment_admin,user_admin WHERE thread_id={$thread_id} and comment_userid=user_id 
                       and user_admin.user_name=$1 ";
                $array2 = array("key" => "{$key}");
            }

            if($from!=""){
                $sql2   .= "and comment_admin.comment_date >= $2 ::timestamp ";
                $array2 += array('from'=>"{$from_db}");
                if($to!=""){
                    $sql2   .= "and comment_admin.comment_date <= $3 ::timestamp ";
                    $array2 += array('to'=>"{$to_db}");
                }
            }
            else if($to!=""){
                $sql2   .= "and comment_admin.comment_date <= $2 ::timestamp ";
                $array2 += array('to'=>"{$to_db}");
            }

            $sql2 .= "ORDER BY comment_date ASC";
            $result2 = pg_query_params($connect,$sql2,$array2);
        }

        # キーワードが入力されず、時間範囲指定のみ
        else if($search==2){
            $sql2="SELECT user_admin.user_name, comment_admin.comment_text, comment_admin.comment_date ,comment_admin.comment_id
            FROM comment_admin,user_admin WHERE thread_id={$thread_id} and comment_userid=user_id ";

            if($from!=""){
                $sql2   .= "and comment_admin.comment_date >= $1 ::timestamp ";
                $array2 = array('from'=>"{$from_db}");
                if($to!=""){
                    $sql2   .= "and comment_admin.comment_date <= $2 ::timestamp ";
                    $array2 += array('to'=>"{$to_db}");
                }
            }
            else if($to!=""){
                $sql2   .= "and comment_admin.comment_date <= $1 ::timestamp ";
                $array2 = array('to'=>"{$to_db}");
            }

            $sql2 .= "ORDER BY comment_date ASC";
            $result2 = pg_query_params($connect,$sql2,$array2);

        }

        //スレッド内のコメントの数を数える
        $sql3="SELECT COUNT(comment_id) FROM comment_admin WHERE thread_id={$thread_id}";
        $result3 = pg_query($connect,$sql3);
        $row3 = pg_fetch_row($result3);
        $comment_count = $row3[0];
        
        //数えたコメントの数をスレッドテーブルに挿入する
        $sql4="UPDATE thread_admin SET comment_count = {$comment_count} WHERE thread_id = {$thread_id}";
        $result4 = pg_query($connect,$sql4);

        $sql5="SELECT COUNT(good_id) FROM good_admin WHERE thread_id={$thread_id} and good_status='true'";
        $result5 = pg_query($connect,$sql5);
        $row5 = pg_fetch_row($result5);
        $good_count = $row5[0];

        $sql6="UPDATE thread_admin SET good_count = {$good_count} WHERE thread_id = {$thread_id}";
        $result6 = pg_query($connect,$sql6);
      }
      else{
        die("スレッドが存在しません");
      }
    ?>


      <form action="" method="get" name="reserch-form">
          <div id="reserch">
              <h2>スレッド内検索 :
                  <input type='hidden' id='thread_id' name='thread_id' value=<?php echo "{$thread_id}"; ?> >  
                  <input id="inputkeyword" type="text" name="key" placeholder="キーワードを入力">
                  <input id="searchbtn" type="submit" value="検索">
              </h2>
              <h3>検索設定 : 
                  <label for="r1"><input type="radio"  name="menu"  id="r1" value="main" checked>コメント本文</label>
                  <label for="r2"><input type="radio"  name="menu"  id="r2" value="user"        >投稿者ID</label>
              </h3>
              <div class="time">
                  <div class="time2">
                      <h3>時間指定 : 
                      <input type="datetime-local" name="from" id="not">～<input type="datetime-local" name="to" id="no">
                      </h3>
                  </div>
              </div>
          </div>
       </form> 


    <div class="thread">
        <?php echo "<h2>{$thread_name}</h2>" ?>
        <form action="source/thread/thread_login.php" method="GET">    
            <div class="submit_thr">
                <input class="submitbtn_own" type="submit" value="スレッド管理">
                <input type='hidden' name='thread_id' value= <?php echo "{$thread_id}"; ?> >
            </div>
        </form>
        <!-- いいねボタン -
        <form action="">
            <div class="favbtn">
              <input id="fav" type="radio" name="star" />
              <label for="fav">★</label>
            </div>
        </form>  
        -->
        <h3 class="aab"><?php echo "{$thread_text}"; ?>
        <dt>作成者：<?php echo "{$thread_username}"; ?></dt>
        <dt>作成日：<?php echo "{$thread_date}"; ?></dt>
        </h3>

        <?php
        if($search==1){
            if($menu=='main'){
                $research_settings="コメント本文";
            }
            else if($menu=='user'){
                $research_settings="投稿者ID";
            }
            else{
                $research_settings="不明";
            }
            echo "<div class='thread_research'>
                  <h3>「{$key}」の検索結果</h3>
                  <h5>検索設定：{$research_settings}";
            
            if($from!="" || $to!=""){
                echo "</h5><h5>時間範囲：{$from_db}～{$to_db}";
            }

            echo "</h5></div>";
        }
        else if($search==2){
            echo "<div class='thread_research'><h5>
                  時間範囲：{$from_db}～{$to_db}</h5></div>";
        }


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
            //スレッド作成者は全てのコメントを削除することができる
            if($user_name==$row2[0] || $user_name==$thread_username){
                echo "<form action='source/thread/delete_comment.php' method='POST'>   
                        <div class='submit_com'>
                          <input class=submitbtn_com name='delete_comment' type='submit' value='コメント削除'>
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
 
    
    <?php if($restrict_user==1){  ?>
    <h5><br>スレッド作成者によって投稿を制限されています</h5>

    <?php }else if($user_name!='' ){ ?>
    <form action="" method="POST">
      <textarea id="message" name="your-message" maxlength='140' placeholder="コメント入力" value=""　></textarea>
      <input type="submit" name="message-button" class="button" value="投稿">
    </form>

    <?php }else{ ?>
    <h5><br>スレッドにコメントをする場合はログインしてください</h5>

    <?php } ?>


    <?php if($user_name!='' ){ ?>
     
        <form method="post" action="" >

            <?php if($good_status == 't'){ ?>
            <button type="submit" class="good_button" name="good_release" value="good_release" title="いいね！の解除">
                <img src="images/good.png" alt="" width="16" height="16">
                <span>いいね！を解除する</span>
            </button>

            <?php }else{ ?>
            <button type="submit" class="good_button" name="good" value="good" title="いいね！">
                <img class="_1pbs inlineBlock img" src="images/good.png" alt="" width="16" height="16">
                <span class="_49vh _2pi7">いいね！</span>
            </button>

            <?php } ?>

            <span class="hukidasi" >
                <?php 
                if($good_count < 10){
                    echo "<img src='images/hukidasi4.jpg' alt='' width='32' height='32'>
                          <span class='good_number_1'>{$good_count}</span>";
                }
                else if($good_count > 9 && $good_count < 100){
                    echo "<img src='images/hukidasi4.jpg' alt='' width='48' height='32'>
                          <span class='good_number_2'>{$good_count}</span>";
                }
                else if($good_count > 99){
                    echo "<img src='images/hukidasi4.jpg' alt='' width='64' height='32'>
                          <span class='good_number_3'>{$good_count}</span>";
                }
                ?>
            </span>
        </form>

     <?php } ?>

</body>
<div id="footer"></div>
</html>