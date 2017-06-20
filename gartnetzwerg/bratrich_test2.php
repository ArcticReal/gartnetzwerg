<?php

require_once 'classes/controller.php'; 
$controller = new Controller();
echo "\n";
$color =  $controller->correction_text(10);
echo "\n".$color."\n\n";
?>