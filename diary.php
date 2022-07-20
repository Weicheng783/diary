<html>
    <head>
        <meta charset="utf-8">
        <title>我的日记本</title>
        <meta name="author" content="2022">
        <meta name="revised" content="2022-7-18">
    </head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

    <body style="display:center; background-color: antiquewhite;">
        <div id='header_group' style="display:block; text-align: center;"></div>
        <p class="narrator" style="font-size: x-large; text-align: center; " id="ymd"></p>
        <p style="text-align: center;"><button type="submit" class="header_button" onclick="location.href='diary_edit.php'" style="text-align: center;">写新记录</button></p>
    
    <?php
        header("Content-Type: text/html; charset=utf-8");

        if (!isset($_COOKIE['diary_name'])){
            echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">';
            echo '<div id="writingArea" style="text-align:center; border-style:dashed; border-width:3px; border-radius:5px; padding:5px; margin:5px;">';
            echo "<p><strong>日记认证系统</strong></p>";
            echo '<form action="diary_login.php" method="post" style="display:center;">
                    <p>日记记录员 Diary Keeper: <input type="password" name="diary_name" class="input_font"></input></p>
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

            //<button type="submit" class="header_button" onclick="" style="text-align:flex;" form="view" disabled="disabled">查看当日PDF大文档[功能已淘汰]</button>

            // start: 查看某一天功能暂时中止 20220718
            // echo '<form action="diary_setDate.php" method="post" style="display:center; text-align:center;" id="date">
            //         <p>年: <input type="input" name="diary_year" value="'.$year.'" class="input_font" id="a" onkeyup="copya()"></input></p>
            //         <p>月: <input type="input" name="diary_month" value="'.$month.'" class="input_font" id="b" onkeyup="copyb()"></input></p>
            //         <p>日: <input type="input" name="diary_day" value="'.$day.'" class="input_font" id="c" onkeyup="copyc()"></input></p>
            //         <button type="submit" class="header_button" onclick="" style="text-align:flex;">查看这一天(默认今天)</button>
            //       </form>';

            // echo '<form action="diary_dateReset.php" method="post" style="display:center; text-align:center;">';
            // echo '<p><button type="submit" class="header_button" onclick="" style="text-align:flex;">回到今天</button></p>';
            // echo '</form>';

            // echo '<form action="diary_seeAll.php" method="post" style="display:center; text-align:center;">';
            // echo '<p><button type="submit" class="header_button" onclick="" style="text-align:flex;">分批查看所有</button></p>';
            // echo '</form>';
            // end

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
                // if(!isset($_COOKIE['diary_date'])){
                //     $sql = 'SELECT * FROM `diary` WHERE `time` LIKE CONCAT(CURDATE(),"%") ORDER BY `time` DESC';
                // }else{
                //     $sql = 'SELECT * FROM `diary` WHERE `time` LIKE CONCAT("'.$_COOKIE['diary_date'].'","%") ORDER BY `time` DESC';
                // }

                // if(isset($_COOKIE['diary_seeall'])){
                //     $sql = 'SELECT * FROM `diary` ORDER BY `time` DESC LIMIT 0,20';
                // }

                $sql = 'SELECT * FROM `diary` ORDER BY `time` DESC';

                $stmt = $pdo->query($sql);
                $row_count = $stmt->rowCount();
                $rows = $stmt->fetchAll();

                if($row_count == 0){
                    echo'<p class="narrator" style="font-size: x-large; text-align: center;">你目前还没有记录生活。</p>';
                }else{

                    for($i = 0; $i < $row_count; $i++){
                        if($rows[$i]['status'] != "removed" && $rows[$i]['status'] != "deleted" && $rows[$i]['status'] != "hide"){
                            echo '<hr /><p class="narrator" style="font-size: large; text-align: center;">' . $rows[$i]['time'] . " 总第 " . $rows[$i]['diary_id'] . " 条.";
                            echo '<p class="narrator" style="text-align: center;"><textarea readonly="readonly" style="background-color:antiquewhite; width:80%; text-align:left; font-size: 18px;" name="content" placeholder="#开始记录你的生活" class="input_font">'. $rows[$i]['content'] .'</textarea></p>';
                            
                            echo '<form action="diary_photo_unlink.php" name="form" method="post" enctype="multipart/form-data" style="font-size: large; text-align: center; color: purple">  
                            图片取消连接序号(source_id from 1): <input type="hidden" name="diary_id" value="'. $rows[$i]['diary_id'] .'" class="input_font">
                            <input type="number" name="source_id" id="unlink_'.$rows[$i]['diary_id'].'"/>
                            <input type="submit" name="submit" value="取消连接(数据库条目删除)" />';
                            echo '</form>';

                            echo '<form action="diary_edit.php#search_index" name="form" method="post" enctype="multipart/form-data" style="font-size: large; text-align: center; color: purple">
                            <input type="hidden" name="search_index" value="'.$rows[$i]['diary_id'].'"/>
                            <input type="submit" name="submit" value="修改这条记录" />';
                            echo '</form>';


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
                                            document.getElementById("'.$rows1[$j]['diary_id'].'_'.$rows1[$j]['source_id'].'_alt").onclick = function(){
                                                document.getElementById("'.$rows1[$j]['diary_id'].'_'.$rows1[$j]['source_id'].'_alt").style.width = 200; 
                                                document.getElementById("'.$rows1[$j]['diary_id'].'_'.$rows1[$j]['source_id'].'_alt").style.height = 200; 
            
                                                document.getElementById("unlink_editor").value = '.$rows1[$j]['source_id'].'; 
                                            }
            
                                            document.getElementById("'.$rows1[$j]['diary_id'].'_'.$rows1[$j]['source_id'].'_alt").ondblclick = function(){
                                                document.getElementById("'.$rows1[$j]['diary_id'].'_'.$rows1[$j]['source_id'].'_alt").style.width = getWidth()-15; 
                                                document.getElementById("'.$rows1[$j]['diary_id'].'_'.$rows1[$j]['source_id'].'_alt").style.height = getHeight()*0.4; 
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

    ?>

    <p style="text-align: center;"><button type="submit" class="header_button" onclick="location.href='diary_reset_cookie.php'" style="text-align: center; color:red;">清除Cookies</button></p>
    <p style="text-align: center;"><button type="submit" class="header_button" onclick="location.href='diary_sync.php'" style="text-align: center;">同步上游服务器</button></p>

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
        // saveWork();
        setTimeout("fun()",1000)
    }


    window.onload = function(){
        setTimeout("fun()",0)
    }
</script>

<script>
//按键触发
// document.getElementById("work").onkeydown = function(){
//     //ctrl+s引导为内保存
//     if (event.ctrlKey && window.event.keyCode==83){
//         var form = document.getElementById('savework');
//         //再次修改input内容
//         form.submit();
//         return false;
//     }
// }

// textarea auto shrinking
const tx = document.getElementsByTagName("textarea");
for (let i = 0; i < tx.length; i++) {
  tx[i].setAttribute("style", "height:" + (tx[i].scrollHeight) + "px; overflow-y:hidden; background-color:antiquewhite; width:80%; text-align:left; font-size: 18px;");
  tx[i].addEventListener("input", OnInput, false);
}

function OnInput() {
  this.style.height = "auto";
  this.style.height = (this.scrollHeight) + "px";
}


// Hiding the image & video if it fails to load
const txxx = document.getElementsByTagName("img");

for (let i = 0; i < txxx.length; i++) {
    txxx[i].addEventListener('error', function handleError() {
        // console.log(tx[i].src + " has loading error thus hiding.");
        txxx[i].style.display = 'none';
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