<?php
    header("Content-type:text/html;charset=utf-8");
    if (!isset($_COOKIE['diary_name'])){
        echo "<script>location.href='diary.php';</script>";
        exit(0);
    }
    setcookie("diary_seeall", "1" ,time()+3600);
    echo "<script>location.href='diary.php';</script>";
?>