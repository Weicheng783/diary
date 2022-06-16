<html>
	<head>
		<meta charset="utf-8">
		<title>位置报告站--活点地图</title>
		<meta name="author" content="2022">
		<meta name="revised" content="Beta Edition 2022-06-15">
		<!-- <script src="https://cdn.maptiler.com/maplibre-gl-js/v1.14.0/maplibre-gl.js"></script>
		<link href="https://cdn.maptiler.com/maplibre-gl-js/v1.14.0/maplibre-gl.css" rel="stylesheet" /> -->
		<script src='https://api.mapbox.com/mapbox-gl-js/v2.8.2/mapbox-gl.js'></script>
    	<link href='https://api.mapbox.com/mapbox-gl-js/v2.8.2/mapbox-gl.css' rel='stylesheet' />
	</head>
</html>

<?php
	header("Content-Type: text/html; charset=utf-8");
    if(!isset($_COOKIE['diary_name'])){
        echo "<script>alert('请先登录。');location.href='diary.php';</script>";
        exit(1);
    }

	echo '<div id="indicator"></div>';
	echo '<div id="map" style="position: absolute; top: 0px; width: 100%; height: 600px;  border-radius: 20px; "></div>';
	echo '<div id="placeholder" style="height:200px;"></div>';
	echo '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>';
	echo '<script type="text/javascript">';
	// echo " var map = new maplibregl.Map({
	// 	container: 'map',
	// 	style: 'https://api.maptiler.com/maps/streets/style.json?key=873s1SijZFFScPeHZHFB',
	// 	center: [-2.234992488745344, 53.47414393324406],
	// 	zoom: 17,
	// 	});
	// </script>";

	echo "mapboxgl.accessToken = 'pk.eyJ1IjoiZGludWQxMSIsImEiOiJjbDE1Nzdib3QwaDJ6M2pzZ2p4bGdhZWo2In0.pNx1qRgo7vsmuoVt0R5-nQ';
			var map = new mapboxgl.Map({
			container: 'map',
			style: 'mapbox://styles/mapbox/streets-v11',
			center: [-2.230912, 53.465211],
			zoom: 6
			});
			var coordinates = [];
			var loopTime = 0;
			var bounds = new mapboxgl.LngLatBounds();
		";
	echo "</script>";


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
		$sql = 'SELECT * FROM `location_store` ORDER BY `date` DESC LIMIT 1000';

		$stmt = $pdo->query($sql);
		$row_count = $stmt->rowCount();
		$rows = $stmt->fetchAll();
		echo "<script>console.log(".$row_count.")</script>";

		if($row_count == 0){
			echo'<p class="narrator" style="font-size: x-large; text-align: center;">查询的日期下并没有记录。</p>';
		}else{
			for($i = 0; $i < $row_count; $i++){
				// echo "<script>console.log(".$rows[$i]['longitude'].")</script>";

				// echo "<script>
				// var london = new maplibregl.Marker()
				//  .setLngLat([".$rows[$i]['latitude'].", ".$rows[$i]['longitude']."])
				//  .addTo(map);
				// </script>";

				echo "
					<script>

					var marker = new mapboxgl.Marker()
					.setLngLat([".$rows[$i]['latitude'].", ".$rows[$i]['longitude']."])
					.setPopup(
						new mapboxgl.Popup({ offset: 25 }) // add popups
						.setHTML(
			       `<h3>".$rows[$i]['date'].' '.$rows[$i]['time']."</h3><p>速度: ".$rows[$i]['speed']."</p><p>可能误差: ".$rows[$i]['drift']."</p><p>经度: ".$rows[$i]['latitude']."</p><p>纬度: ".$rows[$i]['longitude']."</p>`
					)
					)
					.addTo(map);
					coordinates[loopTime] = [".$rows[$i]['latitude'].", ".$rows[$i]['longitude']."];
					loopTime ++;

					</script>
			";
			}

			echo "<script>
			// Map zooming to fit all available points
			if(coordinates.length != 0){
				for (var i of coordinates){
					bounds.extend(i);
				}
				map.fitBounds(bounds, { padding: 100 });
			}
			</script>
			";
		}

	}catch(PDOException $e){
		echo "<script>alert('目前无法连接到数据库.');</script>";
	}

?>

<script>
	var width = document.body.offsetWidth - getElementOffset(document.getElementById("map")).left*2; 
	document.getElementById("map").style.width = width+"px";
	document.getElementById("map").style.top = getElementOffset(document.getElementById("indicator")).top + "px";
	document.getElementById("placeholder").style.height = getElementOffset(document.getElementById("map")).top + 20 + "px";
	
	window.addEventListener('resize', function(event) {
		document.getElementById("map").style.top = getElementOffset(document.getElementById("indicator")).top + "px";
		var width = document.body.offsetWidth - getElementOffset(document.getElementById("map")).left*2; 
		document.getElementById("map").style.width = width+"px";
		document.getElementById("placeholder").style.height = getElementOffset(document.getElementById("map")).top + 20 + "px";
	}, true);
	
	// Fetch an element's left and top according to the 0,0
	function getElementOffset(element) {
	let offset = {left: 0, top: 0}
	let current = element.offsetParent

	offset.left += element.offsetLeft
	offset.top += element.offsetTop

	while (current !== null) {
		offset.left += current.offsetLeft
		offset.top += current.offsetTop
		current = current.offsetParent
	}
	return offset
	}

</script>


<!-- <style>
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

</style> -->