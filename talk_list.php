<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>チャット</title>
    <link rel="stylesheet" href="talk_list.css">
</head>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("function.php"); // ファイルを取り込む

session_start(); // セッションを開始する

// 変数,配列を初期化
$Recipient_id = [];
$Recipient_name = [];
$count = 0;

$Recipient_id = friend_indi($_SESSION['User_id']); // ユーザーの友達情報を取得する関数

for ($i = 0; $i < count($Recipient_id); $i++) {

    $recipient = user_indi($Recipient_id[$i]); // ユーザー情報を取得する関数

    $Recipient_name[] = $recipient['UserName'];
}
?>

<body>
    <div class="container">
        <a href="home.php" class="back-button">ホーム画面に戻る</a>

        <h1>みんなと仲良くなろう！</h1>

        <form action="chat.php" method="post">
            <?php for ($i = 0; $i < count($Recipient_id); $i++) { ?>
                <div class="button-container">
                    <button class="match-button" type="submit" name="recipient" value="<?php echo $Recipient_id[$i]; ?>">
                        <?php echo $Recipient_name[$i]; ?>
                    </button>
                </div>
                <br>
            <?php } ?>
        </form>
    </div>
</body>


</html>