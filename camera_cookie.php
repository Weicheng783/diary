<?php
if(!isset($_REQUEST['date']) or $_REQUEST['date'] == ""){
    echo "<script>alert('请选择日期'); location.href='camera.php';</script>";
}
?>