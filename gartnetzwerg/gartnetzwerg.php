<?php

require_once 'classes/controller.php';

$waterlogging_sensor = new Waterlogging_sensor();

$ip = '192.168.178.40';

$waterlogging_sensor->update($ip);

$erg = $waterlogging_sensor->get_value();

echo "\n";
echo $erg;
echo "\n";

?>