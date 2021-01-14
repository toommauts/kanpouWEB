<?php 
    $urltoken = $_GET["urltoken"];
    $splits = explode("<>", $urltoken);
    $kanpou_num = $splits[0];
    $kanpou_name = $splits[1];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="CSS/mission6-2-home.css">
    <link rel="stylesheet" href="CSS/mission6-2-search.css">
    <title>漢方名検索</title>
</head>
<body>
    <ul id="nav">
        <li><a href="mission6-2-home.php">マイページ</a></li>
        <li><a href="mission6-2-search.php">名前検索</a></li>
        <li><a href="mission6-2-crude.php">成分検索</a></li>
        <li><a href="mission6-2-list.php">索引</a></li>
        <li><a href="mission6-2-login.php">ログアウト</a></li>
    </ul>
    <h1>漢方名や番号から含まれている生薬を検索します（現在クラシエに対応しています）</h1>
    <p>漢方名（フリガナにも対応）か番号を入力してください</p>
    <form action="" method="post">
        <div class="msr_search_04">
            <input type="number" name="id" placeholder="番号を入力" value="<?php if(isset($kanpou_num)){ echo $kanpou_num;}?>">
            <input type="submit">
        </div>
    </form>
    <p>または</p>
    <form action="" method="post">
        <div class="msr_search_04">
            <input type="text" name="name" placeholder="名前を入力" value="<?php if(isset($kanpou_name)){ echo $kanpou_name;}?>">
            <input type="submit">
        </div>
    </form>
    

    <?php
        //DB接続設定
     	$dsn = 'mysql:dbname=データベース名';
    	$user = 'ユーザー名';
    	$password = 'パスワード';
    	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
        $id = $_POST["id"]; // idがこの値のデータだけを抽出したい、とする
        $name = $_POST["name"];
    
        $sql = 'SELECT * FROM kanpou WHERE id=:id OR name=:name OR ruby=:name';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->bindParam(':name', $name, PDO::PARAM_STR); 
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll(); 
    	foreach ($results as $row){
    		//$rowの中にはテーブルのカラム名が入る
    		echo '<ul class="kanpou_list">';
    		echo "<li><h2>"  . $row['id'] . " " .$row['name'] . "( " . $row['ruby'] . " )" . "</h2></li><br>";
    		
    		echo "<li><strong>エキス" . $row['state'] . "：</strong></li><br>";
    		echo "<li><strong>含まれている生薬：</strong><br>";
    		$split_comp = explode("<>", $row['composition']);
    		$split_cont = explode("<>", $row['content']);
    		
    		
    		echo '<ul class="msr_list05">';
    		for($i = 0; $i < count($split_comp) - 1; $i++){
    		    $urltoken = $split_comp[$i];
    		    echo '<li><a href="mission6-2-crude.php?urltoken=' . $urltoken . '">'
    		    . $split_comp[$i] . '</a>' . " ・・・ "  . $split_cont[$i] . " g" . "         </li>";
    		}
    		echo '</li>';
    		echo '</ul>';
    	}
    
    
    ?>
    
    
</body>
</html>