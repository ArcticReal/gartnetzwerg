<!DOCTYPE html>
<html>
<head>
	<title>GartNetzwerg — Pflanzenstatus</title>

	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <link rel="stylesheet" type="text/css" href="./css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="./css/main.css">
</head>
<body onload="state_tabs(0)">
	<?php 
		require_once 'gartnetzwerg/classes/controller.php'; 
			
		$controller = new Controller();
		$controller->init();
		$plants = $controller->get_plants();		
		$plant = $plants[$_GET["plant_id"]];

		$scientific_name = $plant->get_scientific_name();
		$nickname = $plant->get_nickname();
		$name = $plant->get_name();

		$min_soil_humidity = $plant->get_min_soil_humidity();
		$max_soil_humidity = $plant->get_max_soil_humidity();
		$akt_soil_humidity = $plant->get_akt_soil_humidity();

		$min_air_humidity = $plant->get_min_air_humidity();
		$max_air_humidity = $plant->get_max_air_humidity();
		$akt_air_humidity = $plant->get_akt_air_humidity();

		$min_air_temperature = $plant->get_min_air_temperature();
		$max_air_temperature = $plant->get_max_air_temperature();
		$akt_air_temperature = $plant->get_akt_air_temperature();

		$min_soil_temperature = $plant->get_min_soil_temperature();
		$max_soil_temperature = $plant->get_max_soil_temperature();
		$akt_soil_temperature = $plant->get_akt_soil_temperature();

		$min_light_hours = $plant->get_min_light_hours();
		$max_light_hours = $plant->get_max_light_hours();
		$akt_light_hours = $plant->get_akt_light_hours();

		$waterlogging = $plant->tolerates_waterlogging();
		$akt_waterlogging = $plant->get_akt_waterlogging();
	?>

	<div id="header">
		<a href="index.php"><div id="back_to_menu" class="item">
			<i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i>
		</div></a>
		<div id="nick_name" class="item item2">
			<p><?php echo $nickname." (".$name.")"; ?></p>
		</div>
		<a href=<?php echo "flowersettings.php?plant_id=".$_GET["plant_id"];?>><div id="flowersettings" class="item">
			<i class="fa fa-cog fa-3x" aria-hidden="true"></i>
		</div></a>
	</div>

	<div id="nav">
		<a href="#status" onclick="state_tabs(0)"><div id="status" class="item">
			<p>
				<i class="fa fa-table" aria-hidden="true"></i>
				Übersicht
			</p>
		</div></a>
		<a href="#diagramms" onclick="state_tabs(1)"><div id="diagramms" class="item">
			<p>
				<i class="fa fa-area-chart" aria-hidden="true"></i>
				Diagramme
			</p>
		</div></a>
		<a href="#webcam" onclick="state_tabs(2)"><div id="webcam" class="item">
			<p>
				<i class="fa fa-camera" aria-hidden="true"></i>
				Kamera</p>
		</div></a>
		<a href="#info" onclick="state_tabs(3)"><div id="info" class="item">
			<p>
				<i class="fa fa-info" aria-hidden="true"></i>
				Info
			</p>
		</div></a>
	</div>

	<div id="list" class="status">
		<div id="tab_status">
			<div id="img"></div>
		
			<div id="sensordaten">
				<table>
					<tr>
						<th>Sensor</th>
						<th>Ideal</th>
						<th>Aktuell</th>
					</tr>
					<tr>
						<td>Bodenfeuchtigkeit</td>
						<td><?php echo $min_soil_humidity."% - ".$max_soil_humidity."%"; ?></td>
						<td><?php echo $akt_soil_humidity."%"; ?></td>
					</tr>
					<tr>
						<td>Luftfeuchtigkeit</td>
						<td><?php echo $min_air_humidity."% - ".$max_air_humidity."%"; ?></td>
						<td><?php echo $akt_air_humidity."%"; ?></td>
					</tr>
					<tr>
						<td>Temperatur</td>
						<td><?php echo $min_air_temperature." °C - ".$max_air_temperature." °C"; ?></td>
						<td><?php echo $akt_air_temperature." °C"; ?></td>
					</tr>
					<tr>
						<td>Bodentemperatur</td>
						<td><?php echo $min_soil_temperature." °C - ".$max_soil_temperature." °C"; ?></td>
						<td><?php echo $akt_soil_temperature." °C"; ?></td>
					</tr>
					<tr>
						<td>Lichtstunden</td>
						<td><?php echo $min_light_hours." h - ".$max_light_hours." h"; ?></td>
						<td><?php echo $akt_light_hours." h"; ?></td>
					</tr>
					<tr>
						<td>Staunässe</td>
						<td><?php echo $waterlogging; ?></td>
						<td><?php echo $akt_waterlogging; ?></td>
					</tr>
				</table>
			</div>
		</div>

		<div id="tab_diagramme">
			<p>Temperatur-Verlauf</p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas" width="500px" height="200px" style="border:1px solid #000000;">
				</canvas>
				<button id="canvasm" onclick="changeZoom(-1)"></button>
				<button id="canvasp" onclick="changeZoom(+1)"></button>
			</div>

			<p>Feuchtigkeitsverlauf</p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas" width="500px" height="200px" style="border:1px solid #000000;">
				</canvas>
				<button id="canvasm" onclick="changeZoom(-1)"></button>
				<button id="canvasp" onclick="changeZoom(+1)"></button>
			</div>

			<p>Lichtstundenverlauf</p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas" width="500px" height="200px" style="border:1px solid #000000;">
				</canvas>
				<button id="canvasm" onclick="changeZoom(-1)"></button>
				<button id="canvasp" onclick="changeZoom(+1)"></button>
			</div>
		</div>

		<div id="tab_webcam">
			<p>Webcam</p>
		</div>

		<div id="tab_info">
			<h1>Tipps zur Pflanzenpflege einer Aloe Vera</h1>

			<strong>Richtig Gießen</strong>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

			<strong>Richtige Position</strong>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

			<strong>Dünger-Tipps</strong>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
		
			<strong>Winter-Vorbereitungen</strong>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

			<strong>Sommer-Vorbereitungen</strong>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

			<strong>Ungeziefer und Pflege</strong>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

			<strong>Umtopfen und Vermehren</strong>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

			<strong>Spezielle Bedürfnisse</strong>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
		</div>
	</div>
	<script src="js.js"></script>
</body>
</html>