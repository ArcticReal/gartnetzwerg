<?php
require_once 'sensor.php';
class soil_temperature_sensor extends Sensor{
	public function update(){
		exec("sudo python3 /home/pi/Adafruit_Python_DHT/sensor_st.py", $rReturn, $err);
		
		if($rReturn != ""){
			$this->value = $rReturn;
		}
	}
}
?>