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

	<div id="form">
		<div id="alert" class="alert-none"></div>

		<form name="settings">
			<div class="row">
				<div class="cell"><p>Email-Adresse</p></div>
				<div class="cell">
					<input type="email" name="email" placeholder="aloe@gartnetzwerg.com" autofocus>
				</div>
			</div>
			<div class="row">
				<div class="cell"><p>Wohnort</p></div>
				<div class="cell">
					<input type="text" name="name" size="16" maxlength="16" autocomplete="off" width="20" placeholder="Kempten">
				</div>
			</div>
			<div class="row">
				<div class="cell"><p>OpenWeatherMap Key</p></div>
				<div class="cell">
					<input type="text" name="name" placeholder="...">
				</div>
			</div>
			<div class="row">
				<div class="cell"></div>
				<div class="cell">
					<input type="button" name="delete" value="Bilder löschen">
				</div>
			</div>
			<div class="row">
				<div class="cell"></div>
				<div class="cell">
					<input type="button" name="delete" value="Sensordaten löschen">
				</div>
			</div>
		</form>
	</div>

	<div id="footer">
		<div id="back_to_menu" class="button w2">
			<a href="index.php"><i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i></a>
		</div>

		<div id="save" class="button w2">
			<a href="index.php"><i class="fa fa-check-circle fa-3x" aria-hidden="true"></i></a>
		</div>
	</div>

	<script src="js.js"></script>
</body>
</html>