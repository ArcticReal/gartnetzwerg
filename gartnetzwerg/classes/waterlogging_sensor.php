<?php
require_once 'sensor.php';
class Waterlogging_sensor extends Sensor{
	
	public function update($ip){
		$path = "/home/pi/gartnetzwerg/sensor_ws.py";
		$cmd = "ssh -i /home/pi/.ssh/id_rsa pi@".$ip." -t ".$path;
		$this->set_value(shell_exec($cmd));
		
	}
}
?>

