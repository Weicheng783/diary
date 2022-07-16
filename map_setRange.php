<?php
    header("Content-type:text/html;charset=utf-8");
    // if (!isset($_COOKIE['diary_name'])){
    //     echo "<script>location.href='tellmemap.php';</script>";
    //     exit(0);
    // }

    // Input Authentication
    if(!isset($_REQUEST['map_year']) or $_REQUEST['map_year'] == ""){
        $year = date('Y');
    }else{
        $year = $_REQUEST['map_year'];
    }

    if(!isset($_REQUEST['map_month']) or $_REQUEST['map_month'] == ""){
        $month = "01";
    }else{
        $month = $_REQUEST['map_month'];
    }

    if(!isset($_REQUEST['map_day']) or $_REQUEST['map_day'] == ""){
        $day = "01";
    }else{
        $day = $_REQUEST['map_day'];
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

    // Dest
    if(!isset($_REQUEST['map_year2']) or $_REQUEST['map_year2'] == ""){
        $year2 = date('Y');
    }else{
        $year2 = $_REQUEST['map_year2'];
    }

    if(!isset($_REQUEST['map_month2']) or $_REQUEST['map_month2'] == ""){
        $month2 = "01";
    }else{
        $month2 = $_REQUEST['map_month2'];
    }

    if(!isset($_REQUEST['map_day2']) or $_REQUEST['map_day2'] == ""){
        $day2 = "01";
    }else{
        $day2 = $_REQUEST['map_day2'];
    }

    if(strlen($year2) == 2){
        $year2 = "20".$year2;
    }

    if(strlen($month2) == 1){
        $month2 = "0".$month2;
    }

    if(strlen($day2) == 1){
        $day2 = "0".$day2;
    }
    $final2 = $year2."-".$month2."-".$day2;


    //clock
    if(!isset($_REQUEST['map_hour']) or $_REQUEST['map_hour'] == ""){
        $hour = "00";
    }else{
        $hour = $_REQUEST['map_hour'];
    }

    if(!isset($_REQUEST['map_minute']) or $_REQUEST['map_minute'] == ""){
        $minute = "00";
    }else{
        $minute = $_REQUEST['map_minute'];
    }

    if(!isset($_REQUEST['map_second']) or $_REQUEST['map_second'] == ""){
        $second = "00";
    }else{
        $second = $_REQUEST['map_second'];
    }

    if(strlen($hour) == 1){
        $hour = "0".$hour;
    }

    if(strlen($minute) == 1){
        $minute = "0".$minute;
    }

    if(strlen($second) == 1){
        $second = "0".$second;
    }
    $final_time = $hour.":".$minute.":".$second;

        //clock dest
        if(!isset($_REQUEST['map_hour2']) or $_REQUEST['map_hour2'] == ""){
            $hour2 = date('H');
        }else{
            $hour2 = $_REQUEST['map_hour2'];
        }
    
        if(!isset($_REQUEST['map_minute2']) or $_REQUEST['map_minute2'] == ""){
            $minute2 = date('i');
        }else{
            $minute2 = $_REQUEST['map_minute2'];
        }
    
        if(!isset($_REQUEST['map_second2']) or $_REQUEST['map_second2'] == ""){
            $second2 = date('s');
        }else{
            $second2 = $_REQUEST['map_second2'];
        }
    
        if(strlen($hour2) == 1){
            $hour2 = "0".$hour2;
        }
    
        if(strlen($minute2) == 1){
            $minute2 = "0".$minute2;
        }
    
        if(strlen($second2) == 1){
            $second2 = "0".$second2;
        }
        $final_time2 = $hour2.":".$minute2.":".$second2;

    // setcookie("diary_seeall", "", time());
    setcookie("map_date", $final ,time()+3600);
    setcookie("map_year", $year ,time()+3600);
    setcookie("map_month", $month ,time()+3600);
    setcookie("map_day", $day ,time()+3600);

    setcookie("map_date2", $final2 ,time()+3600);
    setcookie("map_year2", $year2 ,time()+3600);
    setcookie("map_month2", $month2 ,time()+3600);
    setcookie("map_day2", $day2 ,time()+3600);

    setcookie("map_time", $final_time ,time()+3600);
    setcookie("map_hour", $hour ,time()+3600);
    setcookie("map_minute", $minute ,time()+3600);
    setcookie("map_second", $second ,time()+3600);

    setcookie("map_time2", $final_time2 ,time()+3600);
    setcookie("map_hour2", $hour2 ,time()+3600);
    setcookie("map_minute2", $minute2 ,time()+3600);
    setcookie("map_second2", $second2 ,time()+3600);

    echo "<script>location.href='tellmemap.php';</script>";
?>