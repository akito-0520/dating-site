<?php
function pdo() // データベースへの接続設定
{
    $pdo = new PDO(
        "mysql:host=localhost;dbname=work;",
        "root",
        "",
        [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

    return ($pdo);
}

function login($Pass) // ログインできるか確認する関数
{
    // データベースへの接続設定
    $pdo = pdo();

    // ユーザを取得するSQL文の作成
    $stmt = $pdo->prepare("SELECT * FROM User WHERE User_id=:User_id;");

    // プレースホルダに値をバインド
    $stmt->bindValue(':User_id', $_POST["User_id"], PDO::PARAM_STR);

    // SQL文の実行
    $stmt->execute();

    if ($stmt->rowCount() > 0) { // 取得したユーザー数が1以上
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($Pass, $result["Pass"])) { // 指定したハッシュがパスワードにマッチした場合
            $_SESSION['User_name'] = $result['UserName']; // セッションに値を代入する

            return (0); // 値を返す
        } else {
            return (1); // 値を返す
        }
    } else {
        return (1); // 値を返す
    }
}

function user_add($User_id, $UserName, $Pass, $sex, $age, $location) // ユーザー情報を追加する関数
{
    // データベースへの接続設定
    $pdo = pdo();

    // ユーザー情報を追加するSQL文
    $stmt = $pdo->prepare("INSERT INTO User (User_id, UserName, Pass, sex, age, location) VALUES(:User_id, :UserName, :Pass, :sex, :age, :location);");

    // パスワードのハッシュ化
    $Pass = password_hash($Pass, PASSWORD_DEFAULT);

    // プレースホルダに値をバインド
    $stmt->bindValue(':User_id', $User_id, PDO::PARAM_STR);
    $stmt->bindValue(':UserName', $UserName, PDO::PARAM_STR);
    $stmt->bindValue(':Pass', $Pass, PDO::PARAM_STR);
    $stmt->bindValue(':sex', $sex, PDO::PARAM_STR);
    $stmt->bindValue(':age', $age, PDO::PARAM_INT);
    $stmt->bindValue(':location', $location, PDO::PARAM_STR);

    // SQL文の実行
    $result = $stmt->execute();

    // 変数を返す
    return ($result);
}

function user_indi($User_id) // ユーザー情報を取得する関数
{
    // データベースへの接続設定
    $pdo = pdo();

    // ユーザー情報を取得するSQL文の作成
    $stmt = $pdo->prepare("SELECT * FROM User WHERE User_id= :User_id;");

    // プレースホルダに値をバインド
    $stmt->bindValue(':User_id', $User_id, PDO::PARAM_STR);

    //SQL文の実行
    $stmt->execute();

    //配列の初期化
    $result = [];

    // 受け取った配列を代入
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $result = $row;
    }

    // 配列を返す
    return ($result);
}

function user_id_indi($Sender_id) // ユーザーIDに対応するユーザー名を取得する関数
{
    // データベースへの接続設定
    $pdo = pdo();

    // ユーザーIDに対応するユーザー名を取得するSQL文
    $stmt = $pdo->prepare("SELECT UserName FROM User WHERE User_id=:User_id;");

    // プレースホルダに値をバインド
    $stmt->bindValue(':User_id', $Sender_id, PDO::PARAM_STR);

    // SQL文を実行
    $stmt->execute();

    // 結果を代入
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 変数の値を返す
    return ($result[0]['UserName']);
}

function user_sex_indi($User_id) // ユーザーの性別を取得する関数
{
    // データベースへの接続設定
    $pdo = pdo();

    // ユーザーの性別を取得するSQL文の作成
    $stmt = $pdo->prepare("SELECT sex FROM User WHERE User_id = :User_id");

    // プレースホルダに値をバインド
    $stmt->bindValue(':User_id', $_SESSION['User_id'], PDO::PARAM_STR);

    // SQL文の実行
    $stmt->execute();

    // 結果を代入
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // 変数を返す
    return ($result['sex']);
}

