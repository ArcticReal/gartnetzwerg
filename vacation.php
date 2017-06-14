<!DOCTYPE html>
<html lang="de">
<head>
	<title>Urlaubsmodus — GartNetzwerg</title>

	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

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

				if($vacation_on == "OFF"){
					print("<p>Wenn Sie den Urlaubsmodus aktivieren, werden alle Pflanzen (die eine automatische Bewässerung besitzen) automatisch bewässert.</p>");
				} else {
					print("<p>[Insert Allgemeine Tipps hier]</p>");
				}
			?>

			<div class="row">
				<div class="cell">
					<p>Füllstände Wassertank</p>
				</div>
				<div class="cell">
					<?php 
						$plants = $controller->get_plants();

						foreach ($plants as $key => $value) {
							$su = $controller->get_sensorunit($value->get_sensor_unit_id());
							$su->calculate_watertank_level();
							$wtl = $su->get_watertank_level();
							if(is_nan($wtl)){
								print("<p><small>keine Daten vorhanden</small></p>");
							} else {
								print("<p>$wtl</p>");
							}
						}
					?>
				</div>
			</div>

			<div id="alert" class="alert-none"></div>

			<form name="vacation" id="vacation" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				<?php
					$s_date = $controller->get_vacation_start_date();
					$e_date = $controller->get_vacation_end_date();

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
				<div class="row">
					<div class="cell1">
						<?php
							if($vacation_on == "OFF"){
								print ("<input type='button' name='vacation' onclick='vacation_submit(1)' value='Urlaubsmodus aktivieren'>");
							} else {
								print ("<input type='button' name='vacation' onclick='vacation_submit(0)' value='Urlaubsmodus frühzeitig deaktivieren'>");
							}
						?>
					</div>
				</div>

				<?php
					function test_input($data){
						$data = trim($data);
						$data = stripslashes($data);
						$data = htmlspecialchars($data);
						return $data;
					}

					$start_date = $end_date = -1;
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
						$start_date = test_input($_POST["start_date"]);
						$end_date = test_input($_POST["end_date"]);
					}

					if(($start_date != -1 && $end_date != -1) && $vacation_on == "OFF"){
						$controller->turn_on_vacation($start_date,$end_date);
					} else if($vacation_on == "ON"){
						$controller->turn_off_vacation();
					} 
				?>
			</form>
		</div>
	</div>
	
	<div id="footer">
		<div id="back_to_main" class="button">
			<a href="index.php"><i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i></a>
		</div>
	</div>

	<script src="js.js"></script>
</body>
</html>