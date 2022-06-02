<?php
    if(!isset($_COOKIE['name'])){
        echo "<script>alert('非法侵入！正常登录应该走流程。');location.href='index.php';</script>";
        exit(1);
    }

    header("Content-type:text/html;charset=gb1232");
    // Input Authentication
    if(!isset($_REQUEST['year']) or $_REQUEST['year'] == ""){
        $year = date('Y');
    }else{
        $year = $_REQUEST['year'];
    }

    if(!isset($_REQUEST['month']) or $_REQUEST['month'] == ""){
        $month = "01";
    }else{
        $month = $_REQUEST['month'];
    }

    if(!isset($_REQUEST['day']) or $_REQUEST['day'] == ""){
        $day = "01";
    }else{
        $day = $_REQUEST['day'];
    }

    if(strlen($year) == 2){
        $year = "20".$year;
    }

    if(strlen($month) == 1){
        $month = "0".$month;
    }

    if(strlen($day) == 1){
        $day = "0".$day;
    }

    $final = $year."-".$month."-".$day;
    setcookie("date", $final ,time()+3600);
    setcookie("year", $year ,time()+3600);
    setcookie("month", $month ,time()+3600);
    setcookie("day", $day ,time()+3600);

    switch ($month)
    {
    case "01":
      $month="1";
      break;  
    case "02":
      $month="2";
      break;  
    case "03":
        $month="3";
        break;  
    case "04":
        $month="4";
        break;
    case "05":
        $month="5";
        break;  
    case "06":
        $month="6";
        break;  
    case "07":
        $month="7";
        break;  
    case "08":
        $month="8";
        break;  
    case "09":
        $month="9";
        break;  
    case "10":
        $month="10";
        break;  
    case "11":
        $month="11";
        break;  
    case "12":
        $month="12";
        break;
    default:
      $month="1";
    }


    switch ($day)
    {
    case "01":
      $day="1";
      break;  
    case "02":
      $day="2";
      break;  
    case "03":
        $day="3";
        break;  
    case "04":
        $day="4";
        break;
    case "05":
        $day="5";
        break;  
    case "06":
        $day="6";
        break;  
    case "07":
        $day="7";
        break;  
    case "08":
        $day="8";
        break;  
    case "09":
        $day="9";
        break;  
    default:
      $day=$day;
    }

    $randnum = strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9)).strval(rand(0,9));

    // if(move_uploaded_file("/home/ubuntu/www/report/".$year."年".$month."月".$day."日".".pdf", "/home/ubuntu/www/public/report/".$randnum.".pdf")){
        // system(, $status);
    exec("cp /home/ubuntu/www/report/".$year."年".$month."月".$day."日".".pdf  /home/ubuntu/www/public/report/".$randnum.".pdf ", $result, $status);
    // echo "<script>alert('".$status."');</script>";

    if(file_exists("/home/ubuntu/www/public/report/".$randnum.".pdf")){
        exec("python3 del.py {$randnum} >/dev/null 2>&1 &");
        // $command = "sleep 10s; rm -rf /home/ubuntu/www/public/report/".$randnum.".pdf";
        // exec("bash -c '"."sleep 10s; rm -rf /home/ubuntu/www/public/report/".$randnum.".pdf;"."' > /dev/null 2>&1 &'");
        // shell_exec( $command . " > /dev/null 2>&1 &" );
        // exec("nohup "."sleep 20s; rm -rf /home/ubuntu/www/public/report/".$randnum.".pdf"." > /dev/null 2>&1 &");
        // system("run_baby_run 'sleep 10s; rm -rf /home/ubuntu/www/public/report/".$randnum.".pdf;' > /dev/null &");
        header("Content-type:text/html;charset=utf-8");
        echo "<script>alert('为信息安全，单次阅读时间为3分钟。3分钟后服务器会自动删除该文件，现在已经开始计时。');location.href='report/".$randnum.".pdf';</script>";

    }else {
       //  echo "A problem encountered while uploading this file, please check the error code below.\n";
       header("Content-type:text/html;charset=utf-8");
       echo "<script>alert('我没有找到相应时间 ‘".$year."年".$month."月".$day."日"."’ 的档案，请等待公布。');location.href='index.php';</script>";
    }
    
?>