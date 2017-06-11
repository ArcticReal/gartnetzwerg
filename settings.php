<!DOCTYPE html>
<html>
<head>
	<title>GartNetzwerg — Einstellungen</title>

	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <link rel="stylesheet" type="text/css" href="./css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="./css/main.css">
</head>
<body>
	<!-- email / standort(owp) / key / -->
	<div id="header">
		<p>Allgemeine Einstellungen</p>
	</div>

	<?php 
		require_once 'gartnetzwerg/classes/controller.php'; 
			
		$controller = new Controller();

		$email = $controller->get_notification_receiving_email_address();
		$wohnort = $controller->get_openweathermap_location();
		$owm_key = $controller->get_openweathermap_api_key();
	?>

	<div id="form">
		<div id="alert" class="alert-none"></div>

		<form name="settings" id="settings" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
			<div class="row">
				<div class="cell"><p>Email-Adresse</p></div>
				<div class="cell">
					<?php
						print("<input type='email' name='email' placeholder='".$email."' autofocus>");
					?>
				</div>
			</div>
			<div class="row">
				<div class="cell"><p>Wohnort</p></div>
				<div class="cell">
					<?php
						print("<input type='text' name='wohnort' autocomplete='off' placeholder=\"".$wohnort."\">");
					?>
				</div>
			</div>
			<div class="row">
				<div class="cell"><p>OpenWeatherMap Key</p></div>
				<div class="cell">
					<?php
						print("<input type='text' name='owm_key' autocomplete='off' placeholder=\"".$owm_key."\">");
					?>
				</div>
			</div>

			<div class="row">
				<div class="cell">Notifications</div>
				<div class="cell">
					<select name="notifications">
						<option value="-1" selected>Keine Notifications</option>
						<option value="1">Kleine Notifications (Reine Sensordaten)</option>
						<option value="2">Große Notifications (Konkrete Arbeitsanweisungen)</option>
					</select>
				</div>
			</div>

			<div class="row">
				<div class="cell"></div>
				<div class="cell">
					<input type="button" name="delete_images" value="Bilder löschen">
				</div>
			</div>

			<div class="row">
				<div class="cell"></div>
				<div class="cell">
					<input type="button" name="delete_sensordata" value="Sensordaten löschen">
				</div>
			</div>

			<div class="row">
				<div class="cell"></div>
				<div class="cell">
					<input type="button" name="delete_sensorunit" value="Sensorunit löschen">
				</div>
			</div>
		</form>
	</div>

	<?php 
		$v_email = $v_wohnort = $v_owm_key = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$v_email = test_input($_POST["email"]);
			$v_wohnort = test_input($_POST["wohnort"]);
			$v_owm_key = test_input($_POST["owm_key"]);
			$v_notifications = test_input($_POST["notifications"]);
		}

		if($v_email != ""){
			$controller->change_email_address($v_email);
		}

		if($v_wohnort != ""){
			$controller->change_openweathermap_location($v_wohnort);
		}

		if($v_owm_key != ""){
			$controller->change_openweathermap_location($v_owm_key);
		}

		if($v_notifications != ""){
			//$controller->change_($v_notifications);
		}

		var_dump($v_email);
		var_dump($v_wohnort);
		var_dump($v_owm_key);
		var_dump($v_notifications);
	?>

	<div id="footer">
		<div id="back_to_menu" class="button w2">
			<a href="index.php"><i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i></a>
		</div>

		<div id="submit" class="button w2">
			<a href="javascript:;" onclick="settings_submit()"><i class="fa fa-check-circle fa-3x" aria-hidden="true"></i></a>
		</div>
	</div>

	<script src="js.js"></script>
</body>
</html>