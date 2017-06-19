<?php
require_once 'sensor.php';
class Light_sensor extends Sensor{

	public function update($ip){
		$path = "/home/pi/gartnetzwerg/sensor_l.py";
		$cmd = "ssh -i /home/pi/.ssh/id_rsa pi@".$ip." -t ".$path;
		$this->set_value(shell_exec($cmd));
		
	}
}
?>