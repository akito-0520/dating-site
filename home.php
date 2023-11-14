<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ホーム画面</title>
    <link rel="stylesheet" href="home.css">
</head>

<?php
session_start(); // セッションを開始する

setcookie("boolean", "true", time() + 500, "/"); // クッキーを設定する
?>

<body>
    <div class="container">
        <h1>ホーム画面</h1>
        <p><?php
            echo ("ID: " . $_SESSION['User_id'] . " でログインしています。<br><br>");
            ?></p>
        <a href="login.html" class="button">ログアウトする</a><br>
        <a href="matc.php" class="button">相手を探す</a><br>
        <a href="hobby_sele.php" class="button">趣味を選び直す</a><br>
        <a href="statistical.php" class="button">趣味の統計データ</a><br>
        <a href="talk_list.php" class="button">チャット</a><br>
    </div>
</body>

</html>