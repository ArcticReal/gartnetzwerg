<?php
require_once 'sensor.php';
class Air_temperature_sensor extends Sensor{

	public function update($mac_address){
	
		$path = "sudo python3 /home/pi/gartnetzwerg/sensor_at.py";
		$cmd = __DIR__."/../connect.sh ".$mac_address." '".$path."'";
		$this->set_value(shell_exec($cmd));
		
	}
	
}
?>