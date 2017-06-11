<!DOCTYPE html>
<html>
<head>
	<title>Infos — GartNetzwerg</title>

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

	<div id="list" class="small">
		<p>
			Wenn Sie den Urlaubsmodus aktivieren, werden alle Pflanzen (die eine automatische Bewässerung besitzen) automatisch bewässert.
		</p>
		
		<div id="urlaub">
			<input type="text" name="start_date"><br/>

			<input type="text" name="end_date"><br/>

			<?php
				print("<input type='button' name='vacation' value='Urlaubsmodus aktivieren'>");
			?>
		</div>
		
		<p>
			Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
		</p>
	</div>
	
	<div id="footer">
		<div id="back_to_main" class="button">
			<a href="index.php"><i class="fa fa-arrow-circle-left fa-3x" aria-hidden="true"></i></a>
		</div>
	</div>

	<script src="js.js"></script>
</body>
</html>