<!-- Connection to DB -->
<?php
    // header("Content-type:text/html;charset=utf-8");
    $user="weicheng";
    $password='awc020826';
    $dsn="mysql:host=localhost";
    try
    {
        // Input Authentication
        echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">';
        echo "<p>IF YOU SEE THIS MESSAGE, YOU MAY ENTER UNAUTHORIZED USER NAME, PLEASE DO NOT ATTEMPT THIS SERVICE.</p>";
        echo "<p>如果你看见这条信息，你很有可能在猜测正确的用户账号和密码，请注意，请不要尝试这项操作，否则后果自负.</p>";
        echo "<p>如果你是那个对的人，看见这条消息说明服务器能够连接，但你输错了用户名和/或密码 或者 数据库无法连接，请注意在有限次错误内成功登录 或 检修数据库服务.</p>";
        echo '<p><a href="diary.php"><button type="button" class="header_button" onclick="" style="text-align:center; width:100%; font-size:20px; "><--返回上一级(<--Back)</button></a></p>';

        if(isset($_COOKIE['diary_name'])){
            $login_name = $_COOKIE['diary_name'];
        }else{
            if(!isset($_REQUEST['diary_name']) or $_REQUEST['diary_name'] == ""){
                echo "<script>alert('Please enter your name for this authentication purpose.');location.href='diary.php';</script>";
                exit(0);
            }else{
                $login_name = $_REQUEST['diary_name'];
            }
        }

        if(isset($_COOKIE['diary_password'])){
            $login_passwd = $_COOKIE['diary_password'];
        }else{
            if(!isset($_REQUEST['diary_password']) or $_REQUEST['diary_password'] == ""){
                echo "<script>alert('Password.');location.href='diary.php';</script>";  
                exit(0);
            }else{
                $login_passwd = $_REQUEST['diary_password'];
            }
        }

        // Data Base Preparatory Work


        $pdo=new PDO($GLOBALS['dsn'],$GLOBALS['user'], $GLOBALS['password']);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        // echo "<h3 style='text-align:center; color:green;'>Database Connected, Entering Login/Registration Phase. You will be redirected to the login page once confirmed.</h3>";

        try{
            $sql = "CREATE DATABASE IF NOT EXISTS diary";
            $pdo->query($sql);

            $pdo = new pdo('mysql:host=localhost; dbname=diary', $user, $password);
            $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            // echo "<h3 style='text-align:center; color:green;'>Database Connected.</h3>";

            $sql = "

            CREATE TABLE IF NOT EXISTS `diary`.`location` ( 
                `name` TEXT NOT NULL , 
                `content` TEXT NOT NULL 
            );

            CREATE TABLE IF NOT EXISTS `user` (
              `id` int NOT NULL,
              `name` TEXT NOT NULL,
              `password` TEXT NOT NULL,
              `status` TEXT NOT NULL,
              PRIMARY KEY (`id`)
            );

            CREATE TABLE IF NOT EXISTS `diary` (
              `diary_id` int NOT NULL AUTO_INCREMENT,
              `time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `content` TEXT NOT NULL,
              `status` TEXT NOT NULL,
               PRIMARY KEY (`diary_id`)
            );

            CREATE TABLE IF NOT EXISTS `gallery` (
                `diary_id` int NOT NULL,
                `source_id` int NOT NULL,
                `address` TEXT NOT NULL,
                PRIMARY KEY (`diary_id`,`source_id`),
                FOREIGN KEY (`diary_id`) REFERENCES `diary`(`diary_id`)
            );

            CREATE TABLE IF NOT EXISTS `comments` (
                `diary_id` int NOT NULL,
                `comment_id` int NOT NULL,
                `address` TEXT NOT NULL,
                PRIMARY KEY (`diary_id`,`comment_id`),
                FOREIGN KEY (`diary_id`) REFERENCES `diary`(`diary_id`)
            );

            ";
            $pdo->query($sql);

            $sql = "            
            CREATE TABLE IF NOT EXISTS `temporaryWork` (
                `id` int NOT NULL AUTO_INCREMENT,
                `time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `content` TEXT NOT NULL,
                PRIMARY KEY (`id`)
            );
            ";

            $pdo->query($sql);

        }catch(PDOException $e){
            echo "<h3 style='text-align:center; color:red;'>Database Disconnected.</h3>";
            echo "<script>alert('此时无法连接数据库，如果问题一直存在，是代码或mysql服务出了问题.');location.href='diary.php';</script>";
        }

        $login_pre_password = $login_passwd;

        // Data Base Preparatory Work
        $dsn="mysql:host=localhost";

        $pdo = new pdo('mysql:host=localhost; dbname=diary', $GLOBALS['user'], $GLOBALS['password']);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    
        try{
            // Login Main Function Code Snippet
 
            $sql = "SELECT * FROM user WHERE name = '".$login_name."';";
            $re = $pdo -> query($sql);
            $rows = $re->fetchAll();
            if($rows == null){
                // echo '<p id="editor">'.$rows[$i]['editor'].'</p>';
                // We have no such user, proceed to register.
                $login_passwd = password_hash($login_passwd, PASSWORD_DEFAULT);

                // Query the only existed user.
                $sql = "SELECT * FROM user WHERE id = '1';";
                $re = $pdo -> query($sql);
                $rows = $re->fetchAll();

                // Confirm the first pass.
                if($rows == null){
                    $sql = "INSERT INTO `user` (`id`, `name`, `password`, `status`) VALUES (1, '".$login_name."', '".$login_passwd."', 'normal')";

                    $re = $pdo -> query($sql);
                    $rows = $re->fetchAll();
                }

                // print_r ($rows);
                // echo "<p>Registration Successful.</p>";
                // setcookie("id", $login_id, time()+3600);
                // setcookie("password", $login_pre_password, time()+3600);
                // setcookie("name", $login_name,time()+3600);
                // setcookie("staff", $login_staff, time()+3600);

                echo "<script>alert('用户不存在。如果是第一次登录，请再试一次，数据库已初始完成.');location.href='diary.php';</script>";
                exit(0);
            }

            if($rows == null){
                // We have no such admin, reject request.
                echo "<script>alert('用户不存在。如果是第一次登录，请再试一次，数据库已初始完成.');location.href='diary.php';</script>";
                exit(0);
            }else{
                // Admin Exists, proceed to authenticate.
                $sql = "SELECT password FROM user WHERE name = '".$login_name."';";
                $re = $pdo -> query($sql);
                $rows = $re->fetchAll();
                if($rows == null){
                    // THIS PERSON DOES NOT EXIST.
                    echo "<script>alert('用户密码错误.');location.href='diary.php';</script>";
                    exit(0);
                }else{
                    // PASSWORD MATCHING
                    if(password_verify($login_pre_password, $rows[0][0])){
                        if(!isset($_COOKIE['diary_name'])){
                            setcookie("diary_name", $login_name,time()+3600);
                        }
                        echo "<script>location.href='diary.php';</script>";
                        exit(0);
                    }else{
                        echo "<script>alert('用户密码错误.');location.href='diary.php';</script>";
                        exit(0);
                    }
                }
            }

        }catch(PDOException $e){
            echo "<h3 style='text-align:center; color:red;'>Database Disconnected.</h3>";
            echo "<script>alert('此时无法连接数据库，如果问题一直存在，是代码或mysql服务出了问题.');location.href='diary.php';</script>";
        }
    }
    catch(PDOException $e)
    {
        echo "<h3 style='text-align:center; color:red;'>Database Disconnected.</h3>";
        echo "<script>alert('此时无法连接数据库，如果问题一直存在，是代码或mysql服务出了问题.');location.href='diary.php';</script>";
    }

?>