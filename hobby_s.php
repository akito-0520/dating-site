<?php
require_once("function.php"); // ファイルを取り込む

session_start(); // セッションを開始する

if (isset($_POST['hobby_id'])) {
    $hobby_id = $_POST['hobby_id']; // 受け取った値を代入
    $User_id = $_SESSION['User_id']; // セッションの値を代入

    try {
        $count = dupli_hobby_conf($User_id); //重複カラムの存在を確認する関数

        if ($count > 0) { // 重複カラムが存在した
            hobby_dele($User_id); // 趣味を削除する関数(既存の趣味を削除)
        }

        hobby_add($User_id, $hobby_id); // 趣味を追加する関数

        echo ("趣味を登録しました。");

        // ページを遷移する
        header("Refresh: 1; home.php");
        exit();
    } catch (Exception $e) {
        // 例外発生時はエラーメッセージを出力する
        print($e->getMessage() . "<br>");
    }
}
