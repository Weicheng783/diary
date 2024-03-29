<html>
    <head>
        <meta charset="utf-8">
        <title>我的日记本</title>
        <meta name="author" content="2022">
        <meta name="revised" content="2022-7-18">
    </head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

    <body style="display:center; background-color: white;">
        <div id='header_group' style="display:block; text-align: center;"></div>
        <p class="narrator" style="font-size: x-large; text-align: center; " id="ymd"></p>
        <p style="text-align: center;"><button type="submit" class="header_button" onclick="location.href='diary.php'" style="text-align: center;">回到主页面</button></p>
        <p style="text-align: center;"><button type="submit" class="header_button" onclick="location.href='diary_reset_cookie.php'" style="text-align: center; color:red;">清除Cookies</button></p>
        <p style="text-align: center;"><button type="submit" class="header_button" onclick="location.href='diary_sync.php'" style="text-align: center;">同步上游服务器</button></p>

    <?php
        header("Content-type:text/html;charset=utf-8");
        date_default_timezone_set('Europe/London');

        if (!isset($_COOKIE['diary_name'])){
            echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">';
            echo '<div id="writingArea" style="text-align:center; border-style:dashed; border-width:3px; border-radius:5px; padding:5px; margin:5px;">';
            echo "<p><strong>日记认证系统-更改页面</strong></p>";
            echo '<form action="diary_login.php" method="post" style="display:center;">
                    <p>日记记录员 Diary Keeper: <input type="input" name="diary_name" class="input_font"></input></p>
                    <p>密钥 Password: <input type="password" name="diary_password" class="input_font"></input></p>
                    <button type="submit" class="header_button" onclick="">进入</button>
                    </form>';
            echo '</div>';
            try{
                if(!isset($_COOKIE['diary_server']) or !isset($_COOKIE['diary_server_port']) or !isset($_COOKIE['diary_server_user']) or !isset($_COOKIE['diary_server_password'])){
                    echo "<h3 style='text-align:center; color:orange;'>Data Server Not Specified, please do this in setup. 请配置服务器.</h3>";
                    echo '<p style="text-align: center;"><button type="submit" class="header_button" onclick="location.href=\'diary_setup.php\'" style="text-align: center;">配置服务器</button></p>';
                }else{
                    $pdo = new pdo('mysql:host='.$_COOKIE['diary_server'].'; port='.$_COOKIE['diary_server_port'].'; dbname=diary', $_COOKIE['diary_server_user'], $_COOKIE['diary_server_password']);
                    echo "<h3 style='text-align:center; color:green;'>Database Status Normal (port ".$_COOKIE['diary_server_port']."). 数据库正常.</h3>";
                }

            }catch(PDOException $e){
                echo "<h3 style='text-align:center; color:red;'>Database Disconnected (port ".$_COOKIE['diary_server_port']."). 数据库目前无法正常连接.</h3>";
                echo '<p style="text-align: center;"><button type="submit" class="header_button" onclick="location.href=\'diary_setup.php\'" style="text-align: center;">检查服务器设置</button></p>';
            }
        }else{

            echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">';

            // Direct jumping
            if(!isset($_REQUEST['search_index']) or $_REQUEST['search_index'] == ""){
                // do nothing
            }else{
                // echo "<script>location.href='diary_edit.php#search_index';</script>";
                setcookie("target", "" , time());
                setcookie("target", $_REQUEST['search_index'] , 2147483647);
                // sleep(1);
                // header("Refresh:0; url=page2.php");
                header("Refresh:0");
            }

            $year = date('Y');
            $month = date('m');
            $day = date('d');

            echo '<form action="diary_reset.php" method="post" style="display:center; text-align:center;">';
            echo '<p><button type="submit" class="header_button" onclick="" style="text-align:flex;">清除已登录状态</button></p>';
            echo '</form>';

            if(isset($_COOKIE['diary_year'])){
                $year = $_COOKIE['diary_year'];
            }

            if(isset($_COOKIE['diary_month'])){
                $month = $_COOKIE['diary_month'];
            }

            if(isset($_COOKIE['diary_day'])){
                $day = $_COOKIE['diary_day'];
            }

            if(isset($_COOKIE['diary_work'])){
                $work = $_COOKIE['diary_work'];
            }else{
                $work = "";
            }

            echo "<hr />";

            try{
                $pdo = new pdo('mysql:host='.$_COOKIE['diary_server'].'; port='.$_COOKIE['diary_server_port'].'; dbname=diary', $_COOKIE['diary_server_user'], $_COOKIE['diary_server_password']);
                $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

                $sql = 'SELECT * FROM `temporaryWork` ORDER BY `time` DESC LIMIT 0,1';

                $stmt = $pdo->query($sql);
                $row_count = $stmt->rowCount();
                $rows = $stmt->fetchAll();

                if($row_count == 0){
                    echo '<p class="narrator" style="font-size: large; text-align: center; color: purple">没有找到历史保存记录，请及时保存工作。</p>';
                }else{
                    echo '<p class="narrator" style="font-size: large; text-align: center; color: purple" id="server_saved_time"></p>';
                    echo "<script>document.getElementById('server_saved_time').innerHTML = '最近一次保存于: ' + '".$rows[0]['time']."' + ', 请及时保存工作。';</script>";
                    if(!isset($_COOKIE['diary_work_preference'])){
                        $work = $rows[0]['content'];
                        // print_r($work);
                        setcookie("diary_work", "", time());
                        setcookie("diary_work", $rows[0]['content'], 2147483647);
                    }
                }

            }catch(PDOException $e){
                
            }

            echo '<form action="diary_save.php" method="post" style="display:center; text-align:center;" id="savework">
            <textarea style="display:none; width:0%; height:0%; text-align:left; font-size: 0px;" name="content" rows="0" placeholder="" class="input_font" onkeyup="saveWork()" id="work1">'.$work.'</textarea>
            </form>';

            $sql = 'SELECT diary_id FROM `diary` ORDER BY `time` DESC LIMIT 0,1';
            if(@$pdo != NULL){
                $stmt = $pdo->query($sql);
                $rows = $stmt->fetchAll();
            }else{
                echo "<p>目前数据库无法连接，在离线模式下请尽快保存重要文件，并快速登出。</p>";
                echo "<p>你最后编辑自动保存的内容是：".$_COOKIE['diary_work']."</p>";
                die;
            }

            if($rows != NULL){
                $count = $rows[0]['diary_id'];
                $count += 1;
            }else{
                $count = 1;

                // We have the responsibility to reset the auto_increment to 1
                $sql = 'ALTER TABLE `diary` AUTO_INCREMENT = 1';
                if($pdo != NULL){
                    $pdo->query($sql);
                }

            }

            $sql = 'SELECT * FROM `gallery`';

            if($pdo != NULL){
                $stmt = $pdo->query($sql);
                $count_gallery = $stmt->rowCount();
                $count_gallery += 1;
            }




            echo '<p class="narrator" style="font-size: large; text-align: center; color: purple">当前在写第 '.$count.' 条记录.</p>';

            echo '<form action="diary_post.php" method="post" style="display:center; text-align:center;" id="date">
                    <button type="submit" class="header_button" onclick="" style="text-align:flex;" form="savework">保存</button>
                    <p><textarea style="width:80%; text-align:left; font-size: 20px;" name="content" rows="6" placeholder="#开始记录生活" class="input_font" onkeyup="saveWork()" id="work">'.$work.'</textarea></p>
                    <input type="hidden" name="status" value="normal" class="input_font"</input>
                    <button type="submit" class="header_button" onclick="" style="text-align:flex;">记录</button>
                    </form>';
            
            echo '<form action="diary_photo_upload_ftp.php" name="form" method="post" enctype="multipart/form-data" style="font-size: large; text-align: center; color: purple">  
                    图片上传: <input type="file" multiple name="uploads[]" />
                    <input type="submit" name="submit" value="上传" />';
            echo '<input type="hidden" name="diary_id" value="'.$count.'" class="input_font">';
            echo '<input type="hidden" name="source_id" value="'.$count_gallery.'" class="input_font">';
            echo '</form>';

            echo '<form action="diary_photo_unlink.php" name="form" method="post" enctype="multipart/form-data" style="font-size: large; text-align: center; color: purple">  
            图片取消连接序号(source_id from 1): <input type="hidden" name="diary_id" value="'.$count.'" class="input_font">
            <input type="number" name="source_id" id="unlink_editor"/>
            <input type="submit" name="submit" value="取消连接(数据库条目删除)" />';
            echo '</form>';

            // BEGIN: PICTURE SHOWING
            $sql = 'SELECT * FROM `gallery` WHERE `diary_id` = "'.$count.'"';
            $stmt = $pdo->query($sql);
            $row_count = $stmt->rowCount();
            $rows = $stmt->fetchAll();
            if(!$row_count == 0){
                for($i=0; $i<$row_count; $i++){
                    if(substr($rows[$i]['address'], -3, -1) != "mp4" && substr($rows[$i]['address'], -3, -1) != "avi" && substr($rows[$i]['address'], -3, -1) != "ogg" && substr($rows[$i]['address'], -3, -1) != "mov"){
                        // We can show pictures then
                        echo '<img src="'.$rows[$i]['address'].'" alt="'.$rows[$i]['address'].'" width="200" height="200" style="border-radius:5px; margin:2px; " id="'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'" tabindex="0"></img>';
                        echo '<img src="'.$rows[$i]['address2'].'" alt="'.$rows[$i]['address2'].'" width="200" height="200" style="border-radius:5px; margin:2px; " id="'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'_alt" tabindex="0"></img>';
                        echo '<script>
                                document.getElementById("'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'").onclick = function(){
                                    document.getElementById("'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'").style.width = 200; 
                                    document.getElementById("'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'").style.height = 200; 

                                    document.getElementById("unlink_editor").value = '.$rows[$i]['source_id'].'; 
                                }

                                document.getElementById("'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'").ondblclick = function(){
                                    document.getElementById("'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'").style.width = getWidth()-15; 
                                    document.getElementById("'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'").style.height = getHeight()*0.4; 
                                }
                            </script>';

                        echo '<script>
                            document.getElementById("'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'_alt").onclick = function(){
                                document.getElementById("'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'_alt").style.width = 200; 
                                document.getElementById("'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'_alt").style.height = 200; 

                                document.getElementById("unlink_editor").value = '.$rows[$i]['source_id'].'; 
                            }

                            document.getElementById("'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'_alt").ondblclick = function(){
                                document.getElementById("'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'_alt").style.width = getWidth()-15; 
                                document.getElementById("'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'_alt").style.height = getHeight()*0.4; 
                            }
                        </script>';
                    }else{
                        echo '<video width="200" height="200" controls>';
                        //width="320" height="240"
                        echo '<source src="'.$rows[$i]['address'].'" type="video/mp4" >';
                        echo 'Your browser does not support the video tag.</video>';

                        echo '<video width="200" height="200" controls>';
                        //width="320" height="240"
                        echo '<source src="'.$rows[$i]['address2'].'" type="video/mp4" >';
                        echo 'Your browser does not support the video tag.</video>';
                    }

                }
            }
            // END: PICTURE SHOWING

            echo "<hr />";

            //<button type="submit" class="header_button" onclick="" style="text-align:flex;" form="view" disabled="disabled">查看当日PDF大文档[功能已淘汰]</button>

            echo '<form action="diary_setEditTarget.php" method="post" style="display:center; text-align:center;" id="date2">
                    <p>序号: <input type="input" name="target" class="input_font" id="search_index"></input></p>
                    <button type="submit" class="header_button" onclick="" style="text-align:flex;">检索</button>
                    </form>';

            // echo '<form action="diary_dateReset.php" method="post" style="display:center; text-align:center;">';
            // echo '<p><button type="submit" class="header_button" onclick="" style="text-align:flex;">回到今天</button></p>';
            // echo '</form>';

            // echo '<form action="diary_seeAll.php" method="post" style="display:center; text-align:center;">';
            // echo '<p><button type="submit" class="header_button" onclick="" style="text-align:flex;">分批查看所有</button></p>';
            // echo '</form>';
            // echo '<form action="viewPDF.php" method="post" style="display:center; text-align:center;" id="view">';
            // echo '<input type="hidden" name="year" value="'.$year.'" class="input_font" id="aa"></input>
            // <input type="hidden" name="month" value="'.$month.'" class="input_font" id="bb"></input>
            // <input type="hidden" name="day" value="'.$day.'" class="input_font" id="cc"></input>';
            // echo '</form>';


            // We fetch data from Data Base
            try{

                $pdo = new pdo('mysql:host='.$_COOKIE['diary_server'].'; port='.$_COOKIE['diary_server_port'].'; dbname=diary', $_COOKIE['diary_server_user'], $_COOKIE['diary_server_password']);
                $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

                // Last Registered diary First
                if(!isset($_COOKIE['target'])){
                    $sql = 'SELECT * FROM `diary` ORDER BY `time` DESC LIMIT 0,1';
                }else{
                    $sql = 'SELECT * FROM `diary` WHERE `diary_id` = "'.$_COOKIE['target'].'" ORDER BY `time` DESC';
                }

                $stmt = $pdo->query($sql);
                $row_count = $stmt->rowCount();
                $rows = $stmt->fetchAll();

                if($row_count == 0){
                    echo'<p class="narrator" style="font-size: x-large; text-align: center;">查无此记录，请检查编号。</p>';
                }else{

                    for($i = 0; $i < $row_count; $i++){

                        echo "<script>document.getElementById('search_index').value = ".$rows[$i]['diary_id']."</script>";

                        if($rows[$i]['status'] != "removed" && $rows[$i]['status'] != "deleted" && $rows[$i]['status'] != "hide"){
                            echo '<hr /><p class="narrator" style="font-size: large; text-align: center;">' . $rows[$i]['time'] . " 总第 " . $rows[$i]['diary_id'] . " 条 <strong style='color=purple'>状态标志: [" . $rows[$i]['status'] . ']</strong></p>';
                            // echo '<p class="narrator" style="text-align: center;"><textarea style="width:0%; text-align:left; font-size: 18px;" name="content" rows="0" placeholder="#开始记录生活" class="input_font">'. $rows[$i]['content'] .'</textarea></p>';

                            echo '<form action="diary_update.php" method="post" style="display:center; text-align:center;">
                            <p><textarea style="width:80%; text-align:left; font-size: 20px;" name="content" rows="15" placeholder="#开始记录生活" class="input_font">'.$rows[$i]['content'].'</textarea></p>
                            <input type="hidden" name="id" class="input_font" value="'. $rows[$i]['diary_id'] .'"></input>
                            <button type="submit" class="header_button" onclick="" style="text-align:flex;">更改</button>
                            </form>';

                            echo '<form action="diary_photo_upload.php" name="form" method="post" enctype="multipart/form-data" style="font-size: large; text-align: center; color: purple">  
                            图片上传: <input type="file" multiple name="uploads[]" />
                            <input type="submit" name="submit" value="上传" />';
                            echo '<input type="hidden" name="diary_id" value="'.$rows[$i]['diary_id'].'" class="input_font">';
                            echo '<input type="hidden" name="source_id" value="'.$count_gallery.'" class="input_font">';
                            echo '</form>';

                            echo '<form action="diary_photo_unlink.php" name="form" method="post" enctype="multipart/form-data" style="font-size: large; text-align: center; color: purple">  
                            图片取消连接序号(source_id from 1): <input type="hidden" name="diary_id" value="'. $rows[$i]['diary_id'] .'" class="input_font">
                            <input type="number" name="source_id" id="unlink_'.$rows[$i]['diary_id'].'"/>
                            <input type="submit" name="submit" value="取消连接(数据库条目删除)" />';
                            echo '</form>';



                            echo '<form action="diary_delete.php" method="post" style="display:center; text-align:center;" id="date">
                                <input type="hidden" name="id" class="input_font" value="'. $rows[$i]['diary_id'] .'"></input>
                                <input type="hidden" name="method" class="input_font" value="local"></input>
                                <button type="submit" class="header_button" onclick="" style="text-align:flex; color:red;">本地删除(所有关联也将删除)</button>
                                </form>';

                            echo '<form action="diary_delete.php" method="post" style="display:center; text-align:center;" id="date">
                                <input type="hidden" name="id" class="input_font" value="'. $rows[$i]['diary_id'] .'"></input>
                                <input type="hidden" name="method" class="input_font" value="remote"></input>
                                <button type="submit" class="header_button" onclick="" style="text-align:flex; color:red;">远程服务器删除</button>
                                </form>';

                            echo '<form action="diary_delete.php" method="post" style="display:center; text-align:center;" id="date">
                                <input type="hidden" name="id" class="input_font" value="'. $rows[$i]['diary_id'] .'"></input>
                                <input type="hidden" name="method" class="input_font" value="dual"></input>
                                <button type="submit" class="header_button" onclick="" style="text-align:flex; color:red;">双清</button>
                                </form>';


                            // BEGIN: PICTURE SHOWING
                            $sql = 'SELECT * FROM `gallery` WHERE `diary_id` = "'.$rows[$i]['diary_id'].'"';
                            $stmt = $pdo->query($sql);
                            $row_count1 = $stmt->rowCount();
                            $rows1 = $stmt->fetchAll();
                            if(!$row_count1 == 0){
                                for($j=0; $j<$row_count1; $j++){
                                    if(substr($rows1[$j]['address'], -3, -1) != "mp4" && substr($rows1[$j]['address'], -3, -1) != "avi" && substr($rows1[$j]['address'], -3, -1) != "ogg" && substr($rows1[$j]['address'], -3, -1) != "mov"){
                                        // We can show pictures then
                                        echo '<img src="'.$rows1[$j]['address'].'" alt="'.$rows1[$j]['address'].'" width="200" height="200" style="border-radius:5px; margin:2px; " id="'.$rows1[$j]['diary_id'].'_'.$rows1[$j]['source_id'].'" tabindex="0"></img>';
                                        echo '<img src="'.$rows1[$j]['address2'].'" alt="'.$rows1[$j]['address2'].'" width="200" height="200" style="border-radius:5px; margin:2px; " id="'.$rows1[$j]['diary_id'].'_'.$rows1[$j]['source_id'].'_alt" tabindex="0"></img>';
                                        echo '<script>
                                                document.getElementById("'.$rows1[$j]['diary_id'].'_'.$rows1[$j]['source_id'].'").onclick = function(){
                                                    document.getElementById("'.$rows1[$j]['diary_id'].'_'.$rows1[$j]['source_id'].'").style.width = 200; 
                                                    document.getElementById("'.$rows1[$j]['diary_id'].'_'.$rows1[$j]['source_id'].'").style.height = 200; 

                                                    document.getElementById("unlink_'.$rows[$i]['diary_id'].'").value = '.$rows1[$j]['source_id'].'; 
                                                }

                                                document.getElementById("'.$rows1[$j]['diary_id'].'_'.$rows1[$j]['source_id'].'").ondblclick = function(){
                                                    document.getElementById("'.$rows1[$j]['diary_id'].'_'.$rows1[$j]['source_id'].'").style.width = getWidth()-15; 
                                                    document.getElementById("'.$rows1[$j]['diary_id'].'_'.$rows1[$j]['source_id'].'").style.height = getHeight()*0.4; 
                                                }
                                            </script>';

                                        echo '<script>
                                            document.getElementById("'.$rows1[$i]['diary_id'].'_'.$rows1[$i]['source_id'].'_alt").onclick = function(){
                                                document.getElementById("'.$rows1[$i]['diary_id'].'_'.$rows1[$i]['source_id'].'_alt").style.width = 200; 
                                                document.getElementById("'.$rows1[$i]['diary_id'].'_'.$rows1[$i]['source_id'].'_alt").style.height = 200; 
            
                                                document.getElementById("unlink_'.$rows[$i]['diary_id'].'").value = '.$rows1[$i]['source_id'].'; 
                                            }
            
                                            document.getElementById("'.$rows1[$i]['diary_id'].'_'.$rows1[$i]['source_id'].'_alt").ondblclick = function(){
                                                document.getElementById("'.$rows1[$i]['diary_id'].'_'.$rows1[$i]['source_id'].'_alt").style.width = getWidth()-15; 
                                                document.getElementById("'.$rows1[$i]['diary_id'].'_'.$rows1[$i]['source_id'].'_alt").style.height = getHeight()*0.4; 
                                            }
                                            </script>';
                                    }else{
                                        echo '<video width="200" height="200" controls>';
                                        //width="320" height="240"
                                        echo '<source src="'.$rows1[$j]['address'].'" type="video/mp4" >';
                                        echo 'Your browser does not support the video tag.</video>';

                                        echo '<video width="200" height="200" controls>';
                                        //width="320" height="240"
                                        echo '<source src="'.$rows1[$j]['address2'].'" type="video/mp4" >';
                                        echo 'Your browser does not support the video tag.</video>';
                                    }

                                }
                            }
                            // END: PICTURE SHOWING

                        }
                    }
                }

            }catch(PDOException $e){
                echo "<script>alert('目前无法连接到数据库.');</script>";
            }
        
        }
        
            // print_r($rows);
    ?>


    </body>

