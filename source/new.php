<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>スレッド新規作成</title>
        <link rel="stylesheet" href="../styles/style2.css">
        <link rel="stylesheet" href="../styles/tiles.css">
        <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
        <script>
            $(function () {
                $("#header").load("./header.html");
                $("#footer").load("./footer.html");
            });
        </script>
        <script>
            function ispublic_check1(ischecked){
                if(ischecked == true){
                    document.getElementById("pass").disabled = true;
                }
                else{
                    document.getElementById("pass").disabled = false;
                }
            }

            function ispublic_check2(ischecked){
                if(ischecked == true){
                    document.getElementById("pass").disabled = false;
                }
                else{
                    document.getElementById("pass").disabled = true;
                }
            }
        </script>
    </head>
    <div id="header"></div>
    <form action="new_check.php" method="post" name="create_thr-form">
        <body>
            <div id="new">
                <div class="newthr">
                    <h3><label for="title">タイトル : </label></h3>
                    <input id="title" type="text" name="title" title="30文字以内" pattern="{1,30}" required>
                    <?php //echo $err_titmes ?>
                </div>
                <div class="password-all">
                    <div class="newthr">
                        <h3><label for="password">管理者パスワード : </h3></label>
                            <input type="password" placeholder="Password" name="password" id="password">
                        </form>

                    <?php //echo $err_passmes ?>
                    </div>
                    <div class="password-check">
                    <input type="checkbox" id="password-check" />パスワードを表示する
                        <script>
                            var pw = document.getElementById('password');
                            var pwCheck = document.getElementById('password-check');
                            pwCheck.addEventListener('change', function() {
                                if(pwCheck.checked) {
                                    pw.setAttribute('type', 'text');
                                } else {
                                    pw.setAttribute('type', 'password');
                                }
                            }, false);
                        </script>
                    </div>
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
