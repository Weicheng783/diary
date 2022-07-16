<?php
header("Content-type:text/html;charset=utf-8");

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
            echo "<script>alert('请返回填写名称.');</script>";
            exit(0);
        }

        if($cost == ""){
            echo "<script>alert('请返回填写成本.');</script>";
            exit(0);
        }

        if($totalnum == ""){
            $totalnum = "1";
        }

        if($usednum == ""){
            $usednum = "0";
        }

        $sql = "INSERT INTO `food` (`name`, `note`, `timeadded`, `usedby`, `cost`, `totalnum`, `usednum`, `status`) VALUES ('".$name."', '".$note."', '".$timeadded."', '".$usedby."', '".$cost."', '".$totalnum."', '".$usednum."', '".$status."');";
        $pdo->query($sql);

        echo "<script>alert('食材 ".$name." 加入成功.');location.href='food.php';</script>";

    }elseif ($request == "update") {
        $id = $_REQUEST['id'];
        $name = $_REQUEST['name'];
        $note = $_REQUEST['note'];
        $timeadded = $_REQUEST['timeadded'];
        $usedby = $_REQUEST['usedby'];
        $cost = $_REQUEST['cost'];
        $totalnum = $_REQUEST['totalnum'];
        $usednum = $_REQUEST['usednum'];
        $status = $_REQUEST['status'];

        if($name == ""){
            echo "<script>alert('请返回填写名称.');</script>";
            exit(0);
        }

        if($cost == ""){
            echo "<script>alert('请返回填写成本.');</script>";
            exit(0);
        }

        if($totalnum == ""){
            $totalnum = "1";
        }

        if($usednum == ""){
            $usednum = "0";
        }

        $sql = "UPDATE `food` SET `name`='".$name."', `note`='".$note."', `timeadded`='".$timeadded."', `usedby`='".$usedby."', `cost`='".$cost."', `totalnum`='".$totalnum."', `usednum`='".$usednum."', `status`='".$status."' WHERE `id`='".$id."';";
        $pdo->query($sql);

        // echo "<script>console.log('".$sql."');</script>";

        echo "<script>alert('食材 ".$name." 更新成功.');location.href='food.php';</script>";
    }

}catch(PDOException $e){
    echo "<script>alert('数据未加入总表, 请返回重试。');</script>";
}

?>