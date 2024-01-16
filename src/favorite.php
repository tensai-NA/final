<?php session_start(); ?>
<?php require 'db-connect.php' ?>
<?php
$pdo=new PDO($connect,USER,PASS);

if(isset($_POST['del'])){
    $sql=$pdo->prepare("delete from favorite where fav_id=?");
    $sql->execute([$_POST['favo']]);
    echo '<script>
    alert("お気に入りから削除しました");
    </script>';

}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">

    <title>お気に入り</title>
</head>
<body>
    <div class="m-6 has-text-centered is-family-code has-text-weight-semibold">
    <p class="title is-3 ">お気に入りリスト</p>
    <div class="m-4 has-text-centered"><a href="main.php">ホーム</a></div>
    <hr>
    <table class="table is-striped" align="center">
    <tr><th>タイトル</th><th>ジャンル</th><th>備考</th></tr>
    <?php
    $pdo=new PDO($connect,USER,PASS);
    $fav=$pdo->query("select fav_id,name,genre.genre_name,memo 
    from favorite,watch,genre
    where favorite.watch_id = watch.watch_id
    and watch.genre_code = genre.genre_code
    order by fav_id asc");
    echo '<form action="" method="post">';
    foreach($fav as $row){
        echo '<tr>';
        echo '<input type="hidden" name="favo" value="',$row['fav_id'],'">';
        echo '<td>',$row['name'],'</td>';
        echo '<td>',$row['genre_name'],'</td>';
        echo '<td>',$row['memo'],'</td>';
        echo '<td><button class="button is-small is-warning is-light is-outlined" type="submit" name="del">削除</button></td>';
        echo '</tr>';
    }
    ?>
    </form>
    </table>
</body>
</html>