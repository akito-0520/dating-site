<?php
require_once("function.php"); // ファイルを取り込む

session_start(); // セッションを開始する

// セッションの値を代入
$User_id = $_SESSION['User_id'];
$Recipient_id = $_SESSION['Recipient_id'];

if (!empty($_POST["Content"])) {
    try {
        // 受け取った値を代入
        $Content = $_POST["Content"];

        $block = block_conf($Recipient_id, $User_id,); // 送信者側がブロックされているか確認する関数

        if ($block == 0) { // ブロックされていなければ

            mess_add($User_id, $Recipient_id, $Content); // メッセージを送信する関数

            // ページを遷移する
            header("Refresh: 0; chat.php");
            exit();
        } else {
?>
            <script type="text/javascript">
                alert("相手ユーザーにブロックされているため、メッセージを送信できません。");
            </script>
<?php
            // ページを遷移する
            header("Refresh: 0; chat.php");
            exit();
        }
    } catch (Exception $e) {
        // 例外発生時はエラーメッセージを出力する
        print($e->getMessage() . "<br>");
    }
}

if (!empty($_POST["Delete"])) {
    try {
        // 受け取った値の代入
        $Chat_id = $_POST["Delete"];

        $conf = mess_dele($User_id, $Chat_id); // メッセージを削除する関数

        if ($conf == 0) { //メッセージ削除ができた時
            // ページを遷移する
            header("Refresh: 0; chat.php"); // ページを遷移する
            exit();
        } else { // メッセージ削除ができなかった時
            echo ("送信者ではないため取り消せません");
            header("Refresh: 2; chat.php"); // ページを遷移する
            exit();
        }
    } catch (Exception $e) {
        // 例外発生時はエラーメッセージを出力する
        print($e->getMessage() . "<br>");
    }
}
?>