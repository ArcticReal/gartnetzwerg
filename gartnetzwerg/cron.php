<?php
/*
 * this skript is called by cron only
 * 
 */

require_once 'classes/controller.php';
$controller = new Controller();
//$controller->update_all_sensor_data(0);
$controller->water(20);


?>