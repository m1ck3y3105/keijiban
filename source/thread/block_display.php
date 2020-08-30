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

  //スレッドの投稿者の中から現在制限しているユーザを取得
  $sql2="SELECT user_admin.user_id, user_admin.user_name, restrict_admin.restrict_status 
  FROM restrict_admin,user_admin WHERE restrict_admin.thread_id={$thread_id} 
  and restrict_admin.user_id=user_admin.user_id and restrict_admin.user_id!={$thread_userid} 
  ORDER BY restrict_admin.restrict_id ASC";
  $result2 = pg_query($connect,$sql2);

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
  　<link rel="icon" type="image/png" href="images/favicon.png">
</head>

<body>
    <div id="sample">
        <?php if($thread_id == 0 || $user_name == ''){  ?>
        <!-- いろいろ対策 -->

        <h2>エラーが発生しました</h2>
        <a class="button8" href="source/index.php">トップへ</a>

        <?php }else{ ?>
        <!-- 正常に遷移すればこっちが表示される -->
        <div class="block-dis">
        投稿制限者設定画面
        </div>
        <div class="block-thread">
            <div class="block-threadid">
            管理対象スレッド：<?php echo "{$thread_name}"; ?>
            <br>
            </div>

        <!-- ブロック中の制限者を表示 -->
        <h4>スレッド投稿者制限状態一覧</h4>
        <form action="source/thread/block_check.php" method="POST">
            <div id="block-display">
                <?php 
                    $i=1; 
                    while($row2 = pg_fetch_row($result2)){ 
                    $block_id=$row2[0];
                    $block_name=$row2[1];
                    $block_status=$row2[2]; 
                ?>
                <div>
                    <?php echo "{$block_name}"; ?>
                    <!-- 現在の状態が「制限中」のユーザはラジオボタンの初期値が「制限」となる -->
                    <?php if($block_status=="t"){ ?>
                    <input type="radio" name="block_status<?php echo $i; ?>" id="block-status" value="block" checked> 制限
                    <input type="radio" name="block_status<?php echo $i; ?>" id="block-status" value="release"> 解除
                    <input type='hidden' name='block_id<?php echo $i; ?>' value= <?php echo "{$block_id}"; ?> >

                    <!-- 現在の状態が「解除中」のユーザはラジオボタンの初期値が「解除」となる -->
                    <?php }else if($block_status=="f"){ ?>
                    <input type="radio" name="block_status<?php echo $i; ?>" id="block-status" value="block"> 制限
                    <input type="radio" name="block_status<?php echo $i; ?>" id="block-status" value="release" checked> 解除
                    <input type='hidden' name='block_id<?php echo $i; ?>' value= <?php echo "{$block_id}"; ?> >

                    <?php }else{ echo "error"; } ?>
                </div> 
                <?php
                    $i++; 
                    } 
                    if($i<=1){
                        echo "<h4>このスレッドに投稿している人はいません</h4>";
                    }
                ?>

            </div>

            <?php if($i>=2){ ?>
            <div id="block">
            <input class="block-save"name="block" type="submit" value="変更内容を保存">
            </div>
            <?php } ?>
        </form>
        </div>
        <br>
        <a class="button6" href="source/thread/thread_admin.php">スレッド管理者トップ画面へ</a>

        <?php } ?>
    </div>
</body>

</html>
