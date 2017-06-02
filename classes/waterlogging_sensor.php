<?php
require_once 'sensor.php';
class Waterlogging_sensor extends Sensor{
	
	public function update(){
		exec("sudo python3 /home/pi/Adafruit_Python_DHT/sensor_ws.py", $rReturn, $err);
		
		if($rReturn != ""){
			$this->value = $rReturn;
		}
	}
}
?>

