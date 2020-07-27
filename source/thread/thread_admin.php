<?php
  session_start();
  $user_name='';
  $thread_id=0;

  // ログインしているユーザ名を取得
  if(!empty($_SESSION["user_name"])){
      $user_name=$_SESSION["user_name"];
  }

  //スレッドIDを取得
  if(!empty($_POST["thread_id"])){
      $thread_id=$_POST["thread_id"];
      $_SESSION["thread_id"]=$thread_id;
  }
  else if(!empty($_SESSION["thread_id"])){
      $thread_id=$_SESSION["thread_id"];
  }

  $connect=pg_connect("dbname=postgres user=postgres password=KMtkm1412");

  //スレッドIDからスレッドタイトルを取得
  $sql1="SELECT thread_name FROM thread_admin WHERE thread_id=$1";
  $array1 = array("thread_id" => "{$thread_id}");
  $result1 = pg_query_params($connect,$sql1,$array1);
  $row1 = pg_fetch_row($result1);
  $thread_name = $row1[0];

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
    <link rel="stylesheet" href="styles/tiles.css">
</head>

<body>

    <?php if($thread_id == 0 || $user_name == ''){  ?>
    <!-- いろいろ対策 -->

    <h1>エラーが発生しました</h1>
    <a class="button1" href="source/index.html">トップへ</a>

    <?php }else{ ?>
    <!-- 正常に遷移すればこっちが表示される -->

    <h1>スレッド管理者トップ画面</h1>
    <h3>管理対象スレッド：<?php echo "{$thread_name}"; ?> </h3>
    <a class="button1" href="source/thread/block_display.php">投稿制限者設定画面へ</a>
    <a class="button1" href="source/thread/delete_thread.php">スレッドを削除</a>
    <!-- <a class="button1" href="">管理用パスワードを変更</a> -->
    <a class="button1" href="source/thread/thread_logout.php">ログアウト</a>

    <?php } ?>

</body>

</html>
    