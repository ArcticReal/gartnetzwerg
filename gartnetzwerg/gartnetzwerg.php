<?php

require_once 'classes/controller.php';

$controller = new Controller();

$picture_array = $controller->get_picture_array(24);

var_dump($picture_array);
$controller->make_time_lapse(24, $picture_array, 10);


?>