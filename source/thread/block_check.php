<?php  
  session_start();
  $user_name='';
  $thread_id=0;
  $OK=0;

  // ログインしているユーザ名を取得
  if(!empty($_SESSION["user_name"])){
      $user_name=$_SESSION["user_name"];
  }
  
  //スレッドIDを取得
  if(!empty($_SESSION["thread_id"])){
      $thread_id=$_SESSION["thread_id"];
  }

  $connect=pg_connect("dbname=postgres user=postgres password=KMtkm1412");

  //スレッドIDからスレッドタイトルを取得
  $sql1="SELECT thread_name,thread_userid FROM thread_admin WHERE thread_id=$1";
  $array1 = array("thread_id" => "{$thread_id}");
  $result1 = pg_query_params($connect,$sql1,$array1);
  $row1 = pg_fetch_row($result1);
  $thread_name = $row1[0];
  $thread_userid = $row1[1];

  if($thread_id != 0 && $user_name != '' && !empty($_POST["block"])){
      $OK=1;

      $error=0;
      for($i=1; $i; $i++){
          $status='block_status'.$i;
          $id='block_id'.$i;

          if(!empty($_POST[$status]) && !empty($_POST[$id])){
              $OK=2;
              $block_id=$_POST[$id];
              if($_POST[$status]=="block"){
                  $block_status="true";
              }else if($_POST[$status]=="release"){
                  $block_status="false";
              }else{
                  $error=1;
                  break;
              }

              $sql2 = "UPDATE restrict_admin SET restrict_status={$block_status} 
              WHERE thread_id={$thread_id} and user_id={$block_id}";
              $result2 = pg_query($connect,$sql2);
          }
          else{
              break; 
          }
      } 

  }

  //スレッドの投稿者の中から現在制限しているユーザを取得
  $sql3="SELECT user_admin.user_name  FROM restrict_admin,user_admin
  WHERE restrict_admin.thread_id={$thread_id} and restrict_admin.restrict_status=true 
  and restrict_admin.user_id=user_admin.user_id and restrict_admin.user_id!={$thread_userid} 
  ORDER BY restrict_admin.restrict_id ASC";
  $result3 = pg_query($connect,$sql3);

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
    <?php if($OK==0){  ?>
    <!-- いろいろ対策 -->

    <h1>エラーが発生しました</h1>
    <a class="button6" href="source/index.html">トップへ</a>

    <?php }else if($OK==1 || $error==1){?>
    <!-- DBにブロックに関する情報を正常に保存できなかったとき -->

    <h1>制限に関する情報を保存できませんでした</h1>
    <a class="button6" href="source/thread/block_display.php">投稿制限者設定画面へ</a>
    <a class="button6" href="source/thread/thread_admin.php">スレッド管理者トップ画面へ</a>


    <?php }else if($OK==2){ ?>
    <!-- 正常に遷移すればこっちが表示される -->
    <div class="block-dis">
        投稿制限者設定が正常に行われました
    </div>

    <div class="block-thread">
        <div class="block-threadid">

            管理対象スレッド：<?php echo "{$thread_name}"; ?>
            <br>
        </div>


        <!-- ブロック中の制限者を表示 -->
        <h4>制限中投稿者一覧</h4>
        <div id="block-display">
            <?php 
                $count=0;
                while($row3 = pg_fetch_row($result3))
                { 
                    $block_name=$row3[0]; 
                    $count++;

                    echo "<div>
                            {$block_name}
                        </div> ";
                }
                if($count==0){
                    echo "<h3>現在ブロックしている投稿者はいません</h3>";
                } 
            ?>
        </div>
    </div>
    <br>
    <a class="button6" href="source/thread/block_display.php">投稿制限者設定画面へ</a>
    <a class="button6" href="source/thread/thread_admin.php">スレッド管理者トップ画面へ</a>

    <?php } ?>
    </div>
</body>

</html>