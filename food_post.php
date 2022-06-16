<?php
header("Content-type:text/html;charset=utf-8");
header(location:getenv("HTTP_REFERER"));

try{
    $dsn="mysql:host=localhost; dbname=diary";
    $user="weicheng";
    $password='awc020826';
    $pdo=new PDO($dsn,$user,$password);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    $request = $_REQUEST['request'];

    if($request == "submit"){

        $name = $_REQUEST['name'];
        $note = $_REQUEST['note'];
        $timeadded = $_REQUEST['timeadded'];
        $usedby = $_REQUEST['usedby'];
        $cost = $_REQUEST['cost'];
        $totalnum = $_REQUEST['totalnum'];
        $usednum = $_REQUEST['usednum'];
        $status = $_REQUEST['status'];

        if($name == ""){
            echo "<script>alert('填写名称.');location.href='food.php';</script>";
            exit(0);
        }

        if($cost == ""){
            echo "<script>alert('填写成本.');location.href='food.php';</script>";
            exit(0);
        }

        $sql = "INSERT INTO `food` (`name`, `note`, `timeadded`, `usedby`, `cost`, `totalnum`, `usednum`, `status`) VALUES ('".$name."', '".$note."', '".$timeadded."', '".$usedby."', '".$cost."', '".$totalnum."', '".$usednum."', '".$status."');";
        $pdo->query($sql);

        echo "<script>alert('食材加入成功.');location.href='food.php';</script>";

    }elseif ($request == "update") {
        $name = $_REQUEST['name'];
        $note = $_REQUEST['note'];
        $timeadded = $_REQUEST['timeadded'];
        $usedby = $_REQUEST['usedby'];
        $cost = $_REQUEST['cost'];
        $totalnum = $_REQUEST['totalnum'];
        $usednum = $_REQUEST['usednum'];
        $status = $_REQUEST['status'];

        if($name == ""){
            echo "<script>alert('填写名称.');history.go(-1);</script>";
            // exit(0);
        }

        if($cost == ""){
            echo "<script>alert('填写成本.');history.go(-1);</script>";
            // exit(0);
        }

        $sql = "INSERT INTO `food` (`name`, `note`, `timeadded`, `usedby`, `cost`, `totalnum`, `usednum`, `status`) VALUES ('".$name."', '".$note."', '".$timeadded."', '".$usedby."', '".$cost."', '".$totalnum."', '".$usednum."', '".$status."');";
        $pdo->query($sql);

        echo "<script>alert('食材加入成功.');location.href='food.php';</script>";
    }

}catch(PDOException $e){
    echo "<script>alert('数据未加入总表, 请重试。');location.href='food.php';</script>";
}

?>