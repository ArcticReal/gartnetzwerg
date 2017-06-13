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

		$plant_name = $plant->get_nickname();
		/*$plant_type = $plant->get_species_id();
		$plant_location = $plant->get_location();
		$plant_indoor = $plant->is_indoor();
		$plant_autowatering = $plant->get_auto_watering();
		$plant_notfication = $plant->get_notification_settings();
		*/

		//$delete = "";

		$v_plant_name = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$v_plant_name = test_input($_REQUEST['plantname']);
			$v_delete = test_input($_REQUEST['delete']);
			$v_delete2 = test_input($_REQUEST['del_plant']);
		}

		if($v_delete == "Pflanze löschen"){
			//$controller->delete_plant($plants[$_REQUEST["plant_id"]]);
		}
	?>

	<div id="header" class="small">
		<p><strong>Blumeneinstellungen</strong></p>
	</div>

	<div id="form" class="small">
		<div id="wrap">
			<div id="alert" class="alert-none"></div>

			<form name="flowersettings" id="flowersettings" action="<?php echo "flowersettings.php?plant_id=".$_REQUEST["plant_id"];?> " method="post">
				<div class="row">
					<div class="cell"><p>Pflanzenname ändern</p></div>

					<div class="cell">
					<?php
						print("<input type='text' name='plantname' autocomplete='off' placeholder='$plant_name'>");
						echo $v_delete;
						echo $v_delete2;
					?>
					</div>
				</div>
				<div class="row">
					<div class="cell"><p>Pflanzenart ändern</p></div>

					<div class="cell">
						<select id="scientific_name" name="scientific_name">
							<?php
								/*$arten = $controller->get_all_species();
								foreach($arten as $id => $scientific_name){
									if($id == $plant_type){
										print("<option value=".$id." selected>".$scientific_name."</option>");
									} else {
										print("<option value=".$id.">".$scientific_name."</option>");
									}
								}*/
							?>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="cell"><p>Auto-Bewässerung</p></div>
					<div class="cell">
						<input type="hidden" name="auto_watering" value=0>
						<?php
							/*if($plant_autowatering==0){
								print('<input type="checkbox" name="auto_watering" value=1>');
							} else {
								print('<input type="checkbox" name="auto_watering" value=1 checked>');
							}*/
						?>
					</div>
				</div>
				<div class="row">
					<div class="cell"><p>Standort anpassen</p></div>
					<div class="cell">
						<?php
							//print("<input type='text' name='name' autocomplete='off' placeholder='$plant_location'>");
						?>
					</div>
				</div>
				<div class="row">
					<div class="cell"></div>
					<div class="cell">
						<?php
							/*if($plant_indoor==1){
								print('Drinnen <input type="radio" name="indoor" value=1 checked>');
								print('Draußen <input type="radio" name="indoor" value=0>');
							} else {
								print('Drinnen <input type="radio" name="indoor" value=1>');
								print('Draußen <input type="radio" name="indoor" value=0 checked>');
							}*/
						?>
					</div>
				</div>
				<div class="row">
					<div class="cell"><p>Notifications einstellen</p></div>
					<div class="cell">
						<select id="scientific_name" name="notifications">
						<?php
							/*switch ($plant_notfication) {
								case 'ON': 
									print('<option value="ON" selected></option>');
									print('<option value="_ONLY"></option>');
									print('<option value="_ONLY"></option>');
									print('<option value="OFF"></option>');
									break;
								case '1_ONLY': 
									print('<option value="ON"></option>');
									print('<option value="_ONLY" selected></option>');
									print('<option value="_ONLY"></option>');
									print('<option value="OFF"></option>');
									break;
								case '2_ONLY':
									print('<option value="ON"></option>');
									print('<option value="_ONLY"></option>');
									print('<option value="_ONLY" selected></option>');
									print('<option value="OFF"></option>');
									break;
								case 'OFF':
									print('<option value="ON"></option>');
									print('<option value="_ONLY"></option>');
									print('<option value="_ONLY"></option>');
									print('<option value="OFF" selected></option>')
									break;								
								default: break;
							}*/
						?>
						</select>
					</div>
				</div>
			</form>
			<form name="delete_plant" id="delete_plant" action="<?php echo "flowersettings.php?plant_id=".$_REQUEST["plant_id"];?> " method="post">
			<!--action="index.php" method="post">-->
				<div class="row">
					<div class="cell"></div>
					<div class="cell">
						<input type="hidden" name="del_plant" value="1">
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