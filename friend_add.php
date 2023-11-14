<?php
require_once("function.php"); // ファイルを取り込む

session_start(); // セッションを開始する

if (isset($_POST["User_id"])) {
    $User_id = $_SESSION['User_id']; // セッションの値を代入
    $Recipient_id = $_POST["User_id"]; // 受け取った値を代入
    try {
        $result = friend_conf($User_id, $Recipient_id);

        if ($result == false) { // まだ友達じゃない場合
            friend_add(); // 友達情報を追加する関数

            echo ("友達登録しました！早速話してみましょう！");

            // ページを遷移する
            header("Refresh: 1; talk_list.php");
            exit();
        } else {
            echo ("すでに友達です。");
            header("Refresh: 1; talk_list.php");
            exit();
        }
    } catch (Exception $e) {
        print($e->getMessage() . "<br>"); // 例外発生時はエラーメッセージを出力する
    }
}
