<?php


class Plant{
	
	private $plant_id;
	private $species_id;
	private $species;
	private $name;
	private $nickname;
	private $scientific_name;
	private $min_light_hours;
	private $max_light_hours;
	private $min_air_humidity;
	private $max_air_humidity;
	private $min_soil_humidity;
	private $max_soil_humidity;
	private $tolerates_waterlogging;
	private $min_air_temperature;
	private $max_air_temperature;
	private $min_soil_temperature;
	private $max_soil_temperature;
	private $min_watering_period;
	private $max_watering_period;
	private $min_fertilizer_period;
	private $max_fertilizer_period;
	private $is_indoor;
	private $location;
	private $birthdate;
	private $akt_light_hours;
	private $akt_air_humidity;
	private $akt_soil_humidity;
	private $akt_waterlogging;
	private $akt_air_temperature;
	private $akt_soil_temperature;
	private $auto_watering;
	private $last_watering;
	private $sensor_unit_id;
	private $winter_prep;
	private $how_to_water;
	private $caretaking_hints;
	private $special_needs;
	private $transplanting;
	private $needed_location;
	private $fertilizing_hints;
	private $summer_prep;
	private $notification_settings;

	
	//setters
	
	public function set_plant_id($plant_id){
		$this->plant_id = $plant_id;
	}
	
	public function set_species_id($species_id){
		$this->species_id = $species_id;
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
	
	public function set_air_humidity($min_air_humidity, $max_air_humidity){
		$this->min_air_humidity = $min_air_humidity;
		$this->max_air_humidity = $max_air_humidity;
	}
	
	public function set_soil_humidity($min_soil_humidity, $max_soil_humidity){
		$this->min_soil_humidity = $min_soil_humidity;
		$this->max_soil_humidity = $max_soil_humidity;
	}
	
	public function set_tolerated_waterlogging($tolerated_waterlogging){
		$this->tolerates_waterlogging = $tolerated_waterlogging;
	}
	
	public function set_air_temperature($min_air_temperature, $max_air_temperature){
		$this->min_air_temperature = $min_air_temperature;
		$this->max_air_temperature = $max_air_temperature;
	}
	
	public function set_soil_temperature($min_soil_temperature, $max_soil_temperature){
		$this->min_soil_temperature = $min_soil_temperature;
		$this->max_soil_temperature = $max_soil_temperature;
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
	
	public function set_akt_light_hours($akt_light_hours){
		$this->akt_light_hours = $akt_light_hours;
	}
	
	public function set_akt_air_humidity($akt_air_humidity){
		$this->akt_air_humidity = $akt_air_humidity;
	}
	
	public function set_akt_soil_humidity($akt_soil_humidity){
		$this->akt_soil_humidity = $akt_soil_humidity;
	}
	
	public function set_akt_waterlogging($akt_waterlogging){
		$this->akt_waterlogging = $akt_waterlogging;
	}
	
	public function set_akt_air_temperature($akt_air_temperature){
		$this->akt_air_temperature = $akt_air_temperature;
	}
	
	public function set_akt_soil_temperature($akt_soil_temperature){
		$this->akt_soil_temperature = $akt_soil_temperature;
	}
	
	public function set_auto_watering($new_auto_watering){
		$this->auto_watering = $new_auto_watering;
	}
	
	public function set_last_watering($new_last_watering){
		$this->last_watering = $new_last_watering;
	}
	
	public function set_sensor_unit_id($sensor_unit_id){
		$this->sensor_unit_id = $sensor_unit_id;
	}
	
	public function set_winter_prep($winter_prep){
		$this->winter_prep = $winter_prep;
	}
	
	public function set_how_to_water($how_to_water){
		$this->how_to_water = $how_to_water;
	}
	
	public function set_caretaking_hints($caretaking_hints){
		$this->caretaking_hints = $caretaking_hints;
	}
	
	public function set_special_needs($special_needs){
		$this->special_needs = $special_needs;
	}
	
	public function set_transplanting($transplanting){
		$this->transplanting = $transplanting;
	}
	
	public function set_needed_location($needed_location){
		$this->needed_location = $needed_location;
	}
	
	public function set_fertilizing_hints($fertilizing_hints){
		$this->fertilizing_hints = $fertilizing_hints;
	}
	
	public function set_summer_prep($summer_prep){
		$this->summer_prep = $summer_prep;
	}
	
	public function set_notification_settings($new_settings){
		$this->notification_settings = $new_settings;
	}
	
	//getters
	
	public function get_plant_id(){
		return $this->plant_id;
	}
	
	public function get_species_id(){
		return $this->species_id;
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
	
	public function get_min_air_humidity(){
		return $this->min_air_humidity;
	}
	
	public function get_max_air_humidity(){
		return $this->max_air_humidity;
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
	
	public function get_min_air_temperature(){
		return $this->min_air_temperature;
	}
	
	public function get_max_air_temperature(){
		return $this->max_air_temperature;
	}
	
	public function get_min_soil_temperature(){
		return $this->min_soil_temperature;
	}
	
	public function get_max_soil_temperature(){
		return $this->max_soil_temperature;
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
	
	public function get_akt_light_hours(){
		return $this->akt_light_hours;
	}
	
	public function get_akt_air_humidity(){
		return $this->akt_air_humidity;
	}
	
	public function get_akt_soil_humidity(){
		return $this->akt_soil_humidity;
	}
	
	public function get_akt_waterlogging(){
		return $this->akt_waterlogging;
	}
	
	public function get_akt_air_temperature(){
		return $this->akt_air_temperature;
	}
	
	public function get_akt_soil_temperature(){
		return $this->akt_soil_temperature;
	}
	
	public function get_auto_watering(){
		return $this->auto_watering;
	}

	public function get_last_watering(){
		return $this->last_watering;
	}
	
	public function get_sensor_unit_id(){
		return $this->sensor_unit_id;
	}
	
	public function get_winter_prep(){
		return $this->winter_prep;
	}
	
	public function get_how_to_water(){
		return $this->how_to_water;
	}
	
	public function get_caretaking_hints(){
		return $this->caretaking_hints;
	}
	
	public function get_special_needs(){
		return $this->special_needs;
	}
	
	public function get_transplanting(){
		return $this->transplanting;
	}
	
	public function get_needed_location(){
		return $this->needed_location;
	}
	
	public function get_fertilizing_hints(){
		return $this->fertilizing_hints;
	}
	
	public function get_summer_prep(){
		return $this->summer_prep;
	}

	public function get_notification_settings(){
		return $this->notification_settings;		
	}
	
}

?>