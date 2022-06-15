<html>
	<head>
		<meta charset="utf-8">
		<title>位置报告站--活点地图</title>
		<meta name="author" content="2022">
		<meta name="revised" content="Beta Edition 2022-06-15">
		<script src="https://cdn.maptiler.com/maplibre-gl-js/v1.14.0/maplibre-gl.js"></script>
		<link href="https://cdn.maptiler.com/maplibre-gl-js/v1.14.0/maplibre-gl.css" rel="stylesheet" />
	</head>
</html>

<?php
	header("Content-Type: text/html; charset=utf-8");
    if(!isset($_COOKIE['diary_name'])){
        echo "<script>alert('请先登录。');location.href='diary.php';</script>";
        exit(1);
    }

	echo '<div id="map"></div>';
	echo '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>';
	echo '<script type="text/javascript">';
	echo " var map = new maplibregl.Map({
		container: 'map',
		style: 'https://api.maptiler.com/maps/streets/style.json?key=873s1SijZFFScPeHZHFB',
		center: [-2.234992488745344, 53.47414393324406],
		zoom: 17,
		});
	</script>";


	try{
		$user = "weicheng";
		$password = "awc020826";

		$pdo = new pdo('mysql:host=localhost; dbname=diary', $user, $password);
		$pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

		// Last Registered Location First
		// if(!isset($_COOKIE['date'])){
		// 	$sql = 'SELECT * FROM `register` WHERE `time_registered` LIKE CONCAT(CURDATE(),"%") ORDER BY `time_registered` DESC';
		// }else{
		// 	$sql = 'SELECT * FROM `register` WHERE `time_registered` LIKE CONCAT("'.$_COOKIE['date'].'","%") ORDER BY `time_registered` DESC';
		// }
		$sql = 'SELECT * FROM `location` ORDER BY `date` DESC';

		$stmt = $pdo->query($sql);
		$row_count = $stmt->rowCount();
		$rows = $stmt->fetchAll();

		if($row_count == 0){
			echo'<p class="narrator" style="font-size: x-large; text-align: center;">查询的日期下并没有记录。</p>';
		}else{
			for($i = 0; $i < $row_count; $i++){
				echo "<script>
				var london = new maplibregl.Marker()
				 .setLngLat([".$rows[$i]['longitude'].", ".$rows[$i]['latitude']."])
				 .addTo(map);
				</script>";
			}
		}

	}catch(PDOException $e){
		echo "<script>alert('目前无法连接到数据库.');</script>";
	}

?>

<style>
    #map {
        position: absolute; 
        top: 0; 
        right: 0; 
        bottom: 0; 
        left: 0;
        border-radius: 10px;
        border-width: 10px;
        border: solid;
        border-color: skyblue;
        background-color: antiquewhite;
        text-align: center;
    }

</style>