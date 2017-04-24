<?php
require_once 'sensorunit.php';
class Controller{
	private $sensorunit_array;
	private $plant_array;
	private $curent_timestamp;		// HH:MM:SS-DD-MM-YYYY
	private $openweathermap_api_key;
	private $default_openweathermap_api_key;
	
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