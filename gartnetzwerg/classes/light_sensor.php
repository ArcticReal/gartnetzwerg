<?php
require_once 'sensor.php';
class Light_sensor extends Sensor{
	public function update($mac_addres){
		exec("sudo python3 /home/pi/Adafruit_Python_DHT/sensor_l.py", $rReturn, $err);
		
		if($rReturn != ""){
			$this->value = $rReturn;
		}
	}
}
?>