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

		$plantname = $plants[$_GET["plant_id"]]->get_nickname();
		$plantname = $plants[$_GET["plant_id"]]->get_nickname();
	?>

	<div id="header" class="small">
		<p><strong>Blumeneinstellungen</strong></p>
	</div>

	<div id="form" class="small">
		<div id="alert" class="alert-none"></div>

		<form name="flowersettings" id="flowersettings" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
			<!--<div class="row">
				<div class="cell"><p>Pflanze favorisieren</p></div>

				<div class="cell">
					<input type="checkbox" name="favorite">
				</div>
			</div>-->
			<div class="row">
				<div class="cell"><p>Pflanzenname ändern</p></div>

				<div class="cell">
				<?php
					print("<input type='text' name='name' autocomplete='off' placeholder='".$plantname."' autofocus>");
				?>
				</div>
			</div>
			<div class="row">
				<div class="cell"><p>Pflanzenart ändern</p></div>

				<div class="cell">
					<select name="scientific_name">
						<option value="s1">Aloe Vera</option>
						<option value="s2">Lumos Maxima</option>
						<option value="s3">Crucio</option>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="cell"><p>Auto-Bewässerung</p></div>
				<div class="cell"><input type="checkbox" name="auto-watering"></div>
			</div>
			<div class="row">
				<div class="cell"><p>Standort anpassen</p></div>
				<div class="cell">
					<input type="text" name="name" placeholder="Wohnzimmer">
				</div>
			</div>
			<div class="row">
				<div class="cell"></div>
				<div class="cell">
					Drinnen <input type="radio" name="indoor" value="Drinnen" checked>
					Draußen <input type="radio" name="indoor" value="Draußen">
				</div>
			</div>
			<div class="row">
				<div class="cell"><p>Notifications einstellen</p></div>
				<div class="cell"><input type="checkbox" name="notifications"></div>
			</div>
			<div class="row">
				<div class="cell"></div>
				<div class="cell"><input type="button" name="delete" value="Pflanze löschen"></div>
			</div>
		</form>
	</div>

	<div id="footer">
		<div id="status" class="button w2">
			<a href=<?php echo "status.php?plant_id=".$_GET["plant_id"];?>><i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i></a>
		</div>

		<div id="submit" class="button w2">
			<a href=<?php echo "status.php?plant_id=".$_GET["plant_id"];?> onclick="flowersettings_submit()"><i class="fa fa-check-circle fa-3x" aria-hidden="true"></i></a>
		</div>
	</div>

	<script src="js.js"></script>
</body>
</html>