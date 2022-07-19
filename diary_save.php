<?php
header("Content-type:text/html;charset=utf-8");

if (!isset($_COOKIE['diary_name'])){
    $content = $_REQUEST['content'];
    setcookie("diary_work","");
    setcookie("diary_work", $content, 2147483647);
    setcookie("diary_work_preference", "cookie", time()+7200);
    echo "<script>alert('⚠️保存失败，已写入缓存，回到页面将以缓存优先. ⚠️请注意保存下面👇你的内容, 这非常重要因为你现在是无登录状态，可能因为cookies过期，不要跳过，下一个页面可能就不会显示了：".$content."');location.href='diary.php';</script>";
   
    echo "<script>location.href='diary.php';</script>";
    exit(0);
}

try{
    $dsn="mysql:host=localhost; dbname=diary";
    $user="weicheng";
    $password='awc020826';
    $pdo=new PDO($dsn,$user,$password);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $content = $_REQUEST['content'];

    $sql = "SELECT id FROM `temporaryWork` ORDER BY `id` DESC LIMIT 0,1;";
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();


    $sql = "DELETE FROM `temporaryWork` WHERE `id`<".$rows[0]['id']."-8;
     INSERT INTO `temporaryWork` (`content`) VALUES ('".$content."');";

    //  echo "<script>console.log('".$content."')</script>";
    //  die;

    $pdo->query($sql);

    setcookie("diary_work_preference","", time());

    echo "<script>location.href='diary_edit.php';</script>";

}catch(PDOException $e){
    $content = $_REQUEST['content'];

    setcookie("diary_work","");
    setcookie("diary_work", $content, 2147483647);
    setcookie("diary_work_preference", "cookie", time()+7200);
    echo "<script>alert('⚠️保存失败，已写入缓存，回到页面将以缓存优先. ⚠️请注意保存下面👇你的内容, 这非常重要因为你现在是离线状态，不要跳过，下一个页面可能就不会显示了：".$content."');location.href='diary_edit.php';</script>";
}

?>