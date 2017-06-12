<?php
require_once (__DIR__.'/../config.php');
require_once 'plant.php';
require_once 'sensorunit.php';

class DB_Handler{
	
	private $mysqli;
	private $plants;
	private $plant_ids;
	private $sensorunits;
	private $sensorunit_ids;
	
	
	//getters
	
	public function get_plants(){
		return $this->plants;
	}
	
	public function get_plant_ids(){
		return $this->plant_ids;
	}
	
	public function get_sensorunits(){
		return $this->sensorunits;
	}
	
	public function get_sensorunit_ids(){
		return $this->sensorunit_ids;
	}
	
	
	//mysql functions
	
	public function connect_sql(){
		
		
		$this->mysqli = mysqli_connect(HOST, USER, PASS, DATABASE);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::connect_sql()\n";
		$this->write_log($logtext);
		
	}
	
	public function disconnect_sql(){
		
		mysqli_close($this->mysqli);
		
		// Logging
		$logtext = date(LOG_TIME_FORMAT)."	DB_handler::disconnect_sql()\n\n";
		$this->write_log($logtext);
	}
	
	
	//fetch functions
	
	public function fetch_plant_ids(){
		
		$query = "SELECT plant_id FROM plants;";
		$result = mysqli_query($this->mysqli, $query);
		
		
		$this->plant_ids = [];
		while($plant_ids = mysqli_fetch_array($result,MYSQLI_NUM)){
			$this->plant_ids[] = $plant_ids[0];
		}
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_plant_ids()\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."  	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."  	Result:	";
		for($i = 0; $i < count($this->plant_ids); $i++){
			$logtext = $logtext."[".$this->plant_ids[$i]."]";
		}
		$logtext = $logtext."\n\n";
		$this->write_log($logtext);
		
	}
	
	public function fetch_sensor_unit_ids(){
		
		$query = "SELECT sensor_unit_id FROM sensor_unit;";
		$result = mysqli_query($this->mysqli, $query);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_sensor_unit_ids()\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)." Result: ";
		
		$this->sensorunit_ids = [];
		while($sensorunit_ids = mysqli_fetch_array($result,MYSQLI_NUM)){
			$this->sensorunit_ids[] = $sensorunit_ids[0];
			$logtext = $logtext." [".$sensorunit_ids[0]."]";
		}
		
