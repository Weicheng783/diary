<?php
header("Content-type:text/html;charset=utf-8");
// $file = $_FILES['file'];
// $name = $file['name'];
// $type = strtolower(substr($name,strrpos($name,'.')+1)); 
// $allow_type = array('jpg','jpeg','gif','png','pjpeg','bmp'); 
// if(!in_array($type, $allow_type)){  
//     return ;
// }

// if(!is_uploaded_file($file['tmp_name'])){
//     return ;
// }

// $dest = "C:/wamp64/www/upload/"; 

// if(move_uploaded_file($file['tmp_name'],$upload_path.$file['name'])){  
//     echo "Successfully!";
// }else{
//     echo "Failed!";
// }

if (!isset($_COOKIE['diary_name'])){
    echo "<script>location.href='diary.php';</script>";
    exit(0);
}

    $dest_folder = "../gallery/";   //上传图片保存的路径
    $arr = array();   //定义一个数组存放上传图片的名称方便以后用
    $count = 0;
    if (!file_exists($dest_folder)) {
        if(!mkdir($dest_folder, 0777, true)){
            echo "dest_folder not created. please check.";
            echo "And do not forget to issue: sudo chmod -R 777 gallery.";
            echo "And do softlink to allow external access.";
        } // 创建文件夹，并赋最高权限
    }

   $tp = array("", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "image/bmp", "image/jpg", "image/gif", "image/pjpeg", "image/jpeg", "image/png", "application/pdf",'application/msword','application/vnd.openxmlformats-officedocument.presentationml.presentation');    //检查上传文件是否在允许上传的类型

    echo "<pre>";
    print_r($_FILES["uploads"]);

    try{
        $dsn="mysql:host=".$_COOKIE['diary_server']."; port=".$_COOKIE['diary_server_port']."; dbname=diary";
        $user=$_COOKIE['diary_server_user'];
        $password=$_COOKIE['diary_server_password'];
        $pdo=new PDO($dsn,$user,$password);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        $sql = "SET FOREIGN_KEY_CHECKS = 0;";
        $pdo->query($sql);

    }catch(PDOException $e){
        echo "<script>alert('服务器连不上.');location.href='diary.php';</script>";
    }

    print $_SERVER['SCRIPT_FILENAME'];

    foreach ($_FILES["uploads"]["error"] as $key => $error) {
        
        echo '文件类型' . $_FILES["uploads"]["type"][$key];
        echo '<br>';

        if (!in_array($_FILES["uploads"]["type"][$key], $tp)) {
            echo "<script language='javascript'>";
            echo "alert(\"文件类型错误。服务器不允许你上传类型为 ".$_FILES["uploads"]["type"][$key]." 的文件，请重试。\");";
            echo "</script>";
            echo "<script>location.href='diary.php';</script>";
            exit;
        }

            $tmp_name = $_FILES["uploads"]["tmp_name"][$key];
            $a = explode(".", $_FILES["uploads"]["name"][$key]);  //截取文件名和后缀
            // $prename = substr($a[0],10);   //如果你到底的图片名称不是你所要的你可以用截取字符得到
            $prename = $a[0];
            $name = date('YmdHis') . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999). mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999) . "." . strtolower($a[1]);  // 文件的重命名 （日期+随机数+后缀）
            // $uploadfile = $dest_folder . $name;     // 文件的路径

            // echo "<script>alert('文件读取时出错，请重试.');</script>";
            $ftp_server="132.145.74.19"; 
            $ftp_user_name="weicheng"; 
            $ftp_user_pass="awc020826"; 
            $file = "/Users/weicheng/Desktop/学业文件/大一上学期/我的坚果云/java1.png";//tobe uploaded 
            $remote_file = "./gallery/" . $name; 

            // set up basic connection 
            $conn_id = ftp_connect($ftp_server); 

            // login with username and password 
            $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 

            // upload a file 
            if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) {
                echo "successfully uploaded $file\n";
                try{
                    $diary_id = $_REQUEST['diary_id'];
                    $source_id = $_REQUEST['source_id'];
    
                    $source_id += $key;
    
                    $sql = "INSERT INTO `gallery` (`diary_id`, `source_id`, `uuid`, `address`, `address2`) VALUES ('".$diary_id."', '".$source_id."', '".$name."', 'http://localhost:8026/gallery/".$name."', 'http://132.145.74.19/gallery/".$name."');";
                    $pdo->query($sql);
                
                }catch(PDOException $e){
                    echo "<script>alert('有数据段未被插入总表，请重试.');location.href='diary_edit.php';</script>";
                }
                $arr[$count] = $uploadfile;
                echo $uploadfile . "<br />";
    
                $count++;
                // exit;
            } else {
                echo "There was a problem while uploading $file\n";
                exit;
            } 
            ftp_close($conn_id); 
        
    }
    if($count == 0){
        echo "<script>alert('没有上传成功诶，或者你没有上传文件。');location.href='diary_edit.php';</script>";
    }else{
        echo "<script>alert('总共 ".$count." 个文件 插入和上传成功.');location.href='diary_edit.php';</script>";
    }

    // echo "总共" . $count . "文件;";
    // echo "数据插入和上传成功！";
    // echo "<a href='diary.php'><button></button></a>";
?>