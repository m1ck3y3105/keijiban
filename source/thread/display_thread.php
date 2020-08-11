<?php
   $connect=pg_connect("dbname=postgres user=postgres password=KMtkm1412");

   //$sql1="SELECT thread_id,thread_name,thread_date FROM thread_admin ORDER BY thread_date DESC";
   

   if($_GET["sort"]=="new" || $_GET["sort"]==""){
      $sql1="SELECT thread_id,thread_name,thread_date FROM thread_admin ORDER BY thread_date DESC";
   }
   else if($_GET["sort"]=="pop"){
      $sql1="SELECT thread_id,thread_name,comment_count FROM thread_admin ORDER BY comment_count DESC";

   }
   else if($_GET["sort"]=="last"){
      $sql1="SELECT thread_id,thread_name,comment_count FROM thread_admin ";
   }
   else if($_GET["sort"]=="comment"){
      $sql1="SELECT thread_id,thread_name,comment_count FROM thread_admin ORDER BY comment_count DESC";
   }

   $result1 = pg_query($connect,$sql1);
 
   $i=1;

?>

<html lang="ja">
    <head>
        <base href="/"></base>
        <meta charset="UTF-8">
        <title>掲示板サイト</title>
        <link rel="stylesheet" href="styles/style.css">
        <link rel="stylesheet" href="styles/style2.css">
        <link rel="stylesheet" href="styles/tiles.css">
        <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
        <script type="text/javascript">       
            function Change_sort() {
                const sort = document.getElementById("s_menu").value;
                history.pushState("","","source/index.php?" + "sort=" + sort);
                location.reload();
            }
        </script>
    </head>
    <body>
        <div class="tile3">
            <div class="sortmenu">
                <h2>ソート順</h2>
                <p>
                    <select id="s_menu" onChange="Change_sort()">
                        <option value="new">新着順</option>
                        <option value="pop">人気順</option>
                        <option value="last">最終更新順</option>
                        <option value="comment">コメントの多い順</option>
                    </select>
                </p>
            </div>

            <?php
            while($row = pg_fetch_row($result1)){
               $thread_id=$row[0];
               $thread_name=$row[1];
               $thread_date=$row[2];  
                 
               echo "<form name='display_thread' action='source/thread/thread.php' method='get'>
                     <label for='thread_id'>{$i}、{$thread_name} 
                     <h6>作成日：{$thread_date}</h6>
                     <input type='hidden' class='thread_id' name='thread_id' value='{$thread_id}'>
                     <input type='submit' class='thread_id' value='移動'>
                     </form>
                     <br><br>";
                $i++;
           }  
           ?>
         </div>
      <div class="arrow">
          <input type="submit" name="return" class="btn-sticky1" value="前へ">
          <input type="submit" name="next" class="btn-sticky2" value="次へ">
      </div>
</body>
</html>