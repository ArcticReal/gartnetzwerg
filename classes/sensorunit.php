<?php
require_once 'air_moisture_sensor.php';
require_once 'temperature_sensor.php';
class Sensorunit{
		
	private $sensor_array;
	private $watertank_level;
	
	public function set_array($new_array){
		$this->sensor_array = $new_array;
	}

	public function set_sensor($sensor_id, $new_sensor){
		$this->sensor_array[$sensor_id] = $new_sensor;
	}

	public function set_watertank_level($new_watertank_level){
		$this->watertank_level = $new_watertank_level;
	}
	
	public function get_array(){
		return $this->sensor_array;
	}

	public function get_sensor($sensor_id){
		return $this->sensor_array[$sensor_id];
	}
	
	public function get_watertank_level(){
		return $this->watertank_level;
	}
	
	public function update_sensor($sensor_id){
		$this->sensor_array[$sensor_id]->update();
		echo("Sensor ".$sensor_id." aktualisiert\n\n");
		
	}
	
	public function update_all(){
		foreach ($this->sensor_array as $key => $value){
			$this->update_sensor($key);
			echo ("Key: ".$key." Value: ".$value->get_value()."\n");
		}
			
	}

	public function make_time_lapse(){
		
	}
	
	public function calculate_watertank_level(){
		
	}
	
}
$test = new Sensorunit();
$test->set_sensor(0, new Air_moisture_sensor());
$test->set_sensor(1, new Temperature_sensor());
$test->get_sensor(0)->set_sensor_id(0);
$test->get_sensor(1)->set_sensor_id(1);
$test->update_all();

?>