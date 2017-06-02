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
	
	
	public function connect_sql(){
		
		
		$this->mysqli = mysqli_connect(HOST, USER, PASS, DATABASE);
		
		// Logging
		$logtext = "\n".date('c')."	Connect to Database\n";
		$this->write_log($logtext);
		
	}
	
	public function disconnect_sql(){
		
		mysqli_close($this->mysqli);
		
		// Logging
		$logtext = date('c')."	Disconnect Database\n\n";
		$this->write_log($logtext);
	}
	
	public function fetch_plant_ids(){
		
		$query = "SELECT plant_id FROM plants;";
		$result = mysqli_query($this->mysqli, $query);
		
		
		$this->plant_ids = [];
		while($plant_ids = mysqli_fetch_array($result,MYSQLI_NUM)){
			$this->plant_ids[] = $plant_ids[0];
		}
		
		// Logging
		$logtext = "\n".date('c')."	fetch_plant_ids()\n";
		$logtext = $logtext.date('c')."  	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."  	Result:	";
		for($i = 0; $i < count($this->plant_ids); $i++){
			$logtext = $logtext."[".$this->plant_ids[$i][0]."]";
		}
		$logtext = $logtext."\n\n";
		$this->write_log($logtext);
		
	}
	
	public function fetch_sensor_unit_ids(){
		
		$query = "SELECT sensor_unit_id FROM sensor_unit;";
		$result = mysqli_query($this->mysqli, $query);
		
		
		$this->sensorunit_ids = [];
		while($sensorunit_ids = mysqli_fetch_array($result,MYSQLI_NUM)){
			$this->sensorunit_ids[] = $sensorunit_ids[0];
		}
		
	}
	
	public function fetch_family(){
		
	}
	
	public function fetch_genus(){
		
	}
	
	public function fetch_all_plants(){
		
		// Logging
		$logtext = date('c')." 	fetch_all_plants()\n";
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
			
			$name = $this->fetch_name($species_id);
			$plant->set_name($name);
			
			$scientific_name = $this->fetch_scientific_name($species_id);
			$plant->set_scientific_name($scientific_name);
			
			$nickname = $this->fetch_nickname($plant_id);
			$plant->set_nichname($nickname);
	
			$min_light_hours = $this->fetch_min_light_hours($species_id, $season_id);
			$max_light_hours = $this->fetch_max_light_hours($species_id, $season_id);
			$plant->set_light_hours($min_light_hours, $max_light_hours);
			
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
			
			/* TODO Methode noch nicht implementiert
			$akt_light_hours = $this->fetch_akt_light_hours($sensor_unit_id);
			$plant->set_akt_light_hours($akt_light_hours); */
			
			$akt_air_humidity = $this->fetch_akt_air_humidity($sensor_unit_id);
			$plant->set_akt_air_humidity($akt_air_humidity);
			
			$akt_soil_humidity = $this->fetch_akt_soil_humidity($sensor_unit_id);
			$plant->set_akt_soil_humidity($akt_soil_humidity);
			
			/* TODO Methode noch nicht implementiert
			$akt_waterlogging = $this->fetch_akt_waterlogging($sensor_unit_id);
			$plant->set_akt_waterlogging($akt_waterlogging); */
			
			$akt_air_temperature = $this->fetch_akt_air_temperature($sensor_unit_id);
			$plant->set_akt_air_temperature($akt_air_temperature);
			
			$akt_soil_temperature = $this->fetch_akt_soil_temperature($sensor_unit_id);
			$plant->set_akt_soil_temperature($akt_soil_temperature);
			
			$this->plants[$plant_id] = $plant;
		}
		
	}
	
	public function fetch_all_sensorunits(){
		$this->fetch_sensor_unit_ids();
		$sensorunit_ids = $this->sensorunit_ids;
		for($i = 0; $i < count($sensorunit_ids); $i++){
			
			$sensorunit_id = $sensorunit_ids[$i];
			$this->sensorunits[$sensorunit_id] = new Sensorunit();
			$this->fetch_sensors($sensorunit_id);
			
		}
		
	}
	
	public function fetch_sensors($sensorunit_id){
		$query = "SELECT sensor_id, type FROM sensor WHERE sensor_unit_id = ".$sensorunit_id.";";
		$result = mysqli_query($this->mysqli, $query);
		
		
		$sensor_ids = [];
		$sensor_types = [];
		while($sensoor_ids = mysqli_fetch_array($result,MYSQLI_NUM)){
			$sensor_ids[] = $sensoor_ids[0];
			$sensor_types[] = $sensoor_ids[1];
		}
		
		$this->sensorunits[$sensorunit_id]->set_sensor_ids($sensor_ids);
	
		for ($i = 0; $i < count($sensor_ids); $i++){
			$type = explode("#", $sensor_types[$i], 2);
			switch ($type[0]){		
				case "Air_humidity_sensor":
					$this->sensorunits[$sensorunit_id]->set_sensor($sensor_ids[$i], new Air_humidity_sensor());
					break;
				case "Air_temperature_sensor":
					$this->sensorunits[$sensorunit_id]->set_sensor($sensor_ids[$i], new Air_temperature_sensor());
					break;
				case "Light_sensor":
					$this->sensorunits[$sensorunit_id]->set_sensor($sensor_ids[$i], new Light_sensor());
					break;
				case "Soil_humidity_sensor":
					$this->sensorunits[$sensorunit_id]->set_sensor($sensor_ids[$i], new Soil_humidity_sensor());
					break;
				case "Soil_temperature_sensor":
					$this->sensorunits[$sensorunit_id]->set_sensor($sensor_ids[$i], new Soil_temperature_sensor());
					break;
				case "Watertank_fillage_sensor":
					$this->sensorunits[$sensorunit_id]->set_sensor($sensor_ids[$i], new Watertank_fillage_sensor());
					$this->sensorunits[$sensorunit_id]->get_sensor($sensor_ids[$i])->set_position(intval($type[1]));
					break;
				case "Waterlogging_sensor":
					$this->sensorunits[$sensorunit_id]->set_sensor($sensor_ids[$i], new Waterlogging_sensor());
					break;
				default:
					echo "i dont know this kind o' sensor\n";
			}
		}
	}
	
	public function fetch_species_id($plant_id){
		
		$query = "SELECT species_id FROM plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$species_id = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_species_id(plant_id: ".$plant_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')." 	Result: ".$species_id[0]."\n\n";
		$this->write_log($logtext);
		
		return $species_id[0];
		
	}
	
	public function fetch_name($species_id){
		
		$query = "SELECT name FROM species WHERE species_id = ".$species_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$name = mysqli_fetch_array($result);
				
		// TODO Logging
		
		return utf8_encode($name[0]);
		
	}
	
	public function fetch_nickname($plant_id){
		
		$query = "SELECT nickname FROM plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$nickname = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_nickname(plant_id: ".$plant_id.")\n";
		$logtext = $logtext.date('c')." 	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$nickname[0]."\n\n";
		$this->write_log($logtext);
		
		return utf8_encode($nickname[0]);
		
	}
	
	public function fetch_scientific_name($species_id){
		
		$query = "SELECT scientific_name FROM species WHERE species_id = ".$species_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$scientific_name = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_scientific_name(species_id: ".$species_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$scientific_name[0]."\n";
		$this->write_log($logtext);
		
		return $scientific_name[0];
	}
	
	public function fetch_min_light_hours($species_id, $season_id){
		
		$query = "SELECT min_light_hours FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$min_light_hours = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_min_light_hours(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$min_light_hours[0]."\n";
		$this->write_log($logtext);
		
		return $min_light_hours[0];
	}
	
	public function fetch_max_light_hours($species_id, $season_id){
		
		$query = "SELECT max_light_hours FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$max_light_hours = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_max_light_hours(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$max_light_hours[0]."\n";
		$this->write_log($logtext);
		
		return $max_light_hours[0];
	}
	
	public function fetch_min_soil_humidity($species_id, $season_id){
		
		$query = "SELECT min_soil_humidity FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$min_soil_humidity = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_min_soil_humidity(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$min_soil_humidity[0]."\n";
		$this->write_log($logtext);
		
		return $min_soil_humidity[0];
	}
	
	public function fetch_max_soil_humidity($species_id, $season_id){
		
		$query = "SELECT max_soil_humidity FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$max_soil_humidity = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_max_soil_humidity(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$max_soil_humidity[0]."\n";
		$this->write_log($logtext);
		
		return $max_soil_humidity[0];
	}
	
	public function fetch_tolerated_waterlogging($species_id, $season_id){
		
		$query = "SELECT waterlogging FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$waterlogging = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_waterlogging(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$waterlogging[0]."\n";
		$this->write_log($logtext);
		
		return $waterlogging[0];
	
	}
	
	public function fetch_min_temperature($species_id, $season_id){
		
		$query = "SELECT min_temp FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$min_temperature = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_min_temperature(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$min_temperature[0]."\n";
		$this->write_log($logtext);
		
		return $min_temperature[0];
		
	}
	
	public function fetch_max_temperature($species_id, $season_id){
		
		$query = "SELECT max_temp FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$max_temperature = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_max_temperature(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$max_temperature[0]."\n";
		$this->write_log($logtext);
		
		return $max_temperature[0];
		
	}
	
	public function fetch_lux(){
		
	}
	
	public function fetch_min_watering_period($species_id, $season_id){
		
		$query = "SELECT min_watering_period FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$min_watering_period = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_min_watering_period(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$min_watering_period[0]."\n";
		$this->write_log($logtext);
		
		return $min_watering_period[0];
		
	}
	
	public function fetch_max_watering_period($species_id, $season_id){
		
		$query = "SELECT max_watering_period FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$max_watering_period = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_max_watering_period(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$max_watering_period[0]."\n";
		$this->write_log($logtext);
		
		return $max_watering_period[0];
		
	}
	
	public function fetch_min_fertilizer_period($species_id, $season_id){
		
		$query = "SELECT min_fertilizing_period FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$min_fertilizer_period = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_min_fertilizer_period(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$min_fertilizer_period[0]."\n";
		$this->write_log($logtext);
		
		return $min_fertilizer_period[0];
		
	}
	
	public function fetch_max_fertilizer_period($species_id, $season_id){
		
		$query = "SELECT max_fertilizing_period FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$max_fertilizer_period = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_max_fertilizer_period(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$max_fertilizer_period[0]."\n";
		$this->write_log($logtext);
		
		return $max_fertilizer_period[0];
		
	}
	
	public function fetch_indoor($plant_id){
		
		$query = "SELECT is_indoor from plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$is_indoor = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_indoor(plant_id: ".$plant_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$is_indoor[0]."\n";
		$this->write_log($logtext);
		
		return $is_indoor[0];
		
	}
	
	public function fetch_location($plant_id){
		
		$query = "SELECT location from plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$location = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_location(plant_id: ".$plant_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$location[0]."\n";
		$this->write_log($logtext);
		
		return $location[0];
		
	}
	
	public function fetch_birthdate($plant_id){
		
		$query = "SELECT birthday FROM plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$birthdate = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_birthdate(plant_id: ".$plant_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$birthdate[0]."\n";
		$this->write_log($logtext);
		
		return $birthdate[0];
		
	}
	
	public function fetch_sensor_unit_id($plant_id){
				
		$query = "SELECT sensor_unit_id FROM plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$sensor_unit_id = mysqli_fetch_array($result);
		
		// TODO Logging
		
		return $sensor_unit_id[0];
	}
	
	public function fetch_akt_light_hours($sensor_unit_id){
		
		
	
		
		// TODO
	}
	
	public function fetch_akt_air_humidity($sensor_unit_id){
		
		$query = "SELECT sensor_id FROM sensor WHERE sensor_unit_id = ".$sensor_unit_id." AND type = 'Air_humidity_sensor';";
		$result = mysqli_query($this->mysqli, $query);
		$sensor_id = mysqli_fetch_array($result);
		
		$query = "SELECT value FROM sensor_data WHERE sensor_id = ".$sensor_id[0]." ORDER BY date LIMIT 1";
		$result = mysqli_query($this->mysqli, $query);
		$akt_air_humidity = mysqli_fetch_array($result);
		
		// TODO Logging
		
		return $akt_air_humidity[0];
	}
	
	public function fetch_akt_soil_humidity($sensor_unit_id){
		
		$query = "SELECT sensor_id FROM sensor WHERE sensor_unit_id = ".$sensor_unit_id." AND type = 'Soil_humidity_sensor';";
		$result = mysqli_query($this->mysqli, $query);
		$sensor_id = mysqli_fetch_array($result);
		
		$query = "SELECT value FROM sensor_data WHERE sensor_id = ".$sensor_id[0]." ORDER BY date LIMIT 1";
		$result = mysqli_query($this->mysqli, $query);
		$akt_soil_humidity = mysqli_fetch_array($result);
		
		// TODO Logging
		
		return $akt_soil_humidity[0];
	}
	
	public function fetch_akt_waterlogging($sensor_unit_id){
		
		// TODO
	}
	
	public function fetch_akt_air_temperature($sensor_unit_id){
		
		$query = "SELECT sensor_id FROM sensor WHERE sensor_unit_id = ".$sensor_unit_id." AND type = 'Air_temperature_sensor';";
		$result = mysqli_query($this->mysqli, $query);
		$sensor_id = mysqli_fetch_array($result);
		
		$query = "SELECT value FROM sensor_data WHERE sensor_id = ".$sensor_id[0]." ORDER BY date LIMIT 1";
		$result = mysqli_query($this->mysqli, $query);
		$akt_air_temperature = mysqli_fetch_array($result);
		
		// TODO Logging
		
		return $akt_air_temperature[0];
	}
	
	public function fetch_akt_soil_temperature($sensor_unit_id){
		
		$query = "SELECT sensor_id FROM sensor WHERE sensor_unit_id = ".$sensor_unit_id." AND type = 'Soil_temperature_sensor';";
		$result = mysqli_query($this->mysqli, $query);
		$sensor_id = mysqli_fetch_array($result);
		
		$query = "SELECT value FROM sensor_data WHERE sensor_id = ".$sensor_id[0]." ORDER BY date DESC LIMIT 1";
		$result = mysqli_query($this->mysqli, $query);
		$akt_soil_temperature = mysqli_fetch_array($result);
		
		// TODO Logging
		
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
		$logtext = "\n".date('c')."	fetch_season()\n";
		$logtext = $logtext.date('c')."	season_id: ".$season_id."\n";
		$this->write_log($logtext);
		
		return $season_id;
	}
	
	public function insert_sensor_unit($mac_address, $name){
		
		// Logging
		$logtext = date('c')."	insert_sensor_unit(mac_address: ".$mac_address.", name: ".$name.")\n";
		
		$query = "INSERT INTO sensor_unit ( mac_address, name) VALUES ('".$mac_address."', '".$name."');";
		$result = mysqli_query($this->mysqli, $query);
		
		// TODO Es müssen noch sensoren dafür inserted werden fertig machen
		
		// Logging
		$logtext = $logtext.date('c')."	SQL: ".$query."\n";
		$logtext = $logtext.date('c')." result: ".$result."\n";
		$this->write_log($logtext);
		
	}
	
	public function insert_sensor_data($sensor_id, $value, $manual){
		//TODO insert query for sensor data
	}
	
	public function put_all_sensors(){
		
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
				
				$this->put_sensor_value($sensor_id, $value);
				
				
			}
			
		}
		
	}
	
	public function put_sensor_value($sensor_id, $value){
		
		$query = "INSERT INTO sensor_data (sensor_id, value, date) VALUES ($sensor_id, $value, CURRENT_DATE);";
		$result = mysqli_query($this->mysqli, $query);

	}
	
	public function get_plant_ids(){
		return $this->plant_ids;
	}
	
	public function get_plants(){
		return $this->plants;
	}
	
	public function get_sensorunits(){
		return $this->sensorunits;
	}
	
		
	public function write_log($logtext){
		
		$logfile = fopen("/var/log/gartnetzwerg/db_handler_log.".date('w'), "a");
		
		fwrite($logfile, $logtext);
		
		fclose($logfile);
	}
	
}

?>