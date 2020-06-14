<!DOCTYPE html>
<?php 
    $banarr = array(
        ",","\"","'",".","`","~","$","%","&","*","(",")","{","}","\\","/"
    );
    $banfl = false;
    $err_titmes = '';
    $err_passmes = '';

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(!empty($_POST['title'])) {
            $title = str_split($_POST['title']);
            foreach($title as $value) {
                foreach($banarr as $banchar) {
                    if(strcmp($value, $banchar) == 0) {
                        $err_titmes = 'E01:タイトルには使用できない文字が含まれています。';
                        $banfl = true;
                        break;
                    }
                }
                if($banfl) {
                    break;
                }
           }
        }
        else {
            $err_titmes = 'E02:タイトルは必須項目です。';
        }

        if(!empty($_POST['password'])) {
            $pass = str_split($_POST['password']);
            echo count($pass);
            if(count($pass) < 8) {
                $err_passmes = 'E03:パスワードは少なくとも8文字以上でないといけません。';
            }
            foreach($pass as $value) {
                foreach($banarr as $banchar) {
                    if(strcmp($value, $banchar) == 0) {
                        $err_passmes = 'E04:パスワードには使用できない文字が含まれています。';
                        $banfl = true;
                        break;
                    }
                }
                if($banfl) {
                    break;
                }
            }
        }
        else {
            $err_passmes = 'E05:パスワードは必須項目です。';
        }
    }
?>

<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>スレッド新規作成</title>
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
    <div class="create_thrForm">
        <form action="" method="post" name="create_thr-form">
            <body id="new">
                <h3><label for="title">タイトル</label></h3><input type="text" name="title">
                <?php echo $err_titmes ?>
                <h3><label for="password">管理者パスワード</h3></label><input type="password" name="password">
                <?php echo $err_passmes ?>
                <h3><label for="comment">内容</label></h3><textarea id="comment" name="comment"></textarea>
                <input type="submit" value="スレッド新規作成">
            </body>
        </form>   
    </div>
    <div id="footer"></div>
</html>