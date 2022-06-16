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
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
</html>

<body style="background-color: antiquewhite;">

<div id='header_group' style="display:block; text-align: center;">
	<!-- <div style="display: inline-flex;"> -->
	<!-- <img src="./logo.png" id="logo" alt="Weicheng_Quiz_Welcome_Message" style=" text-align: left; border-radius:20px; display:inline-block; height:100px; width:auto;"> -->
</div>

<p class="narrator" style="font-size: x-large; text-align: center; " id="ymd"></p>

<?php
	header("Content-Type: text/html; charset=utf-8");
    if(!isset($_COOKIE['diary_name'])){
        echo "<script>alert('请先登录。');location.href='diary.php';</script>";
        exit(1);
    }
	echo "<div style='background-color: antiquewhite;'>";

	$year = date('Y');
	$month = date('m');
	$day = date('d');
	$hour = date('H');
	$minute = date('i');
	$second = date('s');

	$year2 = date('Y');
	$month2 = date('m');
	$day2 = date('d');
	$hour2 = "23";
	$minute2 = "59";
	$second2 = "59";

	echo '<form action="diary_reset.php" method="post" style="display:center; text-align:center;">';
	echo '<p><button type="submit" class="header_button" onclick="" style="text-align:flex;">清除已登录状态</button></p>';
	echo '</form>';

	// echo '<p style="text-align:center;"><a href="showMap.php" class="header_button"><button type="button" class="header_button">当日活点轨迹地图(测试中)</button></a></p>';

	if(isset($_COOKIE['map_year'])){
		$year = $_COOKIE['map_year'];
	}

	if(isset($_COOKIE['map_month'])){
		$month = $_COOKIE['map_month'];
	}

	if(isset($_COOKIE['map_day'])){
		$day = $_COOKIE['map_day'];
	}

	if(isset($_COOKIE['map_hour'])){
		$hour = $_COOKIE['map_hour'];
	}

	if(isset($_COOKIE['map_minute'])){
		$minute = $_COOKIE['map_minute'];
	}

	if(isset($_COOKIE['map_second'])){
		$second = $_COOKIE['map_second'];
	}

	// 2nd round
	if(isset($_COOKIE['map_year2'])){
		$year2 = $_COOKIE['map_year2'];
	}

	if(isset($_COOKIE['map_month2'])){
		$month2 = $_COOKIE['map_month2'];
	}

	if(isset($_COOKIE['map_day2'])){
		$day2 = $_COOKIE['map_day2'];
	}

	if(isset($_COOKIE['map_hour2'])){
		$hour2 = $_COOKIE['map_hour2'];
	}

	if(isset($_COOKIE['map_minute2'])){
		$minute2 = $_COOKIE['map_minute2'];
	}

	if(isset($_COOKIE['map_second2'])){
		$second2 = $_COOKIE['map_second2'];
	}

	echo "<hr />";


	if(!isset($_COOKIE['map_hour']) or $_COOKIE['map_hour'] == ""){
		echo '<form action="map_setRange.php" method="post" style="display:center; text-align:center;" id="date">
		<p>起始年: <input type="input" name="map_year" value="'.$year.'" class="input_font" id="a" onkeyup="copya()"></input> 查找到年份: <input type="input" name="map_year2" value="'.$year.'" class="input_font" id="d" onkeyup="copyd()"></input></p>
		<p>起始月: <input type="input" name="map_month" value="'.$month.'" class="input_font" id="b" onkeyup="copyb()"></input> 查找到月份: <input type="input" name="map_month2" value="'.$month.'" class="input_font" id="e" onkeyup="copye()"></input></p>
		<p>起始日: <input type="input" name="map_day" value="'.$day.'" class="input_font" id="c" onkeyup="copyc()"></input> 查找到日: <input type="input" name="map_date2" value="'.$day.'" class="input_font" id="f" onkeyup="copyf()"></input></p>
		<p>起始时: <input type="input" name="map_hour" value="00" class="input_font" id="g" onkeyup="copyg()"></input> 查找到小时: <input type="input" name="map_hour2" value="'.$hour.'" class="input_font" id="j" onkeyup="copyj()"></input></p>
		<p>起始分: <input type="input" name="map_minute" value="00" class="input_font" id="h" onkeyup="copyh()"></input> 查找到分钟: <input type="input" name="map_minute2" value="'.$minute.'" class="input_font" id="k" onkeyup="copyk()"></input></p>
		<p>起始秒: <input type="input" name="map_second" value="00" class="input_font" id="i" onkeyup="copyi()"></input> 查找到秒: <input type="input" name="map_second2" value="00" class="input_font" id="l" onkeyup="copyl()"></input></p>

		<button type="submit" class="header_button" onclick="" style="text-align:flex;">查看所选范围</button>
	</form>';
		// echo "<p>logic1</p>";
	} else {
		echo '<form action="map_setRange.php" method="post" style="display:center; text-align:center;" id="date">
		<p>起始年: <input type="input" name="map_year" value="'.$_COOKIE['map_year'].'" class="input_font" id="a" onkeyup="copya()"></input> 查找到年份: <input type="input" name="map_year2" value="'.$_COOKIE['map_year2'].'" class="input_font" id="d" onkeyup="copyd()"></input></p>
		<p>起始月: <input type="input" name="map_month" value="'.$_COOKIE['map_month'].'" class="input_font" id="b" onkeyup="copyb()"></input> 查找到月份: <input type="input" name="map_month2" value="'.$_COOKIE['map_month2'].'" class="input_font" id="e" onkeyup="copye()"></input></p>
		<p>起始日: <input type="input" name="map_day" value="'.$_COOKIE['map_day'].'" class="input_font" id="c" onkeyup="copyc()"></input> 查找到日: <input type="input" name="map_date2" value="'.$_COOKIE['map_day2'].'" class="input_font" id="f" onkeyup="copyf()"></input></p>
		<p>起始时: <input type="input" name="map_hour" value="'.$_COOKIE['map_hour'].'" class="input_font" id="g" onkeyup="copyg()"></input> 查找到小时: <input type="input" name="map_hour2" value="'.$_COOKIE['map_hour2'].'" class="input_font" id="j" onkeyup="copyj()"></input></p>
		<p>起始分: <input type="input" name="map_minute" value="'.$_COOKIE['map_minute'].'" class="input_font" id="h" onkeyup="copyh()"></input> 查找到分钟: <input type="input" name="map_minute2" value="'.$_COOKIE['map_minute2'].'" class="input_font" id="k" onkeyup="copyk()"></input></p>
		<p>起始秒: <input type="input" name="map_second" value="'.$_COOKIE['map_second'].'" class="input_font" id="i" onkeyup="copyi()"></input> 查找到秒: <input type="input" name="map_second2" value="'.$_COOKIE['map_second2'].'" class="input_font" id="l" onkeyup="copyl()"></input></p>

		<button type="submit" class="header_button" onclick="" style="text-align:flex;">查看所选范围</button>
	</form>';
		// echo "<p>2logic</p>";
	}

	echo '<form action="map_dateReset.php" method="post" style="display:center; text-align:center;">';
	echo '<p><button type="submit" class="header_button" onclick="" style="text-align:flex; margin-bottom:50px;">清除范围</button></p>';
	echo '</form>';

	echo'<p class="narrator" style="font-size: x-large; text-align: center; id="status_indicator"></p>';

	echo '<div id="indicator"></div>';
	echo '<div id="map" style="position: absolute; top: 0px; width: 100%; height: 80%; border-radius: 20px; border-width: 10px; border: solid; border-color: purple;"></div>';
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

		// $sql = 'SELECT * FROM `location_store` ORDER BY `date` DESC LIMIT 1000';
		$sql = 'SELECT * FROM `location_store` WHERE `date` BETWEEN "'.$year.'-'.$month.'-'.$day.'" AND "'.$year2.'-'.$month2.'-'.$day2.'" AND `time` BETWEEN "'.$hour.':'.$minute.':'.$second.'" AND "'.$hour2.':'.$minute2.':'.$second2.'"';

		$stmt = $pdo->query($sql);
		$row_count = $stmt->rowCount();
		$rows = $stmt->fetchAll();
		echo "<script>console.log(".$row_count.")</script>";

		if($row_count == 0){
			// echo'<p class="narrator" style="font-size: x-large; text-align: center;">查询条件/时间范围下并没有记录。</p>';
			echo '<script>document.getElementById("status_indicator").innerHTML = "查询条件/时间范围下并没有记录。"; </script>';
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
			       `<h3>".$rows[$i]['date'].' '.$rows[$i]['time']."</h3><p>时区: ".$rows[$i]['timezone']."</p><p>速度: ".$rows[$i]['speed']."</p><p>可能误差: ".$rows[$i]['drift']."</p><p>经度: ".$rows[$i]['latitude']."</p><p>纬度: ".$rows[$i]['longitude']."</p>`
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

		echo "</div>";

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

</body>

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