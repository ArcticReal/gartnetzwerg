<?php
require_once (__DIR__.'/../config.php');
require_once 'plant.php';

class DB_Handler{
	
	private $mysqli;
	private $plants;
	private $plant_ids;
	private $sensorunits;
	
	
	public function connect_sql(){
		$this->mysqli = mysqli_connect(HOST, USER, PASS, DATABASE);
	}
	
	public function disconnect_sql(){
		mysqli_close($this->mysqli);
	}
	
	public function fetch_plant_ids(){
		
		$query = "SELECT plant_id FROM plants;";
		$result = mysqli_query($this->mysqli, $query);
		$this->plant_ids = mysqli_fetch_array($result);
		
	}
	
	public function fetch_family(){
		
	}
	
	public function fetch_genus(){
		
	}
	
	public function fetch_all_plants(){
		
		$this->fetch_plant_ids();
		
		$plant_ids = $this->plant_ids;
		
		for($i = 0; $i < count($plant_ids); $i++){
			
			$plants[$i] = new Plant();
			$plant = $plants[$i];
			
			$plant->set_plant_id($i);
			
			$species_id = $this->fetch_species_id($plant);
			$plant->set_species_id($species_id);
			
			$scientific_name = $this->fetch_scientific_name($plant);
			$plant->set_scientific_name($scientific_name);
			
			$this->plants[$i] = $plant;
		}
		
	}
	
	public function fetch_species_id($plant){
		
		$query = "SELECT species_id FROM plants WHERE plant_id = ".$plant->get_plant_id().";";
		$result = mysqli_query($this->mysqli, $query);
		$species_id = mysqli_fetch_array($result);
		
		return $species_id[0];
		
	}
	
	public function fetch_name(){
		
	}
	
	public function fetch_nickname(){
		
	}
	
	public function fetch_scientific_name($plant){
		
		$query = "SELECT scientific_name FROM species WHERE species_id = ".$plant->get_species_id().";";
		$result = mysqli_query($this->mysqli, $query);
		$scientific_name = mysqli_fetch_array($result);
		
		return $scientific_name[0];
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
	
	public function put_soil_humidity_top(){
		
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
	
	public function get_plants(){
		return $this->plants;
	}
	
	public function write_log(){
		
	}
	
}

?>