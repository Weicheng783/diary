<?php
header("Content-type:text/html;charset=utf-8");
date_default_timezone_set('Europe/London');

if (!isset($_REQUEST['verification']) or $_REQUEST['verification'] != '20220719'){
    echo "<script>alert('无效操作：认证失败');location.href='diary.php';</script>";
    exit(0);
}

if (!isset($_REQUEST['diary_server']) or $_REQUEST['diary_server'] == ''){
    $server = "localhost";    
}else{
    $server = $_REQUEST['diary_server'];
}

if (!isset($_REQUEST['diary_server_password']) or $_REQUEST['diary_server_password'] == ''){
    $server_pwd = "";    
}else{
    $server_pwd = $_REQUEST['diary_server_password'];
}

if (!isset($_REQUEST['diary_server_user']) or $_REQUEST['diary_server_user'] == ''){
    $server_user = "root";    
}else{
    $server_user = $_REQUEST['diary_server_user'];
}

if (!isset($_REQUEST['diary_server_port']) or $_REQUEST['diary_server_port'] == ''){
    $server_port = "3306";    
}else{
    $server_port = $_REQUEST['diary_server_port'];
}


setcookie("diary_server","");
setcookie("diary_server", $server, 2147483647);

setcookie("diary_server_password","");
setcookie("diary_server_password", $server_pwd, 2147483647);

setcookie("diary_server_user","");
setcookie("diary_server_user", $server_user, 2147483647);

setcookie("diary_server_port","");
setcookie("diary_server_port", $server_port, 2147483647);

echo "<script>alert('保存成功！');location.href='diary_setup.php';</script>";
?>