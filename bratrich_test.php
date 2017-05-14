<?php

require_once 'classes/sensorunit.php';

$test = new Sensorunit();

$test->set_sensor(0, new Air_moisture_sensor());
$test->get_sensor(0)->set_sensor_id(0);

$test->set_sensor(1, new Air_temperature_sensor());
$test->get_sensor(1)->set_sensor_id(1);

$test->set_sensor(2, new Light_sensor());
$test->get_sensor(2)->set_sensor_id(2);

$test->set_sensor(3, new Soil_humidity_sensor());
$test->get_sensor(3)->set_sensor_id(3);

$test->set_sensor(4, new Soil_temperature_sensor());
$test->get_sensor(4)->set_sensor_id(1);

$test->set_sensor(5, new Camera());
$test->get_sensor(5)->set_sensor_id(2);

$test->update_all();

?>