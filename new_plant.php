<!DOCTYPE html>
<html>
<head>
	<title>GartNetzwerg</title>

	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <link rel="stylesheet" type="text/css" href="./css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="./css/new.css">
	<link rel="stylesheet" type="text/css" href="./css/status.css">
	<link rel="stylesheet" type="text/css" href="./css/main.css">
</head>
<body onload="state_tabs()">
	<?php 
		require_once 'gartnetzwerg/classes/controller.php'; 
					
		//insert_plant($sensorunit_id, $species_id, $nickname, $location, $is_indoor, $auto_watering);
	?>

	<div id="header">
		<p><strong>Neue Pflanze hinzufügen</strong></p>
	</div>

	<div id="form">
		<div id="alert"></div>

		<form name="new_plant" id="new" action="/index.php" method="get">
			<div class="row">
				<div class="cell"><p>Pflanzenname</p></div>

				<div class="cell">
					<input type="text" name="plantname" size="16" maxlength="16" autocomplete="off" width="20" placeholder="z.B. 'Mercy'" autofocus>
				</div>
			</div>

			<div class="row">
				<div class="cell"><p>Pflanzenart</p></div>

				<div class="cell">
					<select name="scientific_name">
						<?php
							$arten = ["Aloe Vera","2","3"];
							foreach($arten as $id => $scientific_name){
								print("<option value=".$id.">".$scientific_name."</option>");
							}
						?>
					</select>
				</div>
			</div>

			<div class="row">
				<div class="cell">
					<span>Auto-Bewässerung</span>
					<a href="#" class="tooltip"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
				</div>

				<div class="cell">
					<input type="checkbox" name="auto-watering">
				</div>
			</div>

			<div class="row">
				<div class="cell"><p>Standort</p></div>

				<div class="cell">
					<input type="text" name="standort" placeholder="z.B. 'Balkon, links'"><br/>
				</div>
			</div>

			<div class="row">
				<div class="cell">
					<p></p>
				</div>

				<div class="cell">
					Drinnen <input type="radio" name="indoor" value="Drinnen" checked>
					Draußen <input type="radio" name="indoor" value="Draußen">
				</div>
			</div>

			<div class="row">
				<div class="cell">
					<span>Notifications</span>
					<a href="#" class="tooltip"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
				</div>

				<div class="cell">
					<input type="checkbox" name="notifications">
				</div>
			</div>

			<div class="row">
				<div class="cell">
					<span>Sensoreinheit</span>
					<a href="#" class="tooltip"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
				</div>

				<div class="cell">
					<select name="scientific_name">
						<?php
							$sensorunits = ["Aloe Vera","2","3"];
							foreach($sensorunits as $id => $sensorunit){
								print("<option value=".$id.">".$sensorunit."</option>");
							}
						?>
					</select>
				</div>
			</div>

			<!--<table>
				<tr>
					<td>
						Sensoreinheit
						<a href="#"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
					</td>
					<td>
						<div id="availSU">
							Verfügbare Sensoreinheiten:<br/>
							<select name="sensorunit">
								<?php
									//foreach
									//print(<option value=".$value.">.$sensor_name.</option>);
								?>

								<option value="s1">Sensor#1</option>
								<option value="s2">Sensor#2</option>
								<option value="s3">Sensor#3</option>
							</select><br/>
						</div>

						<div id="buttonSU">
							<a href="#" onclick="toggle()"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
						</div>

						<div id="newSU">
							Neue Sensoreinheit:<br/>
							Name: <input type="text" name="sensorunit" placeholder="z.B. ..." required><br/>
							MAC-Adresse: <input type="text" name="sensorunit" maxlength="17" size="17" pattern="[0-9A-F][0-9A-F]:{5}[0-9A-F][0-9A-F]" placeholder="XX:XX:XX:XX:XX:XX" required><br/>
						</div>
					</td>
				</tr>
			</table>-->
		</form>
	</div>
	
	<div id="footer">
		<div id="info" class="button w2">
			<a href="index.php"><i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i></a>
		</div>

		<div id="submit" class="button w2">
			<a href="javascript:;" onclick="new_plant_submit()"><i class="fa fa-check-circle fa-3x" aria-hidden="true"></i></a>
		</div>
	</div>

	<script src="js.js"></script>
</body>
</html>