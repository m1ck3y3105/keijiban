<!DOCTYPE html>
<?php 
    $hit = 0;
    $key = '';
    $sort = 'new';
    $from = '';
    $to = '';

    /*
    if(isset($_GET['key'])) {
        $key = $_GET['key'];
    }
    if(isset($_GET['sort'])) {
         $sort = $_GET['sort'];
     }
    if(isset($_GET['from'])) {
        $from = $_GET['from'];
    }
    if(isset($_GET['to'])) {
        $to = $_GET['to'];
    }
    */

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

    #検索のキーワードと時間範囲を取得
    if(!empty($_GET["key"]) || !empty($_GET["from"]) || !empty($_GET["to"])){
        if(!empty($_GET["key"])){
            $search = 1;
        }
        else{
            $search = 2;
        }
        $key = $_GET["key"];
        $menu = $_GET["menu"];
        $from="";
        $to="";
        $from_db="";
        $to_db="";
  
        if(!empty($_GET["from"])){
            $from = $_GET["from"];
            $from_front=substr($from,0,10);
            $from_back=substr($from,11,5);
            $from_db = "{$from_front}" . " " . "{$from_back}" . ":00";
        }
  
        if(!empty($_GET["to"])){
            $to = $_GET["to"];
            $to_front=substr($to,0,10);
            $to_back=substr($to,11,5);
            $to_db = "{$to_front}" . " " . "{$to_back}" . ":00";
        }
    }

    #ソートのパラメータを取得(指定されてない場合は新着順)
    if(!empty($_GET["sort"])){
        $sort=$_GET["sort"];
    }

    $connect=pg_connect("dbname=postgres user=postgres password=KMtkm1412");

    $sql1="SELECT thread_admin.thread_id, thread_admin.thread_name, user_admin.user_name, 
    thread_admin.thread_date, thread_admin.good_count, thread_admin.comment_count  
    FROM thread_admin,user_admin where thread_admin.thread_userid=user_admin.user_id ";
 
    //search=0　→　何も表示されない
    //search=1　→　スレッド検索結果の表示
    //search=2　→　時間範囲指定の表示

    #キーワード、時間、設定をもとにDBからスレッドを検索
    if($search==1){
       
        if($menu=="main"){
            $main_key="%"."{$key}"."%";
            $sql1 .= "and thread_admin.thread_name LIKE $1 ";
            $array1 = array("main_key" => "{$main_key}");
        }
        else if($menu=="user"){
            $sql1 .= "and user_admin.user_name=$1 ";
            $array1 = array("key" => "{$key}");
        }

        if($from!=""){
            $sql1  .= "and thread_admin.thread_date >= $2 ::timestamp ";
            $array1 += array('from'=>"{$from_db}");
            if($to!=""){
                $sql1   .= "and thread_admin.thread_date <= $3 ::timestamp ";
                $array1 += array('to'=>"{$to_db}");
            }
        }
        else if($to!=""){
            $sql1   .= "and thread_admin.thread_date <= $2 ::timestamp ";
            $array1 += array('to'=>"{$to_db}");
        }

        if($sort!=""){
            if($sort=="new"){
                $sql1 .= "ORDER BY thread_date DESC ";
            }
            else if($sort=="pop"){
                $sql1 .= "SORDER BY good_count DESC ";
        
            }
            else if($sort=="comment"){
                $sql1 .= " ORDER BY comment_count DESC ";
            }
        }
        echo $sql1;

        $result1 = pg_query_params($connect,$sql1,$array1);

        $hit = pg_num_rows($result1);

        if($hit<$last){
            $last=$hit;
        }

        $sql1 .= "OFFSET {$offset} LIMIT {$limit}";

        $result1 = pg_query_params($connect,$sql1,$array1);

    }

    # キーワードが入力されず、時間範囲指定のみ
    else if($search==2){

        if($from!=""){
            $sql1  .= "and comment_admin.comment_date >= $1 ::timestamp ";
            $array1 = array('from'=>"{$from_db}");
            if($to!=""){
                $sql1   .= "and comment_admin.comment_date <= $2 ::timestamp ";
                $array1 += array('to'=>"{$to_db}");
            }
        }
        else if($to!=""){
            $sql1   .= "and comment_admin.comment_date <= $1 ::timestamp ";
            $array1 = array('to'=>"{$to_db}");
        }

        if($sort!=""){
            if($sort=="new"){
                $sql1 .= "ORDER BY thread_date DESC ";
            }
            else if($sort=="pop"){
                $sql1 .= "SORDER BY good_count DESC ";
        
            }
            else if($sort=="comment"){
                $sql1 .= " ORDER BY comment_count DESC ";
            }
        }

        $sql1 .= "OFFSET {$offset} LIMIT {$limit}";

        $result1 = pg_query_params($connect,$sql1,$array1);

        $hit = pg_num_rows($result1);

        if($hit<$last){
            $last=$hit;
        }

    }



