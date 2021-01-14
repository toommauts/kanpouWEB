<?php
	//DB接続設定
    $dsn = 'mysql:dbname=データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
    	//仮登録用のDB作成
        // テーブルの作成
    // 	$sql = "CREATE TABLE IF NOT EXISTS pre_account"
    // 	." ("
    // 	. "id INT AUTO_INCREMENT PRIMARY KEY,"
    // 	. "urltoken char(255),"
    // 	. "mail_address char(128),"
    // 	. "date DATETIME,"
    // 	. "flag tinyint"
    // 	.");";
    // 	$stmt = $pdo->query($sql);
    
    //         //  データベースのテーブル一覧を表示
    //     	$sql ='SHOW TABLES';
    //     	$result = $pdo -> query($sql);
    //     	foreach ($result as $row){
    //     		echo $row[0];
    //     		echo '<br>';
    //     	}
    //     	echo "<hr>";
        
    //         //テーブルの詳細表示
    //         $sql ='SHOW CREATE TABLE pre_account';
    //     	$result = $pdo -> query($sql);
    //     	foreach ($result as $row){
    //     		echo $row[1];
    //     	}
    //     	echo "<hr>";
        
    //     $sql = 'SELECT * FROM pre_account';
    // 	$stmt = $pdo->query($sql);
    // 	$results = $stmt->fetchAll();
    // 	foreach ($results as $row){
    // 		//$rowの中にはテーブルのカラム名が入る
    // 		echo $row['id'].',';
    // 		echo $row['urltoken'].',';
    // 		echo $row['mail_address'].',';
    // 		echo $row['date'].',';
    // 		echo $row['flag'].'<br>';
    // 	echo "<hr>";
    // 	}
    
    //     $sql = 'delete from pre_account';
    // 	$stmt = $pdo->prepare($sql);
    // 	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    // 	$stmt->execute();
        
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>メールアドレスの入力</title>
</head>
<body>
    <h1>仮会員登録画面</h1>
    <p>新しいアカウントを作成します。メールアドレスを入力してください</p>
    <form action="" method="post">
        <input type="text" name="mail_address" placeholder="メールアドレス">
        <input type="submit" name="submit">
    </form>
    
    
    
    <?php
        //DB接続設定
        $dsn = 'mysql:dbname=データベース名';
    	$user = 'ユーザー名';
    	$password = 'パスワード';
    	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        
        //メールアドレスを変数に代入
        $mail_address = $_POST["mail_address"];
        //変数の中身がからでなく、またメールアドレスの形式が正しい場合
        if(!empty($mail_address) && preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail_address)){
            
            
            //データベース内に一致するメールアドレスがあるか検索する
            /*$sql = 'SELECT id FROM account WHERE mail_address=:mail_address ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':mail_address', $mail_address, PDO::PARAM_STR); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); */
            $sql = "SELECT id FROM account WHERE mail_address=:mail_address";
            $stm = $pdo->prepare($sql);
            $stm->bindValue(':mail_address', $mail_address, PDO::PARAM_STR);
       
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            
            if(!isset($result['id'])){
                $urltoken = hash('sha256',uniqid(rand(),1));
                $url = "https://portal.tech-base.net/storage/userfile/u45463/mission6-2-set.php?urltoken=".$urltoken;
                //ここでデータベースに登録する
                $sql = "INSERT INTO pre_account (urltoken, mail_address, date, flag) VALUES (:urltoken, :mail_address, now(), '0')";
                $stm = $pdo->prepare($sql);
                $stm->bindParam(':urltoken', $urltoken, PDO::PARAM_STR);
                $stm->bindParam(':mail_address', $mail_address, PDO::PARAM_STR);
                $stm->execute();
                $pdo = null;
                $message = "メールをお送りしました。24時間以内にメールに記載されたURLからご登録下さい。";     
                
                
        	    require '../PHPMailer/Exception.php';
                require '../PHPMailer/PHPMailer.php';
                require '../PHPMailer/SMTP.php';
                require '../PHPMailer/setting.php';
        
                // PHPMailerのインスタンス生成
                $mail = new PHPMailer\PHPMailer\PHPMailer();
            
                $mail->isSMTP(); // SMTPを使うようにメーラーを設定する
                $mail->SMTPAuth = true;
                $mail->Host = MAIL_HOST; // メインのSMTPサーバー（メールホスト名）を指定
                $mail->Username = MAIL_USERNAME; // SMTPユーザー名（メールユーザー名）
                $mail->Password = MAIL_PASSWORD; // SMTPパスワード（メールパスワード）
                $mail->SMTPSecure = MAIL_ENCRPT; // TLS暗号化を有効にし、「SSL」も受け入れます
                $mail->Port = SMTP_PORT; // 接続するTCPポート
            
                // メール内容設定
                $mail->CharSet = "UTF-8";
                $mail->Encoding = "base64";
                $mail->setFrom(MAIL_FROM,MAIL_FROM_NAME);
                $mail->addAddress($mail_address, 'テストアカウントさん'); //受信者（送信先）を追加する
                //    $mail->addReplyTo('xxxxxxxxxx@xxxxxxxxxx','返信先');
                //    $mail->addCC('xxxxxxxxxx@xxxxxxxxxx'); // CCで追加
                //    $mail->addBcc('xxxxxxxxxx@xxxxxxxxxx'); // BCCで追加
                $mail->Subject = MAIL_SUBJECT; // メールタイトル
                $mail->isHTML(true);    // HTMLフォーマットの場合はコチラを設定します
                $body = $message . '<br><br><br>'. $url;
            
                $mail->Body  = $body; // メール本文
                // メール送信の実行
                if(!$mail->send()) {
                	echo 'メッセージは送られませんでした！';
                	echo 'Mailer Error: ' . $mail->ErrorInfo;
                } else {
                	echo '送信完了！';
                }
                //メール送信済画面に遷移
                header("location: mission6-2-mail.php");
            } else {
                echo "このメールアドレスはすでに存在しています";
            }
            
        	
        	
        	//データベースにメールアドレスが存在する場合
        	
            
            
        } else {
            echo "メールアドレスが入力されていない、または形式が正しくありません。";
        }
        
    ?>
</body>
</html>