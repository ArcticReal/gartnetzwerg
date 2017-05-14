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
		//hab jeweils in die Unterklassen die exec reingehaufen, hoffe das passt so
	}
}


?>