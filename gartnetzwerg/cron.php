<?php
/*
 * this skript is called by cron only
 * 
 */

require_once 'classes/controller.php';
$controller = new Controller();
$controller->init();
$controller->update_sensor_data(0);


?>