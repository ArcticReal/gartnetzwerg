<?php
require_once 'air_moisture_sensor.php';
require_once 'temperature_sensor.php';
require_once 'GifCreator.php';

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
		echo("Aktualisiere Sensor ".$sensor_id."\n");
		$this->sensor_array[$sensor_id]->update();
		
		
	}
	
	public function update_all(){
		foreach ($this->sensor_array as $key => $value){
			$this->update_sensor($key);
			echo ("Key: ".$key." Value: ".$value->get_value()."\n\n");
		}
			
	}

	public function make_time_lapse(){
		//links u den bilder im internet
		$frames = array("http://www.sarracenia.com/photos/dionaea/dionamusci070.jpg",
				"http://www.flowers.org.uk/wp-content/uploads/2012/12/Pitcher-Plant.jpg",
				"http://i1110.photobucket.com/albums/h443/meizzwang/IMG_6847.jpg");
		//geht aber auch mit lokalen pfaden
		$frames_local = array('plants/plant01.jpg',
				'plants/plant02.jpg',
				'plants/plant03.jpg');
		//wie lang jedes bild angezeigt wird
		$durations = array(50, 50, 50);
		
		try{			
			$gc = new GifCreator\GifCreator();
			$gc->create($frames_local, $durations, 0);
			$gif_binary = $gc->getGif();
			file_put_contents('animated_picture.gif', $gif_binary); //speichert gif lokal ab
		}
		catch (\Exception $ex){
			echo $ex->getMessage()."\n";
		}
		
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
$test->make_time_lapse();

?>