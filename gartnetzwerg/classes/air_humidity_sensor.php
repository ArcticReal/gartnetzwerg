<?php
require_once 'sensor.php';
class Air_humidity_sensor extends Sensor{
	public function update($mac_addres){
		exec("sudo python3 /home/pi/Adafruit_Python_DHT/sensor_ah.py", $rReturn, $err);
		$cmd = "/var/www/html/gartnetzwerg/sensor_ah.py -".$mac_addres;
		shell_exec($cmd);
		
		if($rReturn != ""){
			$this->value = $rReturn;
		}
	}
}
?>