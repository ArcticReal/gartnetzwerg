<?php
require_once 'sensor.php';
class Soil_temperature_sensor extends Sensor{
	
	public function update($ip){
		$path = "/home/pi/gartnetzwerg/sensor_st.py";
		$cmd = "sudo /var/www/html/gartnetzwerg/update_sensor.sh ".$ip." ".$path;
		$result = shell_exec($cmd);
		$this->set_value($result);
		
	}
}
?>