<!DOCTYPE html>
<html lang="de">
<head>
	<title>GartNetzwerg — Pflanzenstatus</title>

	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="refresh" content="1800" >
    <!--3600-->
    
    <link rel="stylesheet" type="text/css" href="./css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="./css/main.css">
</head>
<body onload="state_tabs(-1)">
	<?php 
		require_once 'gartnetzwerg/classes/controller.php'; 
			
		$controller = new Controller();
		
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

		<div id="nav">
			<a href="#state" onclick="state_tabs(0)"><div id="status" class="item">
				<p>
					<i class="fa fa-table" aria-hidden="true"></i>
					<span>Übersicht</span>
				</p>
			</div></a>
			<a href="#diagramms" onclick="state_tabs(1)"><div id="diagramme" class="item">
				<p>
					<i class="fa fa-bar-chart" aria-hidden="true"></i>
					<span>Diagramme</span>
				</p>
			</div></a>
			<a href="#cam" onclick="state_tabs(2)"><div id="cam" class="item">
				<p>
					<i class="fa fa-camera" aria-hidden="true"></i>
					<span>Kamera</span>
				</p>
			</div></a>
			<a href="#info" onclick="state_tabs(3)"><div id="info" class="item">
				<p>
					<i class="fa fa-info" aria-hidden="true"></i>
					<span>Info</span>
				</p>
			</div></a>
		</div>

	</div>

	<div id="list" class="status">
		<div id="tab_status">
			<!--<div id="img"></div>-->

			<?php
				$v_manual_water = $v_manual_data = $v_manual_photo = $v_live_view = "";
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					$v_manual_water = test_input($_REQUEST['manual_water']);
					$v_manual_data = test_input($_REQUEST['manual_data']);
					$v_manual_photo = test_input($_REQUEST['manual_photo']);
					$v_live_view = test_input($_REQUEST['live_view']);

					if($v_manual_water != ""){
						$controller->water($_GET['plant_id']);
					}

					if($v_manual_data != ""){
						$controller->update_all_sensor_data(1);
					}

					if($v_manual_photo != ""){
						//TODO:$controller->x();
					}

					if($v_live_view != ""){
						//TODO:$controller->x();
					}
				}

				function test_input($data){
					$data = trim($data);
					$data = stripslashes($data);
					$data = htmlspecialchars($data);
					return $data;
				}
			?>

			<div id="sensordaten">
				<?php
					//if($controller-> correction_text($plant)!=-1){
						//print('<div id="alert">'.$controller->correction_text($plant).'</div>');
					//}
				?>

				<div id="sensor_list">
					<div class="row">
						<div class="cell">
							<form name="top_buttons" id="b1" action=<?php echo "status.php?plant_id=".$_GET["plant_id"];?> method="post">
								<input type="hidden" name="manual_water" value="1">
								<input onclick="status_submit(0)" type="button" name="m_water" value="Manuelle Bewässerung"><br/>

								<?php
									if($plant->get_last_watering() != ""){
										print('<small>Zuletzt gegossen:'.$plant->get_last_watering().'</small>');
									} else {
										print('<small>Noch nie per Einheit gegossen.</small>');
									}
								?>
							</form>
						</div>
						<div class="cell">
							<form name="top_buttons" id="b2" action=<?php echo "status.php?plant_id=".$_GET["plant_id"];?> method="post">
								<input type="hidden" name="manual_data" value="1">
								<input onclick="status_submit(1)" type="button" name="m_data" value="Manuelle Messung"><br/>

								<?php
									if($controller->get_last_sensor_update($_GET["plant_id"]) != ""){
										print('<small>Zuletzt gemessen:'.$controller->get_last_sensor_update($_GET["plant_id"]).'</small>');
									} else {
										print('<small>Noch nie gemessen.</small>');
									}
								?>
							</form>
						</div>
					</div>
				</div>

				<table>
					<tr>
						<th>Sensor</th>
						<th colspan="2">Ideal (min - max)</th>
						<th>Aktuell</th>
					</tr>
					<tr>
						<td>Temperatur</td>
						<td><?php echo $min_air_temperature." °C"; ?></td>
						<td><?php echo $max_air_temperature." °C"; ?></td>
						<td><?php echo $akt_air_temperature." °C"; ?></td>
					</tr>
					<tr>
						<td>Bodentemperatur</td>
						<td><?php echo $min_soil_temperature." °C"; ?></td>
						<td><?php echo $max_soil_temperature." °C"; ?></td>
						<td><?php echo $akt_soil_temperature." °C"; ?></td>
					</tr>
					<tr>
						<td>Luftfeuchtigkeit</td>
						<td><?php echo $min_air_humidity."%"; ?></td>
						<td><?php echo $max_air_humidity."%"; ?></td>
						<td><?php echo $akt_air_humidity."%"; ?></td>
					</tr>
					<tr>
						<td>Bodenfeuchtigkeit</td>
						<td><?php echo $min_soil_humidity."%"; ?></td>
						<td><?php echo $max_soil_humidity."%"; ?></td>
						<td><?php echo $akt_soil_humidity."%"; ?></td>
					</tr>
					<tr>
						<td>Lichtstunden</td>
						<td><?php echo $min_light_hours." h"; ?></td>
						<td><?php echo $max_light_hours." h"; ?></td>
						<td><?php echo $akt_light_hours." h"; ?></td>
					</tr>
					<tr>
						<td>Staunässe</td>
						<td id="sn" colspan="3"><?php echo $akt_waterlogging; ?></td>
					</tr>
				</table>

				<table>
					<tr>
						<th></th>
						<th>Aktuell</th>
					</tr>
					<tr>
						<td>Füllstand Wassertank</td>
						<td><?php 
								$su = $controller->get_sensorunit($plant->get_sensor_unit_id());
								$su->calculate_watertank_level();
								$wtl = $su->get_watertank_level();
								if(is_nan($wtl)){
									print("<p><small>keine Daten vorhanden</small></p>");
								} else {
									print("<p>$wtl</p>");
								}
							?></td>
					</tr>
					<tr>
						<td>Wasserbedarf</td>
						<td><?php
								$wu = $controller->sum_water_usage($_GET["plant_id"], 2);
								if($wu=="ml"){
									print("<p><small>kein bisheriger Wasserbedarf</small></p>");
								} else {
									print("<p>$wu</p>");
								}
							?>	
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div id="tab_diagramme">
			<div id="diadebug"></div>
			<?php 
				$days = 365;

				$water_usage_array = $controller->water_usage_per_day($_GET["plant_id"], $days);
				$lighthours_array = $controller->lighthours_per_day($_GET["plant_id"], $days);
				$air_humidity_array = $controller->air_humidity_per_day($_GET["plant_id"], $days);
				$soil_humidity_array = $controller->soil_humidity_per_day($_GET["plant_id"], $days);
				$air_temperature_array = $controller->air_temperature_per_day($_GET["plant_id"], $days);
				$soil_temperature_array = $controller->soil_temperature_per_day($_GET["plant_id"], $days);
				$waterlogging_array = $controller->water_usage_per_day($_GET["plant_id"], $days);
			?>

			<input type="button" id="canvasm" onclick="change_days(-1)" value="-">
			<span><small id="dayfactor">x</small></span>
			<input type="button" id="canvasp" onclick="change_days(1)" value="+"><br/>

			<p>Lufttemperatur-Verlauf <small>(in Celsius)</small></p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas1" height="200px" style="border:1px solid #000000;">
				</canvas>
			</div>

			<p>Bodentemperatur-Verlauf <small>(in Celsius)</small></p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas2" width="600px" height="200px" style="border:1px solid #000000;">
				</canvas>
			</div>

			<p>Luftfeuchtigkeitsverlauf <small>(in 10er Schritten)</small></p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas3" width="600px" height="200px" style="border:1px solid #000000;">
				</canvas>
			</div>

			<p>Bodenfeuchtigkeitsverlauf <small>(in 10er Schritten)</small></p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas4" width="600px" height="200px" style="border:1px solid #000000;">
				</canvas>
			</div>

			<p>Lichtstundenverlauf <small>(in vollen Stunden)</small></p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas5" width="600px" height="200px" style="border:1px solid #000000;">
				</canvas>
			</div>

			<p>Wasserverbrauch <small>(in Liter)</small></p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas6" width="600px" height="200px" style="border:1px solid #000000;">
				</canvas>
			</div>
		</div>

		<div id="tab_cam">
			<?php 
				if (isset($_GET['manual_photo'])) {
					print('Manual Photo');
					//$controller->();
				} else if (isset($_GET['live'])) {
					print('Live');
					//$controller->();
				}
			?>

			<div id="cam_buttons">
				<div class="row">
					<div class="cell">
						<form name="top_buttons" id="b1" action=<?php echo "status.php?plant_id=".$_GET["plant_id"]."#cam";?> method="post">
							<input type="hidden" name="manual_photo" value="1">
							<input onclick="status_submit(2)" type="button" name="m_photo" value="Manuelles Photo"><br/>

							<?php
								if($plant->get_last_watering() != ""){
									print('<small>Letztes Photo:'.$plant->get_last_watering().'</small>');
								} else {
									print('<small>Noch keine Photos.</small>');
								}
							?>
						</form>
					</div>
					<div class="cell">
						<form name="top_buttons" id="b2" action=<?php echo "status.php?plant_id=".$_GET["plant_id"]."#cam";?> method="post">
							<input type="hidden" name="live_view" value="1">
							<input onclick="status_submit(3)" type="button" name="m_live" value="Live View"><br/>
						</form>
					</div>
				</div>
			</div>

			<p>letztes Bild:</p>
			<img src="./img/aloeveratopf.jpg" width="300" alt="letztes Bild"><br/>

			<?php
				function f_zero($par){
					if($par < 10)
						return 0;
				}

				$day = 02;
				$hour = 04;
				$min = 48;
				$sec = 18;

				$path = "/var/www/html/img/testpics/2017-06-02_".$hour."_".$min."_".$sec.".jpg";
				for ($i = 0; $i < -999999; $i++) { 
					$path = "/var/www/html/img/testpics/2017-06-".f_zero($day).$day."_".f_zero($hour).$hour."_".f_zero($min).$min."_".f_zero($sec).$sec.".jpg";
				
					if(file_exists($path)==1){
						print("<img src='./img/testpics/2017-06-".f_zero($day).$day."_".f_zero($hour).$hour."_".f_zero($min).$min."_".f_zero($sec).$sec.".jpg' alt='".$path."' width='30%'>");
					}

					$sec += 1;
					if($sec>59){
						$min += 1;
						$sec = 0;
					}

					if($min>60){
						$min %= 60;
						$hour += 1;
					}

					if($hour >= 22){
						$day += 1;
					}
				}
			?>
		</div>

		<div id="tab_info">
			<h1>Tipps zur Pflanzenpflege</h1>

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
		echo '<script>init_diagrams();</script>';
		echo '<script>change_days(0);</script>';

		foreach ($air_temperature_array as $i => $value) {
			echo "<script>add_data(0,'$i',$value);</script>";
		}
		echo '<script>set_min_max(0,'.$min_air_temperature.','.$max_air_temperature.');</script>';

		foreach ($soil_temperature_array as $i => $value) {
			echo '<script>add_data(1,"'.$i.'",'.$value.');</script>';
		}
		echo '<script>set_min_max(1,'.$min_soil_temperature.','.$max_soil_temperature.');</script>';

		foreach ($air_humidity_array as $i => $value) {
			echo '<script>add_data(2,"'.$i.'",'.$value.');</script>';
		}
		echo '<script>set_min_max(2,'.$min_air_humidity.','.$max_air_humidity.');</script>';

		foreach ($soil_humidity_array as $i => $value) {
			echo '<script>add_data(3,"'.$i.'",'.$value.');</script>';
		}
		echo '<script>set_min_max(3,'.$min_soil_humidity.','.$max_soil_humidity.');</script>';

		foreach ($lighthours_array as $i => $value) {
			echo '<script>add_data(4,"'.$i.'",'.$value.');</script>';
		}
		echo '<script>set_min_max(4,'.$min_light_hours.','.$max_light_hours.');</script>';

		foreach ($water_usage_array as $i => $value) {
			echo '<script>add_data(5,"'.$i.'",'.$value.');</script>';
		}
		echo "<script>set_min_max(5,0,0);</script>";
		
		$days = 7;
		echo "<script>init_diagramm(0,$days);</script>";
		echo "<script>init_diagramm(1,$days);</script>";
		echo "<script>init_diagramm(2,$days);</script>";
		echo "<script>init_diagramm(3,$days);</script>";
		echo "<script>init_diagramm(4,$days);</script>";
		echo "<script>init_diagramm(5,$days);</script>";
	?>
</body>
</html>