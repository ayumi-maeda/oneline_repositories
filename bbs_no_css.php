<?php
  
  // １．データベースに接続する
  $dsn = 'mysql:dbname=oneline_bbs;host=localhost';
  $user = 'root';
  $password='';
  $dbh = new PDO($dsn, $user, $password);
  $dbh->query('SET NAMES utf8');

 // POST送信されたらINSERT分を実行
  if(!empty($_POST)){
	  $nickname = htmlspecialchars($_POST['nickname']);
	  $comment = htmlspecialchars($_POST['comment']);	
	  // ２．SQL文を実行する
	  $sql = 'INSERT INTO `posts`(`nickname`, `comment`, `created`) VALUES ("'.$nickname.'","'.$comment.'",now())';
	  
	// now()はsql関数のため、””で囲まない
	  $stmt = $dbh->prepare($sql);
	  $stmt->execute();
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

</body>
</html>