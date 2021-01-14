<?php
    //DB接続設定
 	$dsn = 'mysql:dbname=データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
    // 	// デモ漢方テーブルの作成
    // 	$sql = "CREATE TABLE IF NOT EXISTS kanpou_demo"
    // 	." ("
    // 	. "id INT PRIMARY KEY,"
    // 	. "name char(32),"
    // 	. "ruby char(32),"
    // 	. "state char(32),"
    // 	. "composition TEXT,"
    // 	. "content TEXT"
    // 	.");";
    // 	$stmt = $pdo->query($sql);
//         // 【！この SQLは tbtest テーブルを削除します！】
// 		$sql = 'DROP TABLE shouyaku_demo';
// 		$stmt = $pdo->query($sql);
        	
    // 	 // デモ生薬テーブルの作成
    // 	$sql = "CREATE TABLE IF NOT EXISTS shouyaku_demo"
    // 	." ("
    // 	. "id INT AUTO_INCREMENT PRIMARY KEY,"
    // 	. "name char(32),"
    // 	. "k_composition TEXT,"
    // 	. "k_ruby TEXT,"
    // 	. "k_number TEXT"
    // 	.");";
    // 	$stmt = $pdo->query($sql);
        	
    // 		//作成したテーブルの構成詳細を確認する。
    // 	$sql ='SHOW CREATE TABLE kanpou';
    // 	$result = $pdo -> query($sql);
    // 	foreach ($result as $row){
    // 		echo $row[1];
    // 	}
    // 	echo "<hr>";
            
    //     //データの削除
    //     $id = 1;
    // 	$sql = 'delete from shouyaku where id=:id';
    // 	$stmt = $pdo->prepare($sql);
    // 	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    // 	$stmt->execute();
    	
    //データベースに登録
    $sql = $pdo -> prepare("INSERT INTO kanpou (id, name, ruby, state, composition, content) VALUES (:id, :name, :ruby, :state, :composition, :content)");
	$sql -> bindParam(':id', $id, PDO::PARAM_INT);
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':ruby', $ruby, PDO::PARAM_STR);
	$sql -> bindParam(':state', $state, PDO::PARAM_STR);
	$sql -> bindParam(':composition', $composition, PDO::PARAM_STR);
	$sql -> bindParam(':content', $content, PDO::PARAM_STR);
	$id = 127;
	$name = '麻黄附子細辛湯';
	$ruby = 'マオウブシサイシントウ'; //好きな名前、好きな言葉は自分で決めること
	$state = '細粒';
	$composition = '日局マオウ<>日局ブシ<>日局サイシン<>';
	$content = '4.0<>1.0<>3.0<>';
	$sql -> execute();

	
	//生薬一つずつに分解する
	$splits = explode("<>", $composition);
	
	foreach($splits as $split){
	    //データベース内に一致する生薬があるか検索する(空白は除く)
	    if(!empty($split)){
	        $sql = "SELECT * FROM shouyaku WHERE name=:split";
            $stm = $pdo->prepare($sql);
            $stm->bindValue(':split', $split, PDO::PARAM_STR);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            
            //shouyakuデータベースに該当生薬が存在する場合
            if(isset($result['id'])){
                
                //shouyakuデータベースを新しく書き換える。
                $upd_id = $result['id']; //変更する投稿番号
            	$k_composition = $result['k_composition'] . $name . "<>";
            	$k_ruby = $result['k_ruby'] . $ruby . "<>";
            	$k_number = $result['k_number'] . $id . "<>";
            	$sql = 'UPDATE shouyaku SET k_composition=:k_composition, k_ruby=:k_ruby, k_number=:k_number WHERE id=:id';
            	$stmt = $pdo->prepare($sql);
            	$stmt->bindParam(':k_composition', $k_composition, PDO::PARAM_STR);
            	$stmt->bindParam(':k_ruby', $k_ruby, PDO::PARAM_STR);
            	$stmt->bindParam(':k_number', $k_number, PDO::PARAM_STR);
            	$stmt->bindParam(':id', $upd_id, PDO::PARAM_INT);
            	$stmt->execute();
            	
            //shouyakuデータベースに該当生薬が存在しない場合	
            } else {
                
                //shouyakuデータベースにデータを追加する。
                $sql = $pdo -> prepare("INSERT INTO shouyaku (name, k_composition, k_ruby, k_number) VALUES (:name, :k_composition, :k_ruby, :k_number)");
            	$sql -> bindParam(':name', $write_name, PDO::PARAM_STR);
            	$sql -> bindParam(':k_composition', $k_composition, PDO::PARAM_STR);
            	$sql -> bindParam(':k_ruby', $k_ruby, PDO::PARAM_STR);
            	$sql -> bindParam(':k_number', $k_number, PDO::PARAM_STR);
            	$write_name = $split;
                $k_composition = $name . "<>";
                $k_ruby = $ruby . "<>";
                $k_number = $id . "<>";
            	$sql -> execute();
            }
	    }
        
	}
    
        
        
        //生薬テーブルを出力する
    	$sql = 'SELECT * FROM shouyaku';
    	$stmt = $pdo->query($sql);
    	$results = $stmt->fetchAll();
    	foreach ($results as $row){
    		//$rowの中にはテーブルのカラム名が入る
    		echo $row['id'].',';
    		echo $row['name'].',';
    		echo $row['k_composition'].',';
    		echo $row['k_ruby'].',';
    		echo $row['k_number'].',';
    	    echo "<hr>";
    	}
	
?>