<?php
    // if (!isset($_COOKIE['diary_name'])){
    //     echo "<script>location.href='diary.php';</script>";
    //     exit(0);
    // }
    setcookie("map_date", "" ,time());
    setcookie("map_year", "" ,time());
    setcookie("map_month", "" ,time());
    setcookie("map_day", "" ,time());

    setcookie("map_date2", "" ,time());
    setcookie("map_year2", "" ,time());
    setcookie("map_month2", "" ,time());
    setcookie("map_day2", "" ,time());

    setcookie("map_time", "" ,time());
    setcookie("map_hour", "" ,time());
    setcookie("map_minute", "" ,time());
    setcookie("map_second", "" ,time());

    setcookie("map_time2", "" ,time());
    setcookie("map_hour2", "" ,time());
    setcookie("map_minute2", "" ,time());
    setcookie("map_second2", "" ,time());

    echo "<script>location.href='tellmemap.php';</script>";
?>