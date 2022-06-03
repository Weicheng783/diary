<?php
    header("Content-type:text/html;charset=utf-8");
    if (!isset($_COOKIE['diary_name'])){
        echo "<script>location.href='diary.php';</script>";
        exit(0);
    }
    // Input Authentication
    if(!isset($_REQUEST['diary_year']) or $_REQUEST['diary_year'] == ""){
        $year = date('Y');
    }else{
        $year = $_REQUEST['diary_year'];
    }

    if(!isset($_REQUEST['diary_month']) or $_REQUEST['diary_month'] == ""){
        $month = "01";
    }else{
        $month = $_REQUEST['diary_month'];
    }

    if(!isset($_REQUEST['diary_day']) or $_REQUEST['diary_day'] == ""){
        $day = "01";
    }else{
        $day = $_REQUEST['diary_day'];
    }

    if(strlen($year) == 2){
        $year = "20".$year;
    }

    if(strlen($month) == 1){
        $month = "0".$month;
    }

    if(strlen($day) == 1){
        $day = "0".$day;
    }
    $final = $year."-".$month."-".$day;
    setcookie("diary_seeall", "", time());
    setcookie("diary_date", $final ,time()+3600);
    setcookie("diary_year", $year ,time()+3600);
    setcookie("diary_month", $month ,time()+3600);
    setcookie("diary_day", $day ,time()+3600);
    echo "<script>location.href='diary.php';</script>";
?>