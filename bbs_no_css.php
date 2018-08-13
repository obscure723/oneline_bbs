<?php

  const INSERT_POST_SQL = 'INSERT INTO posts (nickname, comment, created) VALUES (:nickname, :comment, now()) ';
  const FIND_ALL_POST_SQL = 'SELECT * FROM posts';

  // DBの接続
  $dsn = 'mysql:dbname=oneline_bbs;host=localhost;port=3307';
  $user = 'root';
  $password = '';
  $dbh = new PDO($dsn, $user, $password, array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
  ));

  $dbh->query('SET NAMES utf8');

  if (!empty($_POST['nickname']) && !empty($_POST['comment'])) {

    $nickname = htmlspecialchars($_POST['nickname']);
    $comment = htmlspecialchars($_POST['comment']);

    // ニックネームとコメントが空でない場合
    insertComment($dbh, $nickname, $comment);
  }

  /**
   * postsテーブルにINSERTする
   */
  function insertComment($dbh, $nickname, $comment) {
    $stmt = $dbh->prepare(INSERT_POST_SQL);
    $stmt->bindParam(':nickname', $nickname, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->execute();
  }

  function findAll($dbh) {
    $stmt = $dbh -> prepare(FIND_ALL_POST_SQL);
    $stmt->execute();
    return $stmt->fetchAll();
  }

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

    <table>
      <thead>
        <th>ID</th>
        <th>ニックネーム</th>
        <th>コメント</th>
        <th>投稿日時</th>
      </thead>
      <tbody>
        
          <?php 
            $posts = findAll($dbh);
            foreach($posts as $post):
          ?>
            <tr>
              <td><?php echo  $post['id']?></td>
              <td><?php echo  $post['nickname']?></td>
              <td><?php echo  $post['comment']?></td>
              <td><?php echo  $post['created']?></td>
              <td><?php echo  $post['id']?></td>
              <td><?php echo  $post['id']?></td>
            </tr>

          <?php endforeach; ?>
        </tr>
      </tbody>
    </table>

</body>
</html>