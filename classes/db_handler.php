<?php
require_once '../config.php';

class DB_Handler{
	
	private $mysqli;
	private $plant;
	private $sensorunit;
	
	
	public function connect_sql(){
		$this->mysqli = mysqli_connect(HOST,USER,PASS,DATABASE);
	}
	
	public function disconnect_sql(){
		mysqli_close($this->mysqli);
	}
	
	public function fetch_family(){
		
	}
	
	public function fetch_genus(){
		
	}
	
	public function fetch_species(){
		
	}
	
	public function fetch_name(){
		
	}
	
	public function fetch_nickname(){
		
	}
	
	public function fetch_scientific_name(){
		
	}
	
	public function fetch_light_hours(){
		
	}
	
	public function fetch_soil_humidity(){
		
	}
	
	public function fetch_tolerated_waterlogging(){
		
	}
	
	public function fetch_temperature(){
		
	}
	
	public function fetch_lux(){
		
	}
	
	public function fetch_watering_period(){
		
	}
	
	public function fetch_fertilizer_period(){
		
	}
	
	public function fetch_indoor(){
		
	}
	
	public function fetch_location(){
		
	}
	
	public function fetch_birthdate(){
		
	}
	
	public function init(){
		
	}
	
	public function put_soil_huidity_top(){
		
	}
	
	public function put_soil_humidity_bottom(){
		
	}
	
	public function put_air_moisture(){
		
	}
	
	public function put_lux(){
		
	}
	
	public function put_temperature(){
		
	}
	
	public function put_all(){
		
	}
	
}

?>