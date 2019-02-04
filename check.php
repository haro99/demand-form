<?php
    if(empty($_POST['mail']))       $error = "メールアドレスが書かれていません";
    $mail = $_POST['mail'];
    if(empty($_POST['message']))    $error2 = "要望がかかれていません";
    $message = $_POST['message'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>確認ページ</title>
</head>
<body>
    <div class="top">
        <div class="note">
        <?php
            if(isset($error)) print "<p>$error</p>\n";
            if(isset($error2)) print "<p>$error2</p>\n";
        ?>
        </div>
        <h1>入力内容</h1>

        <div class="inputted">
            <p>メールアドレス：<?= $mail?></p>
            <p>要望：<?= $message?></p>
        </div>
        <div class="button">
            <form>
                <!--メールアドレスと要望がかかれていない場合は送信ボタンを表示しないで戻るボタンだけ表示する-->
                <?php if(empty($error)&&empty($error2)) print "<input type=\"submit\" value=\"送信\">";?>
                <input type="button" value="戻る" onclick="history.back()">
            </form>
        </div>
    </div>
</body>
</html>