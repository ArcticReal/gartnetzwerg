<?php
require_once 'sensor.php';
class Air_humidity_sensor extends Sensor{
	
	public function update($ip){
		$path = "/home/pi/gartnetzwerg/sensor_ah.py";
		$cmd = "sudo /var/www/html/gartnetzwerg/update_sensor.sh ".$ip." ".$path;
		$result = shell_exec($cmd);
		$this->set_value($result);
		
	}
}

?>