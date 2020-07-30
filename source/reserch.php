<!DOCTYPE html>
<?php 
    $hit = 0;
    $key = '';
    $sort = 'old';

    if(isset($_GET['key'])) {
        $key = $_GET['key'];
    }

    if(isset($_GET['sort'])) {
        $sort = $_GET['sort'];
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
            document.getElementById('s_menu').value = (sort !== '') ? (sort) : ('old');
            document.getElementById('inputkeyword').value = key;
        }

        function Set_searchkey() {
            const key = document.getElementById("inputkeyword").value;
            const sort = JSON.parse('<?php echo json_encode($sort) ?>');
            if(key !== ""){
                history.pushState("","","source/reserch.php?key=" + key + "&sort=" + sort);
                location.reload();
            }
        }

        function Change_sort() {
            const key = JSON.parse('<?php echo json_encode($key) ?>');
            const sort = document.getElementById("s_menu").value;
            if(key !== ""){
                history.pushState("","","source/reserch.php?key=" + key + "&sort=" + sort);
                location.reload();
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
                            <input id="searchbtn" type="button" value="検索" onclick="Set_searchkey()">
                    </body>
                </form> 
            </div>
            <div class="time">
                <div class="time2">
                    <label>時間指定 :  </label>
                    <input type="radio"  name="time" onclick="func1()"checked id="r1"><label for="r1">なし</label>
                    <input type="radio" name="time"onclick="func2()" id="r2"><label for="r2">あり</label>
                    <input type="datetime-local"id="not"disabled="disabled">～<input type="datetime-local"id="no"disabled="disabled">
                </div>
                <script>
                    function func1() {
                        document.getElementById("not").disabled = true;
                        document.getElementById("no").disabled = true;
                    }
                    function func2() {
                        document.getElementById("not").disabled = false;
                        document.getElementById("no").disabled = false;
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
                <select id="s_menu" onChange="Change_sort()">
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
    <div id="footer"></div>
</body>
</html>