function same_hobby_indi($User_id, $sex, $hobby_id) // ユーザーの趣味と等しいユーザのIDを取得する関数
{
    // 配列の初期化
    $result = [];

    //データベースへの接続設定
    $pdo = pdo();

    // ユーザーの趣味と等しいユーザのIDを取得するSQL文の作成
    $stmt = $pdo->prepare("SELECT User.User_id FROM User,hobby_user 
    WHERE User.User_id = hobby_user.User_id AND hobby_user.hobby_id = :hobby_id 
    AND hobby_user.User_id != :User_id AND User.sex != :sex ORDER BY RAND() LIMIT 10");

    // プレースホルダに値をバインド
    $stmt->bindValue(':User_id', $User_id, PDO::PARAM_STR);
    $stmt->bindValue(':sex', $sex, PDO::PARAM_STR);

    for ($i = 0; $i < count($hobby_id); $i++) {
        // プレースホルダに値をバインド
        $stmt->bindValue(':hobby_id', $hobby_id[$i], PDO::PARAM_STR);

        // SQL文の実行
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row['User_id']; // 受け取った値を代入
        }
    }

    // 配列を返す
    return ($result);
}

function mess_indi($User_id, $Recipient_id, $num) // メッセージを取得するための関数
{
    // データベースへの接続設定
    $pdo = pdo();

    // メッセージを取得するためのSQL文
    $stmt = $pdo->prepare("SELECT * FROM Message WHERE (Sender_id = :Sender1_id AND Recipient_id = :Recipient1_id) OR (Sender_id = :Sender2_id AND Recipient_id = :Recipient2_id) ORDER BY Timestamp DESC LIMIT :num");

    // プレースホルダに値をバインド
    $stmt->bindValue(':Sender1_id', $User_id, PDO::PARAM_STR);
    $stmt->bindValue(':Recipient1_id', $Recipient_id, PDO::PARAM_STR);
    $stmt->bindValue(':Sender2_id', $Recipient_id, PDO::PARAM_STR);
    $stmt->bindValue(':Recipient2_id', $User_id, PDO::PARAM_STR);
    $stmt->bindValue(':num', $num, PDO::PARAM_STR);

    // SQL文を実行
    $stmt->execute();

    // 受け取った値を代入
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // メッセージの送信者に対応するユーザー名を新しく追加
    for ($i = 0; $i < count($result); $i++) {
        // ユーザーIDに対応するユーザー名を取得する関数の返り値を追加
        $result[$i]['UserName'] = user_id_indi($result[$i]['Sender_id']);
    }

    // 配列の値を返す
    return ($result);
}

function mess_add($User_id, $Recipient_id, $Content) // メッセージ追加の関数
{
    // データベースへの接続設定
    $pdo = pdo();

    // メッセージの追加のためのSQL文の作成
    $stmt = $pdo->prepare("INSERT INTO Message (Sender_id, Recipient_id, Content) VALUES(:Sender_id, :Recipient_id, :Content);");

    // プレースホルダに値をバインド
    $stmt->bindValue(':Sender_id', $User_id, PDO::PARAM_STR);
    $stmt->bindValue(':Recipient_id', $Recipient_id, PDO::PARAM_STR);
    $stmt->bindValue(':Content', $Content, PDO::PARAM_STR);

    // SQL文を実行
    $stmt->execute();
}

function mess_dele($User_id, $Chat_id) // メッセージを非表示にする関数
{
    // データベースへの接続設定
    $pdo = pdo();

    //指定したメッセージIDの送信者とUser_idが一致しているか確認するためのSQL文の作成
    $stmt = $pdo->prepare("SELECT Sender_id FROM Message WHERE Chat_id = :Chat_id");

    // プレースホルダに値をバインド
    $stmt->bindValue(':Chat_id', $Chat_id, PDO::PARAM_STR);

    // SQL文を実行
    $stmt->execute();

    // 取得したIDを代入
    $Sender_id = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($Sender_id[0]['Sender_id'] == $User_id) { // 指定したメッセージIDの送信者とUser_idが一致

        // メッセージの削除のためのSQL文の作成
        $stmt = $pdo->prepare("UPDATE Message SET Display = 0 WHERE Chat_id = :Chat_id");

        // プレースホルダに値をバインド
        $stmt->bindValue(':Chat_id', $Chat_id, PDO::PARAM_STR);

        // SQL文を実行
        $stmt->execute();

        //返り値を返す
        return (0);
    } else {
        //返り値を返す
        return (1);
    }
}

