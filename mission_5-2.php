<!DOCHTYPE html>
<html lang="ja">
    <head>
       <meta charset="utf-8">
       <title>mission_5-2</title>
    </head>
    <body>
        <h1>掲示板（行ってみたい国）</h1>
        
        
        
        
       
        <?php 
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        // テーブルの作成
         $sql = "CREATE TABLE IF NOT EXISTS tbtest_4"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date DATETIME,"
        . "pass TEXT"
        .");";
        $stmt = $pdo->query($sql);
        
        
        
    // <!--// DB接続設定-->
    

        // もし名前とコメントが入力されていれば、文字列を入力する
         if(!empty($_POST["name"]) && !empty($_POST["comment"] && empty($_POST["edit_number"]))){
             $name = $_POST["name"];
             $comment = $_POST["comment"];
             $date = date('Y-m-d H:i:s');
             $pass = $_POST["pass"];
             $sql = $pdo->prepare("INSERT INTO tbtest_4 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
             $sql -> bindParam(':name', $name, PDO::PARAM_STR);
             $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
             $sql -> bindParam(':date', $date, PDO::PARAM_STR);
             $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
             $sql -> execute();
           
           
         
          
    } 
    // 表示機能
         $sql = 'SELECT * FROM tbtest_4';
           $stmt = $pdo->query($sql);
           $results = $stmt->fetchAll();
            foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
             $row['id'].',';
             $row['name'].',';
             $row['comment'].',';
             $row['date'].'<br>';
                
             
             //  以下編集機能
         if(!empty($_POST["edit_number"]) && !empty($_POST["name"]) && !empty($_POST["comment"])){
                $id = $_POST["edit_number"]; //変更する投稿番号
                $name = $_POST["name"];
                $comment = $_POST["comment"];//変更したい名前、変更したいコメントは自分で決めること
                $sql = 'UPDATE tbtest_4 SET name=:name,comment=:comment WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();  
                
      }
    }

  
                 
                 
                
         // <!--削除機能-->
        // 削除機能
         if(!empty($_POST["delete"]) && $_POST["delete_pass"] === $row['pass']){
        $id = $_POST["delete"];
        $sql = 'delete from tbtest_4 where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
             
         }
         
         
         // 編集用機能(編集用番号が送信されたら、文章の番号、名前、コメントを取得)
        if(!empty($_POST["number"]) && ($_POST["edit_pass"]) === $row['pass']){
         $id = $_POST["number"]; 
         $sql = 'SELECT * FROM tbtest_4 WHERE id=:id';
         $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、                 // ←差し替えるパラメータを含めて記述したSQLを準備し、
         $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
         $stmt->execute();                             // ←SQLを実行する。
         $results = $stmt->fetchAll(); 
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                $edit_num = $row['id'];
                $edit_name = $row['name'];
                $edit_comment = $row['comment'];
            }
        }
    
        
   
        
            
  
    
        
        ?>        
        
        <h2>送信フォーム</h2>
        <form action="" method="post">
            <!--名前-->
          <input type="text" name="name" value="<?php 
          if(!empty($edit_name))
          {echo $edit_name;} 
          ?>"placeholder="名前">
          
          <!--コメント-->
          <input type="text" name="comment" value="<?php 
          if(!empty($edit_comment))
          {echo $edit_comment;} 
          ?>"placeholder="コメント">
          
          <input type="hidden" name="edit_number" value="<?php 
          if(!empty($edit_num))
          { echo $edit_num;} 
          ?>">
          <br>
          <input type="text" name="pass" value=""placeholder="パスワード">
          <input type="submit" name="submit" value="送信">
        </form>
        <h2>削除フォーム</h2>*パスワードが指定されていない投稿は消去できません。
        <form action="" method="post">
          <input type="number" name="delete" placeholder="削除番号">
          <input type="submit" name="delete_submit" value="削除">
          <br>
          <input type="text" name="delete_pass" value="" placeholder="パスワード">
        </form>
        <h2>編集番号指定フォーム</h2>*パスワードが指定されていない投稿は編集できません。
        <form action="" method="post">
          <input type="number" name="number" placeholder="編集対象番号">
          <input type="submit" name="edit" value="編集">
          <br>
          <input type="text" name="edit_pass" value=""placeholder="パスワード">
        </form>           
        
    <h2>【投稿一覧】</h2>
    
    <?php
        $sql = 'SELECT * FROM tbtest_4';
           $stmt = $pdo->query($sql);
           $results = $stmt->fetchAll();
            foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
        echo "<hr>";
        }
        
    ?>
    
    </body>
</html>