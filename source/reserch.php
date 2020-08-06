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

    $sql1="SELECT thread_id,thread_name FROM thread_admin WHERE thread_name LIKE '%{$key}%'";
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
    </script>
</head>
<body>
    <div id="header"></div>
    <div style="text-align: center;">
        <div class="timer">
            <div class="tile2">
                <form action="source/reserch.php" method="get" name="reserch-form">
                    <body id="reserch">
                            <input id="inputkeyword" type="text" name="key" placeholder="キーワードを入力">
                            <input id="searchbtn" type="button" value="検索" onclick="Set_search('key')">
                    </body>
                </form> 
            </div>
            <div class="time">
                <div class="time2">
                    <label>時間指定 :  </label>
                    <input type="radio"  name="time" onclick="func1()"checked id="r1"><label for="r1">なし</label>
                    <input type="radio" name="time" onclick="func2()" id="r2"><label for="r2">あり</label>
                    <input type="datetime-local" id="from" onChange="" disabled="disabled">～<input type="datetime-local" id="to" onChange="" disabled="disabled">
                </div>
                <script>
                    function func1() {
                        document.getElementById("from").disabled = true;
                        document.getElementById("to").disabled = true;
                    }
                    function func2() {
                        document.getElementById("from").disabled = false;
                        document.getElementById("to").disabled = false;
                    }
                </script>
            </div>
        </div>   
    </div>
    <div class="search_res">
        <?php echo $key."の検索結果 : ".$hit."件" ?>
    </div>
    <div class="tile3">
        <div class="sortmenu">
            <h2>ソート順</h2>
            <p>
                <select id="s_menu" onChange="Set_search('sort')">
                    <option value="old">古い順</option>
                    <option value="new">新しい順</option>
                    <option value="cmy">コメントの多い順</option>
                    <option value="cfw">コメントの少ない順</option>
                </select>
            </p>
        </div>

        <?php
        if($hit>0){
            while($row = pg_fetch_row($result1)){
                $thread_id=$row[0];
                $thread_name=$row[1];    
                echo "<form name='reserch_thread' action='source/thread/thread.php' method='get'>";
                echo "  <div>";
                echo "    <label for='thread_id'>$thread_name";
                echo "    <input type='hidden' id='thread_id' name='thread_id' value='{$thread_id}'>";
                echo "    <input type='submit' id='thread_id' value='移動'>";
                echo "  </div>";
                echo "</form>";
                echo "<br>";
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