		$logtext = $logtext."\n";
		$this->write_log($logtext);
		
	}
	
	public function fetch_all_sensorunits(){
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_all_sensorunits()\n";
		$this->write_log($logtext);
		
		$this->fetch_sensor_unit_ids();
		$sensorunit_ids = $this->sensorunit_ids;
		for($i = 0; $i < count($sensorunit_ids); $i++){
			
			$sensorunit_id = $sensorunit_ids[$i];
			$this->sensorunits[$sensorunit_id] = new Sensorunit();
			$this->fetch_sensors($sensorunit_id);
			
			$this->sensorunits[$sensorunit_id]->set_name($this->fetch_sensorunit_name($sensorunit_id));
			
			$this->sensorunits[$sensorunit_id]->set_mac_address($this->fetch_mac_address($sensorunit_id));
			
			$this->sensorunits[$sensorunit_id]->set_status($this->fetch_sensorunit_status($sensorunit_id));
			
			
		}
	}
	
	public function fetch_sensors($sensorunit_id){
		
		$query = "SELECT sensor_id, type FROM sensor WHERE sensor_unit_id = ".$sensorunit_id.";";
		$result = mysqli_query($this->mysqli, $query);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_sensors(sensorunit_id: ".$sensorunit_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ";
		
		$sensor_ids = [];
		$sensor_types = [];
		while($sensoor_ids = mysqli_fetch_array($result,MYSQLI_NUM)){
			$sensor_ids[] = $sensoor_ids[0];
			$sensor_types[] = $sensoor_ids[1];
			
			// Logging
			$logtext = $logtext." [".$sensoor_ids[0]."] => [".$sensoor_ids[1]."]\n				";
		}
		
		$logtext = $logtext."\n";
		
		$this->sensorunits[$sensorunit_id]->set_sensor_ids($sensor_ids);
		
		for ($i = 0; $i < count($sensor_ids); $i++){
			$type = explode("#", $sensor_types[$i], 2);
			switch ($type[0]){
				case "Air_humidity_sensor":
					$this->sensorunits[$sensorunit_id]->set_sensor($sensor_ids[$i], new Air_humidity_sensor());
					$logtext = $logtext.date(LOG_TIME_FORMAT)."	Sensor_id: ".$sensor_ids[$i]." => Air_humidity_sensor\n";
					break;
				case "Air_temperature_sensor":
					$this->sensorunits[$sensorunit_id]->set_sensor($sensor_ids[$i], new Air_temperature_sensor());
					$logtext = $logtext.date(LOG_TIME_FORMAT)."	Sensor_id: ".$sensor_ids[$i]." => Air_temperature_sensor\n";
					break;
				case "Light_sensor":
					$this->sensorunits[$sensorunit_id]->set_sensor($sensor_ids[$i], new Light_sensor());
					$logtext = $logtext.date(LOG_TIME_FORMAT)."	Sensor_id: ".$sensor_ids[$i]." => Light_sensor\n";
					break;
				case "Soil_humidity_sensor":
					$this->sensorunits[$sensorunit_id]->set_sensor($sensor_ids[$i], new Soil_humidity_sensor());
					$logtext = $logtext.date(LOG_TIME_FORMAT)."	Sensor_id: ".$sensor_ids[$i]." => Soil_humidity_sensor\n";
					break;
				case "Soil_temperature_sensor":
					$this->sensorunits[$sensorunit_id]->set_sensor($sensor_ids[$i], new Soil_temperature_sensor());
					$logtext = $logtext.date(LOG_TIME_FORMAT)."	Sensor_id: ".$sensor_ids[$i]." => Soil_temperature_sensor\n";
					break;
				case "Watertank_fillage_sensor":
					$this->sensorunits[$sensorunit_id]->set_sensor($sensor_ids[$i], new Watertank_fillage_sensor());
					$this->sensorunits[$sensorunit_id]->get_sensor($sensor_ids[$i])->set_position(intval($type[1]));
					$logtext = $logtext.date(LOG_TIME_FORMAT)."	Sensor_id: ".$sensor_ids[$i]." => Watertank_fillage_sensor\n";
					break;
				case "Waterlogging_sensor":
					$this->sensorunits[$sensorunit_id]->set_sensor($sensor_ids[$i], new Waterlogging_sensor());
					$logtext = $logtext.date(LOG_TIME_FORMAT)."	Sensor_id: ".$sensor_ids[$i]." => Waterlogging_sensor\n";
					break;
				default:
					echo "i dont know this kind o' sensor\n";
					$logtext = $logtext.date(LOG_TIME_FORMAT)."	Sensor_id: ".$sensor_ids[$i]." => Kein Passender Sensortyp\n";
			}
		}
		$this->write_log($logtext);
	}
	
	
	public function fetch_all_plants(){
		
		// Logging
		$logtext = date(LOG_TIME_FORMAT)." 	DB_handler::fetch_all_plants()\n";
		$this->write_log($logtext);
		
		$this->fetch_plant_ids();
		$plant_ids = $this->plant_ids;
		$season_id = $this->fetch_season();
		
		for($i = 0; $i < count($plant_ids); $i++){
			
			$plant_id = $this->plant_ids[$i];
			
			$plant = new Plant();
			
			$plant->set_plant_id($plant_id);
			
			$species_id = $this->fetch_species_id($plant_id);
			$plant->set_species_id($species_id);
			
			$name = $this->fetch_plant_name($species_id);
			$plant->set_name($name);
			
			$scientific_name = $this->fetch_scientific_name($species_id);
			$plant->set_scientific_name($scientific_name);
			
			$nickname = $this->fetch_nickname($plant_id);
			$plant->set_nichname($nickname);
			
			$min_light_hours = $this->fetch_min_light_hours($species_id, $season_id);
			$max_light_hours = $this->fetch_max_light_hours($species_id, $season_id);
			$plant->set_light_hours($min_light_hours, $max_light_hours);
			
			$min_air_humidity = $this->fetch_min_air_humidity($species_id, $season_id);
			$max_air_humidity = $this->fetch_max_air_humidity($species_id, $season_id);
			$plant->set_air_humidity($min_air_humidity, $max_air_humidity);
			
			$min_soil_humidity = $this->fetch_min_soil_humidity($species_id, $season_id);
			$max_soil_humidity = $this->fetch_max_soil_humidity($species_id, $season_id);
			$plant->set_soil_humidity($min_soil_humidity, $max_soil_humidity);
			
			$tolerated_waterlogging = $this->fetch_tolerated_waterlogging($species_id, $season_id);
			$plant->set_tolerated_waterlogging($tolerated_waterlogging);
			
			$min_temperature = $this->fetch_min_temperature($species_id, $season_id);
			$max_temperature = $this->fetch_max_temperature($species_id, $season_id);
			$plant->set_air_temperature($min_temperature, $max_temperature);
			$plant->set_soil_temperature($min_temperature, $max_temperature);
			
			$min_watering_period = $this->fetch_min_watering_period($species_id, $season_id);
			$max_watering_period = $this->fetch_max_watering_period($species_id, $season_id);
			$plant->set_watering_period($min_watering_period, $max_watering_period);
			
			$min_fertilizer_period = $this->fetch_min_fertilizer_period($species_id, $season_id);
			$max_fertilizer_period = $this->fetch_max_fertilizer_period($species_id, $season_id);
			$plant->set_fertilizer_period($min_fertilizer_period, $max_fertilizer_period);
			
			$indoor = $this->fetch_indoor($plant_id);
			$plant->set_is_indoor($indoor);
			
			$location = $this->fetch_location($plant_id);
			$plant->set_location($location);
			
			$birthdate = $this->fetch_birthdate($plant_id);
			$plant->set_birthdate($birthdate);
			
			$sensor_unit_id = $this->fetch_sensor_unit_id($plant_id);
			$plant->set_sensor_unit_id($sensor_unit_id);
			
			$akt_light_hours = $this->fetch_light_hours($sensor_unit_id, date("Y-m-d"));
			$plant->set_akt_light_hours($akt_light_hours); 
			
			$akt_air_humidity = $this->fetch_akt_air_humidity($sensor_unit_id);
			$plant->set_akt_air_humidity($akt_air_humidity);
			
			$akt_soil_humidity = $this->fetch_akt_soil_humidity($sensor_unit_id);
			$plant->set_akt_soil_humidity($akt_soil_humidity);
			
			$akt_waterlogging = $this->fetch_akt_waterlogging($sensor_unit_id);
			$plant->set_akt_waterlogging($akt_waterlogging);
			
			$akt_air_temperature = $this->fetch_akt_air_temperature($sensor_unit_id);
			$plant->set_akt_air_temperature($akt_air_temperature);
			
			$akt_soil_temperature = $this->fetch_akt_soil_temperature($sensor_unit_id);
			$plant->set_akt_soil_temperature($akt_soil_temperature);
			
			$last_watering = $this->fetch_last_watering($plant_id);
			$plant->set_last_watering($last_watering);
			
			$winter_prep = $this->fetch_winter_prep($species_id);
			$plant->set_winter_prep($winter_prep);
			
			$how_to_water = $this->fetch_how_to_water($species_id);
			$plant->set_how_to_water($how_to_water);
			
			$caretaking_hints = $this->fetch_caretaking_hints($species_id);
			$plant->set_caretaking_hints($caretaking_hints);
			
			$special_needs = $this->fetch_special_needs($species_id);
			$plant->set_special_needs($special_needs);
			
			$transplanting = $this->fetch_transplanting($species_id);
			$plant->set_transplanting($transplanting);
			
			$needed_location = $this->fetch_needed_location($species_id);
			$plant->set_needed_location($needed_location);
			
			$fertilizing_hints = $this->fetch_fertilizing_hints($species_id);
			$plant->set_fertilizing_hints($fertilizing_hints);
			
			$summer_prep = $this->fetch_summer_prep($species_id);
			$plant->set_summer_prep($summer_prep);
			
			$notification_settings = $this->fetch_notification_settings($plant_id);
			$plant->set_notification_settings($notification_settings);
					
			$this->plants[$plant_id] = $plant;
		}
		
	}
		
	public function fetch_sensorunit_status($sensorunit_id){
		
		$query = "SELECT status FROM sensor_unit WHERE sensor_unit_id = ".$sensorunit_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$status = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_sensorunit_status(sensorunit_id: ".$sensorunit_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)." 	Result: ".$status[0]."\n\n";
		$this->write_log($logtext);
		
		return $status[0];
	}
	
	public function fetch_mac_address($sensorunit_id){
		
		$query = "SELECT mac_address FROM sensor_unit WHERE sensor_unit_id = ".$sensorunit_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$mac = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_mac_address(sensorunit_id: ".$sensorunit_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)." 	Result: ".$mac[0]."\n\n";
		$this->write_log($logtext);
		
		return $mac[0];
	}
	
	
	public function fetch_sensorunit_name($sensorunit_id){
		
		$query = "SELECT name FROM sensor_unit WHERE sensor_unit_id = ".$sensorunit_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$name = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_sensorunit_name(sensorunit_id: ".$sensorunit_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)." 	Result: ".$name[0]."\n\n";
		$this->write_log($logtext);
		
		return $name[0];
		
	}	
	
	public function fetch_species_id($plant_id){
		
		$query = "SELECT species_id FROM plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$species_id = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_species_id(plant_id: ".$plant_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)." 	Result: ".$species_id[0]."\n\n";
		$this->write_log($logtext);
		
		return $species_id[0];
		
	}
	
	public function fetch_plant_name($species_id){
		
		$query = "SELECT name FROM species WHERE species_id = ".$species_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$name = mysqli_fetch_array($result);
				
		//Logging
		$logtext = date(LOG_TIME_FORMAT)."	DB_handler::fetch_plant_name(Species: ".$species_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$name[0]."\n\n";
		$this->write_log($logtext);
		
		return utf8_encode($name[0]);
		
	}
	
	public function fetch_nickname($plant_id){
		
		$query = "SELECT nickname FROM plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$nickname = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_nickname(plant_id: ".$plant_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)." 	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$nickname[0]."\n\n";
		$this->write_log($logtext);
		
		return utf8_encode($nickname[0]);
		
	}
	
	public function fetch_scientific_name($species_id){
		
		$query = "SELECT scientific_name FROM species WHERE species_id = ".$species_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$scientific_name = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_scientific_name(species_id: ".$species_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$scientific_name[0]."\n";
		$this->write_log($logtext);
		
		return $scientific_name[0];
	}
	
	public function fetch_min_light_hours($species_id, $season_id){
		
		$query = "SELECT min_light_hours FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$min_light_hours = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_min_light_hours(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$min_light_hours[0]."\n";
		$this->write_log($logtext);
		
		return $min_light_hours[0];
	}
	
	public function fetch_max_light_hours($species_id, $season_id){
		
		$query = "SELECT max_light_hours FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$max_light_hours = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_max_light_hours(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$max_light_hours[0]."\n";
		$this->write_log($logtext);
		
		return $max_light_hours[0];
	}
	
	public function fetch_min_air_humidity($species_id, $season_id){
		
		$query = "SELECT min_air_humidity FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$min_air_humidity = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_min_air_humidity(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$min_air_humidity[0]."\n";
		$this->write_log($logtext);
		
		return $min_air_humidity[0];
		
	}
	
	public function fetch_max_air_humidity($species_id, $season_id){
		
		$query = "SELECT max_air_humidity FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$max_air_humidity = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_max_air_humidity(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$max_air_humidity[0]."\n";
		$this->write_log($logtext);
		
		return $max_air_humidity[0];
		
	}
	
	public function fetch_min_soil_humidity($species_id, $season_id){
		
		$query = "SELECT min_soil_humidity FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$min_soil_humidity = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_min_soil_humidity(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$min_soil_humidity[0]."\n";
		$this->write_log($logtext);
		
		return $min_soil_humidity[0];
	}
	
	public function fetch_max_soil_humidity($species_id, $season_id){
		
		$query = "SELECT max_soil_humidity FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$max_soil_humidity = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_max_soil_humidity(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$max_soil_humidity[0]."\n";
		$this->write_log($logtext);
		
		return $max_soil_humidity[0];
	}
	
	public function fetch_tolerated_waterlogging($species_id, $season_id){
		
		$query = "SELECT waterlogging FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$waterlogging = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_waterlogging(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$waterlogging[0]."\n";
		$this->write_log($logtext);
		
		return $waterlogging[0];
	
	}
	
	public function fetch_min_temperature($species_id, $season_id){
		
		$query = "SELECT min_temp FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$min_temperature = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_min_temperature(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$min_temperature[0]."\n";
		$this->write_log($logtext);
		
		return $min_temperature[0];
		
	}
	
	public function fetch_max_temperature($species_id, $season_id){
		
		$query = "SELECT max_temp FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$max_temperature = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_max_temperature(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$max_temperature[0]."\n";
		$this->write_log($logtext);
		
		return $max_temperature[0];
		
	}
	
	public function fetch_min_watering_period($species_id, $season_id){
		
		$query = "SELECT min_watering_period FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$min_watering_period = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_min_watering_period(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$min_watering_period[0]."\n";
		$this->write_log($logtext);
		
		return $min_watering_period[0];
		
	}
	
	public function fetch_max_watering_period($species_id, $season_id){
		
		$query = "SELECT max_watering_period FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$max_watering_period = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_max_watering_period(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$max_watering_period[0]."\n";
		$this->write_log($logtext);
		
		return $max_watering_period[0];
		
	}
	
	public function fetch_min_fertilizer_period($species_id, $season_id){
		
		$query = "SELECT min_fertilizing_period FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$min_fertilizer_period = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_min_fertilizer_period(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$min_fertilizer_period[0]."\n";
		$this->write_log($logtext);
		
		return $min_fertilizer_period[0];
		
	}
	
	public function fetch_max_fertilizer_period($species_id, $season_id){
		
		$query = "SELECT max_fertilizing_period FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$max_fertilizer_period = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_max_fertilizer_period(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$max_fertilizer_period[0]."\n";
		$this->write_log($logtext);
		
		return $max_fertilizer_period[0];
		
	}
	
	public function fetch_indoor($plant_id){
		
		$query = "SELECT is_indoor from plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$is_indoor = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_indoor(plant_id: ".$plant_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$is_indoor[0]."\n";
		$this->write_log($logtext);
		
		return $is_indoor[0];
		
	}
	
	public function fetch_location($plant_id){
		
		$query = "SELECT location from plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$location = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_location(plant_id: ".$plant_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$location[0]."\n";
		$this->write_log($logtext);
		
		return utf8_encode($location[0]);
		
	}
	
	public function fetch_birthdate($plant_id){
		
		$query = "SELECT birthday FROM plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$birthdate = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_birthdate(plant_id: ".$plant_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$birthdate[0]."\n";
		$this->write_log($logtext);
		
		return $birthdate[0];
		
	}
	
	public function fetch_sensor_unit_id($plant_id){
				
		$query = "SELECT sensor_unit_id FROM plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$sensor_unit_id = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_sensor_unit_id(plant_id: ".$plant_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$sensor_unit_id[0]."\n";
		
		return $sensor_unit_id[0];
	}
	
	
	public function fetch_akt_air_humidity($sensor_unit_id){
		
		$query = "SELECT sensor_id FROM sensor WHERE sensor_unit_id = ".$sensor_unit_id." AND type = 'Air_humidity_sensor';";
		$result = mysqli_query($this->mysqli, $query);
		$sensor_id = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_akt_air_humidity(sensor_unit_id: ".$sensor_unit_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$sensor_id[0]."\n";
		
		$query = "SELECT value FROM sensor_data WHERE sensor_id = ".$sensor_id[0]." ORDER BY date LIMIT 1";
		$result = mysqli_query($this->mysqli, $query);
		$akt_air_humidity = mysqli_fetch_array($result);
		
		// Logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$akt_air_humidity[0]."\n";
		$this->write_log($logtext);
		
		return $akt_air_humidity[0];
	}
	
	public function fetch_akt_soil_humidity($sensor_unit_id){
		
		$query = "SELECT sensor_id FROM sensor WHERE sensor_unit_id = ".$sensor_unit_id." AND type = 'Soil_humidity_sensor';";
		$result = mysqli_query($this->mysqli, $query);
		$sensor_id = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_akt_soil_humidity(sensor_unit_id: ".$sensor_unit_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$sensor_id[0]."\n";
		
		$query = "SELECT value FROM sensor_data WHERE sensor_id = ".$sensor_id[0]." ORDER BY date LIMIT 1";
		$result = mysqli_query($this->mysqli, $query);
		$akt_soil_humidity = mysqli_fetch_array($result);
		
		// Logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$akt_soil_humidity[0]."\n";
		$this->write_log($logtext);
		
		return $akt_soil_humidity[0];
	}
	
	public function fetch_akt_waterlogging($sensor_unit_id){
		
		
		$query = "SELECT sensor_id FROM sensor WHERE sensor_unit_id = ".$sensor_unit_id." AND type = 'Waterlogging_sensor';";
		$result = mysqli_query($this->mysqli, $query);
		$sensor_id = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_akt_waterlogging(sensor_unit_id: ".$sensor_unit_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$sensor_id[0]."\n";
		
		$query = "SELECT value FROM sensor_data WHERE sensor_id = ".$sensor_id[0]." ORDER BY date LIMIT 1";
		$result = mysqli_query($this->mysqli, $query);
		$akt_waterlogging = mysqli_fetch_array($result);
		
		// Logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$akt_waterlogging[0]."\n";
		$this->write_log($logtext);
		
		return $akt_waterlogging[0];
		
	}
	
	public function fetch_akt_air_temperature($sensor_unit_id){
		
		$query = "SELECT sensor_id FROM sensor WHERE sensor_unit_id = ".$sensor_unit_id." AND type = 'Air_temperature_sensor';";
		$result = mysqli_query($this->mysqli, $query);
		$sensor_id = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_akt_air_temperature(sensor_unit_id: ".$sensor_unit_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$sensor_id[0]."\n";
		
		$query = "SELECT value FROM sensor_data WHERE sensor_id = ".$sensor_id[0]." ORDER BY date LIMIT 1";
		$result = mysqli_query($this->mysqli, $query);
		$akt_air_temperature = mysqli_fetch_array($result);
		
		// Logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$akt_air_temperature[0]."\n";
		$this->write_log($logtext);
		
		return $akt_air_temperature[0];
	}
	
	public function fetch_akt_soil_temperature($sensor_unit_id){
		
		$query = "SELECT sensor_id FROM sensor WHERE sensor_unit_id = ".$sensor_unit_id." AND type = 'Soil_temperature_sensor';";
		$result = mysqli_query($this->mysqli, $query);
		$sensor_id = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_akt_soil_temperature(sensor_unit_id: ".$sensor_unit_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$sensor_id[0]."\n";
		
		$query = "SELECT value FROM sensor_data WHERE sensor_id = ".$sensor_id[0]." ORDER BY date DESC LIMIT 1";
		$result = mysqli_query($this->mysqli, $query);
		$akt_soil_temperature = mysqli_fetch_array($result);
		
		// Logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$akt_soil_temperature[0]."\n";
		$this->write_log($logtext);
		
		return $akt_soil_temperature[0];
	}
		
	public function fetch_season(){
		
		$season_id = 1;
		if(date('m')> 3){
			$season_id = 2;
			if(date('m')>10){
				$season_id = 1;
			}
		}
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_season()\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	season_id: ".$season_id."\n";
		$this->write_log($logtext);
		
		return $season_id;
	}
	
	
	// messwerte
	
	public function fetch_light_hours($sensor_unit_id, $date){
		
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_light_hours(sensorunit_id: ".$sensor_unit_id.", Date: ".$date.")\n";
		
		$query = "SELECT value FROM sensor JOIN sensor_data ON sensor.sensor_id = sensor_data.sensor_id";
		$query = $query." WHERE sensor.sensor_unit_id = ".$sensor_unit_id;
		$query = $query." AND type = 'Light_sensor' AND DATE(date) = '".$date."'";
		$query = $query." AND manual = 0;";
		$result = mysqli_query($this->mysqli, $query);
		
		$light_hours = 0.0;
		while ($light_hours_row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
			if ($light_hours_row <= 50) $light_hours += 0.5;
		}
		
		// logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$light_hours."\n";
		$this->write_log($logtext);
		return $light_hours;
	}
	
	public function fetch_air_humidity($sensorunit_id, $date){
		
		$query = "SELECT AVG(value) FROM sensor JOIN sensor_data ON sensor.sensor_id = sensor_data.sensor_id";
		$query = $query." WHERE sensor.sensor_unit_id = ".$sensorunit_id;
		$query = $query." AND type = 'Air_humidity_sensor' AND DATE(date) = '".$date."'";
		$query = $query." AND manual = 0;";
		$result = mysqli_query($this->mysqli, $query);
		$air_humidity = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_air_humidity(sensor_unit_id: ".$sensor_unit_id." date: ".$date.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$air_humidity[0]."\n";
		$this->write_log($logtext);
				
		return intval($air_humidity[0]);
	}
	
	public function fetch_soil_humidity($sensorunit_id, $date){
		
		
		$query = "SELECT AVG(value) FROM sensor JOIN sensor_data ON sensor.sensor_id = sensor_data.sensor_id";
		$query = $query." WHERE sensor.sensor_unit_id = ".$sensorunit_id;
		$query = $query." AND type = 'Soil_humidity_sensor' AND DATE(date) = '".$date."'";
		$query = $query." AND manual = 0;";
		$result = mysqli_query($this->mysqli, $query);
		$soil_humidity = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_soil_humidity(sensor_unit_id: ".$sensor_unit_id." date: ".$date.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$soil_humidity[0]."\n";
		$this->write_log($logtext);
		
		return intval($soil_humidity[0]);
	}

	public function fetch_air_temperature($sensorunit_id, $date){
		
		
		$query = "SELECT AVG(value) FROM sensor JOIN sensor_data ON sensor.sensor_id = sensor_data.sensor_id";
		$query = $query." WHERE sensor.sensor_unit_id = ".$sensorunit_id;
		$query = $query." AND type = 'Air_temperature_sensor' AND DATE(date) = '".$date."'";
		$query = $query." AND manual = 0;";
		$result = mysqli_query($this->mysqli, $query);
		$air_temperature = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_air_temperature(sensor_unit_id: ".$sensor_unit_id." date: ".$date.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$air_temperature[0]."\n";
		$this->write_log($logtext);
		
		return intval($air_temperature[0]);
	}

	public function fetch_soil_temperature($sensorunit_id, $date){
		
		
		$query = "SELECT AVG(value) FROM sensor JOIN sensor_data ON sensor.sensor_id = sensor_data.sensor_id";
		$query = $query." WHERE sensor.sensor_unit_id = ".$sensorunit_id;
		$query = $query." AND type = 'Soil_temperature_sensor' AND DATE(date) = '".$date."'";
		$query = $query." AND manual = 0;";
		$result = mysqli_query($this->mysqli, $query);
		$soil_temperature = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_soil_temperature(sensor_unit_id: ".$sensor_unit_id." date: ".$date.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$soil_temperature[0]."\n";
		$this->write_log($logtext);
		
		return intval($soil_temperature[0]);
	}
	
	public function fetch_waterlogging($sensorunit_id, $date){
		
		
		$query = "SELECT AVG(value) FROM sensor JOIN sensor_data ON sensor.sensor_id = sensor_data.sensor_id";
		$query = $query." WHERE sensor.sensor_unit_id = ".$sensorunit_id;
		$query = $query." AND type = 'Waterlogging_sensor' AND DATE(date) = '".$date."'";
		$query = $query." AND manual = 0;";
		$result = mysqli_query($this->mysqli, $query);
		$waterlogging = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_waterlogging(sensor_unit_id: ".$sensor_unit_id." date: ".$date.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$waterlogging[0]."\n";
		$this->write_log($logtext);
		
		return intval($waterlogging[0]);
	}
	
	public function fetch_last_plant_id(){
		
		$query = "SELECT plant_id from plants ORDER BY plant_id DESC LIMIT 1;";
		$result = mysqli_query($this->mysqli, $query);
		$plant_id = mysqli_fetch_array($result);
		
		
		
		return $plant_id[0];
	}
	
	public function fetch_last_watering($plant_id){
		
		$query = "SELECT date FROM water_usage WHERE plant_id = ".$plant_id." ORDER BY water_usage_id DESC LIMIT 1";
		$result = mysqli_query($this->mysqli, $query);
		$last_watering = mysqli_fetch_array($result);
		
		return $last_watering[0];
		
	}
	
	public function fetch_last_watertank_level($sensorunit_id){
		
		// logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_last_watertank_level(Sensorunit_id: ".$sensorunit_id.")\n";
		
		$query = "SELECT DISTINCT count(sensor_id) FROM sensor WHERE sensor_unit_id = ".$sensorunit_id;
		$query = $query." AND type LIKE 'Watertank_fillage_sensor%';";
		$result = mysqli_query($this->mysqli, $query);
		$limit = mysqli_fetch_array($result);
		$logtext = $logtext.date(LOG_TIME_FORMAT)." SQL: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)." Result: ".$limit[0]."\n";
		
		$query = "SELECT value FROM sensor JOIN sensor_data on sensor.sensor_id = sensor_data.sensor_id";
		$query = $query." WHERE type LIKE 'Watertank_fillage_sensor%'";
		$query = $query." AND sensor.sensor_unit_id = ".$sensorunit_id; 
		$query = $query." ORDER BY date DESC LIMIT ".$limit[0].";";
		$result = mysqli_query($this->mysqli, $query);
		$watertank_level = 0.0;
		$watertank_max_level = 0.0;
		
		while ($row = mysqli_fetch_array($result, MYSQLI_NUM)){
			$watertank_level += $row[0];
			$watertank_max_level++;
		}
		$fillage_level = $watertank_level/$watertank_max_level;
		
		// logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)." SQL: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)." Result: ".$fillage_level."\n";
		$this->write_log($logtext);
		
		return $fillage_level;
	}
	
	public function fetch_winter_prep($species_id){
		
		$query = "SELECT winter_prep FROM species WHERE species_id = ".$species_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$winter_prep = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_winter_prep(species_id: ".$species_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".utf8_encode($winter_prep[0])."\n";
		
		return utf8_encode($winter_prep[0]);
		
	}
	
	public function fetch_how_to_water($species_id){
		
		$query = "SELECT how_to_water FROM species WHERE species_id = ".$species_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$how_to_water = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_how_to_water(species_id: ".$species_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".utf8_encode($how_to_water[0])."\n";
		
		return utf8_encode($how_to_water[0]);
		
	}
	
	public function fetch_caretaking_hints($species_id){
		
		$query = "SELECT caretaking_hints FROM species WHERE species_id = ".$species_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$caretaking_hints = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_caretaking_hints(species_id: ".$species_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".utf8_encode($caretaking_hints[0])."\n";
		
		return utf8_encode($caretaking_hints[0]);
		
	}
	
	public function fetch_special_needs($species_id){
		
		$query = "SELECT special_needs FROM species WHERE species_id = ".$species_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$special_needs = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_special_needs(species_id: ".$species_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".utf8_encode($special_needs[0])."\n";
		
		return utf8_encode($special_needs[0]);
		
	}
	
	public function fetch_transplanting($species_id){
		
		$query = "SELECT transplanting FROM species WHERE species_id = ".$species_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$transplanting = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_transplanting(species_id: ".$species_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".utf8_encode($transplanting[0])."\n";
		
		return utf8_encode($transplanting[0]);
		
	}
	
	public function fetch_needed_location($species_id){
		
		$query = "SELECT needed_location FROM species WHERE species_id = ".$species_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$needed_location = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_needed_location(species_id: ".$species_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".utf8_encode($needed_location[0])."\n";
		
		return utf8_encode($needed_location[0]);
		
	}
	
	public function fetch_fertilizing_hints($species_id){
		
		$query = "SELECT fertilizing_hints FROM species WHERE species_id = ".$species_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$fertilizing_hints = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_fertilizing_hints(species_id: ".$species_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".utf8_encode($fertilizing_hints[0])."\n";
		
		return utf8_encode($fertilizing_hints[0]);
		
	}
	
	public function fetch_summer_prep($species_id){
		
		$query = "SELECT summer_prep FROM species WHERE species_id = ".$species_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$summer_prep = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_summer_prep(species_id: ".$species_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".utf8_encode($summer_prep[0])."\n";
		
		return utf8_encode($summer_prep[0]);
		
	}
	
	public function fetch_sensor_ids_from_sensorunit($sensor_unit_id){
		
		$query = "SELECT sensor_id FROM sensor WHERE sensor_unit_id = ".$sensor_unit_id.";";
		$result = mysqli_query($this->mysqli, $query);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::fetch_sensor_ids_from_sensorunit(sensor_unit_id: ".$sensor_unit_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ";
		
		$sensor_ids = [];
		while($tmp = mysqli_fetch_array($result,MYSQLI_NUM)){
			$sensor_ids[] = $tmp[0];
			$logtext = $logtext.date(LOG_TIME_FORMAT)." [".$tmp[0]."]";
		}
		$logtext = $logtext."\n";
		$this->write_log($logtext);
		
		return $sensor_ids;
	}
	
	public function fetch_all_scientific_names(){
		
		$query = "SELECT scientific_name from species;";
		$result = mysqli_query($this->mysqli, $query);
		
		$scientific_names = [];
		while($tmp = mysqli_fetch_array($result,MYSQLI_NUM)){
			$scientific_names[] = $tmp[0];
		}
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_all_scientific_names()\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."  	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."  	Result:	";
		for($i = 0; $i < count($this->plant_ids); $i++){
			$logtext = $logtext."[".$scientific_names[$i]."]";
		}
		$logtext = $logtext."\n\n";
		$this->write_log($logtext);
		
		return $scientific_names;
	}
	
	public function fetch_all_species_ids(){
				
		$query = "SELECT species_id from species;";
		$result = mysqli_query($this->mysqli, $query);
		
		$species_ids = [];
		while($tmp = mysqli_fetch_array($result,MYSQLI_NUM)){
			$species_ids[] = $tmp[0];
		}
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_all_species_ids()\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."  	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."  	Result:	";
		for($i = 0; $i < count($this->plant_ids); $i++){
			$logtext = $logtext."[".$species_ids[$i]."]";
		}
		$logtext = $logtext."\n\n";
		$this->write_log($logtext);
		
		return $species_ids;
	}
	
	public function fetch_notification_settings($plant_id){
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_notification_settings(Plant Id: ".$plant_id.")\n";
		
		$query = "SELECT notification FROM plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$notification_settings = mysqli_fetch_array($result);
		
		//logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$notification_settings[0]."\n";
		$this->write_log($logtext);
		
		return $notification_settings[0];
	}
	
	public function fetch_last_data_update($sensorunit_id){
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::fetch_last_data_update(Sensorunit Id: ".$sensorunit_id.")\n";
		
		$query = "SELECT date FROM sensor JOIN sensor_data ON sensor.sensor_id = sensor_data.sensor_id ";
		$query = $query."WHERE sensor_unit_id = ".$sensorunit_id." ORDER BY date LIMIT 1;";
		$result = mysqli_query($this->mysqli, $query);
		$last_date = mysqli_fetch_array($result);
		
		//logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$last_date[0]."\n";
		$this->write_log($logtext);
		
		return $last_date[0];
	}
	
	//insert functions
	
	public function insert_water_usage($plant_id, $water_usage){
		
		// logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::insert_water_usage(Plant Id: ".$plant_id.", Water Usage: ".$water_usage.")\n";
		
		$query = "INSERT INTO water_usage (plant_id, date, water_usage)".
				" VALUES (".$plant_id.", NOW(), ".$water_usage.");";
		$result = mysqli_query($this->mysqli, $query);
		
		//logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL:".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$result."\n";
		$this->write_log($logtext);
		
		return $result;
	}
	
	public function insert_plant($sensorunit_id, $species_id, $nickname, $location, $is_indoor, $auto_watering){
		
		
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::insert_plant(Sensorunit Id: ".$sensorunit_id;
		$logtext = $logtext.", Species Id: ".$species_id.", Location: ".$location.", Is indoor: ".$is_indoor;
		$logtext = $logtext.", auto watering: ".$auto_watering.")\n";
		
		$query = "INSERT INTO plants (sensor_unit_id, species_id, nickname, birthday, location, is_indoor, auto_watering)";
		$query = $query."VALUES (".$sensorunit_id.", ".$species_id.", '".utf8_decode($nickname)."', NOW(), '".utf8_decode($location)."', ".$is_indoor.", ".$auto_watering.");";
		$result = mysqli_query($this->mysqli, $query);
		
		//logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL: ".utf8_encode($query)."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$result."\n";
		$this->write_log($logtext);
		
		return $result;
	}
	
	
	/**
	 * 
	 * @param unknown $mac_address
	 * @param unknown $name
	 * @return unknown returns the id of the inserted sensorunit
	 */
	public function insert_sensor_unit($mac_address, $name){
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::insert_sensor_unit(mac_address: ".$mac_address.", name: ".$name.")\n";
		
		$query = "INSERT INTO sensor_unit ( mac_address, name, status) VALUES ('".$mac_address."', '".$name."', 'free');";
		$result = mysqli_query($this->mysqli, $query);
		
		// logging 
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL: ".$query."\n";
		
		$query = "SELECT sensor_unit_id FROM sensor_unit ORDER BY sensor_unit_id DESC LIMIT 1";
		$result = mysqli_query($this->mysqli, $query);
		$last_sensorunit_id = mysqli_fetch_array($result);
		$last_sensorunit_id = $last_sensorunit_id[0];
		
		// Logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$last_sensorunit_id."\n";
		$this->write_log($logtext);
		
		$this->insert_sensor_block($last_sensorunit_id);
		
		return $last_sensorunit_id;
	}
	
	public function insert_sensor_block($sensorunit_id){
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::insert_sensor_block(sensorunit_id: ".$sensorunit_id.")\n";
		$this->write_log($logtext);
		
		$this->insert_sensor($sensorunit_id, "Air_humidity_sensor");
		$this->insert_sensor($sensorunit_id, "Air_temperature_sensor");
		$this->insert_sensor($sensorunit_id, "Soil_humidity_sensor");
		$this->insert_sensor($sensorunit_id, "Soil_temperature_sensor");
		$this->insert_sensor($sensorunit_id, "Light_sensor");
		$this->insert_sensor($sensorunit_id, "Waterlogging_sensor");
		$this->insert_sensor($sensorunit_id, "Watertank_fillage_sensor#1");
		$this->insert_sensor($sensorunit_id, "Watertank_fillage_sensor#2");
		$this->insert_sensor($sensorunit_id, "Watertank_fillage_sensor#3");
		
		
		
		
	}
	
	public function insert_sensor($sensorunit_id, $type){
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::insert_sensor(sensorunit_id: ".$sensorunit_id.", Type: ".$type.")\n";
		
		$query = "INSERT INTO sensor (sensor_unit_id, type) VALUES (".$sensorunit_id.", '".$type."');";
		$result = mysqli_query($this->mysqli, $query);
		
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$result."\n";
		$this->write_log($logtext);
		
	}
	
	public function insert_all_sensor_values($manual){
		
		// logging
		$logtext = date(LOG_TIME_FORMAT)."DB_handler::insert_all_sensor_values(manual: ".$manual.")\n";
		$this->write_log($logtext);
		
		$sensorunits = $this->sensorunits;
		$sensorunit_ids = $this->sensorunit_ids;
		
		for($i = 0; $i < count($sensorunit_ids); $i++){
			
			$sensorunit_id = $sensorunit_ids[$i];
			$sensorunit = $sensorunits[$sensorunit_id];
			
			$sensors = $sensorunit->get_array();
			$sensor_ids = $sensorunit->get_sensor_ids();
			
			for($j = 0; $j < count($sensor_ids); $j++){
				
				$sensor_id = $sensor_ids[$j];
				$sensor = $sensors[$sensor_id];
				
				$value = $sensor->get_value();
				
				$this->insert_sensor_data($sensor_id, $value, $manual);
				
				
			}
			
		}
		
	}
	
	public function insert_sensor_data($sensor_id, $value, $manual){
		//logging
		$logtext = date(LOG_TIME_FORMAT)."	DB_Handler::insert_sensor_data(sensor_id: ".$sensor_id.", value: ".$value.", manual: ".$manual.")\n";
		
		$query = "INSERT INTO sensor_data (sensor_id, value, date, manual) VALUES (".$sensor_id.", ".$value.", NOW(), ".$manual.");";
		$result = mysqli_query($this->mysqli, $query);
		
		//logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)." SQL: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)." result: ".$result."\n";
		$this->write_log($logtext);
		
	}
	
	
	//update functions
	
	public function update_sensorunit_status($sensorunit_id, $new_status){
		
		// logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::update_sensorunit_status(Sensorunit Id: ".$sensorunit_id.", Neuer Status: ".$new_status.")\n";
		
		$query = "UPDATE sensor_unit SET status = '".$new_status."' WHERE sensor_unit_id = ".$sensorunit_id.";";
		$result = mysqli_query($this->mysqli, $query);
		
		//logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$result."\n";
		$this->write_log($logtext);
	}
	
	public function update_plant_nickname($plant_id, $nickname){
		
		$query = "UPDATE plants SET nickname = '".$nickname."' WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::update_plant_nickname(plant_id: ".$plant_id.", nickname: ".$nickname.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$result."\n";
		$this->write_log($logtext);
		
		
	}
	
	public function update_plant_location($plant_id, $location, $is_indoor){
		
		$query = "UPDATE plants SET location = '".$location."' ,is_indoor = ".$is_indoor." ' WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::update_plant_location(plant_id: ".$plant_id.", location: ".$location.", is_indoor: ".$is_indoor.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$result."\n";
		$this->write_log($logtext);
	}
	
	public function update_notification_settings($plant_id, $new_settings){
		
		$query = "UPDATE plants SET notification = '".$new_settings."' WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::update_notification_settings(Plant Id: ".$plant_id.", New Settings: ".$new_settings.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$result."\n";
		$this->write_log($logtext);
		
		return $result;
		
	}
	
	
	// delete functions
	
	public function delete_plant($plant_id){
		
		$this->delete_water_usage($plant_id);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::delete_plant(Plant Id: ".$plant_id.")\n";
		
		
		$query = "DELETE FROM plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		
		//logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$result."\n";
		$this->write_log($logtext);
		
		return $result;
		
	}
	
	public function delete_water_usage($plant_id){
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::delete_water_usage(Plant Id: ".$plant_id.")\n";
		
		$query = "DELETE FROM water_usage WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		
		//logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$result."\n";
		$this->write_log($logtext);
		
		return $result;
	}
	
	public function delete_sensor_data($sensor_id){
				
		$query = "DELETE FROM sensor_data WHERE sensor_id = ".$sensor_id.";";
		$result = mysqli_query($this->mysqli, $query);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::delete_sensor_date(sensor_id: ".$sensor_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$result."\n";
		
		
		return $result;		
		
	}
	
	public function delete_sensors($sensor_unit_id){
		
		$query = "DELETE FROM sensor WHERE sensor_unit_id = ".$sensor_unit_id.";";
		$result = mysqli_query($this->mysqli, $query);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::delete_sensors(sensor_unit_id: ".$sensor_unit_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$result."\n";
		
		return $result;
		
	}

	public function delete_sensor_unit($sensor_unit_id){
		
		$query = "DELETE FROM sensor_unit WHERE sensor_unit_id = ".$sensor_unit_id.";";
		$result = mysqli_query($this->mysqli, $query);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::delete_sensor_unit(sensor_unit_id: ".$sensor_unit_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$result."\n";
		
		return $result;
		
	}
	
	//check functions
	
	public function check_sensorunit_mac_address($mac_address){
		
		$query = "SELECT sensor_unit_id FROM sensor_unit WHERE mac_address = '".$mac_address."';";
		$result = mysqli_query($this->mysqli, $query);
		$sensorunit_id = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::check_sensorunit_mac_address(mac_address: ".$mac_address.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$sensorunit_id[0]."\n";
		$this->write_log($logtext);
		
		return $sensorunit_id[0];
		
	}
	
	public function check_sensorunit_name($name){
		
		$query = "SELECT sensor_unit_id FROM sensor_unit WHERE name = '".$name."';";
		$result = mysqli_query($this->mysqli, $query);
		$sensorunit_id = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_Handler::check_sensorunit_name(name: ".$name.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	SQL Query: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$sensorunit_id[0]."\n";
		$this->write_log($logtext);
		
		return $sensorunit_id[0];
		
	}
	
	public function sum_water_usage($plant_id, $date){
		
		$query = "SELECT SUM(water_usage) FROM water_usage WHERE plant_id = ".$plant_id." AND date >= CAST('".$date."' as Datetime);";
		$result =  mysqli_query($this->mysqli, $query);
		$water_usage_sum = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	DB_handler::sum_water_usage(plant_id: ".$plant_id.", date: ".$date.")\n";
		$logtext = $logtext." SQL:	".$query."\n";
		$logtext = $logtext." result:	".$water_usage_sum[0]."\n";
		$this->write_log($logtext);
		
		return $water_usage_sum[0];
	}
	
	public function water_usage_on_day($plant_id,$date){
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)." DB_handler::water_usage_per_day(plant_id: ".$plant_id." date: ".$date.")\n\n";
		
			
		$query = "SELECT SUM(water_usage) FROM water_usage WHERE plant_id = ".$plant_id." AND DATE(date) = '".$date."';";
		$result =  mysqli_query($this->mysqli, $query);
		$return_value = mysqli_fetch_array($result);
		$return_value = $return_value[0];
		
		// Logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)." SQL: ".$query."\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)." result: ".$return_value."\n";

		
		
		$this->write_log($logtext);
		
			
		if($return_value == NULL){
			$water_usage_per_day = 0;
		}else{
			$water_usage_per_day = intval($return_value);
		}
		
		
		return $water_usage_per_day;
	}
	
	
		
	//log functions
	
	public function write_log($logtext){
		
		if(DB_HANDLER_LOGGING){			
			$logfile = fopen("/var/log/gartnetzwerg/gartnetzwerg_log.".date('W'), "a");
			
			fwrite($logfile, $logtext);
			
			fclose($logfile);
		}
	}
}

?>