<?php
require_once 'air_humidity_sensor.php';
require_once 'air_temperature_sensor.php';
require_once 'soil_temperature_sensor.php';
require_once 'light_sensor.php';
require_once 'soil_humidity_sensor.php';
require_once 'watertank_fillage_sensor.php';
require_once 'waterlogging_sensor.php';

require_once 'GifCreator.php';

require_once 'camera.php';

class Sensorunit{
		
	private $sensor_array;
	private $watertank_level;
	private $sensor_ids;
	private $name;
	private $mac_address;
	private $status;
	
	//setters
	
	public function set_array($new_array){
		$this->sensor_array = $new_array;
	}

	public function set_sensor($sensor_id, $new_sensor){
		$this->sensor_array[$sensor_id] = $new_sensor;
		$this->sensor_array[$sensor_id]->set_sensor_id($sensor_id);
	}

	public function set_watertank_level($new_watertank_level){
		$this->watertank_level = $new_watertank_level;
	}
	
	public function set_sensor_ids($sensor_ids){
		$this->sensor_ids = $sensor_ids;
	}
	
	public function set_name($new_name){
		$this->name = $new_name;
	}
	
	public function set_mac_address($new_mac_address){
		$this->mac_address = $new_mac_address;
	}
	
	public function set_status($new_status){
		$this->status = $new_status;
	}
	
	//getters
	
	public function get_array(){
		return $this->sensor_array;
	}

	public function get_sensor($sensor_id){
		return $this->sensor_array[$sensor_id];
	}
	
	public function get_watertank_level(){
		return $this->watertank_level;
	}
	
	public function get_sensor_ids(){
		return $this->sensor_ids;
	}
		
	public function get_name(){
		return $this->name;
	}
	
	public function get_mac_address(){
		return $this->mac_address;
	}
	
	public function get_status(){
		return $this->status;
	}
	
	//functions
	
	public function update_sensor($sensor_id, $ip){
		//echo("update Sensor ".$sensor_id."\n");
		$this->sensor_array[$sensor_id]->update($ip);
	}

	public function update_all(){
		
		$mac_address = $this->get_mac_address();
		$cmd = "sudo /var/www/html/gartnetzwerg/get_ip_address.sh ".$mac_address;
		$ip = shell_exec($cmd);
		
		foreach ($this->sensor_array as $key => $value){
			$this->update_sensor($key, $ip);
			//echo ("Key: ".$key." Value: ".$value->get_value()."\n\n");
		}	
		$this->calculate_watertank_level();
	}

	/**
	 * 
	 * @param unknown $frames an array with the pictures
	 * @param $duration this sets how long a picure will be shown in a time lapse
	 */
	public function make_time_lapse($frames, $duration){
		//links zu den bilder im internet
		/*$frames = array("http://www.sarracenia.com/photos/dionaea/dionamusci070.jpg",
				"http://www.flowers.org.uk/wp-content/uploads/2012/12/Pitcher-Plant.jpg",
				"http://i1110.photobucket.com/albums/h443/meizzwang/IMG_6847.jpg");
		//geht aber auch mit lokalen pfaden
		$frames_local = array('plants/plant01.jpg',
				'plants/plant02.jpg',
				'plants/plant03.jpg');
		*/
		//wie lang jedes bild angezeigt wird
		$durations = [];
		foreach ($frames as $frame){
			$durations[] = $duration;
			
		}
		
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
	
	
	/**
	 * 
	 */
	public function calculate_watertank_level(){
		$watertank_sensor_data = [];

		$fillage_level = 0;
		$max_fillage_level = 0;
		foreach ($this->sensor_array as $key => $value){
			if (is_a($value, "Watertank_fillage_sensor")){
				$watertank_sensor_data[$value->get_position()] = $value->get_value();
			}
			foreach ($watertank_sensor_data as $value){
				$fillage_level += $value;
				$max_fillage_level ++;
			}		
			if ($max_fillage_level != 0){
				
				$this->set_watertank_level($fillage_level/$max_fillage_level);
			}else {
				$this->set_watertank_level(NAN);
			}
		}
	}
}
/*$test = new Sensorunit();
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
*/

?>