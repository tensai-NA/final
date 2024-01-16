<?php session_start(); ?>
<?php require 'db-connect.php' ?>

<?php
$pdo=new PDO($connect,USER,PASS);
if(isset($_POST['add'])){
    $sql=$pdo->prepare("insert into watch value (null,?,?,?,current_date,null)");
    $sql->execute([$_POST['title'], $_POST['zyanru'],$_POST['memo']]);

}else if(isset($_POST['up'])){
     $sql=$pdo->prepare("update watch set name=?,genre_code=?,memo=?,koshinbi=current_date where watch_id=?");
     $sql->execute([$_POST['title'], $_POST['zyanru'],$_POST['memo'],$_POST['id']]);

}else if(isset($_POST['del'])){
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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">

    <title>ウォッチリスト</title>
</head>
<body>
    <div class="m-6 has-text-centered is-family-code has-text-weight-semibold">
    <p class="title is-3 ">ウォッチリスト</p>
    <div class="m-4 has-text-centered"><a href="favorite.php">お気に入り</a></div>
    <div class="m-4 has-text-centered"><a href="genre.php">ジャンル管理</a></div>
    <hr>

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
    <div class="tuika mt-3">新規追加</div>
    <div class="b">

    <div class="field">
    <form action="" method="post">
    <div class="field has-addons-fullwidth has-addons-centered">
        <input type="text" class="input is-small is-focused m-2" placeholder="アニメタイトル" name="title" style="width: 200px;" require>
    </div>
        <div class="select is-small mb-2">
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
        </select></div>
        <div class="field has-addons-fullwidth has-addons-centered">
            <input type="text" class="input is-small is-primary is-focused m-2" placeholder="備考" name="memo" style="width: 200px;" require>
        </div></div>
            <button type="submit" class="button m-2 is-small is-warning is-light is-outlined" name="add">追加</button>
        </form>
        </div>
    </div></div>

    <table class="table is-striped" align="center">
        <tr><th>タイトル</th><th>ジャンル</th><th>備考</th><th>登録日</th><th>更新日</th></tr>
        <div class="field">
        <?php
            $pdo=new PDO($connect,USER,PASS);
            $watch=$pdo->query("select watch_id,name,genre_code,memo,torokubi,koshinbi
             from watch");
                foreach($watch as $row){
        ?>

            <tr>
            <form action="" method="post">
                <input type="hidden" name="id" value="<?= $row['watch_id']?>">
                <td><input type="text" class="input is-normal" name="title" value="<?= $row['name']?>" require></td>
                <td><input type="number" class="input is-normal" min=1 max=7 name="zyanru" value="<?= $row['genre_code']?>" require></td>
                <td><input type="text" class="input is-normal" name="memo" value="<?= $row['memo']?>" require></td>
                <td><?= $row['torokubi'] ?></td>
                <td><?= $row['koshinbi'] ?></td>
                <td><button class="button is-small is-danger is-inverted" type="submit" name="fav">
                    <i class="fas fa-heart"></i>
                </button>
                <button class="button is-small is-link is-light is-outlined" type="submit" name="up">更新</button>
                <button class="button is-small is-warning is-light is-outlined" type="submit" name="del">削除</button></td>
            </form>
            </tr>
                <?php
                }
        ?>
        </div>    
    </table>
    </div>
</body>
</html>