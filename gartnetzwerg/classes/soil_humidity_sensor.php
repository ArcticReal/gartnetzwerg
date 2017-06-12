<?php
require_once 'sensor.php';
class Soil_humidity_sensor extends Sensor{

	
	
	public function update($mac_addres){
		exec("sudo python3 /home/pi/Adafruit_Python_DHT/sensor_sh.py", $rReturn, $err);
		
		if($rReturn != ""){
			$this->value = $rReturn;
		}
	}
}
?>