<!DOCTYPE HTML>

<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Introducing Lollipop, a sweet new take on Android.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>Android</title>

    <!-- Page styles --> 
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
		<link rel="stylesheet" href="./font-awesome/css/font-awesome.css">
		
	
    <style>
    #view-source {
      position: fixed;
      display: block;
      right: 0;
      bottom: 0;
      margin-right: 40px;
      margin-bottom: 40px;
      z-index: 900;
    }
    </style>
  </head>
  <body>
	
	
	<div class="containerLogo">
		
		<div class="logo">
			<img src="images/Logo2.png"/>
			<img src="images/LogoSchrift.png"/>
		</div>
		
		
	</div>

	<div class="containerFlowers">
		<div class="containerPlaceholder"></div>  
		



<?php
		

		
		require_once 'gartnetzwerg/classes/controller.php'; 
		
		$controller = new Controller();
		$controller->init();
		
		$plants = $controller->get_plants();		

		foreach($plants as $plant){
			
			$scientific_name = $plant->get_scientific_name();
			$name = $plant->get_name();
			$plant_id = $plant->get_plant_id();
			
			print "<a href=\"status.php?plant_id=".$plant_id."\">";
			print "	<div class=\"containerFlower flower\">";
			print "		<span><div class=\"flowerName\">".$name." (".$scientific_name.")</div></span>";
			print "	</div>";
			print "</a>";

		}

	
?>
		

	<!--


			print "<a href='status.php'>"
			 ."<div class='containerFlower flower'>"
			 ."<span><div class='flowerName'>Geranie (Wohnzimmer)</div></span>"
			 ."</div>"
			 ."</a>";


			<a href="status.php">
				<div class="containerFlower flower">
					
					<span><div class="flowerName">Geranie (Wohnzimmer)</div></span>
				
				</div>
			</a>
			<a href="diagramm.php">
				<div class="containerFlower flower">
					
					<span><div class="flowerName">Aloe Vera (Rosali)</div></span>
					
				
				</div>
			</a>
			<a href="diagramm2.php">
				<div class="containerFlower flower">
					
					<span><div class="flowerName">Geranie (Liselotte)</div></span>
					
				
				</div>
			</a>
			
			<a href="diagramm3.php">
				<div class="containerFlower flower">
					
					<span><div class="flowerName">Orchidee (Hubert)</div></span>
					
				
				</div>
			</a>
-->
		

		
	<div class="containerSpaceholderBottom"></div> 
	</div>
	  
	<div class="containerBottom" >
		<span><div class="boxSymbol">
			<a href="infos.php"><i class="fa fa-info-circle fa-3x" aria-hidden="true"></i></a>

		</div></span>
		<span><div class="boxSymbol">
		<a href="infos.php"><i class="fa fa-plus-circle fa-3x" aria-hidden="true"></i></a>

		</div></span>
		<span><div class="boxSymbol">
		<a href="infos.php"><i class="fa fa-plane fa-3x" aria-hidden="true"></i></a>

		</div></span>
		
	</div>

	<!--<a href="https://github.com/google/material-design-lite/blob/mdl-1.x/templates/android-dot-com/" target="_blank" id="view-source" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--accent mdl-color-text--accent-contrast">View Source</a>-->
    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
  </body>
 </html>   
