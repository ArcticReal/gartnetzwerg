<!DOCTYPE html>
<html lang="de">
<head>
	<title>Blumeneinstellungen — GartNetzwerg</title>

	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    
    <link rel="stylesheet" type="text/css" href="./css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="./css/main.css">
</head>
<body>
	<?php 
		require_once 'gartnetzwerg/classes/controller.php'; 
		$controller = new Controller();
		$plants = $controller->get_plants();		
		$plant = $plants[$_REQUEST["plant_id"]];
	?>

	<div id="header" class="small">
		<p><strong>Pflanzeneinstellungen</strong></p>
	</div>

	<div id="form" class="small">
		<div id="wrap">
			<?php
				$plant_name = $plant->get_nickname();
				$plant_type = $plant->get_species_id();
				$plant_location = $plant->get_location();
				$plant_indoor = $plant->is_indoor();
				$plant_autowatering = $plant->get_auto_watering();
				$plant_notfication = $plant->get_notification_settings();

				$v_plant_name = $v_plant_location = $v_plant_indoor = $v_plant_autowatering = $v_plant_notification = "";
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					$v_plant_name = test_input($_REQUEST['plantname']);
					$v_plant_location = test_input($_REQUEST['location']);
					$v_plant_indoor = test_input($_REQUEST['indoor']);
					$v_plant_autowatering = test_input($_REQUEST['auto_watering']);
					$v_plant_notification = test_input($_REQUEST['notifications']);

					if($v_plant_name != ""){
						$controller->change_plant_nickname($_REQUEST["plant_id"],$v_plant_name);
						$plant_name = $v_plant_name;
					}

					change_auto_watering($_REQUEST["plant_id"], $v_plant_autowatering);
					$plant_autowatering = $v_plant_autowatering;
					
					if($v_plant_location != ""){
						$controller->change_plant_location($_REQUEST["plant_id"],$v_plant_location,$v_plant_indoor);
						$plant_location = $v_plant_location;
					} else {
						$controller->change_plant_location($_REQUEST["plant_id"],$plant_location,$v_plant_indoor);
					}
					$plant_indoor = $v_plant_indoor;

					if($v_plant_notification == "off" || 
						$v_plant_notification == "both" || 
						$v_plant_notification == "sensordata_only" || 
						$v_plant_notification == "instructions_only"){
						$controller->change_plant_notfication_settings($_REQUEST["plant_id"],$v_plant_notification);
						$plant_notfication = $v_plant_notification;
					}
				}
			?>

			<div id="alert" class="alert-none"></div>

			<form name="flowersettings" id="flowersettings" action="<?php echo "flowersettings.php?plant_id=".$_REQUEST["plant_id"];?> " method="post">
				<div class="row">
					<div class="cell"><p>Pflanzenname ändern</p></div>

					<div class="cell">
					<?php
						print("<input type='text' name='plantname' autocomplete='off' placeholder='$plant_name'>");

						$arten = $controller->get_all_species();
						foreach($arten as $id => $scientific_name){
							if($id == $plant_type){
								print("<p><small><i>".$scientific_name."</i></small></p>");
							}
						}
					?>
					</div>
				</div>
				<div class="row">
					<div class="cell"><p>Auto-Bewässerung</p></div>
					<div class="cell">
						<input type="hidden" name="auto_watering" value=0>
						<?php
							if($plant_autowatering==0){
								print('<input type="checkbox" name="auto_watering" value=1>');
							} else {
								print('<input type="checkbox" name="auto_watering" value=1 checked>');
							}
						?>
					</div>
				</div>
				<div class="row">
					<div class="cell"><p>Standort anpassen</p></div>
					<div class="cell">
						<?php
							print("<input type='text' name='location' autocomplete='off' placeholder='$plant_location'>");
						?>
					</div>
				</div>
				<div class="row">
					<div class="cell"></div>
					<div class="cell">
						<?php
							if($plant_indoor==1){
								print('Drinnen <input type="radio" name="indoor" value=1 checked>');
								print('Draußen <input type="radio" name="indoor" value=0>');
							} else {
								print('Drinnen <input type="radio" name="indoor" value=1>');
								print('Draußen <input type="radio" name="indoor" value=0 checked>');
							}
						?>
					</div>
				</div>
				<div class="row">
					<div class="cell"><p>Notifications einstellen</p></div>
					<div class="cell">
						<?php
							if($controller->get_general_notification_settings()!="BOTH"){
								print('<select id="notifications" name="notifications">');
								switch ($plant_notfication) {
									case 'both': 
										print('<option value="off">Aus</option>');
										print('<option value="sensordata_only">Nur Sensordaten</option>');
										print('<option value="instructions_only">Nur Anweisungen</option>');
										print('<option value="both" selected>Beides</option>');
										break;
									case 'sensordata_only': 
										print('<option value="off">Aus</option>');
										print('<option value="sensordata_only" selected>Nur Sensordaten</option>');
										print('<option value="instructions_only">Nur Anweisungen</option>');
										print('<option value="both">Beides</option>');
										break;
									case 'instructions_only':
										print('<option value="off">Aus</option>');
										print('<option value="sensordata_only">Nur Sensordaten</option>');
										print('<option value="instructions_only" selected>Nur Anweisungen</option>');
										print('<option value="both">Beides</option>');
										break;
									default:
									case 'off':
										print('<option value="off" selected>Aus</option>');
										print('<option value="sensordata_only">Nur Sensordaten</option>');
										print('<option value="instructions_only">Nur Anweisungen</option>');
										print('<option value="both">Beides</option>');
										break;
								}
								print('</select>');
							} else {
								print('<p><small><i>In allgemeinen Einstellungen deaktiviert</i></small></p>');
							}
						?>
					</div>
				</div>
			</form>
			<form name="delete_plant" id="delete_plant" action="index.php" method="post">
				<div class="row">
					<div class="cell"></div>
					<div class="cell">
						<input type="hidden" name="del_plant" value=<?php echo $_REQUEST["plant_id"]; ?>>
						<input onclick="delete_plant_submit()" id="delete_button" type="button" name="delete" value="Pflanze löschen">
					</div>
				</div>
			</form>
		</div>

		<?php 
			function test_input($data){
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}
		?>
	</div>

	<div id="footer">
		<div id="status" class="button w2">
			<a href=<?php echo "status.php?plant_id=".$_REQUEST["plant_id"];?>><i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i></a>
		</div>
		
		<div id="submit" class="button w2">
			<?php print("<a href='javascript:;'' onclick='flowersettings_submit();'><i class='fa fa-check-circle fa-3x' aria-hidden 'true'></i></a>"); ?>
		</div>
	</div>

	<script src="js.js"></script>
</body>
</html>