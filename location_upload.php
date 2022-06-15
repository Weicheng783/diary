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


    // $dest_folder = "/home/ubuntu/www/diary/location";   //上传图片保存的路径 图片放在跟你upload.php同级的picture文件夹里
    // $arr = array();   //定义一个数组存放上传图片的名称方便你以后会用的。
    // $count = 0;
    // if (!file_exists($dest_folder)) {
    //     if(!mkdir($dest_folder, 0777, true)){
    //         echo "dest_folder not created. please check.";
    //         echo "And do not forget to issue: sudo chmod -R 777 gallery.";
    //         echo "And do softlink to allow external access.";
    //     } // 创建文件夹，并给予最高权限
    // }

//    $tp = array("", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "image/bmp", "image/jpg", "image/gif", "image/pjpeg", "image/jpeg", "image/png", "application/pdf",'application/msword','application/vnd.openxmlformats-officedocument.presentationml.presentation');    //检查上传文件是否在允许上传的类型

    // echo "<pre>";
    // print_r($_FILES["uploads"]);

    try{
        $dsn="mysql:host=localhost; dbname=diary";
        $user="weicheng";
        $password='awc020826';
        $pdo=new PDO($dsn,$user,$password);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        $sql = "SET FOREIGN_KEY_CHECKS = 0;";
        $pdo->query($sql);

    }catch(PDOException $e){
        // echo "<script>alert('服务器连不上.');location.href='diary.php';</script>";
        echo "服务器离线";
    }

    // foreach ($_FILES["uploads"]["error"] as $key => $error) {
        
        // echo '文件类型' . $_FILES["uploads"]["type"][$key];
        // echo '<br>';

        // if (!in_array($_FILES["uploads"]["type"][$key], $tp)) {
        //     echo "<script language='javascript'>";
        //     echo "alert(\"文件类型错误。服务器不允许你上传类型为 ".$_FILES["uploads"]["type"][$key]." 的文件，请重试。\");";
        //     echo "</script>";
        //     echo "<script>location.href='diary.php';</script>";
        //     exit;
        // }

        // if ($error == UPLOAD_ERR_OK) {
            // $tmp_name = $_FILES["uploads"]["tmp_name"][$key];
            // $a = explode(".", $_FILES["uploads"]["name"][$key]);  //截取文件名和后缀
            // // $prename = substr($a[0],10);   //如果你到底的图片名称不是你所要的你可以用截取字符得到
            // $prename = $a[0];
            // // $name = date('YmdHis') . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999). mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999) . "." . strtolower($a[1]);  // 文件的重命名 （日期+随机数+后缀）
            // $name = $_FILES["uploads"]["name"][$key];
            // $uploadfile = $dest_folder . $name;     // 文件的路径
            // if(!move_uploaded_file($tmp_name, $uploadfile)){
            //     // echo "<script language='javascript'>";
            //     // echo "alert(\"文件上传途中出现错误或漏传，请重试!\");";
            //     // echo "</script>";
            //     // echo "<script>location.href='diary.php';</script>";
            //     echo "文件上传出错";
            //     exit(0);
            // }

            try{
                $name = $_REQUEST['name'];
                $content = $_REQUEST['content'];

                // $source_id += $key;
            
                $sql = "INSERT INTO `location` (`name`, `content`) VALUES ('".$name."', '".$content."');";
                $pdo->query($sql);
                echo $name . " 插入成功. Status:200.";
            
            }catch(PDOException $e){
                echo "有数据段未被插入总表，请重试.";
            }
            // $arr[$count] = $uploadfile;
            // echo $uploadfile . "<br />";
            // $count++;
        // }
    // }
    // echo "总共" . $count . "文件;";
    // echo "<script>alert('总共 ".$count." 个文件 插入和上传成功.');location.href='diary.php';</script>";
    // echo "数据插入和上传成功！";
    // echo "<a href='diary.php'><button></button></a>";
?>







