<?php session_start(); ?>
<?php require 'db-connect.php' ?>
<?php
$pdo=new PDO($connect,USER,PASS);

if(isset($_POST['del'])){
    $sql=$pdo->prepare("delete from favorite where fav_id=?");
    $sql->execute([$_POST['favo']]);

}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お気に入り</title>
</head>
<body>
    <h1>お気に入りリスト</h1>
    <a href="main.php">ホーム</a>
    <table>
    <tr><th>タイトル</th><th>ジャンル</th><th>備考</th></tr>
    <form action="" method="post">
    <?php
    $pdo=new PDO($connect,USER,PASS);
    $fav=$pdo->query("select fav_id,name,genre.genre_name,memo 
    from watch,genre,favorite
    where watch.watch_id = favorite.watch_id
    and watch.genre_code = genre.genre_code");
    foreach($fav as $row){
        echo '<tr>';
        echo '<td>',$row['fav_id'],'</td>';
        echo '<input type="hidden" name="favo" value="',$row['fav_id'],'">';
        echo '<td>',$row['name'],'</td>';
        echo '<td>',$row['genre_name'],'</td>';
        echo '<td>',$row['memo'],'</td>';
        echo '<td><button type="submit" name="del">削除</button></td>';
        echo '</tr>';
    }
    ?>
    </form>
    </table>
</body>
</html>