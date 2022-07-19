<?php
header("Content-type:text/html;charset=utf-8");

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

    $id = $_REQUEST['id'];

    $sql = "SET FOREIGN_KEY_CHECKS = 0;
    DELETE FROM `diary` WHERE `diary_id` = ".$id.";
    DELETE FROM `comments` WHERE `diary_id` = ".$id.";
    DELETE FROM `gallery` WHERE `diary_id` = ".$id.";";

    $pdo->query($sql);

    echo "<script>alert('删除成功.');location.href='diary.php';</script>";

}catch(PDOException $e){
    echo "<script>alert('本次删除没有成功, 要再试一次.');location.href='diary.php';</script>";
}

?>