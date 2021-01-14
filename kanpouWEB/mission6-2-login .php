<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>漢方.comへログイン</title>
</head>
<body>
    <img src="IMG/IMG_5878.jpg" alt="ロゴ画像">
    <p>会員登録済の場合メールアドレスとパスワードを入力してください</p>
    <form action="" method="post">
        <input type="text" name="mail_address" placeholder="メールアドレス">
        <input type="text" name="password" placeholder="パスワード">
        <br>
        <input type="submit" name="submit" value="ENTER">
    </form>
    <?php
    //DB接続設定
        $dsn = 'mysql:dbname=データベース名';
    	$user = 'ユーザー名';
    	$password = 'パスワード';
    	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        //メールアドレスを変数に代入
        $v_mail_address = $_POST["mail_address"];
        $v_password = $_POST["password"];
        //変数の中身がからでなく、またメールアドレスの形式が正しい場合
        if(!empty($v_mail_address) && !empty($v_password)){
            if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $v_mail_address)){
                $mail_address = $v_mail_address;
                $password = $v_password;
                //データベース内に一致するメールアドレスがあるか検索する
                $sql = "SELECT id FROM account WHERE mail_address=:mail_address AND password=:password ";
                $stm = $pdo->prepare($sql);
                $stm->bindValue(':mail_address', $mail_address, PDO::PARAM_STR);
                $stm->bindValue(':password', $password, PDO::PARAM_STR);
                $stm->execute();
                $result = $stm->fetch(PDO::FETCH_ASSOC);
            
                if(isset($result['id'])){
                    header("location: mission6-2-home.php");
                } else {
                    echo "メールアドレスかパスワードが正しくありません";
                }
            } else {
                echo "メールアドレスの形式が正しくありません";
            } 
            
        }



?>
    <br>
    <a href="mission6-2-home.php">ログインせず利用する</a>
    <hr>
    <a>パスワードを忘れてしまった方はこちら</a>
    <p>会員登録がまだの方</p>
    <a href="MailAuth/mission6-2-create.php">新しくアカウントを作成する</a>
    
    

</body>
</html>