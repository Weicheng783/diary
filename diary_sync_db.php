<html>
    <head>
        <meta charset="utf-8">
        <title>日记本同步</title>
        <meta name="author" content="2022">
        <meta name="revised" content="2022-7-19">
    </head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

    <body style="display:center; background-color: antiquewhite;">
        <div id='header_group' style="display:block; text-align: center;"></div>
        <p class="narrator" style="font-size: x-large; text-align: center; " id="ymd"></p>
        <p class="narrator" style="font-size: x-large; text-align: center; ">上游服务器同步系统---正在同步</p>
        <p style="text-align: center;"><button type="submit" class="header_button" onclick="location.href='diary.php'" style="text-align: center;">回到主页面</button></p>
        <p style="text-align: center;"><button type="submit" class="header_button" onclick="location.href='diary_sync.php'" style="text-align: center;">回到同步页面</button></p>

    <?php
        header("Content-Type: text/html; charset=utf-8");

        if (!isset($_COOKIE['diary_name'])){
            echo "<script>location.href='diary.php'</script>";
            exit(0);
        }

        setcookie("diary_sync_server", "", time());
        setcookie("diary_sync_server", $_REQUEST['diary_sync_server'], 2147483647);

        setcookie("diary_sync_pwd", "", time());
        setcookie("diary_sync_pwd", $_REQUEST['diary_sync_pwd'], 2147483647);

        setcookie("diary_sync_user", "", time());
        setcookie("diary_sync_user", $_REQUEST['diary_sync_user'], 2147483647);

        setcookie("diary_sync_port", "", time());
        setcookie("diary_sync_port", $_REQUEST['diary_sync_port'], 2147483647);

        // echo '<div style="text-align:center; ">';
        // echo '<form action="diary_sync_db.php" method="post" style="display:center;">
        // <p>上游服务器域名或ip地址: Address/IP: <input type="input" name="diary_server" class="input_font" value="'.$sync.'"></input></p>
        // <p>服务器端口 Server Port: <input type="input" name="diary_server_port" class="input_font" value="'.$sync_port.'"></input></p>
        // <p>服务器用户名 Server Login Name: <input type="input" name="diary_server_user" class="input_font" value="'.$sync_user.'"></input></p>
        // <p>服务器密码 Server Login Password: <input type="password" name="diary_server_password" class="input_font" value="'.$sync_pwd.'"></input></p>
        // <input type="hidden" name="verification" value="20220719"></input>
        // <button type="submit" class="header_button" onclick="">开始同步</button>
        // </form>';
        // echo '</div>';

        // First Pass: Server down to Local
        try{
            // login server
            $user=$_REQUEST['diary_sync_user'];
            $password=$_REQUEST['diary_sync_pwd'];
            $dsn="mysql:host=".$_REQUEST['diary_sync_server']."; port=".$_REQUEST['diary_sync_port']."; dbname=diary";
            $pdo=new PDO($GLOBALS['dsn'],$GLOBALS['user'], $GLOBALS['password']);
            $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            echo "<h3 style='text-align:center; color:green;'>✅上游服务器连接成功。</h3>";
        }catch(PDOException $e){
            echo "<h3 style='text-align:center; color:red;'>❌同步中断：给定的上游服务器无法连接。</h3>";
            exit(0);
        }

        try{
            // login local server
            $dsn_local="mysql:host=".$_COOKIE['diary_server']."; port=".$_COOKIE['diary_server_port']."; dbname=diary";
            $pdo_local=new PDO($GLOBALS['dsn_local'],$_COOKIE['diary_server_user'], $_COOKIE['diary_server_password']);
            $pdo_local -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            echo "<h3 style='text-align:center; color:green;'>✅本地服务器连接成功。</h3>";
        }catch(PDOException $e){
            echo "<h3 style='text-align:center; color:red;'>❌同步中断：本地服务器无法连接。</h3>";
            exit(0);
        }

        echo "<h3 style='text-align:center; color:brown;'>正在把 本地服务器 对比 远程服务器，不同之处会下载到本地服务器。</h3>";
        $diary_diff = 0;
        $gallery_diff = 0;
        $comments_diff = 0;

        // task1: diary raw entries pull
        // Fetch all diary entries from server then compare with local
        $sql_diary_up = 'SELECT * FROM `diary`;';
        $stmt = $pdo->query($sql_diary_up);
        $diary_up_row_count = $stmt->rowCount();
        $diary_up_rows = $stmt->fetchAll();
        echo "<h3 style='text-align:center; color:brown;'>远程服务器 diary 记录条数：".$diary_up_row_count."</h3>";
        if($diary_up_row_count != 0){
            for($i=0; $i < $diary_up_row_count; $i++){
                // compare with local by time + content
                $sql_diary_down = 'SELECT * FROM `diary` WHERE `time`="'.$diary_up_rows[$i]['time'].'" AND `content`="'.$diary_up_rows[$i]['content'].'"';
                $stmt_cmp = $pdo_local->query($sql_diary_down);
                $diary_down_row_count = $stmt_cmp->rowCount();
                $diary_down_rows = $stmt_cmp->fetchAll();
                // if the local server had no record, we add record to this server and return its diary_id
                if($diary_down_row_count == 0){
                    $diary_diff ++;

                    $flash_sql = 'INSERT INTO `diary`(`time`, `content`, `status`) VALUES ("'.$diary_up_rows[$i]['time'].'","'.$diary_up_rows[$i]['content'].'","'.$diary_up_rows[$i]['status'].'")';
                    $pdo_local->query($flash_sql);
                    // get the new added diary_id
                    $sql_diary_down = 'SELECT * FROM `diary` WHERE `time`="'.$diary_up_rows[$i]['time'].'" AND `content`="'.$diary_up_rows[$i]['content'].'"';
                    $stmt_cmp = $pdo_local->query($sql_diary_down);
                    $diary_down_rows = $stmt_cmp->fetchAll();
                }

                $diary_local_id = $diary_down_rows[0]['diary_id'];
                $diary_remote_id = $diary_up_rows[$i]['diary_id'];

                // task2: gallery pull
                $sql_variable_up = "SELECT * FROM `gallery` WHERE `diary_id`=".$diary_remote_id."";
                $stmt_var = $pdo->query($sql_variable_up);
                $var_up_row_count = $stmt_var->rowCount();
                $var_up_rows = $stmt_var->fetchAll();
                for($j=0; $j < $var_up_row_count; $j++){
                    $sql_variable_down = "SELECT * FROM `gallery` WHERE `diary_id`=".$diary_local_id." AND `uuid`='".$var_up_rows[$j]['uuid']."'";
                    $stmt_gallery_local = $pdo_local->query($sql_variable_down);
                    $gallery_down_count = $stmt_gallery_local->rowCount();
                    $gallery_down_rows = $stmt_gallery_local->fetchAll();
                    if($gallery_down_count == 0){
                        $gallery_diff ++;

                        $queryre1 = $pdo_local->query("SELECT `source_id` FROM `gallery` WHERE `diary_id`=".$diary_local_id." ORDER BY source_id DESC LIMIT 0,1");
                        $rows1 = $queryre1->fetchAll();
                        $rows1_count = $queryre1->rowCount();
                        if($rows1_count == 0){
                            $gallery_local_put_source_id = 1;
                        }else{
                            $gallery_local_put_source_id = $rows1[0]['source_id'] + 1;
                        }

                        $pdo_local->query("INSERT INTO `gallery`(`diary_id`, `source_id`, `uuid`, `address`, `address2`) VALUES ('".$diary_local_id."','".$gallery_local_put_source_id."','".$var_up_rows[$j]['uuid']."','".$var_up_rows[$j]['address']."','".$var_up_rows[$j]['address2']."')");

                    }

                }

                // task3: comments pull
                $sql_variable_up = "SELECT * FROM `comments` WHERE `diary_id`=".$diary_remote_id."";
                $stmt_var = $pdo->query($sql_variable_up);
                $var_up_row_count = $stmt_var->rowCount();
                $var_up_rows = $stmt_var->fetchAll();
                for($j=0; $j < $var_up_row_count; $j++){
                    $sql_variable_down = "SELECT * FROM `comments` WHERE `diary_id`=".$diary_local_id." AND `uuid`='".$var_up_rows[$j]['uuid']."'";
                    $stmt_gallery_local = $pdo_local->query($sql_variable_down);
                    $gallery_down_count = $stmt_gallery_local->rowCount();
                    $gallery_down_rows = $stmt_gallery_local->fetchAll();
                    if($gallery_down_count == 0){
                        $comments_diff ++;

                        $queryre1 = $pdo_local->query("SELECT `comment_id` FROM `comments` WHERE `diary_id`=".$diary_local_id." ORDER BY comment_id DESC LIMIT 0,1");
                        $rows1 = $queryre1->fetchAll();
                        $rows1_count = $queryre1->rowCount();
                        if($rows1_count == 0){
                            $gallery_local_put_source_id = 1;
                        }else{
                            $gallery_local_put_source_id = $rows1[0]['comment_id'] + 1;
                        }

                        $pdo_local->query("INSERT INTO `comments`(`diary_id`, `comment_id`, `uuid`, `address`, `address2`) VALUES ('".$diary_local_id."','".$gallery_local_put_source_id."','".$var_up_rows[$j]['uuid']."','".$var_up_rows[$j]['address']."','".$var_up_rows[$j]['address2']."')");

                    }

                }

            }
        }

        echo "<h3 style='text-align:center; color:brown;'>下载结果：本地新建 ".$diary_diff." 条 diary 记录，".$gallery_diff." 条 gallery 记录，".$comments_diff." 条 comments 记录。</h3>";
        echo "<h3 style='text-align:center; color:green;'>✅ 服务器 -> 本地 传输完成。</h3>";



        echo "<h3 style='text-align:center; color:brown;'>正在把 远程服务器 对比 本地服务器，不同之处会上传到远程服务器。</h3>";
        $diary_diff = 0;
        $gallery_diff = 0;
        $comments_diff = 0;

        // task1: diary raw entries pull
        // Fetch all diary entries from server then compare with local
        $sql_diary_up = 'SELECT * FROM `diary`;';
        $stmt = $pdo_local->query($sql_diary_up);
        $diary_up_row_count = $stmt->rowCount();
        $diary_up_rows = $stmt->fetchAll();
        echo "<h3 style='text-align:center; color:brown;'>本地服务器 diary 记录条数：".$diary_up_row_count."</h3>";
        if($diary_up_row_count != 0){
            for($i=0; $i < $diary_up_row_count; $i++){
                // compare with local by time + content
                $sql_diary_down = 'SELECT * FROM `diary` WHERE `time`="'.$diary_up_rows[$i]['time'].'" AND `content`="'.$diary_up_rows[$i]['content'].'"';
                $stmt_cmp = $pdo->query($sql_diary_down);
                $diary_down_row_count = $stmt_cmp->rowCount();
                $diary_down_rows = $stmt_cmp->fetchAll();
                // if the local server had no record, we add record to this server and return its diary_id
                if($diary_down_row_count == 0){
                    $diary_diff ++;

                    $flash_sql = 'INSERT INTO `diary`(`time`, `content`, `status`) VALUES ("'.$diary_up_rows[$i]['time'].'","'.$diary_up_rows[$i]['content'].'","'.$diary_up_rows[$i]['status'].'")';
                    $pdo->query($flash_sql);
                    // get the new added diary_id
                    $sql_diary_down = 'SELECT * FROM `diary` WHERE `time`="'.$diary_up_rows[$i]['time'].'" AND `content`="'.$diary_up_rows[$i]['content'].'"';
                    $stmt_cmp = $pdo->query($sql_diary_down);
                    $diary_down_rows = $stmt_cmp->fetchAll();
                }

                $diary_local_id = $diary_down_rows[0]['diary_id'];
                $diary_remote_id = $diary_up_rows[$i]['diary_id'];

                // task2: gallery pull
                $sql_variable_up = "SELECT * FROM `gallery` WHERE `diary_id`=".$diary_remote_id."";
                $stmt_var = $pdo_local->query($sql_variable_up);
                $var_up_row_count = $stmt_var->rowCount();
                $var_up_rows = $stmt_var->fetchAll();
                for($j=0; $j < $var_up_row_count; $j++){
                    $sql_variable_down = "SELECT * FROM `gallery` WHERE `diary_id`=".$diary_local_id." AND `uuid`='".$var_up_rows[$j]['uuid']."'";
                    $stmt_gallery_local = $pdo->query($sql_variable_down);
                    $gallery_down_count = $stmt_gallery_local->rowCount();
                    $gallery_down_rows = $stmt_gallery_local->fetchAll();
                    if($gallery_down_count == 0){
                        $gallery_diff ++;

                        $queryre1 = $pdo->query("SELECT `source_id` FROM `gallery` WHERE `diary_id`=".$diary_local_id." ORDER BY source_id DESC LIMIT 0,1");
                        $rows1 = $queryre1->fetchAll();
                        $rows1_count = $queryre1->rowCount();
                        if($rows1_count == 0){
                            $gallery_local_put_source_id = 1;
                        }else{
                            $gallery_local_put_source_id = $rows1[0]['source_id'] + 1;
                        }

                        $pdo->query("INSERT INTO `gallery`(`diary_id`, `source_id`, `uuid`, `address`, `address2`) VALUES ('".$diary_local_id."','".$gallery_local_put_source_id."','".$var_up_rows[$j]['uuid']."','".$var_up_rows[$j]['address']."','".$var_up_rows[$j]['address2']."')");

                    }

                }

                // task3: comments pull
                $sql_variable_up = "SELECT * FROM `comments` WHERE `diary_id`=".$diary_remote_id."";
                $stmt_var = $pdo_local->query($sql_variable_up);
                $var_up_row_count = $stmt_var->rowCount();
                $var_up_rows = $stmt_var->fetchAll();
                for($j=0; $j < $var_up_row_count; $j++){
                    $sql_variable_down = "SELECT * FROM `comments` WHERE `diary_id`=".$diary_local_id." AND `uuid`='".$var_up_rows[$j]['uuid']."'";
                    $stmt_gallery_local = $pdo->query($sql_variable_down);
                    $gallery_down_count = $stmt_gallery_local->rowCount();
                    $gallery_down_rows = $stmt_gallery_local->fetchAll();
                    if($gallery_down_count == 0){
                        $comments_diff ++;

                        $queryre1 = $pdo->query("SELECT `comment_id` FROM `comments` WHERE `diary_id`=".$diary_local_id." ORDER BY comment_id DESC LIMIT 0,1");
                        $rows1 = $queryre1->fetchAll();
                        $rows1_count = $queryre1->rowCount();
                        if($rows1_count == 0){
                            $gallery_local_put_source_id = 1;
                        }else{
                            $gallery_local_put_source_id = $rows1[0]['comment_id'] + 1;
                        }

                        $pdo->query("INSERT INTO `comments`(`diary_id`, `comment_id`, `uuid`, `address`, `address2`) VALUES ('".$diary_local_id."','".$gallery_local_put_source_id."','".$var_up_rows[$j]['uuid']."','".$var_up_rows[$j]['address']."','".$var_up_rows[$j]['address2']."')");

                    }

                }

            }
        }

        echo "<h3 style='text-align:center; color:brown;'>上传了 ".$diary_diff." 条 diary 记录，".$gallery_diff." 条 gallery 记录，".$comments_diff." 条 comments 记录。</h3>";
        echo "<h3 style='text-align:center; color:green;'>✅ 本地 -> 服务器 传输完成。</h3>";





    ?>

    </body>

