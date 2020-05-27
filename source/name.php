<!DOCTYPE html>
<?php 
    $banarr = array(
        "\"","'",".","`","~","$","%","&","*","(",")","{","}","\\","/"
    );
    $errmes = '';
    $banfl = false;
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['newname'])) {
            $comment = str_split($_POST['newname']);
            foreach($comment as $value) {
                foreach($banarr as $banchar) {
                    if(strcmp($value, $banchar) == 0) {
                        $errmes = 'E06 ニックネームには使用できない文字が含まれています。';
                        $banfl = true;
                        break;
                    }
                }
                if($banfl) {
                    break;
                }
            }
        }
    }
?>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ニックネーム変更</title>
        <link rel="stylesheet" href="../styles/style.css">
        <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
        <script>
            $(function () {
                $("#header").load("./header.html");
                $("#footer").load("./footer.html");
            });
        </script>
    </head>
    <div id="header"></div>
    <form class="nameForm" action="" method="post" name="name-form">
        <body id="name">
            <h3>新しいニックネームを入力してください</h3>
            <h6><input type="text" name="newname"></h6>
            <?php echo $errmes ?>
            <h1><input type="submit" value="更新"></h1>
        </body>
    </form>   
    <div id="footer"></div>
</html>