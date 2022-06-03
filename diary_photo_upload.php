<?php

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

?>

<?php

    $dest_folder = "/home/ubuntu/gallery/";   //上传图片保存的路径 图片放在跟你upload.php同级的picture文件夹里
    $arr = array();   //定义一个数组存放上传图片的名称方便你以后会用的。
    $count = 0;
    if (!file_exists($dest_folder)) {
        if(!mkdir($dest_folder, 777, true)){
            echo "dest_folder not created. please check.";
        } // 创建文件夹，并给予最高权限
    }

   $tp = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "image/bmp", "image/jpg", "image/gif", "image/pjpeg", "image/jpeg", "image/png", "application/pdf",'application/msword','application/vnd.openxmlformats-officedocument.presentationml.presentation');    //检查上传文件是否在允许上传的类型

    echo "<pre>";
    print_r($_FILES["uploads"]);

    foreach ($_FILES["uploads"]["error"] as $key => $error) {
        
        echo '文件类型' . $_FILES["uploads"]["type"][$key];
        echo '<br>';

        if (!in_array($_FILES["uploads"]["type"][$key], $tp)) {
            echo "<script language='javascript'>";
            echo "alert(\"文件类型错误!\");";
            echo "</script>";
            exit;
        }

        if ($error == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES["uploads"]["tmp_name"][$key];
            $a = explode(".", $_FILES["uploads"]["name"][$key]);  //截取文件名和后缀
            // $prename = substr($a[0],10);   //如果你到底的图片名称不是你所要的你可以用截取字符得到
            $prename = $a[0];
            $name = date('YmdHis') . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999). mt_rand(100, 999) . mt_rand(100, 999) . mt_rand(100, 999) . "." . strtolower($a[1]);  // 文件的重命名 （日期+随机数+后缀）
            $uploadfile = $dest_folder . $name;     // 文件的路径
            move_uploaded_file($tmp_name, $uploadfile);
            $arr[$count] = $uploadfile;
            echo $uploadfile . "<br />";
            $count++;
        }
    }
    echo "总共" . $count . "文件";
?>
