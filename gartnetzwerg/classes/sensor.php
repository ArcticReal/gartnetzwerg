<?php

//use PhpGpio\Gpio;

abstract class Sensor{
	
	private $sensor_id;
	private $value;
	//private $gpio = new GPIO();
	
	
	// setter
	
	public function set_sensor_id ($new_sensor_id){
		$this->sensor_id = $new_sensor_id;
	}
	
	public function set_value ($new_value){
		$this->value = $new_value;
	}
	
	
	//getter
		
	public function get_sensor_id(){
		return $this->sensor_id;
	}
	
	public function get_value(){
		return $this->value;
	}
	
	
	//functions
	
	public function update($mac_address){
		//hab jeweils in die Unterklassen die exec reingehaufen, hoffe das passt so
	}
}


?>