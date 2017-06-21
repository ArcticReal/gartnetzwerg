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
    
    <link rel="apple-touch-icon" sizes="57x57" href="./img/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="./img/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="./img/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="./img/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="./img/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="./img/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="./img/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="./img/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="./img/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="./img/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="./img/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="./img/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="./img/favicon/favicon-16x16.png">
	<link rel="manifest" href="./img/favicon/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="./img/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
    
    <link rel="stylesheet" type="text/css" href="./css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="./css/main.css">
</head>
<body onload="state_tabs(-1)">
	<?php 
		require_once 'gartnetzwerg/classes/controller.php'; 
			
		$controller = new Controller();

		$v_manual_water = $v_manual_data = $v_manual_photo = $v_live_view = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$v_manual_water = test_input($_REQUEST['manual_water']);
			$v_manual_data = test_input($_REQUEST['manual_data']);
			$v_manual_photo = test_input($_REQUEST['manual_photo']);
			$v_live_view = test_input($_REQUEST['live_view']);

			if($v_manual_water != ""){
				$controller->water($_REQUEST['plant_id']);
			}

			if($v_manual_data != ""){
				$controller->update_all_sensor_data(1);
			}

			if($v_manual_photo != ""){
				$controller->take_picture($_REQUEST['plant_id']);
			}

			if($v_live_view != ""){
				//$controller->x();
			}
		}
		
		$plants = $controller->get_plants();		
		$plant = $plants[$_REQUEST["plant_id"]];

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

	<div id="list" class="status">
		<div id="tab_status">
		<div id="nick_name" style="text-align: center;">
				<strong><p><?php echo $nickname." (".$name.")"; ?></p></strong>
			</div>

			<?php
				function test_input($data){
					$data = trim($data);
					$data = stripslashes($data);
					$data = htmlspecialchars($data);
					return $data;
				}
			?>

			<div id="sensordaten">
				<?php
					$correction_text = $controller->correction_text($_REQUEST["plant_id"]);
					if($correction_text!=""){
						print('<div id="alert" class="correction"><p>'.$correction_text.'</p></div>');
					}
				?>

				<div id="sensor_list">
					<div class="row">
						<div class="cell">
							<form name="top_buttons" id="b1" action=<?php echo "status.php?plant_id=".$_REQUEST["plant_id"];?> method="post">
								<input type="hidden" name="manual_water" value="1">
								<input onclick="status_submit(0)" type="button" name="m_water" value="Manuelle Bewässerung"><br/>

								<?php
									if($plant->get_last_watering() != ""){
										print('<small>Zuletzt gegossen: '.$plant->get_last_watering().'</small>');
									} else {
										print('<small>Noch nie per Einheit gegossen.</small>');
									}
								?>
							</form>
						</div>
						<div class="cell">
							<form name="top_buttons" id="b2" action=<?php echo "status.php?plant_id=".$_REQUEST["plant_id"];?> method="post">
								<input type="hidden" name="manual_data" value="1">
								<input onclick="status_submit(1)" type="button" name="m_data" value="Manuelle Messung"><br/>

								<?php
									if($controller->get_last_sensor_update($_REQUEST["plant_id"]) != ""){
										print('<small>Zuletzt gemessen: '.$controller->get_last_sensor_update($_REQUEST["plant_id"]).'</small>');
									} else {
										print('<small>Noch nie gemessen.</small>');
									}
								?>
							</form>
						</div>
					</div>
				</div>

				<div id="last_pic">
					<div class='responsive'>
						<?php 
							$pic_array = $controller->get_picture_array($_REQUEST["plant_id"]);
							$folder = $_REQUEST["plant_id"]."_".$plant->get_nickname(); 
							if(count($pic_array > 0)){
								//print("<img  src='./gartnetzwerg/Pictures/$folder/$pic_array[0]' alt='' width='300'>");
							}
						?>
						<img src="./img/aloe.png" height="200px">
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
						<td><?php echo $min_soil_humidity." von 10"; ?></td>
						<td><?php echo $max_soil_humidity." von 10"; ?></td>
						<td><?php echo $akt_soil_humidity." von 10"; ?></td>
					</tr>
					<tr>
						<td>Lichtstunden</td>
						<td><?php echo $min_light_hours." h"; ?></td>
						<td><?php echo $max_light_hours." h"; ?></td>
						<td><?php echo $akt_light_hours." h"; ?></td>
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
								$wu = $controller->sum_water_usage($_REQUEST["plant_id"], 2);
								if($wu=="ml"){
									print("<p><small>kein bisheriger Wasserbedarf</small></p>");
								} else {
									print("<p>$wu</p>");
								}
							?>	
						</td>
					</tr>
					<tr>
						<td>Staunässe</td>
						<td id="sn"><?php echo $akt_waterlogging." von 10"; ?></td>
					</tr>
				</table>
			</div>
		</div>

		<div id="tab_diagramme">
			<div id="diadebug"></div>
			<?php 
				$days = 365;

				$water_usage_array = $controller->water_usage_per_day($_REQUEST["plant_id"], $days);
				$lighthours_array = $controller->lighthours_per_day($plant->get_sensor_unit_id(), $days);
				$air_humidity_array = $controller->air_humidity_per_day($plant->get_sensor_unit_id(), $days);
				$soil_humidity_array = $controller->soil_humidity_per_day($plant->get_sensor_unit_id(), $days);
				$air_temperature_array = $controller->air_temperature_per_day($plant->get_sensor_unit_id(), $days);
				$soil_temperature_array = $controller->soil_temperature_per_day($plant->get_sensor_unit_id(), $days);
				$waterlogging_array = $controller->water_usage_per_day($plant->get_sensor_unit_id(), $days);
			?>

			<input type="button" id="canvasm" onclick="change_days(-1)" value="-">
			<span><small id="dayfactor">x</small></span>
			<input type="button" id="canvasp" onclick="change_days(1)" value="+"><br/>

			<p>Lufttemperatur-Verlauf <small>(in Celsius)</small></p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas1" width="600px" height="300px" style="border:1px solid #000000;">
				</canvas>
			</div>

			<p>Bodentemperatur-Verlauf <small>(in Celsius)</small></p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas2" width="600px" height="300px" style="border:1px solid #000000;">
				</canvas>
			</div>

			<p>Luftfeuchtigkeitsverlauf <small>(in Prozent)</small></p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas3" width="600px" height="300px" style="border:1px solid #000000;">
				</canvas>
			</div>

			<p>Bodenfeuchtigkeitsverlauf <small>(in 10er Schritten)</small></p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas4" width="600px" height="300px" style="border:1px solid #000000;">
				</canvas>
			</div>

			<p>Lichtstundenverlauf <small>(in Stunden)</small></p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas5" width="600px" height="300px" style="border:1px solid #000000;">
				</canvas>
			</div>

			<p>Wasserverbrauch <small>(in Milliliter)</small></p>
			<div id="diagramm1" class="diagramm">
				<canvas id="canvas6" width="600px" height="300px" style="border:1px solid #000000;">
				</canvas>
			</div>
		</div>

		<div id="tab_cam">
			<div id="gallery_modal" class="modal">
				<span class="close">×</span>
				<img class="modal-content" id="modal_img">
				<div id="caption"></div>
			</div>

			<div id="cam_buttons">
				<div class="row">
					<div class="cell">
						<form name="top_buttons" id="b3" action=<?php echo "status.php?plant_id=".$_REQUEST["plant_id"]."#cam";?> method="post">
							<input type="hidden" name="manual_photo" value="1">
							<input onclick="status_submit(2)" type="button" name="m_photo" value="Manuelles Photo"><br/>

							<?php 
								if(count($pic_array)<=0){
									print("<p><small>Noch keine Bilder vorhanden. :(</small></p>");
								} else {
									print("<p><small>&nbsp;</small></p>");
									//print('<small>Letztes Photo:'.$plant->get_last_watering().'</small>');
								}
							?>
						</form>
					</div>
					<div class="cell">
						<form name="top_buttons" id="b4" action=<?php echo "status.php?plant_id=".$_REQUEST["plant_id"]."#cam";?> method="post">
							<input type="hidden" name="live_view" value="1">
							<input onclick="status_submit(3)" type="button" name="m_live" value="Live View"><br/>
							<p><small>&nbsp;</small></p>
						</form>
					</div>
					<div class="cell">
						<?php 
							if(count($pic_array)>0){
								$controller->make_time_lapse($_REQUEST["plant_id"], $pic_array, 10);
								print("<input onclick=\"zeitraffer_modal('".$_REQUEST['plant_id']."_".$plant->get_nickname()."')\" type='button' name='m_timelapse' value='Zeitraffer'><br/>");
							} else {
								print("<input type='button' class='disabled' name='m_timelapse' value='Zeitraffer' disabled><br/>");
							}
						?>
						<p><small>&nbsp;</small></p>
					</div>
				</div>
			</div>

			<script type="text/javascript" charset="utf-8" src="gallery.js"></script>

			<div id="gallery">
				<?php
					foreach ($pic_array as $i => $value) {
						echo '<script>add_pic_array("'.$value.'");</script>';
					}
				?>

				<script>init_gallery(<?php echo "'".$_REQUEST["plant_id"]."_".$plant->get_nickname()."'"; ?>);</script>
			</div>
		</div>

		<div id="tab_info">
			<h1>Tipps zur Pflege für <?php echo "$scientific_name"; ?></h1>

			<p><b>Richtig Gießen</b></p>
			<?php 
				print("<div><p>".$plant->get_how_to_water()."</p></div>");
			?>

			<p><b>Richtige Position</b></p>
			<?php 
				print("<div><p>".$plant->get_needed_location()."</p></div>");
			?>

			<p><b>Dünger-Tipps</b></p>
			<?php 
				print("<div><p>".$plant->get_fertilizing_hints()."</p></div>");
			?>
		
			<p><b>Winter-Vorbereitungen</b></p>
			<?php 
				print("<div><p>".$plant->get_winter_prep()."</p></div>");
			?>

			<p><b>Sommer-Vorbereitungen</b></p>
			<?php 
				print("<div><p>".$plant->get_summer_prep()."</p></div>");
			?>

			<p><b>Ungeziefer und Pflege</b></p>
			<?php 
				print("<div><p>".$plant->get_caretaking_hints()."</p></div>");
			?>

			<p><b>Umtopfen und Vermehren</b></p>
			<?php 
				print("<div><p>".$plant->get_transplanting()."</p></div>");
			?>

			<p><b>Spezielle Bedürfnisse</b></p>
			<?php 
				print("<div><p>".$plant->get_special_needs()."</p></div>");
			?>
		</div>
	</div>

	<div id="footer">
		<div id="status" class="button w2">
			<a href="index.php">
				<div id="back_to_menu" class="item">
					<i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i>
				</div>
			</a>
		</div>
		
		<div id="submit" class="button w2">
			<a href=<?php echo "flowersettings.php?plant_id=".$_REQUEST["plant_id"];?>>
				<div id="flowersettings" class="item">
					<i class="fa fa-cog fa-3x" aria-hidden="true"></i>
				</div>
			</a>
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