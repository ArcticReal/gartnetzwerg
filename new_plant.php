<!DOCTYPE html>
<html lang="de">
<head>
	<title>GartNetzwerg</title>

	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

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

				$plantname = $auto_watering = $standort = $indoor = $su_name = $su_mac = "";
				$suid = $scientific_name = -1;

				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					$plantname = test_input($_POST["plantname"]);
					$scientific_name = test_input($_POST["scientific_name"]);
					$auto_watering = test_input($_POST["auto_watering"]);
					$standort = test_input($_POST["standort"]);
					$indoor = test_input($_POST["indoor"]);
					$su_id = test_input($_POST["sensorunit"]);
					$su_name = test_input($_POST["sensorunit_name"]);
					$su_mac = test_input($_POST["sensorunit_mac"]);
				}
				
				if($su_id!="" && $su_name=="" && $su_mac==""){
					//su_id is da bzw. mac/name is nicht da
					$nickname = $controller->add_plant($su_id, $scientific_name, $plantname, $standort, $indoor, $auto_watering);
					if(is_numeric($nickname) && $nickname == 0){
						print("<div id='alert'><i class='fa fa-times-circle fa-3x' aria-hidden 'true'></i> Pflanze konnte aus undefinierten Gründen nicht eingefügt werden (Fehler-Code #1).</div>");
					} else {
						print("<div id='alert' class='alert-ok'><i class='fa fa-check-circle fa-3x' aria-hidden 'true'></i> Pflanze '$nickname' erfolgreich eingefügt.</div>");
					}
				} else if($su_id=="" && $su_name!="" && $su_mac!=""){
					//su_id is nich da bzw. mac/name is
					$id = $controller->add_sensor_unit($su_mac, $su_name);
					if($id!=-1){
						$nickname = $controller->add_plant($id, $scientific_name, $plantname, $standort, $indoor, $auto_watering);
						if(is_numeric($nickname) && $nickname == 0){
							print("<div id='alert'><i class='fa fa-times-circle fa-3x' aria-hidden 'true'></i> Pflanze konnte aus undefinierten Gründen nicht eingefügt werden (Fehler-Code #2).</div>");
						} else {
							print("<div id='alert' class='alert-ok'><i class='fa fa-check-circle fa-3x' aria-hidden 'true'></i> Pflanze '$nickname' erfolgreich eingefügt.</div>");
						}
					} else {
						print("<div id='alert'><i class='fa fa-times-circle fa-3x' aria-hidden 'true'></i> Sensoreinheit konnte aus undefinierten Gründen nicht eingefügt werden (Fehler-Code #3).</div>");
					}
				} else {
					//case, wenn alles leer ist
				}
			?>

			<div id="alert" class="alert-none"></div>

			<form name="new_plant" id="new" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				<div class="row">
					<div class="cell"><p>Pflanzenname</p></div>

					<div class="cell">
						<?php
							$placeholder_names = Array("Mercy","Ana","Wilson","Testobjekt #76","Polly","Kim","Stephen","Donald","Sir Pflanze von Hohengarten","Aloe Vera","Ghost Pepper","Scott","Ramona","Mei","Carolina Reaper","Groot","Wolfgangs Pflanze","Mr. Pflanze");
						?>
						<input type="text" name="plantname" size="16" maxlength="45" autocomplete="off" width="20" placeholder=<?php echo "\"z.B. '".$placeholder_names[array_rand($placeholder_names)]."'\""; ?>>
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
						<a href="#" class="tooltip" tooltip="Falls die Sensoreinheit an deiner Pflanze einen Wassertank hat, ist Auto-Bewässerung eine gute Einstellung."><i class="fa fa-question-circle" aria-hidden="true"></i></a>
					</div>

					<div class="cell">
						<input type="hidden" name="auto_watering" value=0>
						<input type="checkbox" name="auto_watering" value=1>
					</div>
				</div>

				<div class="row">
					<div class="cell"><p>Standort</p></div>

					<?php
							$placeholder_locations = Array("Wintergarten","Keller","Fenstersims, Wohnzimmer","Gartenhäuschen, links oben","Balkon, West");
						?>
					<div class="cell">
						<input type="text" name="standort" maxlength="45" placeholder=<?php echo "\"z.B. '".$placeholder_locations[array_rand($placeholder_locations)]."'\""; ?>>
					</div>
				</div>

				<div class="row">
					<div class="cell">
						<p></p>
					</div>

					<div class="cell" style="padding-left: 3px">
						Drinnen <input type="radio" name="indoor" value=1 checked>
						Draußen <input type="radio" name="indoor" value=0>
					</div>
				</div>

				<div class="row">
					<div class="cell">
						<span>Sensoreinheit</span>
						<a href="#" class="tooltip" tooltip="Damit wir deine Pflanze überwachen können, müssen wir wissen, welche Sensoreinheit deine Pflanze überwacht. Den Namen der Sensoreinheit solltest du auf der Einheit finden; die MAC-Adresse sollte im Handbuch stehen."><i class="fa fa-question-circle" aria-hidden="true"></i></a>
					</div>

					<div class="cell">
						<!--<input type="hidden" name="sensorunit" value="-1">-->
						<?php
							$sensorunits = $controller->get_free_sensorunits();

							if(count($sensorunits)>0){
								print("<p style='padding-left: 4px; padding-top: 10px;'><small>Freie Sensoreinheit auswählen:</small></p>");
								print("<select id=\"sensorunit\" name=\"sensorunit\"><option value=\"-1\" selected></option>");
								foreach($sensorunits as $id => $sensorunit){
									print("<option value=".$id.">".$sensorunit->get_name()." (".$sensorunit->get_mac_address().")</option>");
								}
								print("</select>");

								print("<p style='padding-left: 4px; padding-top: 10px;'><small>Oder, neue Sensoreinheit einfügen:</small></p>");
								print("<input type='text' name='sensorunit_name' maxlength='45' placeholder='Name der Sensoreinheit'><br/>
									<input type='text' name='sensorunit_mac' title='Die MAC-Adresse muss in einem XX:XX:XX:XX:XX:XX-Format eingegeben werden.' placeholder='MAC-Adresse (XX:XX:XX:XX:XX:XX)'>");
							} else {
								//print("<p><small>Neue Sensoreinheit einfügen:</small></p>");
								print("<input type='text' name='sensorunit_name' maxlength='17' placeholder='Name der Sensoreinheit'><br/>
									<input type='text' name='sensorunit_mac' title='Die MAC-Adresse muss in einem XX:XX:XX:XX:XX:XX-Format eingegeben werden.' placeholder='MAC-Adresse (XX:XX:XX:XX:XX:XX)'>");
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
			<?php print("<a href='javascript:;' onclick='new_plant_submit(".count($sensorunits).");'><i class='fa fa-check-circle fa-3x'></i></a>"); ?>
		</div>
	</div>

	<script src="js.js"></script>
</body>
</html>