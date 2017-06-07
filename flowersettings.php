<!DOCTYPE html>
<html>
<head>
	<title>GartNetzwerg — Blumeneinstellungen</title>

	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <link rel="stylesheet" type="text/css" href="./css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="./css/status.css">
</head>
<body>
	<div id="header">
		<a href=<?php echo "status.php?plant_id=".$_GET["plant_id"];?>><div id="back_to_menu" class="item">
			<i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i>
		</div></a>
		<div id="nick_name" class="item item2">
			<p></p>
		</div>
		<a href="save.php"><div id="flowersettings" class="item">
			<i class="fa fa-check-circle fa-3x" aria-hidden="true"></i>
		</div></a>
	</div>

	<div id="form">
		<form name="settings">
			<table>
				<tr>
					<td>Pflanze favorisieren</td>
					<td><input type="checkbox" name="favorite"></td>
				</tr>
				<tr>
					<td>Nickname ändern</td>
					<td><input type="text" name="name" size="16" maxlength="16" autocomplete="off" width="20" placeholder="Mercy" autofocus></td>
				</tr>
				<tr>
					<td>Art ändern</td>
					<td>
						<select name="scientific_name">
							<option value="s1">Aloe Vera</option>
							<option value="s2">Lumos Maxima</option>
							<option value="s3">Crucio</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Auto-Bewässerung an/aus</td>
					<td><input type="checkbox" name="auto-watering"></td>
				</tr>
				<tr>
					<td>Standort anpassen</td>
					<td>
						<input type="text" name="name" placeholder="Wohnzimmer"><br/>
						Drinnen <input type="radio" name="indoor" value="Drinnen" checked>
						Draußen <input type="radio" name="indoor" value="Draußen">
					</td>
				</tr>
				<tr>
					<td>Notifications einstellen</td>
					<td><input type="checkbox" name="notifications"></td>
				</tr>
				<tr>
					<td>Mit Sensoreinheit verknüpfen</td>
					<td>...</td>
				</tr>
				<tr>
					<td>Pflanze löschen</td>
					<td><input type="button" name="delete" value="Pflanze löschen"></td>
				</tr>
			</table>
		</form>
	</div>
	<script src="js.js"></script>
</body>
</html>