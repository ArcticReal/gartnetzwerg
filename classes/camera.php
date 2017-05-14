<?php
require_once 'sensor.php';
class Camera extends Sensor{
	public function update(){
		/*TODO: läuft theoretisch so, aber was für nen Value ergibt das dann?
				(wurde aber noch nicht auf'm Zero getestet)
		*/
		exec("sudo python3 /home/pi/Adafruit_Python_DHT/sensor_cam.py", $rReturn, $err);
		
		if($rReturn != ""){
			$this->value = $rReturn;
		}
	}
}

?>