<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>いい人が見つかるかな....</title>
    <link rel="stylesheet" href="matc.css">
    <script src="matc.js"></script>
</head>

<?php
require_once("function.php"); // ファイルを取り込む

session_start(); // セッションを開始する

// 変数,配列の初期化
$hobby_id = [];
$Recipient = [];
$matc_count = 0;
$user_id_count = [];
$user_id = [];
$user_info = [];

$User_id = $_SESSION["User_id"]; // セッションの値を代入

if (isset($_POST['jsonData'])) { // JSONファイルを受け取った時
    $user_info = json_decode($_POST['jsonData'], true); // JSONファイルをデコード
    $matc_count = $user_info['matc_count'] + 1;
    $user_info['matc_count'] = $matc_count;

    if ($matc_count == 5) {
        // ページを遷移する
        header("Refresh: 0; matc.php");
        exit();
    }
} else {
    $user_info['matc_count'] = $matc_count;

    $hobby_id = user_hobbyid_indi($User_id); // 趣味IDを取得する関数

    $sex = user_sex_indi($User_id); // ユーザーの性別を取得する関数

    $user_id = same_hobby_indi($User_id, $sex, $hobby_id); // ユーザーの趣味と等しいユーザのIDを取得する関数

    $user_id_count = array_count_values($user_id); // 配列の重複している値をカウント

    $user_id = []; //ユーザーID配列の初期化

    foreach ($user_id_count as $key => $value) {
        $user_id[] = $key;
    }

    $user_info['user_id_count'] = $user_id_count;

    for ($i = 0; $i < 5; $i++) {
        $user[$i] = user_indi($user_id[$i]); // ユーザー情報を取得する関数
    }
    $user_info['user'] = $user;
}

$array_encode = json_encode($user_info); //user_info配列をエンコード

?>

<body>
    <div class="container">
        <a href="home.php" class="back-link">戻る</a>
        <h1>マッチング画面</h1>
        <div class="user-info">
            <p>名前：<?php echo $user_info['user'][$matc_count]['UserName']; ?></p>
            <p>年齢：<?php echo $user_info['user'][$matc_count]['age']; ?></p>
            <p>居住地：<?php echo $user_info['user'][$matc_count]['location']; ?></p>
            <p>マッチ度：<?php echo (20 * $user_info['user_id_count'][$user_info['user'][$matc_count]['User_id']] . "％です！") ?>
            </p>
        </div>
        <div class="action-buttons">
            <form action="friend_add.php" method="POST">
                <button class="button-like" type="submit" name="User_id" value="<?php echo $user_info['user'][$matc_count]['User_id']; ?>">LIKE</button>
            </form>
            <form action="matc.php" method="POST">
                <input type="hidden" name="jsonData" value="<?php echo htmlspecialchars($array_encode); ?>">
                <button class="button-nope" type="submit">NOPE</button>
            </form>
        </div>
    </div>
</body>

</html>