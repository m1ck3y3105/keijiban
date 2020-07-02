<!DOCTYPE html>
<?php 
    /**/ 
?>

<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>スレッド新規作成</title>
        <link rel="stylesheet" href="../styles/style.css">
        <link rel="stylesheet" href="../styles/tiles.css">
        <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
        <script>
            $(function () {
                $("#header").load("./header.html");
                $("#footer").load("./footer.html");
            });
        </script>
    </head>
    <div id="header"></div>
    <form action="" method="post" name="create_thr-form">
        <body>
            <div id="new">
                <div class="newthr">
                    <h3><label for="title">タイトル : </label></h3>
                    <input id="title" type="text" name="title" title="30文字以内" maxlength="30" required>
                    <?php echo $err_titmes ?>
                </div>
                <div class="newthr">
                    <h3><label for="password">管理用パスワード : </h3></label>
                    <input id="pass" type="password" name="password" title="半角英数字6~15文字以内" maxlength="15" pattern="([0-9a-zA-Z]{6,15})" required>
                </div>
                <div class="newthr">
                    <h3><label for="comment">内容 : </label></h3>
                    <textarea id="comment" name="comment" title="スレッドの内容"></textarea>
                </div>
                <div class="submitbtn">
                    <input type="submit" value="スレッド新規作成">
                </div>
            </div>
        </body>
    </form>   
    <div id="footer"></div>
</html>