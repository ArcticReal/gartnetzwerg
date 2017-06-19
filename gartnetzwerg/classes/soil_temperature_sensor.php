<?php
require_once 'sensor.php';
class Soil_temperature_sensor extends Sensor{
	
	public function update($ip){
		$path = "/home/pi/gartnetzwerg/sensor_st.py";
		$cmd = "ssh -i /home/pi/.ssh/id_rsa pi@".$ip." -t ".$path;
		$this->set_value(shell_exec($cmd));
		
	}
}
?>