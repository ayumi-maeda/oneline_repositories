
<?php
  
  // １．データベースに接続する
  $dsn = 'mysql:dbname=oneline_bbs;host=localhost';
  $user = 'root';
  $password='';
  $dbh = new PDO($dsn, $user, $password);
  // クラス（定義や設計図）を元に、実体化させる必要があります。
// PHP では実体化のために new キーワードを用います。
  // SQLを実行する前に、色々用意して、実行するって事。
// そのSQLの条件とか値が異なる時は、そこだけ入れ替えて使えるもの。
  $dbh->query('SET NAMES utf8');
  // 実体の持つプロパティやメソッドへアクセスするために使われる記号が -> です。




 //配列で取得したデータを格納
   // 配列を初期化(配列を使う準備)
       $post_datas = array();
 // POST送信されたらINSERT分を実行
  if(!empty($_POST)){
    $nickname = htmlspecialchars($_POST['nickname']);
    $comment = htmlspecialchars($_POST['comment']); 
    // ２．SQL文を実行する
    $sql = 'INSERT INTO `posts`(`nickname`, `comment`, `created`) VALUES ("'.$nickname.'","'.$comment.'",now());' ;
    // var_dump($sql);
  
  // now()はsql関数のため、””で囲まない
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
  }
  // SELECR分の実行
    $sql = 'SELECT * FROM `posts` ORDER BY `created` DESC ;';
  // SQL分作成(SELECT分)

  // 実行　
    $stmt = $dbh->prepare($sql);

    $stmt->execute();
    // $stmt = とすべきは、実行後にSQLの実行結果に関する情報を得たい場合であり、ただSQLを実行するだけであれば$db->query($sql);のように書けばよいということになります。
   
    // $sql = 'string date ( string $format [, int $timestamp = time() ] )';
 

    // $stmt = $dbh->prepare($sql);
    // $stmt->execute();

  // 繰り返し文でデータ取得（フェッチ）
    while (1) {
     $rec = $stmt->fetch(PDO::FETCH_ASSOC);
     // そのSQLの中を連想配列で取得するよ。って事。
     //  カラム名をキーとする連想配列で取得する
     // PDOは「PHP Data Objects」の頭文字をとった名称
     if($rec == false){
         break;
     }
     // echo $rec['nickname'];
     $post_datas[] = $rec;
    }
  
  // ３．データベースを切断する
  $dbh = null;


?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>AYumi's first 掲示版</title>

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="assets/css/form.css">
  <link rel="stylesheet" href="assets/css/timeline.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
  <!-- ナビゲーションバー -->
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#page-top"><span class="strong-title"><i class="fa fa-linux"></i> Oneline bbs</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <!-- Bootstrapのcontainer -->
  <div class="container">
    <!-- Bootstrapのrow -->
    <div class="row">

      <!-- 画面左側 -->
      <div class="col-md-4 content-margin-top">
        <!-- form部分 -->
        <form action="bbs.php" method="post">
          <!-- nickname -->
          <div class="form-group">
            <div class="input-group">
              <input type="text" name="nickname" class="form-control" id="validate-text" placeholder="nickname" required>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
          <!-- comment -->
          <div class="form-group">
            <div class="input-group" data-validate="length" data-length="4">
              <textarea type="text" class="form-control" name="comment" id="validate-length" placeholder="comment" required></textarea>
              <span class="input-group-addon danger"><span class="glyphicon glyphicon-remove"></span></span>
            </div>
          </div>
          <!-- つぶやくボタン -->
          <button type="submit" class="btn btn-primary col-xs-12" disabled>TWIET</button>
        </form>
         
      </div>
        
      <!-- 画面右側 -->
      <div class="col-md-8 content-margin-top">
        <div class="timeline-centered">
        <?php  
                foreach ($post_datas as $post_each){?> 
          <article class="timeline-entry">
              
              <div class="timeline-entry-inner">

                
                  
              
                  <div class="timeline-icon bg-success">
                    <i class="fa fa-user" aria-hidden="true"></i>
                  </div>
                  <div class="timeline-label">
                      
                      
                      
                      <h2><a href="#"><?php echo $post_each['nickname'] . '<br>'; ?></a><span><a href="#"><?php echo $post_each['comment'] . '<br>';?></a></span></h2>
                     <?php
                        // 一旦日時型に変換(STRING型からDateTime型)
                         $created = strtotime($post_each['created']);
                        // 書式を変換
                        $created = date('Y-m-d',$created);
                     ?>
                       
                       <p><?php echo $created; ?></p>
                      <!-- <p>つぶやいたコメント</p> -->
                  </div> 
              </div>

          </article>
            <?php } ?>

          <article class="timeline-entry begin">
              <div class="timeline-entry-inner">
                  <div class="timeline-icon" style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);">
                      <i class="entypo-flight"></i> +
                  </div>
              </div>
          </article>
        </div>
      </div>

    </div>
  </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/form.js"></script>
</body>
</html>



