<?php
    echo var_dump($_POST);

    //ページ変数の初期化
    $page_flag = 0;
    $clean = array();

    //  サニタイズ
    if( !empty($_POST) ) {
        foreach ($_POST as $key => $value) {
            $clean[$key] = htmlspecialchars( $value, ENT_QUOTES);
        }
    }
    echo var_dump($clean);
    if(!empty($_POST['btn_kakunin'])){
        $page_flag = 1;
    } elseif(!empty($_POST['btn_sousin'])){

        $page_flag = 2;

        //  変数とタイムゾーンの変更
        $admin_reply_subject = null;
        $admin_reply_text = null;
        date_default_timezone_set('Asia/Tokyo');
        /*
        テストなのでメールは実装しない
        $subject = "メール送信のテスト";
        $text = "メールが本文です。";
        mb_send_mail($_POST['mail'], $subject, $text);

        $admin_reply_text = "下記の内容でお問い合わせがありました。\n\n";
        $admin_reply_text .= "お問い合わせ日時：" . date("Y-m-d H:i") . "\n";
        $admin_reply_text .= "氏名：" . $_POST['your_name'] . "\n";
        $admin_reply_text .= "メールアドレス：" . $_POST['email'] . "\n\n";

        // 運営側へメール送信
        mb_send_mail( 'webmaster@gray-code.com', $admin_reply_subject, $admin_reply_text);
        */
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>お問い合わせフォーム</title>
    </head>
    <body>
        <div class="top">
            <div class="top_title">
                <h1 style="text-align:center;">お問い合わせフォーム</h1>
                <?php if($page_flag === 1): ?>
                <!--入力確認ページ-->
                <form method="POST" acition="">
                    <p>
                        お名前
                        <?php echo $clean['your_name'];?>
                    </p>
                    <p>
                        メールアドレス　
                        <?php echo $clean['mail'];?>
                    </p>
                    <p>
                        お問い合わせ内容
                        <?php echo $clean['message'];?>
                    </p>
                    <p>
                        プライバシーポリシーに同意する：
                        <?php   
                                if( $clean['agreement'] === "1" ){ echo '同意する'; }
                                else{ echo '同意しない'; }
                        ?>
                    </p>
                    <input type="submit" name="btn_back" value="戻る">
                    <input type="submit" name="btn_sousin" value="送信">
                    <input type="hidden" name="your_name" value="<?php echo $clean['your_name']?>">
                    <input type="hidden" name="mail" value="<?php echo $clean['mail'];?>">
                    <input type="hidden" name="message" value="<?php echo $clean['message'];?>">
                </form>
                <?php elseif($page_flag === 2): ?>
                <!--完了ページ-->
                <p>送信が完了しました。</p>
                <?php else: ?>
                <!--最初の入力ページ-->
                <div class="top_form">
                    <form action="" method="POST">
                        お名前
                        <p><input type="text" name="your_name" value=<?php if(!empty($clean['your_name'])) echo $clean['your_name'];?>></p>
                        メールアドレス
                        <p><input type="text" name="mail" value="<?php if(!empty($clean['mail'])) echo $clean['mail'];?>"></p>
                        お問い合わせ内容
                        <p><textarea rows="10" cols="70" wrap="OFF" name="message"><?php if(!empty($clean['message'])) echo $clean['message'];?></textarea></p>
                        <input type="checkbox" name="agreement" value="1">プライバシーポリシーに同意する<br>
                        <input type="submit" value="入力確認" name="btn_kakunin">
                    </form>
                </div>
                <?php endif;?>
            </div>
        </div>
    </body>
</html>