<?php
    header("Content-type:text/html;charset=utf-8");
    // Input Authentication
    if(!isset($_REQUEST['year']) or $_REQUEST['year'] == ""){
        $year = date('Y');
    }else{
        $year = $_REQUEST['year'];
    }

    if(!isset($_REQUEST['month']) or $_REQUEST['month'] == ""){
        $month = "01";
    }else{
        $month = $_REQUEST['month'];
    }

    if(!isset($_REQUEST['day']) or $_REQUEST['day'] == ""){
        $day = "01";
    }else{
        $day = $_REQUEST['day'];
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
    setcookie("date", $final ,time()+3600);
    setcookie("year", $year ,time()+3600);
    setcookie("month", $month ,time()+3600);
    setcookie("day", $day ,time()+3600);
    echo "<script>location.href='index.php';</script>";
?>