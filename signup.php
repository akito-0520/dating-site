<?php
require_once("function.php"); // ファイルを取り込む

session_start(); // セッションを開始する

if (
    isset($_POST["User_id"]) && isset($_POST["UserName"]) && isset($_POST["Pass"])
    && isset($_POST["sex"]) && isset($_POST["age"]) && isset($_POST["location"])
) {
    try {
        // 受け取った値の代入
        $User_id = $_POST["User_id"];
        $UserName = $_POST["UserName"];
        $Pass = $_POST["Pass"];
        $sex = $_POST["sex"];
        $age = $_POST["age"];
        $location = $_POST["location"];

        $location = Address($location); // 居住地を取得する関数

        if ($location["results"] == NULL) { // 返り値がNULLなら
            echo "その住所は存在しません。\nもう一度入力してください。";
            header("Refresh: 2; signup.html");
            exit();
        } else {
            $location = $location["results"][0]["address1"] . "," . $location["results"][0]["address2"];

            $result = user_add($User_id, $UserName, $Pass, $sex, $age, $location); // ユーザー情報を追加する関数
        }
        if ($result == true) { // 正常に実行できた場合
            echo "登録しました<br>";
            echo "ID：" . $_POST["User_id"] . "<br>";
            echo "名前：" . $_POST["UserName"] . "<br>";
            echo "パスワード：" . $_POST["Pass"] . "<br>";
            echo "性別：" . $_POST["sex"] . "<br>";
            echo "年齢：" . $_POST["age"] . "<br>";
            echo "居住地：" . $location . "<br>";

            $_SESSION['User_id'] = $User_id; //セッションに変数を代入

            setcookie("boolean", "false", time() + 120, "/"); // クッキーの設定

            // ページを遷移する
            header("Refresh: 2; hobby_sele.php");
            exit();
        } else {
            echo "登録に失敗しました<br>";

            // ページを遷移する
            header("Refresh: 1; index.html");
            exit();
        }
    } catch (Exception $e) {
        echo "IDが被っています。別のIDを指定してください。";

        // ページを遷移する
        header("Refresh: 1; signup.html");
        exit();
    }
}