function block_add($User_id, $Recipient_id, $block) // ブロックしている情報を変更する関数
{
    // データベースへの接続設定
    $pdo = pdo();

    // ブロックしている情報を変更するSQL文の作成
    $stmt = $pdo->prepare("UPDATE friend SET block = :block WHERE User_id = :User_id AND Recipient_id = :Recipient_id;");

    // プレースホルダに値をバインド
    $stmt->bindValue(':User_id', $User_id, PDO::PARAM_STR);
    $stmt->bindValue(':Recipient_id', $Recipient_id, PDO::PARAM_STR);
    $stmt->bindValue(':block', $block, PDO::PARAM_STR);

    // SQL文の実行
    $stmt->execute();
}

function block_conf($User_id, $Recipient_id) // ブロックされているかを確認する関数
{
    // データベースへの接続設定
    $pdo = pdo();

    // ブロックされているかの確認のSQL文の作成
    $stmt = $pdo->prepare("SELECT block FROM friend WHERE User_id = :User_id AND Recipient_id = :Recipient_id;");

    // プレースホルダに値をバインド
    $stmt->bindValue(':User_id', $User_id, PDO::PARAM_STR);
    $stmt->bindValue(':Recipient_id', $Recipient_id, PDO::PARAM_STR);

    // SQL文を実行
    $stmt->execute();

    // 結果を代入する
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 変数の値を返す
    return ($result[0]['block']);
}

function friend_indi($User_id) // ユーザーの友達情報を取得する関数
{
    // データベースへの接続設定
    $pdo = pdo();

    // ユーザーの友達情報を取得するSQL文の作成
    $stmt = $pdo->prepare("SELECT * FROM friend WHERE User_id = :User_id");

    // プレースホルダに値をバインド
    $stmt->bindValue(':User_id', $User_id, PDO::PARAM_STR);

    // SQL文の実行
    $stmt->execute();

    // 実行結果を代入
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $result[] = $row['Recipient_id'];
    }

    // 配列を返す
    return ($result);
}
function friend_conf($User_id, $Recipient_id) // 友達情報が既存するかを確認する関数
{
    // データベースへの接続設定
    $pdo = pdo();

    // 友達情報が既存するかを確認するSQL文
    $stmt = $pdo->prepare("SELECT User_id FROM friend WHERE User_id = :User_id AND Recipient_id=:Recipient_id;");

    // プレースホルダに値をバインド
    $stmt->bindValue(':User_id', $User_id, PDO::PARAM_STR);
    $stmt->bindValue(':Recipient_id', $Recipient_id, PDO::PARAM_STR);

    // SQL文の実行
    $stmt->execute();

    // 結果を代入する
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 変数の値を返す
    return ($result);
}

function friend_add() //友達情報を追加する関数
{
    //データベースへの接続設定
    $pdo = pdo();

    // 友達情報を追加するSQL文
    $stmt = $pdo->prepare("INSERT INTO friend (User_id, Recipient_id) VALUES(:User_id, :Recipient_id);");

    // プレースホルダに値をバインド
    $stmt->bindValue(':User_id', $_SESSION["User_id"], PDO::PARAM_STR);
    $stmt->bindValue(':Recipient_id', $_POST["User_id"], PDO::PARAM_STR);

    // SQL文の実行
    $stmt->execute();

    // プレースホルダに値(逆)をバインド
    $stmt->bindValue(':User_id', $_POST["User_id"], PDO::PARAM_STR);
    $stmt->bindValue(':Recipient_id', $_SESSION["User_id"], PDO::PARAM_STR);

    // SQL文の実行
    $stmt->execute();
}

function dupli_hobby_conf($User_id) //重複カラムの存在を確認する関数
{
    // データベースへの接続設定
    $pdo = pdo();

    // 重複カラムの存在を確認するSQL文の作成
    $stmt = $pdo->prepare("SELECT COUNT(User_id) AS count FROM hobby_user WHERE User_id=:User_id");

    // プレースホルダに値をバインド
    $stmt->bindValue(':User_id', $User_id, PDO::PARAM_STR);

    // SQL文の実行
    $stmt->execute();

    // 結果を代入する
    $result = $stmt->fetchColumn();

    // 変数の値を返す
    return ($result);
}

