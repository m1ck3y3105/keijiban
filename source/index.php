<?php

   $sort="new";

   #スレッド一覧に４つのスレッドを表示するためのパラメータの準備
   if(!empty($_GET["first"]) && $_GET["first"]>0){
       $first =$_GET["first"];
       $last  =$first + 3;
       $offset=$first - 1;
   }
   else{
       $first = 1;
       $last  = 4;
       $offset= 0;
   }
   $limit = 4;

   $connect=pg_connect("dbname=postgres user=postgres password=KMtkm1412");

   #スレッドがいくつあるか数える
   $sql1="SELECT COUNT(thread_id) FROM thread_admin";
   $result1 = pg_query($connect,$sql1);
   $row1 = pg_fetch_row($result1);
   $count = $row1[0];

   if($count<$last){
       $last=$count;
   }

   $sql2="SELECT thread_admin.thread_id, thread_admin.thread_name, user_admin.user_name, 
    thread_admin.thread_date, thread_admin.good_count, thread_admin.comment_count  
    FROM thread_admin,user_admin where thread_admin.thread_userid=user_admin.user_id ";

   #ソートのパラメータを取得(指定されてない場合は新着順)
   if(!empty($_GET["sort"])){
       $sort=$_GET["sort"];

        if($_GET["sort"]=="new"){
            $sql2 .= "ORDER BY thread_date DESC ";
        }
        else if($_GET["sort"]=="pop"){
            $sql2 .= "ORDER BY good_count DESC ";
        }
        else if($_GET["sort"]=="comment"){
            $sql2 .= "ORDER BY comment_count DESC ";
        }
   }
   else{
       $sql2 .= "ORDER BY thread_date DESC ";
   }

   $sql2 .= "OFFSET {$offset} LIMIT {$limit}";

   $result2 = pg_query($connect,$sql2);

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
            $(function () {
                $("#header").load("source/header.php");
                $("#footer").load("source/footer.html");
            });
        </script>
        <script type="text/javascript">
            function Set_searchkey() {
                const key = document.getElementById("inputkeyword").value;
                if(key !== ""){
                    window.location = "source/reserch.php?key=" + key
                }
            }
        
            function Change_sort() {
                const sort = document.getElementById("s_menu").value;
                history.pushState("","","source/index.php?" + "sort=" + sort);
                location.reload();
            }
        </script>
    </head>
    <body>
        <div id="header"></div>

        <!-- 今のところ、スレッド検索のフォーム（以下のソースコード）はresearch.phpと同じにしている -->
        <div class="cen">スレッド検索</div>
        <!-- 送信したい情報４つ（key,menu,from,to） -->
        <!-- onclickを使わずに、普通にtype="submit"にしたら割と問題なくいったので、そのままにしてる-->
        <!-- まだ時間範囲指定とか検索内容指定とかは、検索結果に反映させてません -->
        <div style="text-align: center;">
            <div class="timer">
                <form action="source/reserch.php" method="get" name="reserch-form">
                    <div class="tile2">
                        <div id="reserch">
                            <input id="inputkeyword" type="text" name="key" placeholder="キーワードを入力">
                            <input id="searchbtn" type="submit" value="検索">
                        </div>
                    </div>
                    <div class="time">
                        <div class="time2">
                            <h3>検索設定 : 
                                <label for="r1"><input type="radio"  name="menu"  id="r1" value="main" checked>スレッドタイトル</label>
                                <label for="r2"><input type="radio"  name="menu"  id="r2" value="user"        >投稿者ID</label>
                            </h3>
                            <h3>時間指定 :
                            <input type="datetime-local" name="from" id="not">～<input type="datetime-local" name="to" id="no">
                            </h3>
                            <input type="hidden" name="sort" value="new">
                        </div>
                    </div>
                </form>    
            </div>
        </div>

        <div class="cen">スレッド一覧</div>
        <div class="tile3">
            <div class='search_result'>
                <div class="sortmenu">
                    <h4>ソート順<br>
                        <select id="s_menu" onChange="Change_sort()">
                            <option value="new"     <?php if($sort=='new'){ echo "selected";} ?> >新着順</option>
                            <option value="pop"     <?php if($sort=='pop'){ echo "selected";} ?> >人気順</option>
                            <option value="comment" <?php if($sort=='comment'){ echo "selected";} ?> >コメントの多い順</option>
                        </select>
                    </h4>
                </div>

                <div class='search_count'>
                    <?php echo "{$first}件目～{$last}件目"; ?>
                </div>
            </div>

                <?php
                while($row = pg_fetch_row($result2)){
                    $thread_id=$row[0];
                    $thread_name=$row[1];
                    $thread_user=$row[2];
                    $thread_date=substr($row[3],0,16);  
                    $good_count=$row[4];    
                    $comment_count=$row[5]; 


                            
                    echo "<div class = 'display_thread'>
                            <form name='display_thread' action='source/thread/thread.php' method='get'>
                                <div class='thread_title'>{$thread_name}</div>
                                <div class='thread_user'>作成者:{$thread_user}</div>
                                <div class='thread_info'>作成日:{$thread_date}　いいね:{$good_count}　コメント数:{$comment_count}</div>
                                <div class='move_thread'><input class=submitbtn_mv type='submit' id='thread_submit' value='移動 >'></div>
                                <input type='hidden' id='thread_id' name='thread_id' value='{$thread_id}'>
                            </form>
                        </div>";
    
                }
            ?>

            <div class="front-next">

                <?php if($first > 4){?>

                <form action="" method="GET">
                    <input type="submit" class="btn-sticky1" value="前へ">
                    <input type="hidden" name="sort" value=<?php echo $sort; ?>>
                    <input type="hidden" name="first" value=<?php echo $first - 4; ?>>
                </form>

                <?php }  if($count > $last){?>

                <form action="" method="GET">
                    <input type="submit" class="btn-sticky2" value="次へ">  
                    <input type="hidden" name="sort" value=<?php echo $sort; ?>>
                    <input type="hidden" name="first" value=<?php echo $first + 4; ?>>
                </form>  
                
                <?php } ?>
            </div>  
        </div>

        <div class=make><a class="button5" href="source/thread/create_thread.php">スレッド新規作成はコチラ</a></div>
    </body>
    <div id="footer"></div>
</html>
