<?php

   $sort="new";

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

   if(!empty($_GET["sort"])){
       $sort=$_GET["sort"];

        if($_GET["sort"]=="new"){
            $sort_num=1;
            #スレッド名、スレッド作成日時
            $sql2="SELECT thread_id,thread_name,thread_date FROM thread_admin ORDER BY thread_date DESC ";
        }
        else if($_GET["sort"]=="pop"){
            $sort_num=2;
            #スレッド名、いいねの数（未実装）
            $sql2="SELECT thread_id,thread_name,good_count FROM thread_admin ORDER BY good_count DESC ";
    
        }
        else if($_GET["sort"]=="last"){
            $sort_num=3;
            #スレッド名、最新コメント投稿日時（未実装）
            $sql2="SELECT thread_id,thread_name,last_date FROM thread_admin ORDER BY last_date DESC ";
        }
        else if($_GET["sort"]=="comment"){
            $sort_num=4;
            #スレッド名、コメント数
            $sql2="SELECT thread_id,thread_name,comment_count FROM thread_admin ORDER BY comment_count DESC ";
        }
   }
   else{
       $sort_num=1;
       $sql2="SELECT thread_id,thread_name,thread_date FROM thread_admin ORDER BY thread_date DESC ";
   }

   $sql2 .= "OFFSET {$offset} LIMIT {$limit}";

   $result2 = pg_query($connect,$sql2);
 
   $i=$first;

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
                                <label for="r1"><input type="radio"  name="menu"  id="r1" value="main" checked>コメント本文</label>
                                <label for="r2"><input type="radio"  name="menu"  id="r2" value="user"        >投稿者ID</label>
                            </h3>
                            <h3>時間指定 :
                            <input type="datetime-local" name="from" id="not">～<input type="datetime-local" name="to" id="no">
                            </h3>
                        </div>
                    </div>
                </form>    
            </div>
        </div>

        <div class="cen">スレッド一覧</div>
        <div class="tile3">
            <div class="sortmenu">
                <?php echo"<h4>{$first}件目から{$last}件目　　　　　　　　 ソート順："; ?>
                    <select id="s_menu" onChange="Change_sort()">
                        <option value="new"     <?php if($sort_num==1){ echo "selected";} ?> >新着順</option>
                        <option value="pop"     <?php if($sort_num==2){ echo "selected";} ?> >人気順</option>
                        <option value="last"    <?php if($sort_num==3){ echo "selected";} ?> >最終更新順</option>
                        <option value="comment" <?php if($sort_num==4){ echo "selected";} ?> >コメントの多い順</option>
                    </select>
                </h4>
            </div>

            <?php
            while($row = pg_fetch_row($result2)){
               $thread_id=$row[0];
               $thread_name=$row[1];
               $thread_status=$row[2];    
               echo "<center>
                     <form name='display_thread' action='source/thread/thread.php' method='get'>
                     <label for='thread_id' id='thread_menu'>{$i}、{$thread_name}";
               if($sort_num==1){
                   echo "<div class=thread_status>作成日：{$thread_status}<div>";
               }
               else if($sort_num==2){
                   echo "<h6>いいねの数：{$thread_status}</h6>";
               }
               else if($sort_num==3){
                   echo "<h6>最終更新日：{$thread_status}</h6>"; 
               }
               else if($sort_num==4){
                   echo "<h6>コメント数：{$thread_status}</h6>";
               }
               echo "<input type='hidden' id='thread_id' name='thread_id' value='{$thread_id}'>
                     <input type='submit' id='thread_id' value='移動'>
                     </form>
                     </center>
                     <br><br>";
                $i++;
           }  
           ?>

           <div class="arrow">

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
