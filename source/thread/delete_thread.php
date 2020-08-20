<?php
  session_start();
  $user_name='';
  $thread_id=0;

  // ログインしているユーザ名を取得
  if(!empty($_SESSION["user_name"])){
      $user_name=$_SESSION["user_name"];
  }
  
  //スレッドIDを取得
  if(!empty($_SESSION["thread_id"])){
      $thread_id=$_SESSION["thread_id"];
  }

  $connect=pg_connect("dbname=group02 user=group02 password=Re_zero_1109 host=localhost");

  //スレッドIDからスレッドタイトルを取得
  $sql1="SELECT thread_name,thread_userid FROM thread_admin WHERE thread_id=$1";
  $array1 = array("thread_id" => "{$thread_id}");
  $result1 = pg_query_params($connect,$sql1,$array1);
  $row1 = pg_fetch_row($result1);
  $thread_name = $row1[0];
  $thread_userid = $row1[1];

?>

<!doctype html>
<html lang="ja">
<head>
    <base href="/~group02/"></base>
    <meta charset="UTF-8">
    <title>掲示板サイト</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/style2.css">
    <link rel="stylesheet" href="styles/yota.css">
    <link rel="stylesheet" href="styles/tiles.css">
    <link rel="stylesheet" href="styles/block-dis.css">
    <link rel="stylesheet" href="styles/yota2.css">
</head>
<body>
    <div id="dele_come">

        <?php if($thread_id == 0 || $user_name == ''){ ?>
            <h2>エラーが発生しました</h2>
            <a class="button8" href="source/index.php">トップへ</a>


        <?php }else{ ?>
        <div class="a">
            <div class="b">
                <h3>削除対象スレッド：<?php echo "{$thread_name}"; ?> </h3>
                <h3>本当に削除しますか？</h3>
            </div>
        </div>

        <form action="source/thread/delete_thread_check.php" method="POST">    
            <div class="submit">
                <input class="submitbtn" name="yes" type="submit" value="はい">
            </div>
        </form>
        <form action="source/thread/thread_admin.php" method="POST">    
            <div class="submit">
                <input class="submitbtn" name="no" type="submit" value="いいえ">
            </div>
        </form>

        <?php } ?>
    </div>
    
</body>
</html>