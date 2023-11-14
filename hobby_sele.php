<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>趣味を選ぼう!!</title>
    <script src="hobby_sele.js"></script>
    <link rel="stylesheet" href="hobby_sele.css">
</head>

<?php
require_once("function.php"); // ファイルを取り込む

session_start(); // セッションを開始する

// 配列の初期化
$hobbyName = [];
$hobby_id = [];

$result = hobby_indi(); // 趣味の一覧データを取得する関数

for ($i = 0; $i < count($result); $i++) { // 一覧データをそれぞれの配列に代入
    $hobbyName[] = $result[$i]['hobby_name']; // 趣味名の配列
    $hobby_id[] = $result[$i]['hobby_id']; // 趣味IDの配列
}

?>

<body>
    <?php
    if ($_COOKIE['boolean'] == "true") { ?>
        <button onclick="location.href='home.php'" class="button-back">ホーム画面に戻る</button>
    <?php
    } ?>
    <h1>趣味を1〜5個選びましょう！！</h1>

    <!-- 趣味のチェックボックスを表示する -->
    <form action="hobby_s.php" method="post">
        <?php for ($i = 0; $i < count($result); $i++) { ?>
            <?php if ($i != 0 && $i % 5 == 0) {
            ?>
                <p><br></p>
            <?php } ?>
            <input type="checkbox" name="hobby_id[]" onchange="checkLimit()" value="<?php echo $hobby_id[$i]; ?>"><?php echo $hobbyName[$i]; ?></input>
        <?php } ?>
        <button type="submit" value="送信">送信</button>
    </form>
</body>

</html>