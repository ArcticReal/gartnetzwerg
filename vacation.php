<!DOCTYPE html>
<html lang="de">
<head>
	<title>Urlaubsmodus — GartNetzwerg</title>

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
		function vacation_submit(i){
			if(i==1){
				if(document.forms["vacation"]["start_date"].value == ""){
					document.getElementById("alert").className = "";
					document.getElementById("alert").innerHTML = "Das Start-Datum darf nicht leer sein.";
				} else if(document.forms["vacation"]["end_date"].value == ""){
					document.getElementById("alert").className = "";
					document.getElementById("alert").innerHTML = "Das End-Datum darf nicht leer sein.";
				} else {
					document.getElementById("vacation").submit();
				}
			} else if(i==0){
				document.getElementById("vacation").submit();
			} else {

			}
		}
	</script>

	<div id="header" class="small">
		<p><strong>Urlaubsmodus</strong></p>
	</div>
		
	<div id="form" class="small">
		<div id="wrap">
			<div style="text-align: center;">
				<?php
					require_once 'gartnetzwerg/classes/controller.php'; 
					$controller = new Controller();
					$vacation_on = $controller->lookup_config("VACATION_FUNCTION");

					$s_date = $controller->get_vacation_start_date();
					$e_date = $controller->get_vacation_end_date();

					$start_date = $end_date = -1;
					$va = $va_act = $va_deact = "";
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
						$start_date = test_input($_REQUEST["start_date"]);
						$end_date = test_input($_REQUEST["end_date"]);

						$va_act = test_input($_REQUEST["vac_act"]);
						$va_deact = test_input($_REQUEST["vac_deact"]);

						$s_date = $start_date;
						$e_date = $end_date;
					}

					if($va_act=='1'){
						$controller->turn_on_vacation($start_date,$end_date);
					} else if($va_deact=='1'){
						$controller->turn_off_vacation();
					}

					if($va_act == '1') {
						print("<p>Falls du über den Herbst/Winter in den Urlaub fährst, stelle alle Pflanzen, die nach drinnen gehen sollten frühzeitig hinein. Fülle bitte die Wassertanks der Außeneinheiten auf (siehe Wasserverbrauch unten).");
					} else if($vacation_on == "ON" && $va_deact!='1') {
						print("<p>Viel Spaß im Urlaub!</p><p><small>(Urlaubsmodus noch bis $e_date aktiviert.)</small></p>");
					} else {
						print("<p style='padding: 8px; margin-bottom: 16px'>Wenn Sie den Urlaubsmodus einschalten, werden alle Pflanzen (die eine automatische Bewässerung besitzen) automatisch in bestimmten Tagesabständen bewässert. Der Urlaubsmodus wird automatisch im angegebenen Zeitraum aktiv, und schaltet sich auch automatisch wieder aus. Sie können den Urlaubsmodus auch frühzeitig ausschalten.</p>");
					}
				?>
			</div>

			<div id="alert" class="alert-none"></div>

			<form name="vacation" id="vacation" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				<?php
					if($s_date == ""){
						$s_date = "Startdatum";
					}

					if($e_date == ""){
						$e_date = "Enddatum";
					}

					if(($vacation_on == "OFF" && $va_act!='1') || ($s_date == "Startdatum" && $e_date == "Enddatum")){
						print ("<div class='row'><div class='cell'><p>Startdatum</p></div><div class='cell'><input type='date' name='start_date' placeholder='$s_date'></div></div>");
						print ("<div class='row'><div class='cell'><p>Enddatum</p></div><div class='cell'><input type='date' name='end_date' placeholder='$e_date'></div></div>");
					}
				?>

				<?php
					function test_input($data){
						$data = trim($data);
						$data = stripslashes($data);
						$data = htmlspecialchars($data);
						return $data;
					}
				?>
			</form>

			<div class="row">
				<?php
					if(($vacation_on == "ON" || $va_act=='1') && $va_deact!='1'){
						print('<div class="cell"><p>Wassertank Füllstand</p></div>');
						print('<div class="cell"><p>Zu erwartender Wasserverbrauch</p></div>');	
					}
				?>
			</div>

			<div class="row">
			<?php 
				if(($vacation_on == "ON" || $va_act=='1') && $va_deact!='1'){

					$plants = $controller->get_plants();

					$date1=date_create($s_date);
					$date2=date_create($e_date);
					$diff=date_diff($date1,$date2);

					foreach ($plants as $key => $value) {
						$su = $controller->get_sensorunit($value->get_sensor_unit_id());
						$su->calculate_watertank_level();
						$wtl = $su->get_watertank_level();
						
						if($diff != "" && !is_bool($diff)){
							$water_usage = $controller->sum_water_usage($value->get_plant_id(), $diff->format("%a"));
						}

						if(!is_nan($wtl)){
							print("<div class='cell'><p style='padding-left: 5px'>".$value->get_nickname().": $wtl</p></div>");
						} else {
							print("<div class='cell'><p style='padding-left: 5px'><small>Keine Wassertank-Daten zu ".$value->get_nickname()." vorhanden</small></p></div>");
						}

						if($s_date != "Startdatum" || $e_date != "Enddatum"){
							print("<div class='cell'><p style='padding-left: 5px'>$water_usage</p></div>");
						} else {
							print("<div class='cell'><p style='padding-left: 5px'><small>Kein Zeitraum angegeben.</small></p></div>");
						}
					}

				}
			?>
			</div>
		</div>
	</div>
	
	<div id="footer">
		<div id="back_to_main" class="button w2">
			<a href="index.php"><i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i></a>
		</div>
		<div class="button w2 vacation">
			<?php
				if($vacation_on == "ON" && $va_deact!='1'){
					print("<input form='vacation' type='hidden' name='vac_deact' value='1'>");
					print ("<input form='vacation' type='button' name='vac' onclick='vacation_submit(0)' value='Urlaubsmodus frühzeitig deaktivieren'>");
				} else if($s_date != "Startdatum" && $e_date != "Enddatum"){
					print("<input form='vacation' type='hidden' name='vac_deact' value='1'>");
					print ("<input form='vacation' type='button' name='vac' onclick='vacation_submit(0)' value='Urlaubsmodus deaktivieren'>");
				} else {
					print("<input form='vacation' type='hidden' name='vac_act' value='1'>");
					print ("<input form='vacation' type='button' name='vac' onclick='vacation_submit(1)' value='Urlaubsmodus aktivieren'>");
				}
			?>
		</div>
	</div>
</body>
</html>