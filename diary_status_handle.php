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

    $status = $_REQUEST['status'];
    $id = $_REQUEST['diary_id'];

    $sql = "SET FOREIGN_KEY_CHECKS = 0;
    UPDATE `diary` SET `status`='".$status."' WHERE `diary_id` = ".$id.";
    ";

    $pdo->query($sql);

    echo "<script>alert('数据更新成功. 记录状态已改为：".$status." .');location.href='diary.php';</script>";

}catch(PDOException $e){
    echo "<script>alert('本次数据未被更改, 请重试.');location.href='diary.php';</script>";
}

?>