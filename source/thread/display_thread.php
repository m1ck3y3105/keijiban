<!DOCTYPE html>
<html lang="ja">
<div class="thread_id"> 
<?php
   $connect=pg_connect("dbname=postgres user=postgres password=msh2570");
   
/*
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
      $sort="SELECT thread_id,thread_name,comment_count FROM thread_admin ORDER BY comment_count DESC";
   }
*/
   $sql1="SELECT thread_id,thread_name,thread_date FROM thread_admin ORDER BY thread_date DESC";
   $result1 = pg_query($connect,$sql1);
 
   $i=1;
   while($row = pg_fetch_row($result1)){
       $thread_id=$row[0];
       $thread_name=$row[1];
       $thread_date=$row[2];
   
       echo "<form name='display_thread' action='source/thread/thread.php' method='get'>
             <label for='thread_id'>{$i}、{$thread_name} 
             <h6>作成日：{$thread_date}</h6>
             <input class='thread_id' type='hidden' id='thread_id' name='thread_id' value='{$thread_id}'>
             <input class='thread_id' type='submit' id='thread_id' value='移動'>
             </form>
             <br><br>";
        $i++;

   }  
   //echo <a class='button1' href='./thread.php'>トップ</a> 
?>
</div>
</html>