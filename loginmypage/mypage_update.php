<?php
mb_internal_encoding("utf8");

//セッションスタート
session_start();

try{
//DB接続
$pdo = new PDO("mysql:dbname=lesson01;host=localhost;","root","root");
}catch(PDOException $e){
die("<p>申し訳ございません。現在サーバーが混み合っており一時的にアクセス出来ません。<br>しばらくしてから再度ログインをしてください。</p>
　<a herf='http://localhost/login_mypage/login.php'>ログイン画面へ</a>"
   );
}

//プリペアードステートメントでSQL文の型を作る
$stmt = $pdo->prepare("update login_mypage set name=?, mail=?, password=?, comments=? where id=?;");

//bindValueを使用
$stmt->bindValue(1,$_POST['name']);
$stmt->bindValue(2,$_POST['mail']);
$stmt->bindValue(3,$_POST['password']);
$stmt->bindValue(4,$_POST['comments']);
$stmt->bindValue(5,$_SESSION['id']);

//executeでクエリを実行。
$stmt->execute();

$stmt = $pdo->prepare("select * from login_mypage where mail = ? && password = ?");

$stmt->bindValue(1,$_POST['mail']);
$stmt->bindValue(2,$_POST['password']);

$stmt->execute();



//while文でデータ取得し、sessionに代入
while($row=$stmt->fetch()){
    $_SESSION['id']=$row['id'];
    $_SESSION['name']=$row['name'];
    $_SESSION['mail']=$row['mail'];
    $_SESSION['password']=$row['password'];
    $_SESSION['piture']=$row['picture'];
    $_SESSION['comments']=$row['comments'];
}

//データベースを切断
$stmt = NULL;

//mypage.phpへリダイレクト
header("Location:mypage.php");

?>