<!-- Connection to DB -->
<?php
    header("Content-type:text/html;charset=utf-8");
    try
    {
        // Input Authentication

        if(isset($_COOKIE['name'])){
            $login_name = $_COOKIE['name'];
        }else{
            if(!isset($_REQUEST['name']) or $_REQUEST['name'] == ""){
                echo "<script>alert('你没有输入账号.');location.href='index.php';</script>";
                exit(0);
            }else{
                $login_name = $_REQUEST['name'];
            }
        }

        if(isset($_COOKIE['password'])){
            $login_passwd = $_COOKIE['password'];
        }else{
            if(!isset($_REQUEST['password']) or $_REQUEST['password'] == ""){
                echo "<script>alert('你没有输入密码.');location.href='index.php';</script>";  
                exit(0);          
            }else{
                $login_passwd = $_REQUEST['password'];
            }
        }

        $login_pre_password = $login_passwd;

        // Data Base Preparatory Work
        $dsn="mysql:host=localhost";
        $user="";
        $password='';
        $pdo = new pdo('mysql:host=localhost; dbname=location', $user, $password);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    
        try{
            // Login Main Function Code Snippet
 
            $sql = "SELECT * FROM admins WHERE name = '".$login_name."';";
            $re = $pdo -> query($sql);
            $rows = $re->fetchAll();
            if($rows == null){
                // We have no such admin, reject request.
                echo "<script>alert('用户不存在，请检查用户名.');location.href='index.php';</script>";
                exit(0);
            }else{
                // Admin Exists, proceed to authenticate.
                $sql = "SELECT password FROM admins WHERE name = '".$login_name."';";
                $re = $pdo -> query($sql);
                $rows = $re->fetchAll();
                if($rows == null){
                    // THIS PERSON DOES NOT EXIST.
                    echo "<script>alert('用户密码错误.');location.href='index.php';</script>";
                    exit(0);
                }else{
                    // PASSWORD MATCHING
                    if(password_verify($login_pre_password, $rows[0][0])){
                        if(!isset($_COOKIE['name'])){
                            setcookie("name", $login_name,time()+3600);
                        }
                        echo "<script>location.href='index.php';</script>";
                        exit(0);
                    }else{
                        echo "<script>alert('用户密码错误.');location.href='index.php';</script>";
                        exit(0);
                    }
                }
            }

        }catch(PDOException $e){
            echo "<h3 style='text-align:center; color:red;'>Database Disconnected.</h3>";
            echo "<script>alert('此时无法连接数据库，如果问题一直存在，请向管理员报告.');location.href='index.php';</script>";
        }
    }
    catch(PDOException $e)
    {
        echo "<h3 style='text-align:center; color:red;'>Database Disconnected.</h3>";
        echo "<script>alert('此时无法连接数据库，如果问题一直存在，请向管理员报告.');location.href='index.php';</script>";
    }

?>