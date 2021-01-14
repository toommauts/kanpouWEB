<?php
	//require "mission6-2-create.php";

	// DB接続設定
	$dsn = 'mysql:dbname=データベース名';
    $user = 'ユーザー名';
	$password = 'パスワード名';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
	// 【！この SQLは tbtest テーブルを削除します！】
    // 	$sql = 'DROP TABLE account';
    // 	$stmt = $pdo->query($sql);

    //テーブルの作成
    // 	$sql = "CREATE TABLE IF NOT EXISTS account"
    // 	." ("
    // 	. "id INT AUTO_INCREMENT PRIMARY KEY,"
    // 	. "user_id char(32),"
    // 	. "mail_address char(32),"
    // 	. "password char(32)"
    // 	.");";
    // 	$stmt = $pdo->query($sql);
    
    // 	//  データベースのテーブル一覧を表示
    // 	$sql ='SHOW TABLES';
    // 	$result = $pdo -> query($sql);
    // 	foreach ($result as $row){
    // 		echo $row[0];
    // 		echo '<br>';
    // 	}
    // 	echo "<hr>";
    
    //  $sql ='SHOW CREATE TABLE account';
    // 	$result = $pdo -> query($sql);
    // 	foreach ($result as $row){
    // 		echo $row[1];
    // 	}
    // 	echo "<hr>";

    //デリート
    // 	$sql = 'delete from account';
    // 	$stmt = $pdo->prepare($sql);
    // 	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    // 	$stmt->execute();
    
    $urltoken = $_GET["urltoken"];
    echo $urltoken . "<br>";
    
	$sql = "SELECT mail_address FROM pre_account WHERE urltoken=(:urltoken) AND flag =0 AND date > now() - interval 24 hour";
    $stm = $pdo->prepare($sql);
	$stm->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
	$stm->execute();
		
		//レコード件数取得
	$row_count = $stm->rowCount();
	echo $row_count . "<br>";
		//24時間以内に仮登録され、本登録されていないトークンの場合
	if( $row_count ==1){
	    $mail_array = $stm->fetch();
		$mail = $mail_array["mail_address"];
		$_SESSION['mail'] = $mail;
		}else{
			echo "このURLはご利用できません。有効期限が過ぎたかURLが間違えている可能性がございます。もう一度登録をやりなおして下さい。";
		}

    
	
    
    
    //$v_id = $_POST["v_id"];
    //入力された値がそれぞれ空でない場合
    if(!empty($_POST["v_id"]) && !empty($_POST["mail_address"]) && !empty($_POST["password"]) && !empty($_POST["c_password"])){
        if($_POST["password"] == $_POST["c_password"]){
            echo "パスワードあってます！";
            //パスワードが一致した時id,password,mail_addressをデータベースに入力する
            $sql = $pdo -> prepare("INSERT INTO account (user_id, mail_address, password) VALUES (:user_id, :mail_address, :password)");
        	$sql -> bindParam(':user_id', $user_id, PDO::PARAM_STR);
        	$sql -> bindParam(':mail_address', $mail_address, PDO::PARAM_STR);
        	$sql -> bindParam(':password', $password, PDO::PARAM_STR);
        	$user_id = $_POST["v_id"];
        	$mail_address = $_POST["mail_address"];
        	$password = $_POST["password"]; 
        	$sql -> execute();
        	//ページ遷移
            header("location: mission6-2-success.php");
        } else {
            echo "パスワードが間違っています！もう一度ご確認の上入力してください";
        }
    }
    
    
    
	
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ID・パスワードの入力</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="mail_address" placeholder="メールアドレス" value="<?=htmlspecialchars($_SESSION['mail'], ENT_QUOTES)?>" readonly>
        <br>
        <input type="text" name="v_id" placeholder="idの入力" value="<?php if(isset($v_id)) {echo $v_id; } ?>">
        <br>
        <input type="text" name="password" placeholder="パスワードの入力" >
        <p>確認のうえもう一度パスワードの入力をお願いします。</p>
        <input type="text" name="c_password" placeholder="パスワードの再入力" >
        <input type="submit" name="submit">
    </form>
    
    
    

</body>
</html>