<?php

require_once 'classes/controller.php';
require_once 'classes/db_handler.php';

$controller = new Controller();
$plant = new Plant();
$unit = new Sensorunit();

$unit->set_sensor(0, new Air_moisture_sensor());
$unit->get_sensor(0)->set_sensor_id(0);
$unit->get_sensor(0)->set_value(random_int(0,100));

$unit->set_sensor(1, new Air_temperature_sensor());
$unit->get_sensor(1)->set_sensor_id(1);
$unit->get_sensor(1)->set_value(random_int(-20,35));

$unit->set_sensor(2, new Light_sensor());
$unit->get_sensor(2)->set_sensor_id(2);
$unit->get_sensor(2)->set_value(random_int(0,1024));

$unit->set_sensor(3, new Soil_humidity_sensor());
$unit->get_sensor(3)->set_sensor_id(3);
$unit->get_sensor(3)->set_value(random_int(0,100));

$unit->set_sensor(4, new Soil_temperature_sensor());
$unit->get_sensor(4)->set_sensor_id(1);
$unit->get_sensor(4)->set_value(random_int(-20,35));

$unit->set_sensor(5, new Camera());
$unit->get_sensor(5)->set_sensor_id(2);
$unit->get_sensor(5)->set_value(0);

$controller->set_sensorunit($unit, 0);

$controller->set_plant($plant, 0);
$plant->set_air_temperature(-10,10);
$plant->set_soil_temperature(-10,10);
$plant->set_air_humidity(30,50);
$plant->set_soil_humidity(30,50);
$plant->set_light_hours(0, 1000);

$controller->count_lighthours(0);

?>