?>
<html lang="ja">
<head>
    <base href="/"></base>
    <meta charset="UTF-8">
    <title>スレッド検索</title>
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
    <script>
        window.onload = function() {
            var key = JSON.parse('<?php echo json_encode($key) ?>'); 
            var sort = JSON.parse('<?php echo json_encode($sort) ?>');
            var t_from = JSON.parse('<?php echo json_encode($from) ?>');
            var t_to = JSON.parse('<?php echo json_encode($to) ?>');
            document.getElementById('s_menu').value = (sort !== '') ? (sort) : ('old');
            document.getElementById('inputkeyword').value = key;
            document.getElementById('from').value = (t_from !== '') ? (t_from) : ('');
            document.getElementById('to').value = (t_to !== '') ? (t_to) : ('');
        }

        function Set_search(mode) {
            var inkey = document.getElementById('inputkeyword').value;
            var from = document.getElementById('from').value;
            var to = document.getElementById('to').value

            if(inkey !== ""){
                var prmarr = new Object;
                var prm = location.search.substring(1).split('&');
                var key = '', sort = '';

                for(var i = 0; prm[i]; i++){
                    var keyvalue = prm[i].split('=');
                    prmarr[keyvalue[0]] = keyvalue[1];
                }

                if(mode === 'key'){
                    key = '?key=' + inkey;
                    sort = '&sort=' + ((prmarr['sort'] === undefined)?('old'):(prmarr['sort']));
                }
                else if(mode === 'sort'){
                    key = '?key=' + prmarr['key'];
                    sort = '&sort=' + document.getElementById('s_menu').value;
                }

                history.pushState("","",location.pathname + key + sort + '&from=' + from + '&to=' + to);
                location.reload();
            }
            else{
                alert('検索するキーワードを入力してください')
            }
        }

        function Change_sort() {
            const sort = document.getElementById("s_menu").value;
            history.pushState("","","source/index.php?" + "sort=" + sort);
            location.reload();
        }

        function func1() {
            document.getElementById("not").disabled = true;
            document.getElementById("no").disabled = true;
        }/*アリとなしの選択*/
        function func2() {
            document.getElementById("not").disabled = false;
            document.getElementById("no").disabled = false;
        }
    </script>
</head>
<body>
    <div id="header"></div>
    
    <!-- 今のところ、スレッド検索のフォーム（以下のソースコード）はindex.phpと同じにしている -->
    <div class="cen">スレッド検索</div>
    <!-- 送信したい情報４つ（key,menu,from,to） -->
    <!-- できれば、エンターキー押した時と検索ボタン押した時の処理同じにしてほしい -->
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
                            <label for="r2"><input type="radio"  name="menu"  id="r2" value="user"        >作成者ID</label>
                        </h3>
                        <h3>時間指定 :
                            <input type="datetime-local" name="from" id="not">～<input type="datetime-local" name="to" id="no">
                        </h3>
                    </div>
                </div>
            </form>    
        </div>
    </div>

    <div class="search_res">
        <?php echo $key."の検索結果 : ".$hit."件" ?>
    </div>
    <div class="tile3">
        <div class='search_result'>
            <div class="sortmenu">
                <h4>ソート順：
                    <select id="s_menu" onChange="Change_sort()">
                        <option value="new"     <?php if($sort=='new')    { echo "selected";} ?> >新着順</option>
                        <option value="pop"     <?php if($sort=='pop')    { echo "selected";} ?> >人気順</option>
                        <option value="comment" <?php if($sort=='comment'){ echo "selected";} ?> >コメントの多い順</option>
                    </select>
                </h4>
            </div>
        
            <?php if($hit>0){ ?>
            <div class='search_count'>
                <?php echo "{$first}件目～{$last}件目"; ?>
            </div>
            <?php } ?>

        </div>
       

        <?php  
        #検索結果があった場合
        if($hit>0){ 
            while($row = pg_fetch_row($result1)){
                $thread_id=$row[0];
                $thread_name=$row[1];
                $thread_user=$row[2];
                $thread_date=$row[3]; 
                $good_count=$row[4];    
                $comment_count=$row[5]; 
                        
                echo "<div class = 'display_thread'>
                        <form name='display_thread' action='source/thread/thread.php' method='get'>
                            <div class='thread_title'>{$thread_name}</div>
                            <div class='thread_user'>作成者：{$thread_user}</div>
                            <div class='thread_info'>作成日：{$thread_date}　いいね:{$good_count}　コメント数：{$comment_count}</div>
                            <div class='move_thread'><input  type='submit' id='thread_submit' value='移動'></div>
                            <input type='hidden' id='thread_id' name='thread_id' value='{$thread_id}'>
                        </form>
                    </div>";
 
            }

            echo "<div class = 'front-next'>";
        
                if($first > 4){
                    $first_front=$first-4;
                    echo "<form action='' method='GET'>
                            <input type='submit' class='btn-sticky1' value='前へ'>
                            <input type='hidden' name='key' value={$key}>
                            <input type='hidden' name='menu' value={$menu}>
                            <input type='hidden' name='from' value={$from}>
                            <input type='hidden' name='to' value={$to}>
                            <input type='hidden' name='sort' value={$sort}>
                            <input type='hidden' name='first' value={$first_front}>
                        </form>";

                }  

                if($hit > $last){
                    $first_next=$first+4;
                    echo "<form action='' method='GET'>
                            <input type='submit' class='btn-sticky2' value='次へ'> 
                            <input type='hidden' name='key' value={$key}>
                            <input type='hidden' name='menu' value={$menu}>
                            <input type='hidden' name='from' value={$from}>
                            <input type='hidden' name='to' value={$to}> 
                            <input type='hidden' name='sort' value={$sort}>
                            <input type='hidden' name='first' value={$first_next}>
                        </form>" ;
                } 

            echo "</div>";
        }


        #検索結果がなかった場合
        else if($hit==0){
            if($menu=='main'){
                echo "<h2>タイトルに「{$key}」が含まれるスレッドは存在していませんでした</h2>";
            }
            else if($menu='user'){
                echo "「{$key}」が作成したスレッドは存在していませんでした</h2>";
            }        
        }  
        ?> 

    </div>

</body>
<div id="footer"></div>
</html>
