<?php
require_once 'sensor.php';
class Soil_temperature_sensor extends Sensor{
	
	public function update($mac_addres){
		exec("sudo python3 /home/pi/Adafruit_Python_DHT/sensor_st.py", $rReturn, $err);
		
		if($rReturn != ""){
			$this->value = $rReturn;
		}
	}
}
?>