<html>
    <head>
        <meta charset="utf-8">
        <title>食材管理中心</title>
        <meta name="author" content="2022">
        <meta name="revised" content="2022-6-16">
    </head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

    <body style="background-color: antiquewhite;">

            <div id='header_group' style="display:block; text-align: center;">
                <!-- <div style="display: inline-flex;"> -->
                <!-- <img src="./logo.png" id="logo" alt="Weicheng_Quiz_Welcome_Message" style=" text-align: left; border-radius:20px; display:inline-block; height:100px; width:auto;"> -->
            </div>

            <p class="narrator" style="font-size: x-large; text-align: center;">食材管理中心</p>
            <p class="narrator" style="font-size: x-large; text-align: center; " id="ymd"></p>
            <p class="narrator" style="font-size: x-large; text-align: center;">过好每一天的生活</p>
    
    <?php
        $user = "weicheng";
        $password = "awc020826";
        header("Content-Type: text/html; charset=utf-8");

        try{
            // First fetch weekly cost
            // Sort out start and end date
            // 当前日期
            $defaultDate = date("Y-m-d");
            //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
            $first=1;
            //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
            $w=date('w',strtotime($sdefaultDate));
            //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
            $week_start=date('Y-m-d',strtotime("$defaultDate -".($w ? $w - $first : 6).' days'));
            //本周结束日期
            $week_end=date('Y-m-d',strtotime("$week_start +6 days"));


            $pdo = new pdo('mysql:host=localhost; dbname=diary', $GLOBALS['user'], $GLOBALS['password']);
            $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

            $sql = "SELECT SUM(`cost`) FROM `food` WHERE `food`.`timeadded` BETWEEN '".$week_start." 00:00:00' AND '".$week_end." 23:59:59'";

            $stmt = $pdo->query($sql);
            $row_count = $stmt->rowCount();
            $rows = $stmt->fetchAll();

            if($w == "0"){
                $w = "7";
            }

            for($i=0; $i<$row_count; $i++){
                echo '<p class="narrator" style="font-size: x-large; text-align: center; color: orange">本周('.$week_start.' - '.$week_end.')，总成本 <strong>'.$rows[$i]['SUM(`cost`)'].'</strong> 英镑，尽量节约。今天是本周的第 '.$w.' 天。</p>';
            }


            // First fetch monthly cost
            // Sort out start and end date
            $startDateMonth = date('Y-m-01',time());//获取该月份的第一天
            $endDateMonth = date('Y-m-t',time());//获取该月份的最后一天
            // SELECT SUM(`cost`) FROM `food` WHERE `food`.`timeadded` BETWEEN '2022-06-01 00:00:00' AND '2022-06-30 23:59:59'

            $sql = "SELECT SUM(`cost`) FROM `food` WHERE `food`.`timeadded` BETWEEN '".$startDateMonth." 00:00:00' AND '".$endDateMonth." 23:59:59'";

            $stmt = $pdo->query($sql);
            $row_count = $stmt->rowCount();
            $rows = $stmt->fetchAll();

            for($i=0; $i<$row_count; $i++){
                echo '<p class="narrator" style="font-size: x-large; text-align: center; color: orange">本月('.$startDateMonth.' - '.$endDateMonth.')，总成本 <strong>'.$rows[$i]['SUM(`cost`)'].'</strong> 英镑。</p>';
            }

        }catch(PDOException $e){
                    
        }
    ?>


    <?php
        $user = "weicheng";
        $password = "awc020826";
        header("Content-Type: text/html; charset=utf-8");

                echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">';

                echo '<p class="narrator" style="font-size: large; text-align: center; color: purple">添加食材表</p>';
                echo "<div style='display:center; text-align:center;'>";
                echo "
                <table border='1' style='display:center; text-align:center;'>
                    <tr>
                        <th>食材</th>
                        <th>注解</th>
                        <th>录入时间</th>
                        <th>应用完时间</th>
                        <th>总数</th>
                        <th>已用数量</th>
                        <th>成本</th>
                        <th>状态</th>
                    </tr>";
                
                // sudo cp /usr/share/zoneinfo/Europe/London /etc/localtime; date -R

                echo"
                    <form action='food_post.php' method='post' style='margin:auto'>
                    <tr>
                        <td><input class='input_font' name='name' value=''></input></td>
                        <td><input class='input_font' name='note' value=''></input></td>
                        <td><input type='datetime' class='input_font' name='timeadded' value='".date('Y',strtotime("-1 hour"))."-".date('m',strtotime("-1 hour"))."-".date('d',strtotime("-1 hour"))." ".date('H',strtotime("-1 hour")).":".date('i',strtotime("-1 hour")).":".date('s',strtotime("-1 hour"))."'></input></td>
                        <td><input type='date' class='input_font' name='usedby' value='".date('Y',strtotime("-1 hour"))."-".date('m',strtotime("-1 hour"))."-".date('d',strtotime("-1 hour"))."'></input></td>
                        <td><input class='input_font' name='totalnum' value=''></input></td>
                        <td><input class='input_font' name='usednum' value=''></input></td>
                        <td><input class='input_font' name='cost' value=''></input></td>
                        <td><select class='input_font' id='select_status' name='status'>
                            <option value='已储存' selected>已储存</option>
                            <option value='已用完'>已用完</option>
                            <option value='已丢弃'>已丢弃</option>
                            <option value='已退赠'>已退赠</option>
                        </select></td>
                        <td><button type='submit' class='header_button' onclick=''>提交</button></td>
                        <input type='hidden' name='request' value='submit'></input>
                    </tr>

                    </form>
                ";

                echo "</table>";
                echo "<div>";



                $year = date('Y');
                $month = date('m');
                $day = date('d');

                echo "<hr />";

                try{
                    // MAIN LOGIC
                    $pdo = new pdo('mysql:host=localhost; dbname=diary', $GLOBALS['user'], $GLOBALS['password']);
                    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

                    $sql = 'SELECT * FROM `food` ORDER BY `usedby` DESC';

                    $stmt = $pdo->query($sql);
                    $row_count = $stmt->rowCount();
                    $rows = $stmt->fetchAll();

                    if($row_count == 0){
                        // echo "rowcount=0";
                        echo '<p class="narrator" style="font-size: large; text-align: center; color: purple">添加食材以开始。</p>';
                    }else{
                        echo "
                        <table border='1'>
                            <tr>
                                <th>食材</th>
                                <th>注解</th>
                                <th>录入时间</th>
                                <th>应用完时间</th>
                                <th>总数</th>
                                <th>已用数量</th>
                                <th>成本</th>
                                <th>状态</th>
                            </tr>";

                        for($i=0; $i<$row_count; $i++){
                            if($rows[$i]['status'] == "已储存"){
                                // show food entries
                                // <td><input class='input_font' name='status' value='".$rows[$i]['status']."'></input></td>
                                echo"
                                    <form action='food_post.php' method='post' style='display:center; text-align:center;'>
                                    <tr>
                                        <td><input class='input_font' name='name' value='".$rows[$i]['name']."'></input></td>
                                        <td><input class='input_font' name='note' value='".$rows[$i]['note']."'></input></td>
                                        <td><input type='datetime' class='input_font' name='timeadded' value='".$rows[$i]['timeadded']."'></input></td>
                                        <td><input type='datetime' class='input_font' name='usedby' value='".$rows[$i]['usedby']."'></input></td>
                                        <td><input class='input_font' name='totalnum' value='".$rows[$i]['totalnum']."'></input></td>
                                        <td><input class='input_font' name='usednum' value='".$rows[$i]['usednum']."'></input></td>
                                        <td><input class='input_font' name='cost' value='".$rows[$i]['cost']."'></input></td>
                                        <td><select class='input_font' id='select_status' name='status' value='".$rows[$i]['status']."'>
                                            <option value='已储存'>已储存</option>
                                            <option value='已用完'>已用完</option>
                                            <option value='已丢弃'>已丢弃</option>
                                            <option value='已退赠'>已退赠</option>
                                        </select></td>
                                        <td><button type='submit' class='header_button' onclick=''>更改'".$rows[$i]['name']."'</button></td>
                                    </tr>
                                    <input type='hidden' name='id' value='".$rows[$i]['id']."'></input>
                                    <input type='hidden' name='request' value='update'></input>
                                    </form>
                                ";
                            }
                        }

                        echo "</table>";
                    }

                }catch(PDOException $e){
                    
                }

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
        // saveWork();
        setTimeout("fun()",1000)
    }


    window.onload = function(){
        setTimeout("fun()",0)
    }
</script>

<!-- <script>
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
</script> -->

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
        font-size: 15px;
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