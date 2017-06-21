<!DOCTYPE html>
<html lang="de">
<head>
	<title>GartNetzwerg</title>

	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="refresh" content="1800" >
    <!--3600-->

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
		function smiley(i){
			if(i == 0){
				document.getElementById("empty_flower_list").innerHTML = "<span><i class='fa fa-3x fa-meh-o'></i><p id='trigger'>Hier ist es ganz schön leer.<br/><small><i>Erste Pflanze einfügen</i></small></p></span>";
			} else {
				document.getElementById("empty_flower_list").innerHTML = "<span><i class='fa fa-3x fa-smile-o'></i><p id='trigger'>Hier ist es ganz schön leer.<br/><small><i>Erste Pflanze einfügen</i></small></p></span>";
			}
		}
	</script>

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

			function test_input($data){
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$v_delete = test_input($_REQUEST['del_plant']);
				
				if($v_delete >= 0){
					$controller->delete_plant($v_delete);
				}	
			}

			$plants = $controller->get_plants();

			if(count($plants)>0){
				foreach($plants as $plant){
					$scientific_name = $plant->get_scientific_name();
					$nickname = $plant->get_nickname();
					$name = $plant->get_name();
					$plant_id = $plant->get_plant_id();
					$location = $plant->get_location();

					$color = $controller->color_state($plant_id,0.5,0.5,0.5,0.5,0.5);
					
					print "<a href='status.php?plant_id=$plant_id'><div class='flower $color'><span><p>$nickname<br/><small>$name ($scientific_name)<br/>Standort: $location</small></p></span></div></a>";
				}
			} else {
				print "<a onmouseover='smiley(1)' onmouseout='smiley(0)' href='new_plant.php'><div id='empty_flower_list'><span><i class='fa fa-3x fa-meh-o'></i><p id='trigger'>Hier ist es ganz schön leer.<br/><small><i>Erste Pflanze einfügen</i></small></p></span></div></a>";
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
			<a href="vacation.php"><i class=<?php if(random_int(1, 1000)==999){echo "'fa fa-rocket fa-3x'";} else {echo "'fa fa-plane fa-3x'";} ?> aria-hidden="true"></i></a>
		</div>
		<div id="settings" class="button">
			<a href="settings.php"><i class="fa fa-cog fa-3x" aria-hidden="true"></i></a>
		</div>
	</div>

	<script src="js.js"></script>
</body>
</html>