</html>


<script>
    function fun(){
        var date = new Date()
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate(); 
        var hh = date.getHours();
        var mm = date.getMinutes();
        var ss = date.getSeconds();
        if(hh <= 6 & hh >= 0){
            var notice = "凌晨好，好梦."
        }else if(hh > 6 & hh < 11){
            var notice = "现在是早上或上午，抓紧时间做事情了."
        }else if(hh >= 11  & hh <= 12){
            var notice = "正在中午."
        }else if(hh > 12 & hh <= 18){
            var notice = "现在是下午."
        }else if(hh >= 19 & hh <= 22){
            var notice = "晚上来了."
        }else if(hh > 22 & hh <= 23){
            var notice = "晚安，好梦."
        }else{
            var notice = "Have a nice day."
        }

        document.getElementById("ymd").innerHTML = +y+"-"+m+"-"+d+" "+hh+":"+mm+":"+ss+" "+notice+"";
        setTimeout("fun()",1000)
    }


    window.onload = function(){
        setTimeout("fun()",0)
    }
</script>


<style>
    img:focus {
        outline: 5px solid orange;
        border-radius: 5px;
    }

    #map {
        position: relative; 
        top: 0; 
        right: 0; 
        bottom: 0; 
        left: 0;
        border-radius: 5px;
        border-width: 5px;
        border: solid;
        border-color: skyblue;
        background-color: antiquewhite;
        text-align: center;
        display:inline-block;
        margin-left: 25%;
    }

    .narrator{
        /* animation-name: narrator_enter; 
        animation-duration:5s; */
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    }

    .table_font{
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        font-size: 20px;
    }

    .input_font{
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        font-size: 25px;
        text-align: center;
    }

    @keyframes narrator_enter {
        0%   {margin-top:-50px;}
        100% {margin-top:15px;}
    }

    #logo{
        text-align:left;
    }

    #header {
        
        /* display: inline-block; */
        border-radius: 5px;
        border-width: 5px;
        border: solid;
        border-color: skyblue;
        background-color: antiquewhite;
        text-align: center;
        display:inline-block;
        margin-left: 25%;
        /* margin-right: 50%; */
        
    }

    .header_button {
        margin: 20px, 20px, 20px, 20px;
        border-radius: 10px;
        /* text-align: right; */

        font-size: large;
    }

    .header_button:hover{
        background-color: rgb(36, 200, 221);
    }

    .header_button:active{
        background-color: sandybrown;
    }

    .good{
        text-align: center;    
    }

</style>