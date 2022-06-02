<?php
    if (!isset($_COOKIE['diary_name'])){
        echo "<script>location.href='diary.php';</script>";
        exit(0);
    }
    setcookie("diary_name", "", 0);
    echo "<script>alert('日记本已安全退出.');location.href='diary.php';</script>";
?>