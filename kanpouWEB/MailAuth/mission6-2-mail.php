<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>メールが送信されました</title>
</head>
<body>
    <p>メールが送信されました。ご確認ください</p>
    <p>届かない場合</p>
    <a href="mission6-2-create.php">メールを再送する</a>
    
    
    
    <?php
        // DB接続設定
    	$dsn = 'mysql:dbname=データベース名';
    	$user = 'ユーザー名';
    	$password = 'パスワード';
    	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    ?>
</body>
</html>