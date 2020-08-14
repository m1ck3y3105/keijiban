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

  $connect=pg_connect("dbname=postgres user=postgres password=msh2570");

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
    <link rel="stylesheet" href="styles/block-dis.css">
</head>

<body>
    <div id="sample">

        <?php if($thread_id == 0 || $user_name == ''){  ?>
        <!-- いろいろ対策 -->

        <h1>エラーが発生しました</h1>
        <a class="button7" href="source/index.php">トップへ</a>

        <?php }else{ ?>
        <!-- 正常に遷移すればこっちが表示される -->

        <div class=thread_ad>
            スレッド管理者トップ画面
        </div>

        <div class="block-thread">
            <div class="block-threadid">
            管理対象スレッド：<?php echo "{$thread_name}"; ?>
            <br>
            </div>
        </div>

        <br>

        <center>
            <h2>スレッド管理メニュー</h2>
        </center>

        <!-- <img src="images/keijiban_otera.jpg" alt="掲示板"> -->

        <div class=admin_menu>
            <div class=menu_item><a class="button7" href="source/thread/block_display.php">投稿制限者設定</a></div>
            <div class=menu_item><a class="button7" href="source/thread/delete_thread.php">スレッド削除</a></div>
            <div class=menu_item><a class="button7" href="source/thread/thread_logout.php">ログアウト</a></div>
        </div>

        <?php } ?>
    </div>
</body>

</html>
    