<?php
    if (!isset($_COOKIE['diary_name'])){
        echo "<script>location.href='diary.php';</script>";
        exit(0);
    }
    setcookie("diary_date", "", time());
    setcookie("diary_year", "", time());
    setcookie("diary_month", "", time());
    setcookie("diary_day", "", time());
    setcookie("diary_seeall", "", time());
    echo "<script>location.href='diary.php';</script>";
?>