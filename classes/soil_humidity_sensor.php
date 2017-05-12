<?php
require_once 'sensor.php';
class Soil_humidity_sensor extends Sensor{
	private $position;
	
	public function set_position($new_position){
		$this->position = $new_position;
	}
	
	public function get_position(){
		return $this->position;
	}
	
	public function update(){
		exec("sudo python3 /home/pi/Adafruit_Python_DHT/sensor_sh.py", $rReturn, $err);
		
		if($rReturn != ""){
			$this->value = $rReturn;
		}
	}
}
?>