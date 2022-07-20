<?php
    setcookie("diary_name", "", 0);
    setcookie("diary_server", "", 0);
    setcookie("diary_server_user", "", 0);
    setcookie("diary_server_password", "", 0);
    setcookie("diary_server_port", "", 0);

    setcookie("diary_sync_port", "", 0);
    setcookie("diary_sync_server", "", 0);
    setcookie("diary_sync_user", "", 0);
    setcookie("diary_sync_pwd", "", 0);


    echo "<script>alert('日记本重要Cookies已全部清除.');location.href='diary.php';</script>";
?>