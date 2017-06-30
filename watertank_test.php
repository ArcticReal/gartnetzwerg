<?php

require_once 'gartnetzwerg/classes/watertank_fillage_sensor.php';

$waterf1 = new Watertank_fillage_sensor();
$waterf2 = new Watertank_fillage_sensor();
$waterf3 = new Watertank_fillage_sensor();

$waterf1->set_position(1);
$waterf2->set_position(2);
$waterf3->set_position(3);

$waterf1->update("192.168.178.22");
$waterf2->update("192.168.178.22");
$waterf3->update("192.168.178.22");

var_dump($waterf1->get_value());
var_dump($waterf2->get_value());
var_dump($waterf3->get_value());

?>