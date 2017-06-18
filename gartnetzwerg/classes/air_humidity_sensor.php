<?php
require_once 'sensor.php';
class Air_humidity_sensor extends Sensor{
	
	public function update($ip){
		$path = "/home/pi/gartnetzwerg/sensor_ah.py";
		$cmd = "ssh -i /home/pi/.ssh/id_rsa pi@".$ip." -t ".$path;
		$this->set_value(shell_exec($cmd));
		
	}
}

?>