</html>




<script>

function getWidth() {
  return Math.max(
    document.body.scrollWidth,
    document.documentElement.scrollWidth,
    document.body.offsetWidth,
    document.documentElement.offsetWidth,
    document.documentElement.clientWidth
  );
}

function getHeight() {
  return Math.max(
    document.body.scrollHeight,
    document.documentElement.scrollHeight,
    document.body.offsetHeight,
    document.documentElement.offsetHeight,
    document.documentElement.clientHeight
  );
}


function saveWork(){
    document.all["work1"].value=document.all["work"].value
}

function copya(){
    document.all["aa"].value=document.all["a"].value
}

function copyb(){
    document.all["bb"].value=document.all["b"].value
}

function copyc(){
    document.all["cc"].value=document.all["c"].value
}

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
        saveWork();
        setTimeout("fun()",1000)
    }


    window.onload = function(){
        setTimeout("fun()",0)
    }
</script>

<script>
//按键触发
document.getElementById("work").onkeydown = function(){
    //ctrl+s引导为内保存
    saveWork();
    if (event.ctrlKey && window.event.keyCode==83){
        var form = document.getElementById('savework');
        //再次修改input内容
        form.submit();
        return false;
    }
}

// const txHeight = 16;
// const tx = document.getElementsByTagName("textarea");

// for (let i = 0; i < tx.length; i++) {
//   if (tx[i].value == '') {
//     tx[i].setAttribute("style", "height:" + txHeight + "px; overflow-y:hidden; background-color:antiquewhite; width:80%; text-align:left; font-size: 18px;");
//   } else {
//     tx[i].setAttribute("style", "height:" + (tx[i].scrollHeight) + "px; overflow-y:hidden; background-color:antiquewhite; width:80%; text-align:left; font-size: 18px;");
//   }
//   tx[i].addEventListener("input", OnInput, false);
// }

// function OnInput() {
//   this.style.height = "auto";
//   this.style.height = (this.scrollHeight) + "px";
// }

// Hiding the image & video if it fails to load
const tx = document.getElementsByTagName("img");

for (let i = 0; i < tx.length; i++) {
    tx[i].addEventListener('error', function handleError() {
        // console.log(tx[i].src + " has loading error thus hiding.");
        tx[i].style.display = 'none';
        console.clear();
    });
}

const txx = document.getElementsByTagName("video");

for (let i = 0; i < txx.length; i++) {
    txx[i].addEventListener('error', function handleError() {
        // console.log(tx[i].src + " has loading error thus hiding.");
        txx[i].style.display = 'none';
        console.clear();
    });
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