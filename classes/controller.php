<?php
require_once 'sensorunit.php';
class Controller{
	private $sensorunit_array;
	private $plant_array;
	private $curent_timestamp;		// HH:MM:SS-DD-MM-YYYY
	private $openweathermap_api_key;
	private $default_openweathermap_api_key;
	
	private $value;
	
	public function set_sensorunit($new_sensorunit, $new_sensorunit_id){
		$this->sensorunit_array[$new_sensorunit_id] = $new_sensorunit;
	}
	
	public function set_current_timestamp($new_current_timestamp){
		$this->curent_timestamp = $new_current_timestamp;
	}
	
	public function set_openweathermap_api_key($new_openweathermap_api_key){
		$this->openweathermap_api_key = $new_openweathermap_api_key;
	}
	
	public function set_default_openweathermap_api_key($new_default_openweathermap_api_key){
		$this->default_openweathermap_api_key = $new_default_openweathermap_api_key;
	}
	
	public function get_openweathermap_api_key(){
		return $this->openweathermap_api_key;	
	}
	
	public function get_default_openweathermap_api_key(){
		return $this->default_openweathermap_api_key;
	}
	
	public function open_openweathermap_connection(){
				
	}
	
	public function get_openweathermap_data(){
		
	}
	
	public function close_openweathermap_connection(){
		
	}
	
	public function send_mail(){
		
	}
	
	public function color_state(){
		$this->value = 0;
		//sucht sich werte der sensoren zusammen (aus plant.php?)
		
		//berechnet abweichung (mit den soll-werten über db_handler.php?)
		//a1 = soll1 - akt1;
		//a2 = soll2 - akt2;
		//a3 = soll3 - akt3;
		//a4 = soll4 - akt4;
		//a5 = soll5 - akt5;
		
		//multipliziert sensor-werte mit sensor-gewichtung (die, theoretisch irgendwo in der Pflanze noch gespeichert werden müsste
		//m1 = a1 * g1;
		//m2 = a2 * g2;
		//m3 = a3 * g3;
		//m4 = a4 * g4;
		//m5 = a5* g5;
		
		//addiert mächtigkeiten
		//$this->value = m1 + m2 + m3 + m4 + m5;
		
		if($this->value>= 0.5){
			//return grün
		} else if($this->value>= 1){
			//return gelb
		} else if($this->value>= 2){
			//return orange
		} else if($this->value>= 3){
			//return rot
		} else {
			//return gold
		}
	}
	
/*	public function make_time_laps(){
		
	}
	
	public function calculate_water_level(){
		
	}
	
	public function open_sensorunit_connection(){
		
	}
	
	public function close_sensorunit_connection(){
		
	}
*/
}

?>