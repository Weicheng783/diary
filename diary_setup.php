<html>
    <head>
        <meta charset="utf-8">
        <title>日记本设置</title>
        <meta name="author" content="2022">
        <meta name="revised" content="2022-7-18">
    </head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

    <body style="display:center; background-color: antiquewhite;">

        <div id='header_group' style="display:block; text-align: center;">
        </div>

        <p class="narrator" style="font-size: x-large; text-align: center; " id="ymd"></p>
        <p class="narrator" style="font-size: x-large; text-align: center; ">日记本设置</p>
        <p style="text-align: center;"><button type="submit" class="header_button" onclick="location.href='diary.php'" style="text-align: center;">回到主页面</button></p>



<?php
    if (!isset($_COOKIE['diary_server']) or $_COOKIE['diary_server'] == ''){
        $server = "localhost";    
    }else{
        $server = $_COOKIE['diary_server'];
    }

    if (!isset($_COOKIE['diary_server_password']) or $_COOKIE['diary_server_password'] == ''){
        $server_pwd = "";    
    }else{
        $server_pwd = $_COOKIE['diary_server_password'];
    }

    if (!isset($_COOKIE['diary_server_user']) or $_COOKIE['diary_server_user'] == ''){
        $server_user = "root";    
    }else{
        $server_user = $_COOKIE['diary_server_user'];
    }

    if (!isset($_COOKIE['diary_server_port']) or $_COOKIE['diary_server_port'] == ''){
        $server_port = "3306";    
    }else{
        $server_port = $_COOKIE['diary_server_port'];
    }

    echo '<div style="text-align:center; ">';
    echo '<form action="diary_setup_save.php" method="post" style="display:center;">
    <p>服务器域名或ip地址: Address/IP: <input type="input" name="diary_server" class="input_font" value="'.$server.'"></input></p>
    <p>服务器端口 Server Port: <input type="input" name="diary_server_port" class="input_font" value="'.$server_port.'"></input></p>
    <p>服务器用户名 Server Login Name: <input type="input" name="diary_server_user" class="input_font" value="'.$server_user.'"></input></p>
    <p>服务器密码 Server Login Password: <input type="password" name="diary_server_password" class="input_font" value="'.$server_pwd.'"></input></p>
    <input type="hidden" name="verification" value="20220719"></input>
    <button type="submit" class="header_button" onclick="">保存设置</button>
    </form>';
    echo '</div>';

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