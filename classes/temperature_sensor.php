<?php
require_once 'sensor.php';
class Temperature_sensor extends Sensor{
	public function update(){
		/*TODO: Unterscheidung zwischen _at (Lufttemperatursensor) und _st (Bodentemperatursensor)
				Muss da noch eine weitere Sensor-Klasse her? ich glaube schon; mach ich vielleicht nachher mal
		*/
		exec("sudo python3 /home/pi/Adafruit_Python_DHT/sensor_at.py", $rReturn, $err);
		
		if($rReturn != ""){
			$this->value = $rReturn;
		}
	}
}
?>