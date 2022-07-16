<html>
    <head>
        <meta charset="utf-8">
        <title>weicheng摄像机记录回放</title>
        <meta name="author" content="2022">
        <meta name="revised" content="Beta Edition 2022-01-10">
    </head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

    <body style="background-color: antiquewhite;">

        <div id='header_group' style="display:block; text-align: center;"></div>

        <p class="narrator" style="font-size: x-large; text-align: center; " id="ymd"></p>
        <p class="narrator" style="font-size: x-large; text-align: center;">在下方选择你要看的时间段</p>

        <?php
            foreach (glob("/var/www/html/camera/*") as $filename) {
                echo "$filename" . "\n";
            }
        ?>

        <?php
            // Connect to FTP server
            // $ftp_server = "132.145.74.19";

            // // Establish ftp connection
            // $ftp_connection = ftp_connect($ftp_server, 21);

            // // Port number 21 is used as second parameter
            // // in the function ftp_connect()
            // if( $ftp_connection ) {
            //     echo "<p style='text-align:center;'>ftp文件服务器正常!</p>";

            //     $login = ftp_login($ftp_connection, "weicheng", "awc020826");
            //     // echo $login;
            //     // get contents of the root directory
            //     $contents = ftp_nlist($ftp_connection, ".");

            //     foreach ($contents as $filename) {
            //         // $filename = substr($filename, 15);
            //         echo "<a href='http://132.145.74.19/camera/".$filename."' style='text-align:center;'>".$filename."</a>\n";
            //         echo $filename;
            //     }
                
            //     // Closing connection
            //     ftp_close( $ftp_connection );
            // }else{
            //     echo "<p style='text-align:center;'>此时无法连接到ftp文件服务器： $ftp_server</p>";
            // }
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
            var notice = "凌晨好."
        }else if(hh > 6 & hh < 11){
            var notice = "现在是早上或上午."
        }else if(hh >= 11  & hh <= 12){
            var notice = "正在中午."
        }else if(hh > 12 & hh <= 18){
            var notice = "现在是下午."
        }else if(hh >= 19 & hh <= 22){
            var notice = "晚上来了."
        }else if(hh > 22 & hh <= 23){
            var notice = "好梦."
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