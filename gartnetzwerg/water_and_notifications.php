<?php
/*
 * this script is only run by cron
 * 
 */

require_once 'classes/controller.php';
$controller = new Controller();
$controller->check_for_watering();
foreach ($controller->get_plants() as $plant_id => $plant){
	
	$controller->daily_notification($plant_id);
}


?>