<?php
require_once 'sensor.php';
class Light_sensor extends Sensor{

	public function update($mac_address){
		$path = "sudo python3 /home/pi/gartnetzwerg/sensor_l.py";
		$cmd = __DIR__."/../connect.sh ".$mac_address." '".$path."'";
		$this->set_value(shell_exec($cmd));
		
	}
}
?>