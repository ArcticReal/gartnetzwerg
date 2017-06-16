<?php
require_once 'sensor.php';
class Watertank_fillage_sensor extends Sensor{
	private $position;
	
	public function set_position($new_position){
		$this->position = $new_position;
	}
	
	public function get_position(){
		return $this->position;
	}
	
	public function update_value(){
		$this->set_value(random_int(0, 1));
		
	}
	
	public function update($mac_address){
		$path = "sudo python3 /home/pi/gartnetzwerg/sensor_wf.py ".($this->position+3);
		$cmd = __DIR__."/../connect.sh ".$mac_address." '".$path."'";
		echo $test = shell_exec($cmd);
		//$this->set_value(shell_exec($cmd));
		
	}
}


?>