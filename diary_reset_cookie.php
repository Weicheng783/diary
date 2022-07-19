<?php
    setcookie("diary_name", "", 0);
    setcookie("diary_server", "", 0);
    setcookie("diary_server_user", "", 0);
    setcookie("diary_server_password", "", 0);
    setcookie("diary_server_port", "", 0);

    echo "<script>alert('日记本Cookies已全部清除.');location.href='diary.php';</script>";
?>