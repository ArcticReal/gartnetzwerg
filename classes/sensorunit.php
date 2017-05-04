<?php
require_once 'air_moisture_sensor.php';
require_once 'temperature_sensor.php';
require_once 'watertank_fillage_sensor.php';
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
		echo("update Sensor ".$sensor_id."\n");
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
		echo "calculating watertank fillage\n\n";
		$watertank_sensors = array();
		$fillage_level = 0;
		$max_fillage_level = 0;
		foreach ($this->sensor_array as $key => $value){
			echo "Testing sensor with the id ".$key.":\n";
			if (is_a($value, "Watertank_fillage_sensor")){
				echo "\tYeah a watertank fillage sensor\n\twatertank fillage sensor is meassuring the "
						.$value->get_position()." level of the watertank\n";
				$watertank_sensors[$value->get_position()] = $value->get_value();
				
			}else {
				echo "\tohh nah! its another sensor\n\n";
			}
		}
		echo "\nlet me summarize that for you:\nWe have ".sizeof($watertank_sensors)
				." watertank sensors\n";
		foreach ($watertank_sensors as $key => $value){
			echo "Sensor ".$key." says: ".$value."\n";
			$fillage_level += $value;
			$max_fillage_level ++;
		}
		echo "wich means that ".$fillage_level." out of ".$max_fillage_level." sensors sense water\n"
				."so our watertank is ".$fillage_level/$max_fillage_level." full\n";
	}
	
}
$test = new Sensorunit();
$test->set_sensor(0, new Air_moisture_sensor());
$test->get_sensor(0)->set_sensor_id(0);
$test->set_sensor(1, new Temperature_sensor());
$test->get_sensor(1)->set_sensor_id(1);
$test->set_sensor(2, new Watertank_fillage_sensor());
$test->get_sensor(2)->set_sensor_id(2);
$test->get_sensor(2)->set_position("top");
$test->set_sensor(3, new Watertank_fillage_sensor());
$test->get_sensor(3)->set_sensor_id(3);
$test->get_sensor(3)->set_position("bottom");
$test->set_sensor(4, new Watertank_fillage_sensor());
$test->get_sensor(4)->set_sensor_id(4);
$test->get_sensor(4)->set_position("middle");
$test->update_all();
$test->make_time_lapse();
$test->calculate_watertank_level();

?>