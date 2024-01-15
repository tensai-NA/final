<?php session_start(); ?>
<?php require 'db-connect.php' ?>

<?php
$pdo=new PDO($connect,USER,PASS);
if(isset($_POST['add'])){   // 追加ボタンが押された処理
    $sql=$pdo->prepare("insert into watch value (null,?,?,?,current_date,null)");
    $sql->execute([$_POST['title'], $_POST['zyanru'],$_POST['memo']]);

}else if(isset($_POST['up'])){  //　更新ボタンが押された処理
     $sql=$pdo->prepare("update watch set name=?,genre_code=?,memo=?,koshinbi=current_date where watch_id=?");
     $sql->execute([$_POST['title'], $_POST['zyanru'],$_POST['memo'],$_POST['id']]);
}else if(isset($_POST['del'])){ //削除ボタンが押された処理 カートに入っていたらけせないようにする(ituka)
     $sql=$pdo->prepare("delete from watch where watch_id=?");
     $sql->execute([$_POST['id']]);
}else if(isset($_POST['fav'])){
    $han=$pdo->prepare("select watch_id from favorite where watch_id = ?");
    $han->execute([$_POST['id']]);
    $count = $han->rowCount();
    if($count > 0){
        echo '<script>
        alert("すでにお気に入りに追加されています");
       </script>';
    }else{
        $sql=$pdo->prepare("insert into favorite value(null,?)");
        $sql->execute([$_POST['id']]);
        echo '<script>
        alert("お気に入りに追加しました");
        </script>';
    }
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ウォッチリスト</title>
</head>
<body>
    <h1>ウォッチリスト</h1>
    <!-- tuika -->
    <script src="https://code.jquery.com/jquery.min.js"></script>
    <script>
    $(function() {
        $(".tuika").click(function() {
            $(".b").slideToggle("");
        });
    });
    </script>
    <style>
        .b{
            display: none;
        }
    </style>
    <div class="tuika">新規追加</div>
    <div class="b">
    <form action="" method="post">
        <p><input type="text" placeholder="アニメタイトル" name="title" require></p>
        <select name="zyanru">
            <?php
                $pdo=new PDO($connect,USER,PASS);
                $zya=$pdo->query("select * from genre");
                foreach($zya as $row){
                    echo '<option value="',$row['genre_code'],'">';
                    echo $row['genre_code'],"：";
                    echo $row['genre_name'];
                    echo '</option>';
                }
            ?>
        </select>
        <p><input type="text" placeholder="備考" name="memo" require></p>
        <button type="submit" name="add">追加</button>
        </form>
    </div>

    
    <a href="favorite.php">お気に入り</a>
    <hr>
    <table>
        <tr><th>タイトル</th><th>ジャンル</th><th>備考</th><th>登録日</th><th>更新日</th></tr>
        <?php
            $pdo=new PDO($connect,USER,PASS);
            $watch=$pdo->query("select watch_id,name,genre_code,memo,torokubi,koshinbi
             from watch");
                foreach($watch as $row){
        ?>

            <tr>
            <form action="" method="post">
                <input type="hidden" name="id" value="<?= $row['watch_id']?>">
                <td><input type="text" name="title" value="<?= $row['name']?>" require></td>
                <td><input type="number" min=1 max=7 name="zyanru" value="<?= $row['genre_code']?>" require></td>
                <td><input type="text" name="memo" value="<?= $row['memo']?>" require></td>
                <td><?= $row['torokubi'] ?></td>
                <td><?= $row['koshinbi'] ?></td>
                <td><button type="submit" name="fav">♥</button></td>
                <td><button type="submit" name="up">更新</button>
                <button type="submit" name="del">削除</button></td>
            </form>
            </tr>
                <?php
                }
        ?>
    </table>
</body>
</html>