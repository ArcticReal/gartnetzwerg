<?php

require_once 'classes/controller.php';

$controller = new Controller();
$controller ->init();
$plants = $controller->get_plants();

foreach($plants as $plant){
	echo "\n";
	echo $plant->get_name();
	echo "\n";
	echo $plant->get_nickname();
	echo "\n";
}

/*
$db_handler = new DB_Handler();
$db_handler->connect_sql();

$db_handler->fetch_all_plants();

$plants = $db_handler->get_plants();


for($i = 0; $i < count($plants); $i++){
	
	$plant_ids = $db_handler->get_plant_ids();
	$id = $plant_ids[$i];
	
	echo "\n";
	echo "id: ";
	echo $plants[$id]->get_plant_id();
	echo "\n";
	echo "scientific_name: ";
	echo $plants[$id]->get_scientific_name();
	echo "\n";
	echo "species_id: ";
	echo $plants[$id]->get_species_id();
	echo "\n";
	echo "nickname: ";
	echo $plants[$id]->get_nickname();
	echo "\n";
	echo "max_light_hours: ";
	echo $plants[$id]->get_max_light_hours();
	echo "\n";
	echo "min_light_hours: ";
	echo $plants[$id]->get_min_light_hours();
	echo "\n";
	echo "max_soil_humidity: ";
	echo $plants[$id]->get_max_soil_humidity();
	echo "\n";
	echo "min_soil_humidity: ";
	echo $plants[$id]->get_min_soil_humidity();
	echo "\n";
	echo "waterlogging: ";
	echo $plants[$id]->tolerates_waterlogging();
	echo "\n";
	echo "max_temperature: ";
	echo $plants[$id]->get_max_temperature();
	echo "\n";
	echo "min_temperature: ";
	echo $plants[$id]->get_min_temperature();
	echo "\n";
	echo "max_watering_period: ";
	echo $plants[$id]->get_max_watering_period();
	echo "\n";
	echo "min_watering_period: ";
	echo $plants[$id]->get_min_watering_period();
	echo "\n";
	echo "max_fertilizer_period: ";
	echo $plants[$id]->get_max_fertilizer_period();
	echo "\n";
	echo "min_fertilizer_period: ";
	echo $plants[$id]->get_min_fertilizer_period();
	echo "\n";
	echo "is_indoor: ";
	echo $plants[$id]->is_indoor();
	echo "\n";
	echo "location: ";
	echo $plants[$id]->get_location();
	echo "\n";
	echo "birthday: ";
	echo $plants[$id]->get_birthdate();
	echo "\n";
	
}

$db_handler->disconnect_sql();
*/
?>