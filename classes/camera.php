<?php
require_once 'sensor.php';
class Camera extends Sensor{
	public function update(){
		/*TODO: gibt's nocht nich, weiß auch noch nich wie das mit'm Abspeichern, etc is.
		  Is bisher auf Eis gelegt, weil die Cam glaub net für den Pilotbetrieb benötigt wird
		*/
		//exec("sudo python3 /home/pi/Adafruit_Python_DHT/sensor_cam.py", $rReturn, $err);
		
		//if($rReturn != ""){
		//	$this->value = $rReturn;
		//}
	}
}

?>