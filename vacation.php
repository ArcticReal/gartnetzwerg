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
	<div id="header" class="small">
		<p><strong>Urlaubsmodus</strong></p>
	</div>
		
	<div id="form" class="small">
		<div id="wrap">
			<?php
				require_once 'gartnetzwerg/classes/controller.php'; 
				$controller = new Controller();
				$vacation_on = $controller->lookup_config("VACATION_FUNCTION");

				$s_date = $controller->get_vacation_start_date();
				$e_date = $controller->get_vacation_end_date();

				$start_date = $end_date = -1;
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					$start_date = test_input($_REQUEST["start_date"]);
					$end_date = test_input($_REQUEST["end_date"]);
					$s_date = $start_date;
					$e_date = $end_date;
				}

				if(($start_date != -1 && $end_date != -1) && $vacation_on == "OFF"){
					$controller->turn_on_vacation($start_date,$end_date);
				}

				if($vacation_on == "OFF"){
					print("<p style='padding: 8px; margin-bottom: 16px'>Wenn Sie den Urlaubsmodus einschalten, werden alle Pflanzen (die eine automatische Bewässerung besitzen) automatisch in bestimmten Tagesabständen bewässert. Der Urlaubsmodus wird automatisch im angegebenen Zeitraum aktiv, und schaltet sich auch automatisch wieder aus. Sie können den Urlaubsmodus auch frühzeitig ausschalten.</p>");
				} else if($s_date != "" && $e_date != ""){
					print("<p style='text-align: center'><small>Urlaubsmodus eingeschalten, aber nicht aktiv.</small></p><p>Falls Sie über den Herbst/Winter in den Urlaub fahren, stellen Sie alle Pflanzen, die nach drinnen gehen sollten frühzeitig hinein. Füllen Sie die Wassertanks der Außeneinheiten auf (siehe Wasserverbrauch unten).");
				} else {
					$date1=date_create($s_date);
					$date2=date_create();
					$diff2=date_diff($date1,$date2);
					print("<p>Viel Spaß im Urlaub! Noch $diff2 Tage übrig.</p><p></p>");
				}
			?>

			<div class="row">
				<div class="cell"><p>Wassertank Füllstand</p></div>
				<div class="cell"><p>Zu erwartender Wasserverbrauch</p></div>
			</div>
			<div class="row">
				<div class="cell">
					<?php 
						$plants = $controller->get_plants();

						foreach ($plants as $key => $value) {
							$su = $controller->get_sensorunit($value->get_sensor_unit_id());
							$su->calculate_watertank_level();
							$wtl = $su->get_watertank_level();
							if(is_nan($wtl)){
								print("<p style='padding-left: 5px'><small>".$value->get_nickname().": keine Daten vorhanden</small></p>");
							} else {
								print("<p style='padding-left: 5px'>".$value->get_nickname().": $wtl</p>");
							}
						}
					?>
				</div>
				<div class="cell">
					<?php 
						$date1=date_create($s_date);
						$date2=date_create($e_date);
						$diff=date_diff($date1,$date2);

						foreach ($plants as $key => $value) {
							$water_usage = $controller->sum_water_usage($value->get_plant_id(), $diff->format("%a"));
							if(is_nan($water_usage) || $water_usage==""){
								print("<p style='padding-left: 5px'><small>keine Daten vorhanden</small></p>");
							} else {
								print("<p style='padding-left: 5px'>$water_usage</p>");
							}
						}
					?>
				</div>
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

					if($vacation_on == "OFF"){
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
		</div>
	</div>
	
	<div id="footer">
		<div id="back_to_main" class="button w2">
			<a href="index.php"><i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i></a>
		</div>
		<div class="button w2 vacation">
			<?php
				if($vacation_on == "OFF"){
					print ("<input form='vacation' type='button' name='vacation' onclick='vacation_submit(1)' value='Urlaubsmodus aktivieren'>");
				} else if($s_date != "" && $e_date != ""){
					print ("<input form='vacation' type='button' name='vacation' onclick='vacation_submit(0)' value='Urlaubsmodus deaktivieren'>");
				} else {
					print ("<input form='vacation' type='button' name='vacation' onclick='vacation_submit(0)' value='Urlaubsmodus frühzeitig deaktivieren'>");
				}
			?>
		</div>
	</div>

	<script src="js.js"></script>
</body>
</html>