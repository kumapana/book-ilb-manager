<?php
  function h($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  }
?>


<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>絞り込み</title>
    <link rel=stylesheet href="main.css" type="text/css" charset="utf-8" />
    <script src="jq/jquery-3.2.1.js" type="text/javascript"></script>
    <script src="jq/jquery.tablesorter.min.js" type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function(){
          $("#booksTable").tablesorter();
        }
      );
    </script>
    </head>
  <body>
    <?php
    try{
      require_once 'DbManager.php';
      $db = getDb();
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }catch(PDOException $Exception){
      die('接続エラー：' .$Exception->getMessage());
    }
    try{
      $column = $_GET['column'];
      $char = $_GET['text'];
      $sql = "SELECT * FROM 一般 WHERE $column LIKE '%$char%'";
      $stmh = $db->prepare($sql);
      $stmh->execute();
    }catch(Exception $Exception){
      echo '検索エラー：' .$Exception->getMessage();
      die();
    }
    ?>
    <h1>絞り込み <?php echo $h($_GET['column']).": ".$h($_GET['text']) ?></h1>
    <table class="books">
      <thead>
        <tr>
          <th>タイトル</th>
          <th>著者</th>
          <th>イラスト</th>
          <th>出版</th>
          <th>種別</th>
        </tr>
      </thead>
      <tbody>
        <?php
          while($row = $stmh->fetch(PDO::FETCH_ASSOC)){
            ?>
              <tr>
                <td><a href="edit.php?id=<?=$h($row['蔵書番号'])?>"><?=$h($row['タイトル'])?></a></th>
          		  <td><a href="refine.php?column=著者&text=<?=$h($row['著者'])?>"><?=$h($row['著者'])?></a></th>
          		  <td><a href="refine.php?column=イラスト&text=<?=$h($row['イラスト'])?>"><?=$h($row['イラスト'])?></a></th>
          		  <td><a href="refine.php?column=出版&text=<?=$h(['出版'])?>"><?=$h($row['出版'])?></a></th>
          		  <td><a href="refine.php?column=種別&text=<?=$h($row['種別'])?>"><?=$h($row['種別'])?></a></th>
              </tr>
              <?php
          }
          $db = null;
        ?>
      </tbody>
    </table>
    <a href="./">一覧に戻る</a>
  </body>
</html>
