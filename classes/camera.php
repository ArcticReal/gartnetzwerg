<?php
require_once 'sensor.php';
class Camera extends Sensor{
	public function update(){
		/*TODO: (wurde aber noch nicht auf'm Zero getestet)
		
		if($rReturn != ""){
			$this->value = $rReturn;
		}*/
	}
	
	public function take_pic(){
		exec("sudo python3 /home/pi/Adafruit_Python_DHT/sensor_cam.py", $rReturn, $err);
	}
	
	public function set_cam(){
		exec("sudo python3 /home/pi/Adafruit_Python_DHT/sensor_set_cam.py", $rReturn, $err);
	}
}

?>