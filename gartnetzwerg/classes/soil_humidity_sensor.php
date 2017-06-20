<?php
require_once 'sensor.php';
class Soil_humidity_sensor extends Sensor{

	
	public function update($ip){
		$path = "/home/pi/gartnetzwerg/sensor_sh.py";
		$cmd = "sudo /var/www/html/gartnetzwerg/update_sensor.sh ".$ip." ".$path;
		$result = shell_exec($cmd);
		
		if($result == 0){
			$return = 10;
		}elseif($result <= 102){
			$return = 9;
		}elseif($result <= 204){
			$return = 8;
		}elseif($result <= 306){
			$return = 7;
		}elseif($result <= 408){
			$return = 6;
		}elseif($result <= 510){
			$return = 5;
		}elseif($result <= 612){
			$return = 4;
		}elseif($result <= 714){
			$return = 3;
		}elseif($result <= 816){
			$return = 2;
		}elseif($result <= 918){
			$return = 1;
		}else{
			$return = 0;
		}
		
		
		$this->set_value($return);
		
	}
}
?>