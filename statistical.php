<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>趣味の統計を見て見てみよう</title>
   <link rel="stylesheet" href="statistical.css">
</head>

<?php
require_once("function.php"); // ファイルを取り込む

session_start(); // セッションを開始する

// 変数,配列の初期化
$data = [];
$sum;
$percent = [];

for ($i = 0; $i < 2; $i++) {
   //変数,配列の初期化
   $sum = 0;
   $data = [];

   $data = hobby_sex_indi($i); // 趣味の性別別の統計データを取得する関数

   for ($j = 0; $j < count($data); $j++) { // 取得した統計データから合計人数を求める
      $sum += $data[$j]['COUNT(hobby_user.hobby_id)'];
   }

   for ($j = 0; $j < count($data); $j++) { // 趣味の合計人数あたりの割合を求める
      $percent[$i][$j] = [$data[$j]['hobby_name'], round(($data[$j]['COUNT(hobby_user.hobby_id)'] / $sum) * 100, 2)];
   }
}

//変数,配列の初期化
$sum = 0;
$data = [];

$data = hobby_sum_indi(); // 趣味の統計データを取得する関数

for ($i = 0; $i < count($data); $i++) { // 取得した統計データから合計人数を求める
   $sum += $data[$i]['COUNT(hobby_user.hobby_id)'];
}

for ($i = 0; $i < count($data); $i++) { // 趣味の合計人数あたりの割合を求める
   $percent[2][$i] = [$data[$i]['hobby_name'], round(($data[$i]['COUNT(hobby_user.hobby_id)'] / $sum) * 100, 2)];
}
?>

<body>
   <div class="container">
      <h1>趣味の統計</h1>
      <p>データを参考に趣味を決めてマッチングしよう!!</p>
      <button onclick="location.href='home.php'" class="button-back">ホーム画面に戻る</button>
      <table>
         <thead>
            <tr>
               <th>性別</th>
               <th>趣味</th>
               <th>割合</th>
               <th></th>
               <th>性別</th>
               <th>趣味</th>
               <th>割合</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $count = max(count($percent[0]), count($percent[1]));

            for ($i = 0; $i < $count; $i++) {
               echo "<tr>";

               if ($i < count($percent[0])) { // 女性の統計データを表示
                  echo "<td>女性</td>";
                  echo "<td>" . $i + 1 . ":{$percent[0][$i][0]}</td>";
                  echo "<td>{$percent[0][$i][1]}%</td>";
               } else {
                  echo "<td></td>";
                  echo "<td></td>";
                  echo "<td></td>";
               }

               if ($i < count($percent[1])) { // 男性の統計データを表示
                  echo "<td></td>";
                  echo "<td>男性</td>";
                  echo "<td>" . $i + 1 . ":{$percent[1][$i][0]}</td>";
                  echo "<td>{$percent[1][$i][1]}%</td>";
               } else {
                  echo "<td></td>";
                  echo "<td></td>";
                  echo "<td></td>";
                  echo "<td></td>";
               }

               echo "</tr>";
            }
            ?>
         </tbody>
      </table>

      <table>
         <thead>
            <tr>
               <th>性別</th>
               <th>趣味</th>
               <th>割合</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $count = count($percent[2]);

            for ($i = 0; $i < $count; $i++) { // 全体の統計データを表示
               echo "<tr>";
               echo "<td>総合</td>";
               echo "<td>" . $i + 1 . ":{$percent[2][$i][0]}</td>";
               echo "<td>{$percent[2][$i][1]}%</td>";
               echo "</tr>";
            }
            ?>
         </tbody>
      </table>
   </div>
</body>

</html>