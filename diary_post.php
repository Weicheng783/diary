<?php
header("Content-type:text/html;charset=utf-8");
date_default_timezone_set('Europe/London');

if (!isset($_COOKIE['diary_name'])){
    echo "<script>location.href='diary.php';</script>";
    exit(0);
}

try{
    $dsn="mysql:host=".$_COOKIE['diary_server']."; port=".$_COOKIE['diary_server_port']."; dbname=diary";
    $user=$_COOKIE['diary_server_user'];
    $password=$_COOKIE['diary_server_password'];
    $pdo=new PDO($dsn,$user,$password);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $content = $_REQUEST['content'];
    $status = $_REQUEST['status'];

    $sql = "INSERT INTO `temporaryWork` (`content`) VALUES ('".$content."');";
    $pdo->query($sql);

    setcookie("diary_work_preference", "");

    $sql = "INSERT INTO `diary` (`content`, `status`, `time`) VALUES ('".$content."', '".$status."', '".date('Y-m-d H:i:s', time())."');";
    $pdo->query($sql);

    setcookie("diary_work", "", time());

    echo "<script>alert('数据插入成功.');location.href='diary.php';</script>";

}catch(PDOException $e){
    setcookie("diary_work", "", time());
    setcookie("diary_work", $content, 2147483647);
    setcookie("diary_work_preference", "cookie", 2147483647);

    echo "<script>alert('本次数据未被插入总表, 但已写入缓存, 回到页面将以缓存优先取回.');location.href='diary.php';</script>";
}

?>