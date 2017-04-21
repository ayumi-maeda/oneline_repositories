<?php
  
  // １．データベースに接続する
  $dsn = 'mysql:dbname=oneline_bbs;host=localhost';
  $user = 'root';
  $password='';
  $dbh = new PDO($dsn, $user, $password);
  $dbh->query('SET NAMES utf8');




 //配列で取得したデータを格納
   // 配列を初期化(配列を使う準備)
    $post_datas = array();

 // POST送信されたらINSERT分を実行
  if(!empty($_POST)){
	  $nickname = htmlspecialchars($_POST['nickname']);
	  $comment = htmlspecialchars($_POST['comment']);	
	  // ２．SQL文を実行する
	  $sql = 'INSERT INTO `posts`(`nickname`, `comment`, `created`) VALUES ("'.$nickname.'","'.$comment.'",now());' ;
    var_dump($sql);
	
	// now()はsql関数のため、””で囲まない
	  $stmt = $dbh->prepare($sql);
	  $stmt->execute();

  // SELECR分の実行
    $sql = 'SELECT * FROM `posts`;';
  // SQL分作成(SELECT分)

  // 実行　
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
   

  // 繰り返し文でデータ取得（フェッチ）
    while (1) {
     $rec = $stmt->fetch(PDO::FETCH_ASSOC);
     if($rec == false){
         break;
     }
     // echo $rec['nickname'];
     $post_datas[] = $rec;
    }
  }
  // ３．データベースを切断する
  $dbh = null;


?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>セブ掲示版</title>
</head>
<body>
    <form method="post" action="">
      <p><input type="text" name="nickname" placeholder="nickname"></p>
      <p><textarea type="text" name="comment" placeholder="comment"></textarea></p>
      <p><button type="submit" >つぶやく</button></p>
    </form>
    <!-- ここにニックネーム、つぶやいた内容、日付を表示する -->
    <?php
        foreach ($post_datas as $post_each) {
          echo $post_each['nickname'].'<br>';
          echo $post_each['comment'].'<br>';
          echo $post_each['created'].'<br>';
      }
    ?>

</body>
</html>