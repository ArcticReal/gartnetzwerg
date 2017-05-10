<?php

//use PhpGpio\Gpio;

abstract class Sensor{
	
	private $sensor_id;
	private $value;
	private $gpio_pin_id;
	//private $gpio = new GPIO();
	
	public function set_sensor_id ($new_sensor_id){
		$this->sensor_id = $new_sensor_id;
	}
	
	public function set_value ($new_value){
		$this->value = $new_value;
	}
	
	public function set_gpio_pin_id($new_gpio_pin_id){
		$this->gpio_pin_id = $new_gpio_pin_id;
	}
	
	public function get_sensor_id(){
		return $this->sensor_id;
	}
	
	public function get_value(){
		return $this->value;
	}
	
	public function get_gpio_pin_id(){
		return $this->gpio_pin_id;
	}
	
	/*public function update_value(){
		$this->set_value(random_int(0,255));
	}*/
	
	public function update(){
		//$this->update_value();
		//TODO: Ich geh mal davon aus, dass die SensorID ne nummer is, und dass die Nummer immer die gleiche für den jeweiligen Sensor is.
		exec("sudo python3 /home/pi/Adafruit_Python_DHT/sensor_".$this->get_sensor_id().".py", $rReturn, $err);
		
		//foreach($rReturn as $key => $val){
			//echo $val."<br/>";
			if($val != ""){
				$this->value = $val;
			}
		//}
	}
}


?>