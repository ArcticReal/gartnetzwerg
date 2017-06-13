<?php
require_once 'sensor.php';
class Air_humidity_sensor extends Sensor{
	public function update($mac_address){
		$path = "sudo python3 /home/pi/Adafruit_Python_DHT/sensor_ah.py";
		$path = "sudo php -version";
		$cmd = "/var/www/html/gartnetzwerg/connect.sh ".$mac_address." '".$path."'";
		echo shell_exec($cmd)."\n";
		
		/*if($rReturn != ""){
			//$this->value = $rReturn;
		}*/
	}
}
//$test = new Air_humidity_sensor();
//$test->update("B8:27:EB:BD:F1:A7");
?>