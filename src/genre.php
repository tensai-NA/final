<?php session_start(); ?>
<?php require 'db-connect.php' ?>

<?php
        $pdo=new PDO($connect,USER,PASS);
if(isset($_POST['up'])){
    $sql=$pdo->prepare("update genre set genre_name=? where genre_code=?");
    $sql->execute([$_POST['genre'],$_POST['code']]);
}else if(isset($_POST['add'])){
    $tui=$pdo->prepare("insert into genre value (null,?)");
    $tui->execute([$_POST['zyanru']]);
}else if(isset($_POST['del'])){
    $sql=$pdo->prepare("delete from genre where genre_code=?");
    $sql->execute([$_POST['code']]);
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <title>ジャンル編集</title>
</head>
<body>
    <div class="m-6 has-text-centered is-family-code has-text-weight-semibold">
    <p class="title is-3 ">ジャンル編集</p>
    <div class="m-4 has-text-centered"><a href="main.php">ホーム</a></div>
    <hr>

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
    <form action="" method="post">
        <input type="text" class="input is-small is-warning is-focused" placeholder="ジャンル名" name="zyanru" style="width: 200px;" require>
        <p><button type="submit" class="button m-2 is-small is-warning is-light is-outlined" name="add">追加</button></p>
    </form>
    </div>

    <form action="" method="post">
    <table class="table is-striped" align="center">
    <tr><th>ジャンルコード</th><th>ジャンル名</th>

    <?php
        $pdo=new PDO($connect,USER,PASS);
        $genre=$pdo->query("select * from genre");
        foreach($genre as $row){
            echo '<tr>';
            echo '<td>',$row['genre_code'],'</td>';
        ?>
        <input type="hidden" name="code" value="<?= $row['genre_code']?>">
        <td><input type="text" class="input is-normal" name="genre" value="<?= $row['genre_name']?>" require></td>
        <td><button class="button is-small is-link is-light is-outlined" type="submit" name="up">更新</button>
        <button class="button is-small is-danger is-light is-outlined" type="submit" name="del">削除</button></td>
        </tr>
    <?php
        }
    ?>
</form>
</table>
    </div>
</body>
</html>