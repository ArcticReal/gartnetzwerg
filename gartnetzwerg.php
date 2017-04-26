<?php

require_once 'classes/db_handler.php';


$db_handler = new DB_Handler();
$db_handler->connect_sql();

$db_handler->fetch_all_plants();

$plants = $db_handler->get_plants();


for($i = 0; $i < count($plants); $i++){
	
	echo "\n\n";
	echo "id: ";
	echo $plants[$i]->get_plant_id();
	echo "\n";
	echo "scientific_name: ";
	echo $plants[$i]->get_scientific_name();
	echo "\n";
	echo "species_id: ";
	echo $plants[$i]->get_species_id();
	
	
}

$db_handler->disconnect_sql();

?>