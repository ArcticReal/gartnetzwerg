<?php

require_once 'classes/controller.php';

$controller = new Controller();

$picture_array = $controller->get_picture_array(6);

var_dump($picture_array);

?>