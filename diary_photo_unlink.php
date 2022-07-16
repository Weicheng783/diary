<?php
header("Content-type:text/html;charset=utf-8");


if (!isset($_COOKIE['diary_name'])){
    echo "<script>location.href='diary.php';</script>";
    exit(0);
}

try{
    $dsn="mysql:host=localhost; dbname=diary";
    $user="weicheng";
    $password='awc020826';
    $pdo=new PDO($dsn,$user,$password);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $diary_id = $_REQUEST['diary_id'];
    $source_id = $_REQUEST['source_id'];

    $sql = "SET FOREIGN_KEY_CHECKS = 0;
    DELETE FROM `gallery` WHERE `gallery`.`diary_id` = ".$diary_id." AND `gallery`.`source_id` = ".$source_id.";";
    $pdo->query($sql);

    echo "<script>alert('取消连接图像条目成功.');location.href='diary.php';</script>";

}catch(PDOException $e){
    echo "<script>alert('服务器连不上/unlink失败.');location.href='diary.php';</script>";
}

?>