<?php
require_once 'sensor.php';
class Watertank_fillage_sensor extends Sensor{
	private $position;
	
	public function set_position($new_position){
		$this->position = $new_position;
	}
	
	public function get_position(){
		return $this->position;
	}
	
	public function update_value(){
		$this->set_value(random_int(0, 1));
		
	}
	
	public function update(){
		/*TODO: theoretisch hab ich ein python-file für sensor_ws (Wassersensor), 
		        aber ich glaube das ist der zweite Bodenfeuchtigkeitssensor, weil der ne andere Bauart hat, als der obere.
		*/
		//exec("sudo python3 /home/pi/Adafruit_Python_DHT/sensor_ah.py", $rReturn, $err);
		
		//if($rReturn != ""){
		//	$this->value = $rReturn;
		//}
	}
}


?>