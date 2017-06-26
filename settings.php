<!DOCTYPE html>
<html lang="de">
<head>
	<title>GartNetzwerg — Einstellungen</title>

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
	<script type="text/javascript">
		function delete_sensor_unit_submit(){
			if(document.forms["delete_su"]["sensorunit"].value == -1){
				document.getElementById("alert").className = "";
				document.getElementById("alert").innerHTML = "Keine Sensoreinheit zum Löschen ausgewählt.";
			} else {
				document.getElementById("delete_su").submit();
			}
		}

		function settings_submit(){
			var errors = new Array();

			var email = document.forms["settings"]["email"].value;
			var n = email.search(/^(([^<>()\[\]\\.,;:\s@"']+(\.[^<>()\[\]\\.,;:\s@"']+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
			
			var owm_location = document.forms["settings"]["wohnort"].value;
			var n2 = owm_location.search(/^[^\\'"]{2,}$/);

			var owm_key = document.forms["settings"]["owm_key"].value;
			var n3 = owm_key.search(/^[^\\'"]{2,}$/);

			if(email!="" && n == -1){
				errors.push("Ungültige Email.");
			} else if(owm_location!="" && n2 == -1){
				errors.push("Der Standort darf kein ',\" oder \ enthalten.");
			} else if(owm_key!="" && n3 == -1){
				errors.push("Der OpenWeatherMap-Key darf kein ',\" oder \ enthalten.");
			} else {
				document.getElementById("settings").submit();
			}
		}
	</script>

	<!-- email / standort(owp) / key / -->
	<div id="header" class="small">
		<p>Allgemeine Einstellungen</p>
	</div>

	<?php 
		require_once 'gartnetzwerg/classes/controller.php'; 
			
		$controller = new Controller();

		$v_email = $v_wohnort = $v_owm_key = $v_notifications = $v_su = "";
		
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$v_email = test_input($_REQUEST["email"]);
			$v_wohnort = test_input($_REQUEST["wohnort"]);
			$v_owm_key = test_input($_REQUEST["owm_key"]);
			$v_notifications = test_input($_REQUEST["notifications"]);
			$v_su = test_input($_REQUEST["sensorunit"]);
		}

		if($v_email != ""){
			$email = $v_email;
			$controller->change_email_address($v_email);
		}

		if($v_wohnort != ""){
			$wohnort = $v_wohnort;
			$controller->change_openweathermap_location($v_wohnort);
		}

		if($v_owm_key != ""){
			$controller->change_openweathermap_api_key($v_owm_key);
		}

		if($v_notifications != ""){
			$controller->change_general_notification_settings($v_notifications);
		}

		if($v_su != ""){
			$controller->delete_sensorunit($v_su);
		}

		$email = $controller->get_notification_receiving_email_address();
		$wohnort = $controller->get_openweathermap_location();
		$owm_key = $controller->get_openweathermap_api_key();
		$notifications = $controller->get_general_notification_settings();
	?>

	<div id="form" class="small">
		<div id="wrap">
			<div id="alert" class="alert-none"></div>

			<form name="settings" id="settings" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				<div class="row">
					<div class="cell">
						<span>Email-Adresse</span>
						<a href="#" class="tooltip" tooltip="Deine Email für Notifications."><i class="fa fa-question-circle" aria-hidden="true"></i></a>
					</div>
					<div class="cell">
						<?php
							if($notifications=="ON"){
								print("<input type='text' name='email' id='email' placeholder='$email'>");
							} else {
								print("<input type='text' name='email' id='email' placeholder='[Notifications unten deaktiviert]'>");
							}
						?>
					</div>
				</div>
				<div class="row">
					<div class="cell">
						<span>Wohnort</span>
						<a href="#" class="tooltip" tooltip="Dein Wohnort für OpenWeatherMap."><i class="fa fa-question-circle" aria-hidden="true"></i></a>
					</div>
					<div class="cell">
						<?php
							print("<input type='text' name='wohnort' autocomplete='off' placeholder='$wohnort'>");
						?>
					</div>
				</div>
				<div class="row">
					<div class="cell">
						<span>OpenWeatherMap Key</span>
						<a href="#" class="tooltip" tooltip="Mit OpenWeatherMap überprüfen wir den Wetterbericht, für deine Outdoor-Pflanzen; im Falle dass es Regnen sollte wird dann die automatische Bewässerung kurzzeitig deaktiviert. Falls du zufällig einen Premium OpenWeatherMap Key besitzt, kannst du ihn hier eintragen um genauere Wetterdaten für unsere Überprüfung zu verwenden."><i class="fa fa-question-circle" aria-hidden="true"></i></a>
					</div>
					<div class="cell">
						<?php
							print("<input type='text' name='owm_key' autocomplete='off' placeholder='$owm_key'>");
						?>
					</div>
				</div>

				<div class="row">
					<div class="cell"><span>Notifications</span></div>
					<div class="cell">
						<?php
							if($notifications=="ON"){
								print('<input type="hidden" name="notifications" value="OFF">');
								print('<input type="checkbox" name="notifications" value="ON" checked>');
							} else {
								print('<input type="hidden" name="notifications" value="OFF">');
								print('<input type="checkbox" name="notifications" value="ON">');
							}
						?>
					</div>
				</div>
			</form>

			<form name="delete_su" id="delete_su" action="settings.php" method="post">
				<div class="row">
					<div class="cell"></div>
					<div class="cell">
						<input type="hidden" name="del_su" value=1>
						<?php
							$sensorunits = $controller->get_free_sensorunits();
				
							if(count($sensorunits)>0){
								print("<select id=\"sensorunit\" name=\"sensorunit\"><option value=\"-1\" selected></option>");
								foreach($sensorunits as $id => $sensorunit){
									print("<option value=".$id.">".$sensorunit->get_name()." (".$sensorunit->get_mac_address().")</option>");
								}
								print("</select>");

								print('<input onclick="delete_sensor_unit_submit()" class="delete" id="delete_su_button" type="button" name="delete" value="Sensoreinheit löschen">');
							}
						?>
					</div>
				</div>
			</form>
		</div>
	</div>

	<?php
		function test_input($data){
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
	?>

	<div id="footer">
		<div id="back_to_menu" class="button w2">
			<a href="index.php"><i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i></a>
		</div>

		<div id="submit" class="button w2">
			<a href="javascript:;" onclick="settings_submit()"><i class="fa fa-check-circle fa-3x" aria-hidden="true"></i></a>
		</div>
	</div>
</body>
</html>