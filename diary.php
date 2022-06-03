<html>
    <head>
        <meta charset="utf-8">
        <title>我的日记本</title>
        <meta name="author" content="2022">
        <meta name="revised" content="2022-1-27/28">
    </head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

    <body style="background-color: antiquewhite;">

            <div id='header_group' style="display:block; text-align: center;">
                <!-- <div style="display: inline-flex;"> -->
                <!-- <img src="./logo.png" id="logo" alt="Weicheng_Quiz_Welcome_Message" style=" text-align: left; border-radius:20px; display:inline-block; height:100px; width:auto;"> -->
            </div>

            <p class="narrator" style="font-size: x-large; text-align: center; " id="ymd"></p>
            <p class="narrator" style="font-size: x-large; text-align: center;">过好每一天的生活</p>

            <!-- <form action="reset.php" method="post" style="text-align:center; display:center;"> -->
              <!-- <p class="narrator"><button type="submit" class="header_button" onclick="">Log Out</button></p> -->
            <!-- </form> -->

<!--             <form action="create.php" method="post" style="text-align:center; display:center;">
              <p class="narrator"><button type="submit" class="header_button" onclick="">Go Back to Quiz page</button></p>
            </form> -->
    <?php
        $user = "weicheng";
        $password = "awc020826";

        header("Content-Type: text/html; charset=utf-8");
            if (!isset($_COOKIE['diary_name'])){
                echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">';
                echo '<div id="writingArea" style="text-align:center; border-style:dashed; border-width:3px; border-radius:5px; padding:5px; margin:5px;">';
                echo "<p><strong>日记中心认证系统--赶紧登录鸭。</strong></p>";
                // echo "<p>在下方输入授权用户名和密码，并点击提交:</p>";
                echo '<form action="diary_login.php" method="post" style="display:center;">
                        <p>日记记录员 Diary Keeper: <input type="input" name="diary_name" class="input_font"></input></p>
                        <p>密钥 Password: <input type="password" name="diary_password" class="input_font"></input></p>
                        <button type="submit" class="header_button" onclick="">Login/登入</button>
                      </form>';
                echo '</div>';
                try{
                    $pdo = new pdo('mysql:host=localhost; dbname=diary', $GLOBALS['user'], $GLOBALS['password']);
                    echo "<h3 style='text-align:center; color:green;'>Database Status Normal (3306). 数据库正常.</h3>";
                }catch(PDOException $e){
                    echo "<h3 style='text-align:center; color:red;'>Database Disconnected (3306). 数据库目前无法正常连接.</h3>";
                }
            }else{

                echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">';

                $year = date('Y');
                $month = date('m');
                $day = date('d');

                echo '<form action="diary_reset.php" method="post" style="display:center; text-align:center;">';
                echo '<p><button type="submit" class="header_button" onclick="" style="text-align:flex;">清除已登录状态</button></p>';
                echo '</form>';

                // echo '<p style="text-align:center;"><a href="showMap.php" class="header_button"><button type="button" class="header_button">当日活点轨迹地图(测试中)</button></a></p>';

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

                    $pdo = new pdo('mysql:host=localhost; dbname=diary', $GLOBALS['user'], $GLOBALS['password']);
                    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

                    $sql = 'SELECT * FROM `temporaryWork` ORDER BY `time` DESC LIMIT 0,1';

                    $stmt = $pdo->query($sql);
                    $row_count = $stmt->rowCount();
                    $rows = $stmt->fetchAll();

                    if($row_count == 0){
                        // echo "rowcount=0";
                        echo '<p class="narrator" style="font-size: large; text-align: center; color: purple">没有找到历史保存记录，请及时保存工作。</p>';
                    }else{
                        echo '<p class="narrator" style="font-size: large; text-align: center; color: purple" id="server_saved_time"></p>';
                        echo "<script>document.getElementById('server_saved_time').innerHTML = '最近一次保存于: ' + '".$rows[0]['time']."' + ', 请及时保存工作。';</script>";
                        if(!isset($_COOKIE['diary_work_preference'])){
                            $work = $rows[0]['content'];
                            // print_r($work);
                            setcookie("diary_work", "");
                            setcookie("diary_work", $rows[0]['content'], time()+7200);
                        }
                    }

                }catch(PDOException $e){
                    
                }

                // print_r($work);
                // TODO TODO TODO TODO TODO
                // exit(0);
                echo '<form action="diary_save.php" method="post" style="display:center; text-align:center;" id="savework">
                <textarea style="display:none; width:0%; height:0%; text-align:left; font-size: 0px;" name="content" rows="0" placeholder="#说说你的日常叭" class="input_font" onkeyup="saveWork()" id="work1">'.$work.'</textarea>
                </form>';

                $sql = 'SELECT diary_id FROM `diary` ORDER BY `time` DESC LIMIT 0,1';

                $stmt = $pdo->query($sql);
                $rows = $stmt->fetchAll();
                $count = $rows[0]['diary_id'];
                $count += 1;

                $sql = 'SELECT * FROM `gallery`';

                $stmt = $pdo->query($sql);
                $count_gallery = $stmt->rowCount();
                $count_gallery += 1;


                echo '<p class="narrator" style="font-size: large; text-align: center; color: purple">当前是总第 '.$count.' 条记录.</p>';

                echo '<form action="diary_post.php" method="post" style="display:center; text-align:center;" id="date">
                        <button type="submit" class="header_button" onclick="" style="text-align:flex;" form="savework">保存</button>
                        <p><textarea style="width:80%; text-align:left; font-size: 20px;" name="content" rows="15" placeholder="#说说你的日常叭" class="input_font" onkeyup="saveWork()" id="work">'.$work.'</textarea></p>
                        <input type="hidden" name="status" value="normal" class="input_font"</input>
                        <button type="submit" class="header_button" onclick="" style="text-align:flex;">记录</button>
                      </form>';
                
                echo '<form action="diary_photo_upload.php" name="form" method="post" enctype="multipart/form-data" style="font-size: large; text-align: center; color: purple">  
                        图片上传: <input type="file" multiple name="uploads[]" />
                        <input type="submit" name="submit" value="上传" />';
                echo '<input type="hidden" name="diary_id" value="'.$count.'" class="input_font">';
                echo '<input type="hidden" name="source_id" value="'.$count_gallery.'" class="input_font">';
                echo '</form>';

                
                $sql = 'SELECT * FROM `gallery` WHERE `diary_id` = "'.$count.'"';
                $stmt = $pdo->query($sql);
                $row_count = $stmt->rowCount();
                $rows = $stmt->fetchAll();
                if(!$row_count == 0){
                    for($i=0; $i<$row_count; $i++){
                        if(substr($rows[$i]['address'], -3, -1) != "mp4" && substr($rows[$i]['address'], -3, -1) != "avi" && substr($rows[$i]['address'], -3, -1) != "ogg" && substr($rows[$i]['address'], -3, -1) != "mov"){
                            // We can show pictures then
                            echo '<img src="'.$rows[$i]['address'].'" alt="'.$rows[$i]['address'].'" width="200" height="200" style="border-radius:5px; " id="'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'"></img>';
                            echo '<script>
                                    document.getElementById("'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'").onclick = function(){
                                        document.getElementById("'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'").style.width = 200; 
                                        document.getElementById("'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'").style.height = 200; 
                                    }

                                    document.getElementById("'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'").ondblclick = function(){
                                        document.getElementById("'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'").style.width = body.offsetWidth*0.8; 
                                        document.getElementById("'.$rows[$i]['diary_id'].'_'.$rows[$i]['source_id'].'").style.height = body.offsetHeight*0.8; 
                                    }
                                </script>';
                        }else{
                            echo '<video width="200" height="200" controls>';
                            //width="320" height="240"
                            echo '<source src="'.$rows[$i]['address'].'" type="video/mp4" >';
                            echo 'Your browser does not support the video tag.</video>';
                        }

                    }
                }

                echo "<hr />";

                //<button type="submit" class="header_button" onclick="" style="text-align:flex;" form="view" disabled="disabled">查看当日PDF大文档[功能已淘汰]</button>

                echo '<form action="diary_setDate.php" method="post" style="display:center; text-align:center;" id="date">
                        <p>年: <input type="input" name="diary_year" value="'.$year.'" class="input_font" id="a" onkeyup="copya()"></input></p>
                        <p>月: <input type="input" name="diary_month" value="'.$month.'" class="input_font" id="b" onkeyup="copyb()"></input></p>
                        <p>日: <input type="input" name="diary_day" value="'.$day.'" class="input_font" id="c" onkeyup="copyc()"></input></p>
                        <button type="submit" class="header_button" onclick="" style="text-align:flex;">查看这一天(默认今天)</button>
                      </form>';

                echo '<form action="diary_dateReset.php" method="post" style="display:center; text-align:center;">';
                echo '<p><button type="submit" class="header_button" onclick="" style="text-align:flex;">回到今天</button></p>';
                echo '</form>';

                echo '<form action="diary_seeAll.php" method="post" style="display:center; text-align:center;">';
                echo '<p><button type="submit" class="header_button" onclick="" style="text-align:flex;">分批查看所有</button></p>';
                echo '</form>';
                // echo '<form action="viewPDF.php" method="post" style="display:center; text-align:center;" id="view">';
                // echo '<input type="hidden" name="year" value="'.$year.'" class="input_font" id="aa"></input>
                // <input type="hidden" name="month" value="'.$month.'" class="input_font" id="bb"></input>
                // <input type="hidden" name="day" value="'.$day.'" class="input_font" id="cc"></input>';
                // echo '</form>';

                // We fetch data from Data Base
                try{

                    $pdo = new pdo('mysql:host=localhost; dbname=diary', $GLOBALS['user'], $GLOBALS['password']);
                    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

                    // Last Registered diary First
                    if(!isset($_COOKIE['diary_date'])){
                        $sql = 'SELECT * FROM `diary` WHERE `time` LIKE CONCAT(CURDATE(),"%") ORDER BY `time` DESC';
                    }else{
                        $sql = 'SELECT * FROM `diary` WHERE `time` LIKE CONCAT("'.$_COOKIE['diary_date'].'","%") ORDER BY `time` DESC';
                    }

                    if(isset($_COOKIE['diary_seeall'])){
                        $sql = 'SELECT * FROM `diary` ORDER BY `time` DESC LIMIT 0,20';
                    }

                    $stmt = $pdo->query($sql);
                    $row_count = $stmt->rowCount();
                    $rows = $stmt->fetchAll();

                    if($row_count == 0){
                        echo'<p class="narrator" style="font-size: x-large; text-align: center;">你没有在查询的日期中记录生活。</p>';
                    }else{

                        for($i = 0; $i < $row_count; $i++){
                            if($rows[$i]['status'] != "removed" && $rows[$i]['status'] != "deleted" && $rows[$i]['status'] != "hide"){
                                echo '<hr /><p class="narrator" style="font-size: large; text-align: center;">' . $rows[$i]['time'] . " 总第 " . $rows[$i]['diary_id'] . " 条 <strong style='color=purple'>状态标志: [" . $rows[$i]['status'] . ']</strong></p>';
                                echo '<p class="narrator" style="text-align: center;"><textarea readonly="readonly" style="width:80%; text-align:left; font-size: 18px;" name="content" rows="15" placeholder="#说说你的日常叭" class="input_font">'. $rows[$i]['content'] .'</textarea></p>';
                            }
                            // echo "<script>
                            // var london = new maplibregl.Marker()
                            //  .setLngLat([".$rows[$i]['longitude'].", ".$rows[$i]['latitude']."])
                            //  .addTo(map);
                            // </script>";
                        }
                        // echo "</table>";
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
    if (event.ctrlKey && window.event.keyCode==83){
        var form = document.getElementById('savework');
        //再次修改input内容
        form.submit();
        return false;
    }
}
</script>

<style>
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
        animation-name: narrator_enter; 
        animation-duration:5s;
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