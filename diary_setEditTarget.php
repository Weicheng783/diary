<?php
    header("Content-type:text/html;charset=utf-8");
    if (!isset($_COOKIE['diary_name'])){
        echo "<script>location.href='diary.php';</script>";
        exit(0);
    }
    // Input Authentication
    if(!isset($_REQUEST['target']) or $_REQUEST['target'] == ""){
        $target = "1";
    }else{
        $target = $_REQUEST['target'];
    }

    setcookie("target", "" , time());
    setcookie("target", $target , 2147483647);

    echo "<script>location.href='diary_edit.php';</script>";
?>