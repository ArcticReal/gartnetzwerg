<?php


class Plant{
	
	private $plant_id;
	private $species_id;
	private $family;
	private $genus;
	private $species;
	private $name;
	private $nickname;
	private $scientific_name;
	private $min_light_hours;
	private $max_light_hours;
	private $min_soil_humidity;
	private $max_soil_humidity;
	private $tolerates_waterlogging;
	private $min_temperature;
	private $max_temperature;
	private $min_lux;
	private $max_lux;
	private $min_watering_period;
	private $max_watering_period;
	private $min_fertilizer_period;
	private $max_fertilizer_period;
	private $is_indoor;
	private $location;
	private $birthdate;

	public function set_plant_id($plant_id){
		$this->plant_id = $plant_id;
	}
	
	public function set_species_id($species_id){
		$this->species_id = $species_id;
	}
	
	public function set_family($family){
		$this->family = $family;
	}
	
	public function set_genus($genus){
		$this->genus = $genus;
	}
	
	public function set_species($species){
		$this->species = $species;
	}
	
	public function set_name($name){
		$this->name = $name;
	}
	
	public function set_nichname($nickname){
		$this->nickname = $nickname;
	}
	
	public function set_scientific_name($scientific_name){
		$this->scientific_name = $scientific_name;
	}
	
	public function set_light_hours($min_light_hours, $max_light_hours){
		$this->min_light_hours = $min_light_hours;
		$this->max_light_hours = $max_light_hours;
	}
	
	public function set_soil_humidity($min_soil_humidity, $max_soil_humidity){
		$this->min_soil_humidity = $min_soil_humidity;
		$this->max_soil_humidity = $max_soil_humidity;
	}
	
	public function set_tolerated_waterlogging($tolerated_waterlogging){
		$this->tolerates_waterlogging = $tolerated_waterlogging;
	}
	
	public function set_temperature($min_temperature, $max_temperature){
		$this->min_temperature = $min_temperature;
		$this->max_temperature = $max_temperature;
	}
	
	public function set_lux($min_lux, $max_lux){
		$this->min_lux = $min_lux;
		$this->max_lux = $max_lux;
	}
	
	public function set_watering_period($min_watering_period, $max_watering_period){
		$this->min_watering_period = $min_watering_period;
		$this->max_watering_period = $max_watering_period;
	}
	
	public function set_fertilizer_period($min_fertilizer_period, $max_fertilizer_period){
		$this->min_fertilizer_period = $min_fertilizer_period;
		$this->max_fertilizer_period = $max_fertilizer_period;
	}
	
	public function set_is_indoor($is_indoor){
		$this->is_indoor = $is_indoor;
	}
	
	public function set_location($location){
		$this->location = $location;
	}
	
	public function set_birthdate($birthdate){
		$this->birthdate = $birthdate;
	}
	
	public function get_plant_id(){
		return $this->plant_id;
	}
	
	public function get_species_id(){
		return $this->species_id;
	}
	
	public function get_family(){
		return $this->family;
	}
	
	public function get_genus(){
		return $this->genus;
	}
	
	public function get_species(){
		return $this->species;
	}
	
	public function get_name(){
		return $this->name;
	}
	
	public function get_nickname(){
		return $this->nickname;
	}
	
	public function get_scientific_name(){
		return $this->scientific_name;
	}
	
	public function get_min_light_hours(){
		return $this->min_light_hours;
	}
	
	public function get_max_light_hours(){
		return $this->max_light_hours;
	}
	
	public function get_min_soil_humidity(){
		return $this->min_soil_humidity;
	}
	
	public function get_max_soil_humidity(){
		return $this->max_soil_humidity;
	}
	
	public function tolerates_waterlogging(){
		return $this->tolerates_waterlogging;
	}
	
	public function get_min_temperature(){
		return $this->min_temperature;
	}
	
	public function get_max_temperature(){
		return $this->max_temperature;
	}
	
	public function get_min_lux(){
		return $this->min_lux;
	}
	
	public function get_max_lux(){
		return $this->max_lux;
	}
	
	public function get_min_watering_period(){
		return $this->min_watering_period;
	}
	
	public function get_max_watering_period(){
		return $this->max_watering_period;
	}
	
	public function get_min_fertilizer_period(){
		return $this->min_fertilizer_period;
	}
	
	public function get_max_fertilizer_period(){
		return $this->max_fertilizer_period;
	}
	
	public function is_indoor(){
		return $this->is_indoor;
	}
	
	public function get_location(){
		return $this->location;
	}
	
	public function get_birthdate(){
		return $this->birthdate;
	}
	
}

?>