<html>
    <head>
        <meta charset="utf-8">
        <title>位置报告站</title>
        <meta name="author" content="2022">
        <meta name="revised" content="Beta Edition 2022-01-10">
    </head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

    <body style="background-color: antiquewhite;">

            <div id='header_group' style="display:block; text-align: center;">
                <!-- <div style="display: inline-flex;"> -->
                <!-- <img src="./logo.png" id="logo" alt="Weicheng_Quiz_Welcome_Message" style=" text-align: left; border-radius:20px; display:inline-block; height:100px; width:auto;"> -->
            </div>

            <p class="narrator" style="font-size: x-large; text-align: center;"></p>
            <p class="narrator" style="font-size: x-large; text-align: center; " id="ymd"></p>
            
            <!-- <form action="reset.php" method="post" style="text-align:center; display:center;"> -->
              <!-- <p class="narrator"><button type="submit" class="header_button" onclick="">Log Out</button></p> -->
            <!-- </form> -->

<!--             <form action="create.php" method="post" style="text-align:center; display:center;">
              <p class="narrator"><button type="submit" class="header_button" onclick="">Go Back to Quiz page</button></p>
            </form> -->
    <?php
        header("Content-Type: text/html; charset=utf-8");
            if (!isset($_COOKIE['name'])){
                echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">';
                echo '<div id="writingArea" style="text-align:center; border-style:dashed; border-width:3px; border-radius:5px; padding:5px; margin:5px;">';
                echo "<p><strong>请登录以查看状态信息。</strong></p>";
                echo "<p>在下方输入给定的用户名和密码，并点击提交：</p>";
                echo '<form action="login.php" method="post" style="display:center;">
                        <p>用户名: <input type="input" name="name" class="input_font"></input></p>
                        <p>密码: <input type="password" name="password" class="input_font"></input></p>
                        <button type="submit" class="header_button" onclick="">登录/提交</button>
                      </form>';
                echo '</div>';
            }else{

                // echo '<iframe id="map" width="500" height="300" src="https://api.maptiler.com/maps/osm-standard/?key=873s1SijZFFScPeHZHFB#16/53.47414393324406/-2.234992488745344"></iframe>';

                $year = date('Y');
                $month = date('m');
                $day = date('d');

                echo '<form action="reset.php" method="post" style="display:center; text-align:center;">';
                echo '<p><button type="submit" class="header_button" onclick="" style="text-align:flex;">清除已登录状态</button></p>';
                echo '</form>';

                echo '<p style="text-align:center;"><a href="showMap.php" class="header_button"><button type="button" class="header_button">当日活点轨迹地图</button></a></p>';

                if(isset($_COOKIE['year'])){
                    $year = $_COOKIE['year'];
                }

                if(isset($_COOKIE['month'])){
                    $month = $_COOKIE['month'];
                }

                if(isset($_COOKIE['day'])){
                    $day = $_COOKIE['day'];
                }

                // @Deprecated viewPDF from 2022-01-28
                //<button type="submit" class="header_button" onclick="" style="text-align:flex;" form="view" disabled="disabled">查看当日PDF大文档[功能已淘汰]</button>
                echo '<form action="setDate.php" method="post" style="display:center; text-align:center;" id="date">
                        <p>年: <input type="input" name="year" value="'.$year.'" class="input_font" id="a" onkeyup="copya()"></input></p>
                        <p>月: <input type="input" name="month" value="'.$month.'" class="input_font" id="b" onkeyup="copyb()"></input></p>
                        <p>日: <input type="input" name="day" value="'.$day.'" class="input_font" id="c" onkeyup="copyc()"></input></p>
                        <button type="submit" class="header_button" onclick="" style="text-align:flex;">查看这一天(默认今天)</button>
                      </form>';

                echo '<form action="dateReset.php" method="post" style="display:center; text-align:center;">';
                echo '<p><button type="submit" class="header_button" onclick="" style="text-align:flex;">回到今天</button></p>';
                echo '</form>';

                echo '<form action="viewPDF.php" method="post" style="display:center; text-align:center;" id="view">';
                echo '<input type="hidden" name="year" value="'.$year.'" class="input_font" id="aa"></input>
                <input type="hidden" name="month" value="'.$month.'" class="input_font" id="bb"></input>
                <input type="hidden" name="day" value="'.$day.'" class="input_font" id="cc"></input>';
                echo '</form>';
                // We fetch data from Data Base
                try{
                    $user = "root";
                    $password = "Awc020826*";

                    $pdo = new pdo('mysql:host=localhost; dbname=location', $user, $password);
                    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

                    // Last Registered Location First
                    if(!isset($_COOKIE['date'])){
                        $sql = 'SELECT * FROM `register` WHERE `time_registered` LIKE CONCAT(CURDATE(),"%") ORDER BY `time_registered` DESC';
                    }else{
                        $sql = 'SELECT * FROM `register` WHERE `time_registered` LIKE CONCAT("'.$_COOKIE['date'].'","%") ORDER BY `time_registered` DESC';
                    }

                    $stmt = $pdo->query($sql);
                    $row_count = $stmt->rowCount();
                    $rows = $stmt->fetchAll();

                    if($row_count == 0){
                        echo'<p class="narrator" style="font-size: x-large; text-align: center;">查询的日期下并没有记录。</p>';
                    }else{
                        echo "<table border='1' style='text-align:center;' class='table_font'>
                        <tr>
                        <th>登记时间(英国当地时间)</th>
                        <th>地点全名</th>
                        <th>三词地点</th>
                        <th>经度</th>
                        <th>纬度</th>
                        <th>唯一参考码</th>
                        </tr>";

                        for($i = 0; $i < $row_count; $i++){
                            echo "<tr>";
                            echo "<td>" . $rows[$i]['time_registered'] . "</td>";
                            echo "<td>" . $rows[$i]['full_address'] . "</td>";
                            echo "<td>" . $rows[$i]['what_three_words_address'] . "</td>";
                            echo "<td>" . $rows[$i]['longitude'] . "</td>";
                            echo "<td>" . $rows[$i]['latitude'] . "</td>";
                            echo "<td>" . $rows[$i]['register_ref'] . "</td>";
                            echo "</tr>";

                            // echo "<script>
                            // var london = new maplibregl.Marker()
                            //  .setLngLat([".$rows[$i]['longitude'].", ".$rows[$i]['latitude']."])
                            //  .addTo(map);
                            // </script>";
                        }
                        echo "</table>";
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

        document.getElementById("ymd").innerHTML = +y+"-"+m+"-"+d+" "+hh+":"+mm+":"+ss+"  ---  "+notice+" (显示时间为你的本机时间)";
        setTimeout("fun()",1000)
    }


    window.onload = function(){
        setTimeout("fun()",0)
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