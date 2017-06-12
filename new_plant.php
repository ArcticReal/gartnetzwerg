<!DOCTYPE html>
<html lang="de">
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
<body>
	<?php 
		require_once 'gartnetzwerg/classes/controller.php'; 
		$controller = new Controller();

		$notifications = $controller->get_general_notification_settings();
	?>

	<div id="header" class="small">
		<p><strong>Neue Pflanze hinzufügen</strong></p>
	</div>

	<div id="form" class="small">
		<div id="wrap">
			<?php

				$plantname = $auto_watering = $standort = $indoor = $notifications = $su_name = $su_mac = "";
				$suid = $scientific_name = -1;
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					$plantname = test_input($_POST["plantname"]);
					$scientific_name = test_input($_POST["scientific_name"]);
					$auto_watering = test_input($_POST["auto_watering"]);
					$standort = test_input($_POST["standort"]);
					$indoor = test_input($_POST["indoor"]);
					$notifications = test_input($_POST["notifications"]);
					$su_id = test_input($_POST["sensorunit"]);
					$su_name = test_input($_POST["sensorunit_name"]);
					$su_mac = test_input($_POST["sensorunit_mac"]);
				}

				var_dump($plantname);
				var_dump($scientific_name);
				var_dump($auto_watering);
				var_dump($standort);
				var_dump($indoor);
				var_dump($notifications);
				var_dump($sensorunit);
				var_dump($sensorunit_name);
				var_dump($sensorunit_mac);

				if($su_name=="" && $su_mac=="" && $suid!=-1){
					print("<div id='alert'>Keine Sensorunits ausgewählt.</div>");
				} else if(($su_name=="" || $su_mac=="") && isset($su_id)){
					$nickname = $controller->add_plant($su_id, $scientific_name, $plantname, $standort, $indoor, $auto_watering);
					if($nickname!=0){
						print("<div id='alert' class='alert-ok'>Pflanze $nickname erfolgreich eingefügt.</div>");
					} else {
						print("<div id='alert'>Pflanze konnte nicht eingefügt werden.</div>");
					}
				} else if($su_name!="" && $su_mac!="" && !isset($su_id)){
					$id = $controller->add_sensor_unit($su_mac, $su_name);
					if(strpos($id,'Fehler')===false){
						$nickname = $controller->add_plant($id, $scientific_name, $plantname, $standort, $indoor, $auto_watering);
						if($nickname!=0){
							print("<div id='alert' class='alert-ok'>Pflanze $nickname erfolgreich eingefügt.</div>");
						} else {
							print("<div id='alert'>Pflanze konnte nicht eingefügt werden.</div>");
						}
					} else {
						print("<div id='alert'>Sensorunit konnte nicht hinzugefügt werden.</div>");
					}
				}

			?>

			<div id="alert" class="alert-none"></div>

			<form name="new_plant" id="new" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				<div class="row">
					<div class="cell"><p>Pflanzenname</p></div>

					<div class="cell">
						<input type="text" name="plantname" size="16" maxlength="16" autocomplete="off" width="20" placeholder="z.B. 'Mercy'" autofocus>
					</div>
				</div>

				<div class="row">
					<div class="cell"><p>Pflanzenart</p></div>

					<div class="cell">
						<select id="scientific_name" name="scientific_name">
							<option value="-1" selected> </option>
							<?php
								$arten = $controller->get_all_species();
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
						<a href="#" class="tooltip" tooltip="Falls die Sensoreinheit an deiner Pflanze einen Wassertank hat, ist Auto-Bewässerung eine gute Einstellung." tooltip-persistent><i class="fa fa-question-circle" aria-hidden="true"></i></a>
					</div>

					<div class="cell">
						<input type="hidden" name="auto_watering" value=0>
						<input type="checkbox" name="auto_watering" value=1>
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
						Drinnen <input type="radio" name="indoor" value=1 checked>
						Draußen <input type="radio" name="indoor" value=0>
					</div>
				</div>

				<div class="row">
					<div class="cell">
						<span>Sensoreinheit</span>
						<a href="#" class="tooltip" tooltip="Damit wir deine Pflanze überwachen können, müssen wir wissen, welche Sensoreinheit deine Pflanze überwacht. Den Namen der Sensoreinheit solltest du auf der Einheit finden; die MAC-Adresse sollte im Handbuch stehen." tooltip-persistent><i class="fa fa-question-circle" aria-hidden="true"></i></a>
					</div>

					<div class="cell">
						<input type="hidden" name="sensorunit" value="-1">
						<?php
							$sensorunits = $controller->get_free_sensorunits();

							if(count($sensorunits)>0){
								print("<div><select name='sensorunit'><option value='-1' selected> </option>");
								foreach($sensorunits as $id => $sensorunit){
									print("<option value=".$id.">".$sensorunit->get_name()." (".$sensorunit->get_mac_address().")</option>");
								}
								print("</select></div>");

								print("<div><input type='text' name='sensorunit_name' placeholder='z.B. node_6'><br/><input type='text' name='sensorunit_mac' 
									pattern='([\da-f]{2}\:){5}[\da-f]{2}\b' title='Die MAC-Adresse muss in einem XX:XX:XX:XX:XX:XX-Format eingegeben werden.' placeholder='XX:XX:XX:XX:XX:XX'></div>");
							} else {
								print("<div><input type='text' name='sensorunit_name' placeholder='z.B. node_6'><br/><input type='text' pattern='([\da-f]{2}\:){5}[\da-f]{2}\b' title='Die MAC-Adresse muss in einem XX:XX:XX:XX:XX:XX-Format eingegeben werden.' name='sensorunit_mac' placeholder='XX:XX:XX:XX:XX:XX'></div>");
							}

							function test_input($data){
								$data = trim($data);
								$data = stripslashes($data);
								$data = htmlspecialchars($data);
								return $data;
							}
						?>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<div id="footer">
		<div id="back_to_menu" class="button w2">
			<a href="index.php"><i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i></a>
		</div>

		<div id="submit" class="button w2">
			<a href="javascript:;" onclick="new_plant_submit(<?php echo($sensorunits); ?>)"><i class="fa fa-check-circle fa-3x" aria-hidden="true"></i></a>
		</div>
	</div>

	<script src="js.js"></script>
</body>
</html>