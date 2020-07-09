<?php
   $connect=pg_connect("dbname=postgres user=postgres password=KMtkm1412");
   
   $sql1="SELECT thread_id,thread_name FROM thread_admin ORDER BY thread_date DESC";
   
   $result1 = pg_query($connect,$sql1);
 
   while($row = pg_fetch_row($result1)){
       $text_id=$row[0];
       $text_name=$row[1];    
       echo "<form name='popular_thread' action='thread.php' method='post'>";
       echo "   $text_name";
       echo "  <input type='hidden' name='thread_id' value='{$text_id}'>";
       echo "  <input type='submit' name='send' value='移動'>";
       echo "</form>";
       echo "<br>";

   } 
   //echo <a class='button1' href='./thread.php'>トップ</a> 
?>