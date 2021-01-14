<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="mission6-2-home.css">
    <link rel="stylesheet" href="mission6-2-list.css">
    <title>索引</title>
</head>
<body>
    <ul id="nav">
        <li><a href="mission6-2-home.php">マイページ</a></li>
        <li><a href="mission6-2-search.php">名前検索</a></li>
        <li><a href="mission6-2-crude.php">成分検索</a></li>
        <li><a href="mission6-2-list.php">索引</a></li>
        <li><a href="mission6-2-login.php">ログアウト</a></li>
    </ul>
    <h1>漢方の索引検索をします(クラシエに対応しています)</h1>
    <br>
    <p>メニューを操作して送信ボタンを押してください</p>
    <form action="" method="post">
        <div class="msr_pulldown_04">
            <select name="msr_pulldown_04">
                <option value="0" selected>漢方名</option>
                <option value="1">生薬名</option>
            </select>
            <input type="submit" value="送信" >
        </div>
        
    </form>
    <br>
    <?php
        //DB接続設定
     	$dsn = 'mysql:dbname=データベース名';
    	$user = 'ユーザー名';
    	$password = 'パスワード';
    	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        //モード切り替え用の変数
        $mode = $_POST["msr_pulldown_04"];
        $count = 0;
        if($mode == 0){
            $sql = 'SELECT * FROM kanpou';
	        $stmt = $pdo->query($sql);
	        $results = $stmt->fetchAll();
	        echo '<ul class="msr_list05">';
	        foreach ($results as $row){
	            $urltoken = $row['id'] . '<>' . $row['name'];
		        echo '<li><a href="mission6-2-search.php?urltoken=' . $urltoken . '">' . 
		        $row['id'] . " " . $row['name'] . " ( " . $row['ruby'] . " ) " . "</a></li>";
	        }
	        echo '</ul>';
        } elseif($mode = 1) {
            $sql = 'SELECT * FROM shouyaku';
	        $stmt = $pdo->query($sql);
	        $results = $stmt->fetchAll();
	        echo '<ul class="msr_list05">';
	        foreach ($results as $row){
	            $urltoken = $row['name'];
		    //$rowの中にはテーブルのカラム名が入る
		        echo '<li><a href="mission6-2-crude.php?urltoken=' . $urltoken . '">' . $row['name'] . "</a></li>";

	        }
	        echo '</ul>';
        }
        
        
    ?>
    
</body>
</html>