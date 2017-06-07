<!DOCTYPE html>
<html>
<head>
	<title>GartNetzwerg</title>

	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <link rel="stylesheet" type="text/css" href="./css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="./css/main.css">
</head>
<body>
	<div id="header">
		<div id="helper">
			<div id="logo"></div>
			<div id="font"></div>
		</div>
	</div>

	<div id="list">
		<?php
			require_once 'gartnetzwerg/classes/controller.php'; 
			
			$controller = new Controller();
			$controller->init();
			$plants = $controller->get_plants();		

			foreach($plants as $plant){
				$scientific_name = $plant->get_scientific_name();
				$nickname = $plant->get_nickname();
				$name = $plant->get_name();
				$plant_id = $plant->get_plant_id();

				$colors = ["green","yellow","orange","red","black"];
				//$controller->color_state(1,1,1,1,1);
				
				print "<a href=\"status.php?plant_id=".$plant_id."\"><div class=\"flower ".$colors[random_int(0,4)]."\"><span><p>".$nickname."<br/><small>".$name." (".$scientific_name.")</small></p></span></div></a>";
			}
		?>
	</div>
	
	<div id="footer">
		<div id="info" class="button">
			<a href="infos.php"><i class="fa fa-info-circle fa-3x" aria-hidden="true"></i></a>
		</div>
		<div id="new" class="button">
			<a href="new_plant.php"><i class="fa fa-plus-circle fa-3x" aria-hidden="true"></i></a>
		</div>
		<div id="vacation" class="button">
			<a href="vacation.php"><i class="fa fa-plane fa-3x" aria-hidden="true"></i></a>
		</div>
		<div id="settings" class="button">
			<a href="settings.php"><i class="fa fa-cog fa-3x" aria-hidden="true"></i></a>
		</div>
	</div>

	<script src="js.js"></script>
</body>
</html>