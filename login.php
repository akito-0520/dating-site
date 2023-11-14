<?php
require_once("function.php"); // ファイルを取り込む

session_start(); // セッションを開始する

if (isset($_POST["User_id"]) && isset($_POST["Pass"])) {
    $Pass = $_POST["Pass"]; // 受け取った値を代入
    try {
        $result = login($Pass); // ログインできるか確認する関数

        if ($result == 0) { // ログインできた場合
            $_SESSION['User_id'] = $_POST["User_id"]; // セッションに値を代入する

            // ページを遷移する
            header("Refresh: 0; home.php");
            exit();
        } else {
            echo "<br><br>ログインに失敗しました<br>";

            // ページを遷移する
            header("Refresh: 1; login.html");
            exit();
        }
    } catch (Exception $e) {
        print($e->getMessage() . "<br>"); // 例外発生時はエラーメッセージを出力する
    }
}
