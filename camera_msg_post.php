<?php
if(!isset($_REQUEST['msg']) or $_REQUEST['msg'] == ""){
    echo "<script>alert('message cannot be empty.'); location.href='camera_msg.php';</script>";
}
$myfile = fopen("message.txt", "w") or die("Unable to open file!");
$txt = $_REQUEST['msg'];
fwrite($myfile, $txt);
fclose($myfile);
?>