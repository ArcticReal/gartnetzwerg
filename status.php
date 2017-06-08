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
<body onload="state_tabs(-1)">
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
		<a href="#state" onclick="state_tabs(0)"><div id="status" class="item">
			<p>
				<i class="fa fa-table" aria-hidden="true"></i>
				Übersicht
			</p>
		</div></a>
		<a href="#diagramms" onclick="state_tabs(1)"><div id="diagramme" class="item">
			<p>
				<i class="fa fa-area-chart" aria-hidden="true"></i>
				Diagramme
			</p>
		</div></a>
		<a href="#cam" onclick="state_tabs(2)"><div id="cam" class="item">
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
			<!--<div id="img"></div>-->
		
			<div id="sensordaten">
				<button>Jetzt gießen</button>

				<form action=<?php echo "status.php?plant_id=".$_GET["plant_id"];?> method="POST">
					<input type="submit" name="update_data" value="update_data" onclick="update_data()">
				</form>
				
				<?php
					function update_data(){
						$controller->update_sensor_data(1);
					}
				?>

				<small>Zuletzt gemessen: xx.xx.xxxx</small>
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
			<div id="diadebug">x</div>
			<?php 
				$days = 14;

				$water_usage_array = $controller->water_usage_per_day($_GET["plant_id"], $days);
				$lighthours_array = $controller->lighthours_per_day($_GET["plant_id"], $days);
				$air_humidity_array = $controller->air_humidity_per_day($_GET["plant_id"], $days);
				$soil_humidity_array = $controller->soil_humidity_per_day($_GET["plant_id"], $days);
				$air_temperature_array = $controller->air_temperature_per_day($_GET["plant_id"], $days);
				$soil_temperature_array = $controller->soil_temperature_per_day($_GET["plant_id"], $days);
				$waterlogging_array = $controller->waterlogging_per_day($_GET["plant_id"], $days);
			?>

			<p>Temperatur-Verlauf</p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas1" width="500px" height="200px" style="border:1px solid #000000;">
				</canvas>
				<button id="canvasm" onclick="changeZoom(-1)"></button>
				<button id="canvasp" onclick="changeZoom(+1)"></button>
			</div>

			<p>Feuchtigkeitsverlauf</p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas2" width="500px" height="200px" style="border:1px solid #000000;">
				</canvas>
				<button id="canvasm" onclick="changeZoom(-1)"></button>
				<button id="canvasp" onclick="changeZoom(+1)"></button>
			</div>

			<p>Lichtstundenverlauf</p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas3" width="500px" height="200px" style="border:1px solid #000000;">
				</canvas>
				<button id="canvasm" onclick="changeZoom(-1)"></button>
				<button id="canvasp" onclick="changeZoom(+1)"></button>
			</div>

			<p>Wasserverbrauch</p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas4" width="500px" height="200px" style="border:1px solid #000000;">
				</canvas>
				<button id="canvasm" onclick="changeZoom(-1)"></button>
				<button id="canvasp" onclick="changeZoom(+1)"></button>
			</div>
		</div>

		<div id="tab_cam">
			<button>Jetzt schießen</button>
			<button>Live View?</button><br/>
			
			<p>letztes Bild:</p>
			<img src="./img/aloeveratopf.jpg" width="300"><br/>

			<img src="./img/aloeveratopf.jpg" width="100">
			<img src="./img/aloeveratopf.jpg" width="100">
			<img src="./img/aloeveratopf.jpg" width="100"><br/>

			<img src="./img/aloeveratopf.jpg" width="100">
			<img src="./img/aloeveratopf.jpg" width="100">
			<img src="./img/aloeveratopf.jpg" width="100">
		</div>

		<div id="tab_info">
			<h1>Tipps zur Pflanzenpflege einer Aloe Vera</h1>

			<strong>Richtig Gießen</strong>
			<?php 
				print("<p>".$plant->get_how_to_water()."</p>");
			?>

			<strong>Richtige Position</strong>
			<?php 
				print("<p>".$plant->get_needed_location()."</p>");
			?>

			<strong>Dünger-Tipps</strong>
			<?php 
				print("<p>".$plant->get_fertilizing_hints()."</p>");
			?>
		
			<strong>Winter-Vorbereitungen</strong>
			<?php 
				print("<p>".$plant->get_winter_prep()."</p>");
			?>

			<strong>Sommer-Vorbereitungen</strong>
			<?php 
				print("<p>".$plant->get_summer_prep()."</p>");
			?>

			<strong>Ungeziefer und Pflege</strong>
			<?php 
				print("<p>".$plant->get_caretaking_hints()."</p>");
			?>

			<strong>Umtopfen und Vermehren</strong>
			<?php 
				print("<p>".$plant->get_transplanting()."</p>");
			?>

			<strong>Spezielle Bedürfnisse</strong>
			<?php 
				print("<p>".$plant->get_special_needs()."</p>");
			?>
		</div>
	</div>
	<script src="js.js"></script>

	<?php
		foreach ($air_temperature_array as $i => $value) {
			echo '<script>add_data(0,'.$value.')</script>';
		}
		echo '<script>set_min_max(0,'.$min_air_temperature.','.$max_air_temperature.')</script>';

		//foreach ($soil_temperature_array as $i => $value) {
		//	echo '<script>add_data(3,'.$value.')</script>';
		//}

		foreach ($air_humidity_array as $i => $value) {
			echo '<script>add_data(1,'.$value.')</script>';
		}

		//foreach ($soil_humidity_array as $i => $value) {
		//	echo '<script>add_data(3,'.$value.')</script>';
		//}

		foreach ($lighthours_array as $i => $value) {
			echo '<script>add_data(2,'.$value.')</script>';
		}

		foreach ($water_usage_array as $i => $value) {
			echo '<script>add_data(3,'.$value.')</script>';
		}

		/*foreach ($waterlogging_array as $i => $value) {
			echo '<script>add_data(x,'.$value.')</script>';
		}*/

		echo '<script>init_diagramms('.$days.')</script>';
	?>
</body>
</html>