function user_hobbyid_indi($User_id) // 趣味IDを取得する関数
{
    // 配列の初期化
    $result = [];

    // データベースへの接続設定
    $pdo = pdo();

    // 利用者の趣味IDを取得するSQL文の作成
    $stmt = $pdo->prepare("SELECT hobby_id FROM hobby_user WHERE User_id= :User_id;");

    // プレースホルダに値をバインド
    $stmt->bindValue(':User_id', $User_id, PDO::PARAM_STR);

    // SQL文の実行
    $stmt->execute();

    // 受け取った値を代入
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $result[] = $row['hobby_id'];
    }

    // 配列の値を返す
    return ($result);
}

function hobby_sum_indi() // 趣味の統計データを取得する関数
{
    // データベースへの接続設定
    $pdo = pdo();

    // 趣味の統計データを取得するSQL文の作成
    $stmt = $pdo->prepare("SELECT hobby_name, COUNT(hobby_user.hobby_id) FROM hobby_user,hobby 
WHERE hobby_user.hobby_id = hobby.hobby_id GROUP BY hobby_user.hobby_id ORDER BY COUNT(hobby_user.hobby_id) DESC LIMIT 8;");

    // SQL文の実行
    $stmt->execute();

    // 実行結果を代入
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 配列を返す
    return ($result);
}

function hobby_sex_indi($sex) // 趣味の性別別の統計データを取得する関数
{
    // データベースへの接続設定
    $pdo = pdo();

    // 趣味の性別別の統計データを取得するSQL文の作成
    $stmt = $pdo->prepare("SELECT hobby_name, COUNT(hobby_user.hobby_id) FROM hobby_user,hobby,User 
   WHERE hobby_user.hobby_id = hobby.hobby_id AND hobby_user.User_id = User.User_id AND User.sex = :sex 
   GROUP BY hobby_user.hobby_id ORDER BY COUNT(hobby_user.hobby_id) DESC LIMIT 8;");

    // プレースホルダに値をバインド
    $stmt->bindValue(':sex', $sex, PDO::PARAM_STR);

    // SQL文の実行
    $stmt->execute();

    // 実行結果を代入
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 配列を返す
    return ($result);
}

function hobby_dele($User_id) // 趣味を削除する関数
{
    // データベースへの接続設定
    $pdo = pdo();

    // 趣味を削除するSQL文の作成
    $stmt = $pdo->prepare("DELETE FROM hobby_user WHERE User_id = :User_id");

    // プレースホルダに値をバインド
    $stmt->bindValue(':User_id', $User_id, PDO::PARAM_STR);

    // SQL文を実行
    $stmt->execute();
}

function hobby_add($User_id, $hobby_id) // 趣味を追加する関数
{
    // データベースへの接続設定
    $pdo = pdo();

    // 趣味を追加するSQL文の作成
    $stmt = $pdo->prepare("INSERT INTO hobby_user (User_id, hobby_id) VALUES(:User_id, :hobby_id);");

    // プレースホルダに値をバインド
    $stmt->bindValue(':User_id', $User_id, PDO::PARAM_STR);

    foreach ($hobby_id as $id) { // 追加する趣味の数だけループ

        // プレースホルダに値をバインド
        $stmt->bindValue(':hobby_id', $id, PDO::PARAM_STR);

        // SQL文を実行
        $stmt->execute();
    }
}

function hobby_indi() // 趣味の一覧データを取得する関数
{
    // データベースへの接続設定
    $pdo = pdo();

    //趣味の一覧データを取得するSQL文の作成
    $stmt = $pdo->prepare("SELECT * FROM hobby");

    //SQL文の実行
    $stmt->execute();

    // 結果を代入する
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 配列の値を返す
    return ($result);
}

function Address($address) // 居住地を取得する関数
{
    $url = "https://zipcloud.ibsnet.co.jp/api/search?zipcode=" . $address;

    // 通信のセッション (開始から終了までのこと)を初期化します
    $ch = curl_init();

    // 各種通信の設定を行います
    curl_setopt($ch, CURLOPT_URL, $url); // URLの設定
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 結果を文字列として変数に格納する設定

    // 通信を実行して，結果を取得します
    $response = curl_exec($ch);

    if ($response == false) {
        print("再入力してください。<br>");
    } else {
        // 受け取った文字列をJSONデータとして処理します    
        $json_response = json_decode($response, true);
    }
    // 通信のセッションを閉じます
    curl_close($ch);

    return ($json_response);
}
