<?php
require_once 'sensor.php';
class Waterlogging_sensor extends Sensor{
	
	public function update($ip){
		$path = "/home/pi/gartnetzwerg/sensor_ws.py";
		$cmd = "sudo /var/www/html/gartnetzwerg/update_sensor.sh ".$ip." ".$path;
		$result = shell_exec($cmd);
				
		if($result == 0){
			$return = 10;
		}elseif($result <= 64){
			$return = 9;
		}elseif($result <= 128){
			$return = 8;
		}elseif($result <= 192){
			$return = 7;
		}elseif($result <= 256){
			$return = 6;
		}elseif($result <= 320){
			$return = 5;
		}elseif($result <= 384){
			$return = 4;
		}elseif($result <= 448){
			$return = 3;
		}elseif($result <= 512){
			$return = 2;
		}elseif($result <= 576){
			$return = 1;
		}else{
			$return = 0;
		}
		
		$this->set_value($return);
		
	}
}
?>

