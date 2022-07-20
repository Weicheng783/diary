<?php
header("Content-type:text/html;charset=utf-8");

if (!isset($_COOKIE['diary_name'])){
    echo "<script>location.href='diary.php';</script>";
    exit(0);
}

if(!isset($_COOKIE['diary_sync_server']) && ($_REQUEST['method'] == "dual" or $_REQUEST['method'] == "remote")){
    echo "<script>alert('你还没有设置远程服务器同步信息，请设置后再做删除操作.');location.href='diary_sync.php';</script>";
    exit(0);
}else if($_REQUEST['method'] == "dual" or $_REQUEST['method'] == "remote"){
    try{
        // local query content & time
        $dsn="mysql:host=".$_COOKIE['diary_server']."; port=".$_COOKIE['diary_server_port']."; dbname=diary";
        $user=$_COOKIE['diary_server_user'];
        $password=$_COOKIE['diary_server_password'];
        $pdo=new PDO($dsn,$user,$password);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $stmt = $pdo->query("SELECT `content`, `time` FROM diary WHERE `diary_id`=".$_REQUEST['id']."");
        $result = $stmt->fetchAll();

        $diary_content = $result[0]['content'];
        $diary_time = $result[0]['time'];

        // reversing query diary_id from remote server
        $dsn="mysql:host=".$_COOKIE['diary_sync_server']."; port=".$_COOKIE['diary_sync_port']."; dbname=diary";
        $user=$_COOKIE['diary_sync_user'];
        $password=$_COOKIE['diary_sync_pwd'];
        $pdo=new PDO($dsn,$user,$password);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $stmt = $pdo->query("SELECT `diary_id` FROM diary WHERE `time`='".$diary_time."' AND `content`='".$diary_content."'");
        $result = $stmt->fetchAll();
        $row_count = $stmt->rowCount();

        if($row_count == 0){
            if($_REQUEST['method'] != "dual"){
                echo "<script>alert('远端服务器 并没有这条记录.');location.href='diary_edit.php';</script>";
                exit(0);
            }else{
                $flag = 1;
                echo "<script>alert('远端服务器并没有这条记录, 仍然会继续删除本地记录.');</script>";
            }
        }else{
            $diary_id_remote = $result[0]['diary_id'];
            $id = $diary_id_remote;
        }

    }catch(PDOException $e){
        echo "<script>alert('远端服务器 连接/查询diary_id失败，暂不能删除.');location.href='diary_edit.php';</script>";
        exit(0);
    }

    if(!isset($flag)){
        try{
            $sql = "SET FOREIGN_KEY_CHECKS = 0;
            DELETE FROM `diary` WHERE `diary_id` = ".$id.";
            DELETE FROM `comments` WHERE `diary_id` = ".$id.";
            DELETE FROM `gallery` WHERE `diary_id` = ".$id.";";
            $pdo->query($sql);
        }catch(PDOException $e){
            echo "<script>alert('远端服务器 删除过程中 遇到失败，请检查服务器.');location.href='diary_edit.php';</script>";
            exit(0);
        }
    }

}

try{
    if($_REQUEST['method'] == "dual" or $_REQUEST['method'] == "local"){
        $dsn="mysql:host=".$_COOKIE['diary_server']."; port=".$_COOKIE['diary_server_port']."; dbname=diary";
        $user=$_COOKIE['diary_server_user'];
        $password=$_COOKIE['diary_server_password'];
        $pdo=new PDO($dsn,$user,$password);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    
        $id = $_REQUEST['id'];
    
        $sql = "SET FOREIGN_KEY_CHECKS = 0;
        DELETE FROM `diary` WHERE `diary_id` = ".$id.";
        DELETE FROM `comments` WHERE `diary_id` = ".$id.";
        DELETE FROM `gallery` WHERE `diary_id` = ".$id.";";
    
        $pdo->query($sql);
        
        if($_REQUEST['method'] == "local"){
            echo "<script>alert('本地删除成功.');location.href='diary.php';</script>";
            exit(0);
        }else{
            if(!isset($flag)){
                echo "<script>alert('本地和远程双删成功.');location.href='diary.php';</script>";
                exit(0);
            }else{
                echo "<script>alert('双删部分成功：本地成功，远程失败，原因可能是远程并没有这一条记录.');location.href='diary.php';</script>";
                exit(0);
            }
        }
    }else{
        echo "<script>alert('远程记录删除成功.');location.href='diary_edit.php';</script>";
        exit(0); 
    }

}catch(PDOException $e){
    echo "<script>alert('在本地删除时没有成功, 要再试一次.');location.href='diary_edit.php';</script>";
}

?>