<!DOCTYPE html>
<?php 
    $hit = 0;
    $key = '';
    $sort = 'old';
    $from = '';
    $to = '';

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


    $connect=pg_connect("dbname=postgres user=postgres password=KMtkm1412");

    $sql1="SELECT thread_id,thread_name,thread_date FROM thread_admin WHERE thread_name LIKE '%{$key}%'";
    $result1 = pg_query($connect,$sql1);

    $hit=pg_num_rows($result1);
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

    <div class="search_res">
        <?php echo $key."の検索結果 : ".$hit."件" ?>
    </div>
    <div class="tile3">
        <div class="sortmenu">
            <h4>ソート順：
                <select id="s_menu" onChange="Change_sort()">
                    <option value="new"     <?php if($sort=='new')    { echo "selected";} ?> >新着順</option>
                    <option value="pop"     <?php if($sort=='pop')    { echo "selected";} ?> >人気順</option>
                    <option value="last"    <?php if($sort=='last')   { echo "selected";} ?> >最終更新順</option>
                    <option value="comment" <?php if($sort=='comment'){ echo "selected";} ?> >コメントの多い順</option>
                </select>
            </h4>
        </div>

        <?php
        if($hit>0){
            $i=1;
            while($row = pg_fetch_row($result1)){
               $thread_id=$row[0];
               $thread_name=$row[1];
               $thread_status=$row[2];    
               echo "<center>
                     <form name='display_thread' action='source/thread/thread.php' method='get'>
                     <label for='thread_id' id='thread_menu'>{$i}、{$thread_name}";
               if($sort=='new'){
                   echo "<h6>作成日：{$thread_status}</h6>";
               }
               else if($sort=='pop'){
                   echo "<h6>いいねの数：{$thread_status}</h6>";
               }
               else if($sort=='last'){
                   echo "<h6>最終更新日：{$thread_status}</h6>"; 
               }
               else if($sort=='comment'){
                   echo "<h6>コメント数：{$thread_status}</h6>";
               }
               echo "<input type='hidden' id='thread_id' name='thread_id' value='{$thread_id}'>
                     <input type='submit' id='thread_id' value='移動'>
                     </form>
                     </center>
                     <br><br>";
                $i++;
           }  
        }
        else if($hit==0){
            echo "<h2>タイトルに「{$key}」が含まれるスレッドは存在していませんでした</h2>";
        }
        ?> 

    </div>

</body>
<div id="footer"></div>
</html>
