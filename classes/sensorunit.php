<?php
require_once 'sensor.php';
class Sensorunit{
		
	private $sensor_array;
	
	public function set_array($new_array){
		$this->sensor_array = $new_array;
	}

	public function set_sensor($sensor_id, $new_sensor){
		$this->sensor_array[$sensor_id] = $new_sensor;
	}

	public function get_array(){
		return $this->sensor_array;
	}

	public function get_sensor($sensor_id){
		return $this->sensor_array[$sensor_id];
	}
	
	public function update_sensor($sensor_id){
		
		echo("sensor ".$sensor_id." aktualisiert\n\n");
		//TODO: Sensordaten aktualisieren
	}
	
	public function update_all(){
		foreach ($this->sensor_array as $key => $value){
			$this->update_sensor($key);
			echo ("Key: ".$key." Value:".$value."\n");
		}
			
	}

}
/**$test = new Sensorunit();
$test->set_sensor(0, 'dat Sensor');
$test->set_sensor(1, 'Sensor2');
$test->update_all();
*/
?>