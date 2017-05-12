<?php
require_once 'sensor.php';
class Air_moisture_sensor extends Sensor{
	public function update(){
		exec("sudo python3 /home/pi/Adafruit_Python_DHT/sensor_ah.py", $rReturn, $err);
		
		if($rReturn != ""){
			$this->value = $rReturn;
		}
	}
}
?>