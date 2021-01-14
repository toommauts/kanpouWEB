<?php
    $urltoken = $_GET["urltoken"];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="mission6-2-home.css">
    <link rel="stylesheet" href="mission6-2-search.css">
    <title>成分検索</title>
</head>
<body>
    <ul id="nav">
      <li><a href="mission6-2-home.php">マイページ</a></li>
      <li><a href="mission6-2-search.php">名前検索</a></li>
      <li><a href="mission6-2-crude.php">成分検索</a></li>
      <li><a href="mission6-2-list.php">索引</a></li>
      <li><a href="mission6-2-login.php">ログアウト</a></li>
    </ul>
    <h1>生薬の名前から含まれている漢方を検索します（現在クラシエに対応しています）</h1>
    <p>日局〇〇の形式で入力してください</p>
    <form action="" method="post">
        <div class="msr_search_04">
            <input type="text" name="name" placeholder="名前を入力" value="<?php if(isset($urltoken)) {echo $urltoken;}?>">
            <input type="submit">
        </div>
    </form>
    
    <?php
        $name = $_POST["name"];
        
        //DB接続設定
        $dsn = 'mysql:dbname=データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        
        $sql = 'SELECT * FROM shouyaku WHERE name=:name';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':name', $name, PDO::PARAM_STR); 
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll(); 
    	foreach ($results as $row){
    		//$rowの中にはテーブルのカラム名が入る
    		echo "<h2>生薬名：" .$row['name'] . "</h2><br>";

    		echo "<strong>この生薬が含まれている漢方：</strong><br><br>";
    		$split_k_comp = explode("<>", $row['k_composition']);
    		$split_k_ruby = explode("<>", $row['k_ruby']);
    		$split_k_num = explode("<>", $row['k_number']);
    		echo '<ul class="msr_list05">';
    		for($i = 0; $i < count($split_k_comp) - 1; $i++){
    		    $urltoken = $split_k_num[$i] . "<>" . $split_k_comp[$i];
    		    echo '<li>' . $split_k_num[$i] . "　　" . '<a href="mission6-2-search.php?urltoken=' . $urltoken . '">' . $split_k_comp[$i] 
    		    . " ( "  . $split_k_ruby[$i] . " ) " . "</a></li>";
    		}
    		echo '</ul>';
    	}
    
    
    ?>
</body>
</html>