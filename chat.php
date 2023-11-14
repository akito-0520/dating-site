<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>チャット</title>
    <link rel="stylesheet" href="chat.css">
</head>

<?php
require_once("function.php"); // ファイルを取り込む

session_start(); // セッションを開始する

// セッションの値を代入
$User_id = $_SESSION['User_id'];
$Recipient_id = $_SESSION['Recipient_id'];
$num = 10; //初期値の設定

if (isset($_POST['recipient'])) {

    $_SESSION['Recipient_id'] = $_POST['recipient']; // 受け取った値をセッションに代入

} else if (isset($_POST['num'])) {

    $num = $_POST['num'] + 10; // 受け取った値を加算

} else if (isset($_POST['block'])) {

    $block = $_POST['block']; //受け取った値を変数に代入

    block_add($User_id, $Recipient_id, $block); //ブロックの情報を変更する関数
}

$Recipient_id = $_SESSION['Recipient_id']; //セッションの値を変数に代入

$mess = mess_indi($User_id, $Recipient_id, $num); // メッセージを取得するための関数

$block = block_conf($User_id, $Recipient_id); // ブロックしているか確認する関数
?>

<body>
    <div class="container">
        <a href="talk_list.php"><button>トーク一覧へ移動</button></a>
        <?php
        if ($block == 1) {
            echo ("ブロックしています");
        } ?>

        <p><?php echo "最近のメッセージ" . $num . "件を表示します。"; ?></p>

        <div class="scroll-container">
            <?php
            for ($i = 0; $i < count($mess); $i++) {
                if ($mess[$i]['Display'] == 1) { // 送信取消されていない
                    echo "<p>送信者：" . $mess[$i]['UserName']  . "／時間：" . $mess[$i]['Timestamp'] . "／メッセージID：" . $mess[$i]['Chat_id'] . "<br>" . nl2br($mess[$i]['Content']) . "</p>";
                } else {
                    echo "<p>送信者：" . $mess[$i]['UserName'] . "／時間：" . $mess[$i]['Timestamp'] . "／メッセージID：" . $mess[$i]['Chat_id'] . "<br>送信は取り消されました。<br>" . "</p>";
                }
            }
            ?>
        </div>

        <script>
            function confirm_test() { // 確認するダイアログを表示
                if (func.key.value == 'block') { // 押されたボタンがblockかを確認
                    var res = confirm("本当にブロックしますか？");
                    if (res == true) {
                        alert("ブロックしました");
                        return res; // trueを返す
                    } else {
                        return res; // falseを返す
                    }
                } else {
                    return true; // trueを返す
                }
            }
        </script>

        <?php
        if ($block == 0) { // ブロックしていない
        ?>
            <form name="func" action="chat.php" method="post" onsubmit="return confirm_test()">
                <button type="submit" name="num" value="<?php echo $num; ?>" onclick="func.key.value='num'">表示する件数を増やす</button>
                <button type="submit" name="block" value=1 onclick="func.key.value='block'">ブロックする</button>
                <input name="key" type="hidden" value="" />
            </form>
        <?php
        } else {
        ?>
            <form action="chat.php" method="post">
                <button type="submit" name="num" value="<?php echo $num; ?>">表示する件数を増やす</button>
                <button type="submit" name="block" value=0>ブロックを解除する</button>
            </form>
        <?php
        }
        ?>

        <p>送信する内容</p>
        <form action="chat_s.php" method="post">
            <input type="text" id="Content" name="Content" required>
            <input type="submit" value="送信">
        </form>

        <p>取り消しするメッセージID</p>
        <form action="chat_s.php" method="post">
            <input type="text" id="Delete" name="Delete" required>
            <input type="submit" value="送信">
        </form>
    </div>
</body>

</html>