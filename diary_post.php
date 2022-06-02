<?php
header("Content-type:text/html;charset=utf-8");

if (!isset($_COOKIE['diary_name'])){
    echo "<script>location.href='diary.php';</script>";
    exit(0);
}

try{
    $dsn="mysql:host=150.230.127.102; dbname=diary";
    $user="weicheng";
    $password='awc020826';
    $pdo=new PDO($dsn,$user,$password);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $content = $_REQUEST['content'];
    $status = $_REQUEST['status'];

    $sql = "INSERT INTO `temporaryWork` (`content`) VALUES ('".$content."');";
    $pdo->query($sql);

    setcookie("diary_work_preference", "");

    $sql = "INSERT INTO `diary` (`content`, `status`) VALUES ('".$content."', '".$status."');";
    $pdo->query($sql);

    echo "<script>alert('数据插入成功.');location.href='diary.php';</script>";

}catch(PDOException $e){
    setcookie("diary_work", "", time());
    setcookie("diary_work", $content, time()+7200);
    setcookie("diary_work_preference", "cookie", time()+7200);

    echo "<script>alert('本次数据未被插入总表, 但已写入缓存, 回到页面将以缓存优先取回.');location.href='diary.php';</script>";
}

?>