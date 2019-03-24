<?php
    //echo var_dump($_POST);

    //ページ変数の初期化
    $page_flag = 0;
    $clean = array();
    $error = array();

    //  サニタイズ処理
    if( !empty($_POST) ) {
        foreach ($_POST as $key => $value) {
            $clean[$key] = htmlspecialchars( $value, ENT_QUOTES);
        }
    }
    //echo var_dump($clean);
    if(!empty($clean['btn_kakunin'])){

        $error = validation($clean);

        if( empty($error) ){

            $page_flag = 1;

            //  セッションの書き込み
            session_start();
            $_SESSION['page'] = true;
        }



    }elseif(!empty($clean['btn_sousin'])){

        session_start();
        if( !empty($_SESSION['page']) && $_SESSION['page'] === true ) {
    
            // セッションの削除
            unset($_SESSION['page']);
            $page_flag = 2;

            //  変数とタイムゾーンの変更
            $header = null;
            $auto_reply_subject = null;
            $auto_reply_text = null;
            $admin_reply_subject = null;
            $admin_reply_text = null;
            date_default_timezone_set('Asia/Tokyo');

            // ヘッダー情報を設定
            $header = "MIME-Version: 1.0\n";
            $header .= "From: TESTCODE <noreply@gray-code.com>\n";
            $header .= "Reply-To: GRAYCODE <noreply@gray-code.com>\n";

            //  件名を設定
            $auto_reply_subject = 'お問い合わせありがとうございます。';

                // 本文を設定
            $auto_reply_text = "この度は、お問い合わせ頂き誠にありがとうございます。
            下記の内容でお問い合わせを受け付けました。\n\n";
            $auto_reply_text .= "お問い合わせ日時：" . date("Y-m-d H:i") . "\n";
            $auto_reply_text .= "氏名：" . $_POST['your_name'] . "\n";
            $auto_reply_text .= "メールアドレス：" . $_POST['email'] . "\n\n";
            $auto_reply_text .= "test 事務局";

            //  テスト段階なので実際にはメールは実装しない
            // メール送信
            //mb_send_mail( $_POST['email'], $auto_reply_subject, $auto_reply_text);
        
            //  運営側へ送る
            $admin_reply_subject = "お問い合わせを受け付けました";

            //  本文を設定
            $admin_reply_text = "下記の内容でお問い合わせがありました。\n\n";
            $admin_reply_text .= "お問い合わせ日時：" . date("Y-m-d H:i") . "\n";
            $admin_reply_text .= "氏名：" . $_POST['your_name'] . "\n";
            $admin_reply_text .= "メールアドレス：" . $_POST['email'] . "\n\n";

            //  テスト段階なので実際にはメールは実装しない
            // 運営側へメール送信
            //mb_send_mail( 'webmaster@gray-code.com', $admin_reply_subject, $admin_reply_text);

        } else {
            $page_flag = 0;
        }
    }
    
    function validation($date) {

        $error = array();

        //  氏名のバリデーション
        if( empty($date['your_name']) ) {
            $error[] = "「氏名」は必ず入力してください。";
        } elseif( 20 < mb_strlen($date['your_name']) ){
            $error[] = "「氏名」は20文字以内で入力してください。";
        }

        //  メールアドレスのバリデーション
        if( empty($date['email']) ) {
            $error[] = "「メールアドレス」は必ず入力してください。";
        } elseif( !preg_match( '/^[0-9a-z_.\/?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$/', $date['email']) ) {
            $error[] = "「メールアドレス」は正しい形式で入力してください。";
        }

        //  お問い合わせ内容のバリデーション
        if( empty($date['message']) ) {
            $error[] = "「お問い合わせ内容は」は必ず入力してください。";
        }

        //  プライバシーポリシー同意のバリデーション
        if( empty($date['agreement']) ) {
            $error[] = "プライバシーポリシーをご確認ください。";
        } elseif( (int)$date['agreement'] !== 1 ) {
            $error[] = "プライバシーポリシーをご確認ください。";
        }
        return $error;
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <style>
        .error_list {
            color: #ff2e5a;
        }
        </style>
        <title>お問い合わせフォーム</title>
    </head>
    <body>
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
                <?php echo $clean['email'];?>
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
            <input type="hidden" name="email" value="<?php echo $clean['email'];?>">
            <input type="hidden" name="message" value="<?php echo $clean['message'];?>">
            <input type="hidden" name="agreement" value="<?php echo $clean['agreement']; ?>">
        </form>
        <?php elseif($page_flag === 2): ?>
        <!--完了ページ-->
        <p>送信が完了しました。</p>
        <?php else: ?>
        <!--最初の入力ページ-->
        <div class="top_form">
            <?php if( !empty($error) ): ?>
                <ul class="error_list">
                <?php foreach( $error as $value ): ?>
                    <li><?php echo $value; ?></li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <form action="" method="POST">
                お名前
                <p><input type="text" name="your_name" value=<?php if(!empty($clean['your_name'])) echo $clean['your_name'];?>></p>
                メールアドレス
                <p><input type="text" name="email" value="<?php if(!empty($clean['email'])) echo $clean['email'];?>"></p>
                お問い合わせ内容
                <p><textarea rows="10" cols="70" wrap="OFF" name="message"><?php if(!empty($clean['message'])) echo $clean['message'];?></textarea></p>
                <input type="checkbox" name="agreement" value="1"<?php if( !empty($_POST['agreement']) && $_POST['agreement'] === "1" ){ echo 'checked';} ?>>プライバシーポリシーに同意する<br>
                <input type="submit" value="入力確認" name="btn_kakunin">
            </form>
        </div>
        <?php endif;?>
    </body>
</html>
