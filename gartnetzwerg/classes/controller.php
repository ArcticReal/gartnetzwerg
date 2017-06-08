<?php


require_once 'sensorunit.php';
require_once 'plant.php';
require_once 'db_handler.php';
require_once 'Mail.php';
require_once 'db_handler.php';

class Controller{
	private $sensorunit_array;
	private $plant_array;
	private $curent_timestamp;		// HH:MM:SS-DD-MM-YYYY
	private $openweathermap_api_key;
	private $default_openweathermap_api_key = "cd91b0f34b0fd55a44899fa358743139";
	private $openweathermap_location;
	private $notification_receiving_email_address;
	private $vacation_start_date;
	private $vacation_end_date;
		
	
	//constructor
	
	function __construct(){
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::__construct()\n";
		$this->write_log($logtext);
		
		$this->init();
		
	}
	

	//setters:
	
	public function set_sensorunit($new_sensorunit, $new_sensorunit_id){
		$this->sensorunit_array[$new_sensorunit_id] = $new_sensorunit;
	}
	
	public function set_plant($new_plant, $newplant_id){
		$this->plant_array[$newplant_id] = $new_plant;
	}
	
	public function set_current_timestamp($new_current_timestamp){
		$this->curent_timestamp = $new_current_timestamp;
	}
	
	public function set_openweathermap_api_key($new_openweathermap_api_key){
		$this->openweathermap_api_key = $new_openweathermap_api_key;
	}
	
	public function set_default_openweathermap_api_key($new_default_openweathermap_api_key){
		$this->default_openweathermap_api_key = $new_default_openweathermap_api_key;
	}
	
	public function set_notification_receiving_email_address($new_notification_receiving_email_address){
		$this->notification_receiving_email_address = $new_notification_receiving_email_address;	
	}
	
	public function set_vacation_start_date($new_start_date){
		$this->vacation_start_date = $new_start_date;	
	}
	
	public function set_vacation_end_date($new_end_date){
		$this->vacation_end_date = $new_end_date;
	}
	
	public function set_openweathermap_location($new_location){
		$this->openweathermap_location = $new_location;
	}
	
	//getters:
	
	public function get_sensorunit($sensorunitid){
		return $this->sensorunit_array[$sensorunitid];
	}
	
	public function get_plant($plant_id){
		return $this->plant_array[$plant_id];
	}
	
	public function get_plants(){
		return $this->plant_array;
	}
	
	public function get_openweathermap_api_key(){
		return $this->openweathermap_api_key;	
	}
	
	public function get_default_openweathermap_api_key(){
		return $this->default_openweathermap_api_key;
	}
	
	public function get_notification_receiving_email_address(){
		return $this->notification_receiving_email_address;
	}
	
	public function get_vacation_start_date(){
		return $this->vacation_start_date;
	}
	
	public function get_vacation_end_date(){
		return $this->vacation_end_date;
	}
	
	public function get_openweathermap_location(){
		return $this->openweathermap_location;
	}
	

	/**
	 * TODO:
	 * Bei sämtlichen eingabestrings, die in die datenbank kommen auf ' und " überprüfen, 
	 * da diese die inserts kaputt machen
	 * 
	 * 
	 * 
	 * 
	 */
	
	
	//functions

	public function get_free_sensorunits(){
		$free_sensorunits_array = [];
		
		foreach ($this->sensorunit_array as $sensorunit_id => $sensorunit){
			if($sensorunit->get_status() == "free"){
				$free_sensorunits_array[$sensorunit_id] = $sensorunit;
			}
		}
		
		return $free_sensorunits_array;
	}
	
	public function get_all_species(){
		
		// TODO logging
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		$ids = $db_handler->fetch_all_species_ids();
		$names = $db_handler->fetch_all_scientific_names();
		$return_array = [];
		
		for ($i = 0; $i < count($ids); $i++){
			$return_array[$ids[$i]] = $names[$i];
			
		}
		
		$db_handler->disconnect_sql();
		return $return_array;
		
	}
	
	/**
	 * Always execute this after restarting the script
	 */
	public function init(){
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::init()\n";
		
		
		//read config file 
		$this->set_notification_receiving_email_address($this->lookup_config("SEND_MAIL_TO"));
		//read openweathermap info
		$this->set_openweathermap_api_key($this->lookup_config("OPENWEATHERMAP_API_KEY"));
		$this->set_openweathermap_location($this->lookup_config("OPENWEATHERMAP_LOCATION"));

		//logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Lookups: \n					";
		$logtext = $logtext."SEND_MAIL_TO:			".$this->get_notification_receiving_email_address()."\n					";
		$logtext = $logtext."OPENWEATHERMAP_API_KEY:		".$this->get_openweathermap_api_key()."\n					";
		$logtext = $logtext."OPENWEATHERMAP_LOCATION:	".$this->get_openweathermap_location()."\n					";
		
		
		//read info for vacation function
		if ($this->lookup_config("VACATION_FUNCTION") == "ON"){
			//vacation ON
			
			$this->set_vacation_start_date($this->lookup_config("VACATION_START_DATE"));
			$this->set_vacation_end_date($this->lookup_config("VACATION_END_DATE"));
			
			if (date("Y-m-d", strtotime($this->get_vacation_end_date())) < date("Y-m-d") ){
		
				//vacation end date reached
				$this->write_config("VACATION_FUNCTION", "OFF");

				$this->set_vacation_start_date("");
				$this->set_vacation_end_date("");
				
				
				//logging
				$logtext = $logtext."VACATION_FUNCTION:		OFF\n";
				
			}elseif (date("Y-m-d", strtotime($this->get_vacation_start_date())) < date("Y-m-d")){

				//logging
				$logtext = $logtext."VACATION_FUNCTION:		ON\n";
				$logtext = $logtext."					VACATION_START_DATE:		".$this->get_vacation_start_date()."\n";
				$logtext = $logtext."					VACATION_END_DATE:		".$this->get_vacation_end_date()."\n";

			}
			
		}else {
			// vacation function OFF
			
			//logging
			$logtext = $logtext."VACATION_FUNCTION:		ON\n";
			$logtext = $logtext."					VACATION_START_DATE:		".$this->get_vacation_start_date()."\n";
			$logtext = $logtext."					VACATION_END_DATE:		".$this->get_vacation_end_date()."\n";
			
			
		}
		
		
		//logging
		$this->write_log($logtext);

		$this->refresh_local_objects();
		
		if ($this->get_vacation_start_date() != ""){
			
			//alle auto-bewässern werte umschalten
			$this->turn_on_all_auto_watering();
			
		}
		
	}
	
	public function refresh_local_objects(){
		
		// logging
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		$db_handler->fetch_all_plants();
		$this->plant_array = $db_handler->get_plants();
		$db_handler->fetch_all_sensorunits();
		$this->sensorunit_array = $db_handler->get_sensorunits();
		$db_handler->disconnect_sql();
		
		
	}
	
	public function turn_on_all_auto_watering(){
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::turn_on_all_auto_watering()\n";
		$this->write_log($logtext);
		
		foreach ($this->plant_array as $plant){
			$plant->set_auto_watering(1);
		}
		
		
	}
	
	/**
	 * puts a plant into database
	 * 
	 * 
	 * @param unknown $sensorunit_id
	 * @param unknown $species_id
	 * @param unknown $nickname
	 * @param unknown $location
	 * @param unknown $is_indoor
	 * @param unknown $auto_watering
	 */
	public function add_plant($sensorunit_id, $species_id, $nickname, $location, $is_indoor, $auto_watering){
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		$status = $db_handler->fetch_sensorunit_status($sensorunit_id);
		
		if ($status != "free"){
			$return_string = 0;
		}else {
			$insert_result = $db_handler->insert_plant($sensorunit_id, $species_id, $nickname, $location, $is_indoor, $auto_watering);
			if ($insert_result !== FALSE){
				if ($nickname != "Fehler"){
					$return_string = " ".$nickname;
				}else {
					$return_string = 1;
				}
					
				$db_handler->update_sensorunit_status($sensorunit_id, "active");
			}else {
				$return_string = 0;				
			}
		}
		$db_handler->disconnect_sql();
		$this->refresh_local_objects();
		
		$plant_id = $db_handler->fetch_last_plant_id();
		$command = "../add_picture_folder.sh ".$plant_id."_".$nickname;
		exec($command);
		
		return $return_string;
	}
	
	public function add_sensor_unit($mac_address, $name){
		
		if (count($this->sensorunit_array)<32){
			
			$db_handler = new DB_Handler();
			$db_handler->connect_sql();
			$mac_error = $db_handler->check_sensorunit_mac_address($mac_address);
			$name_error = $db_handler->check_sensorunit_name($name);
			
			
			if ($name_error == NULL & $mac_error == NULL){
				
				//no error
				$error_return = 1;
				$return_string .= $db_handler->insert_sensor_unit($mac_address, $name);
				
				$this->refresh_local_objects();
			}else{
				
				//error
				$error_return = 0;
			}
			$db_handler->disconnect_sql();
		}else {
			
			//error too much units
			$error_return = 0;
		}
		
		
		return $error_return;
	}
	
	public function delete_plant($plant_id){
		
		// logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::delete_plant(Plant Id: ".$plant_id.")\n";
		$this->write_log($logtext);
		
		$sensorunit_id = $this->plant_array[$plant_id]->get_sensor_unit_id();
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		if ($db_handler->delete_plant($plant_id)){
			$sensor_ids = $db_handler->fetch_sensor_ids_from_sensorunit($sensorunit_id);
			foreach ($sensor_ids as $sensor_id){
				$db_handler->delete_sensor_data($sensor_id);
			}
			$db_handler->update_sensorunit_status($sensorunit_id, "free");
			
		}
		
		$db_handler->disconnect_sql();
	}
	
	/**
	 * checks all plants, if they are indoor or outdoor and if they need water
	 */
	public function check_for_watering(){
		foreach ($this->plant_array as $plant) {
			if($plant->is_indoor() == false){
				//TODO: plant is outdoor, check for weather and watering stuff
			} else {
				//TODO: plant is indoor, no owm rain check (still check for sun though)
			}
		}
	}
	
	/**
	 * turns the pump on to pump WATER_PER_TIME ml of water
	 * 
	 * @param unknown $plant_id
	 */
	public function water($plant_id){
		
		
		
		$sensorunit_id = $this->plant_array[$plant_id];
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::water(Plant Id: ".$plant_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Getting sensorunit_id: ".$sensorunit_id."\n";
		$this->write_log($logtext);
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		$mac_address = $db_handler->fetch_mac_address($sensorunit_id);
		//exec(somthingsomething) mit der mac
		$db_handler->insert_water_usage($plant_id, WATER_PER_TIME);
		$db_handler->disconnect_sql();
		
		
		
	}
	
	public function change_plant_nickname($plant_id,$nickname){
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::change_plant_nickname(Plant Id: ".$plant_id.", Nickname: ".$nickname.")\n";
		$this->write_log($logtext);
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		$db_handler->update_plant_nickname($plant_id, $nickname);
		$db_handler->disconnect_sql();
		
		$this->refresh_local_objects();
	}
	
	public function change_plant_location($plant_id,$location,$is_indoor){
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::change_plant_location(Plant Id: ".$plant_id.", Location: ".$location.", Is Indoor: ".$is_indoor.")\n";
		$this->write_log($logtext);
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		$db_handler->update_plant_location($plant_id, $location,$is_indoor);
		$db_handler->disconnect_sql();
		
		$this->refresh_local_objects();
		
	}
	
	
	/**
	 * sets the new email and writes it to config
	 * 
	 * @param unknown $new_email_address
	 */
	public function change_email_address($new_email_address){
		
		$this->set_notification_receiving_email_address($new_email_address);
		$this->write_config("SEND_MAIL_TO", $new_email_address);
		
	}
	
	public function change_openweathermap_location($new_location){
		
		$this->set_openweathermap_location($new_location);
		$this->write_config("OPENWEATHERMAP_LOCATION", $new_location);
	}
	
	
	/**
	 * request a forecast from openweathermap, if api key is an empty string, it automatically uses 
	 * the default api key
	 * 
	 * the location is read from the config.txt file
	 * 
	 * @return returns the data in an array
	 */
	public function get_openweathermap_data(){
		if ($this->get_openweathermap_api_key() == ""){
			// gets forecast with default api key if the api key value is empty
			$json = file_get_contents('http://api.openweathermap.org/data/2.5/forecast?APPID='.$this->get_default_openweathermap_api_key().'&q='.$this->get_openweathermap_location());
			$data = json_decode($json, true);
		} else {
			//gets forecast with api key
			$json = file_get_contents('http://api.openweathermap.org/data/2.5/forecast?APPID='.$this->get_openweathermap_api_key().'&q='.$this->get_openweathermap_location());		
			echo $json;
			$data = json_decode($json, true);
			if ($data == ""){ //if api doesnt work then use the default one
				$this->set_openweathermap_api_key("");
				echo "\nrequest failed;\ntrying with default key:\n";
				$data = $this->get_openweathermap_data();	
			}
		}
		
		return $data;
	}
	
	

	
	/**
	 * Searches in the confix.txt for the $searchkeyword and returns the value of the config
	 * 
	 * @param unknown $search_keyword
	 * @return string
	 */
	public function lookup_config($search_keyword){
		$config = file(__DIR__.'/../config.txt');
		$substr = "";
		foreach ($config as $key => $line){
			if (strpos($line, $search_keyword." =") !== FALSE){
				$pos = strpos($line, "\"");
				$substr = substr($line, $pos+1);
				$substr = substr($substr, 0, strpos($substr, "\""));
			}
		}
		return $substr;
	}
	
	/**
	 * Writes a value to the config.txt file if there is a line with the keyword it replaces this one
	 * if not it will write a new line at the bottom of the file under ## other settings
	 * 
	 * @param unknown $keyword
	 * @param unknown $value
	 */
	public function write_config($keyword, $value){
		$config = file(__DIR__.'/../config.txt');
		$substr = "";
		$changed = FALSE;
		foreach ($config as $key => $line){
			if (strpos($line, $keyword." =") !== FALSE){
				$config[$key] = $keyword." = \""."$value"."\"\n";
				$changed = TRUE;	
			}
		}
		if (!$changed){
			$config[count($config)+1] = $keyword." = \""."$value"."\"\n";
		}
		file_put_contents(__DIR__.'/../config.txt', $config);	
	}
	
	
	
	
	/**
	 * 
	 * sends a Mail to the $notification_receiving_email_address
	 * setting this address happens in the init-function so be sure to call it
	 * 
	 */
	public function send_mail($subject, $message){
		
		
		$from = "gartnetzwerg@outlook.de";
		
		$host = "smtp-mail.outlook.com";
		$username = "gartnetzwerg@outlook.de";
		$password = "hellomy4plants";
		
		$headers = array ('From' => $from,
				'To' => $this->get_notification_receiving_email_address(),
				'Subject' => $subject);
		
		$smtp = Mail::factory('smtp',
				array ('host' => $host,
						'auth' => true,
						'username' => $username,
						'password' => $password)
				);
		
				
		
		$mail = $smtp->send($this->get_notification_receiving_email_address(), $headers, $message);
		
		if (PEAR::isError($mail)) {
			echo("<p>" . $mail->getMessage() . "</p>\n");
		} else {
			echo("<p>Message successfully sent!</p>\n");
		}
				
	}
	
	
	/**
	 * sub-function for color_state. 
	 * 
	 * @param takes $min and $max value from the database and compares it to the current $sensor value.$this
	 * @return offset/difference between current value and ideal value
	 * 
	 * TODO: Siehe Diskussion (is alles drin, außer Zeitliche Veränderung, Lichtsensor-Korrektheit und eventuell Icons)
	 */
	public function sensor_offset($sensor, $min, $max, $gewichtung){
		//echo "Sensorvalue: ".$sensor->get_value()." min: ".$min." max: ".$max."\n";
		if($sensor->get_value() - $min < 0){
			return (-1 * $gewichtung);
		} else if($max - $sensor->get_value() < 0){
			return (1 * $gewichtung);
		}
		return 0;
	}
	
	/**
	 * @param takes plant_id and sensor_id
	 * @return text depending on sensor and offset-value
	 */
	public function correction_text($plant, $sensor){
		$offset = 0;
		$prio = 1;

		//TODO: auch hier gehe davon aus dass der Index von sensor_unit array und plant_array die gleiche nummer sind.
		echo $plant.", ". $sensor .", ". get_class($this->get_sensorunit($plant)->get_sensor($sensor)) ."\n";
		
		switch (get_class($this->get_sensorunit($plant)->get_sensor($sensor))) {
			case "Air_temperature_sensor":
				$offset = $this->sensor_offset($this->get_sensorunit($plant)->get_sensor($sensor), $this->plant_array[$plant]->get_min_air_temperature(), $this->plant_array[$plant]->get_max_air_temperature(),$prio);
				if($offset == -1){
					//TODO: get negative offset feedback; later return
					echo "test: es isch zu kalt, tu was\n\n";
				} else if($offset == 1){
					//TODO: get positive offset feedback; later return
					echo "test: hilfe, ich brenne!\n\n";
				}
				break;
				
			case "Air_moisture_sensor":
				$offset = $this->sensor_offset($this->get_sensorunit($plant)->get_sensor($sensor), $this->plant_array[$plant]->get_min_air_humidity(), $this->plant_array[$plant]->get_max_air_humidity(),$prio);
				if($offset == -1){
					//TODO: get negative offset feedback
				} else if($offset == 1){
					//TODO: get positive offset feedback
				}
				break;
				
			case "Light_sensor":
				//TODO: Light_sensor is ein Value, aber Light_hours is eine Zeitspanne, die irgendwie noch gezählt werden muss.
				$offset = $this->sensor_offset($this->get_sensorunit($plant)->get_sensor($sensor), $this->plant_array[$plant]->get_min_light_hours(), $this->plant_array[$plant]->get_max_light_hours(),$prio);
				if($offset == -1){
					//TODO: get negative offset feedback
				} else if($offset == 1){
					//TODO: get positive offset feedback
				}
				break;
				
			case "Soil_humidity_sensor":
				$offset = $this->sensor_offset($this->get_sensorunit($plant)->get_sensor($sensor), $this->plant_array[$plant]->get_min_soil_humidity(), $this->plant_array[$plant]->get_max_soil_humidity(),$prio);
				if($offset == -1){
					//TODO: get negative offset feedback
				} else if($offset == 1){
					//TODO: get positive offset feedback
				}
				break;
				
			case "Soil_temperature_sensor":
				$offset = $this->sensor_offset($this->get_sensorunit($plant)->get_sensor($sensor), $this->plant_array[$plant]->get_min_soil_temperature(), $this->plant_array[$plant]->get_max_soil_temperature(),$prio);
				if($offset == -1){
					//TODO: get negative offset feedback
				} else if($offset == 1){
					//TODO: get positive offset feedback
				}
				break;
				
			default:;break;
		}
	}
	
	/**
	 * @param takes plant_id and sensor_id
	 * @return icon depending on sensor and offset-value
	 */
	public function sensor_icon($plant, $sensor){
		$offset = 0;
		$prio = 1;
		
		//TODO: auch hier gehe davon aus dass der Index von sensor_unit array und plant_array die gleiche nummer sind.
		echo $plant.", ". $sensor .", ". get_class($this->get_sensorunit($plant)->get_sensor($sensor)) ."\n";
		
		switch (get_class($this->get_sensorunit($plant)->get_sensor($sensor))) {
			case "Air_temperature_sensor":
				$offset = $this->sensor_offset($this->get_sensorunit($plant)->get_sensor($sensor), $this->plant_array[$plant]->get_min_air_temperature(), $this->plant_array[$plant]->get_max_air_temperature(),$prio);
				if($offset == -1){
					//TODO: get negative offset feedback; later return
					echo "test: freezing_plant_icon.png\n\n";
				} else if($offset == 1){
					//TODO: get positive offset feedback; later return
					echo "test: buring_plant_icon.png\n\n";
				}
				break;
				
			case "Air_moisture_sensor":
				$offset = $this->sensor_offset($this->get_sensorunit($plant)->get_sensor($sensor), $this->plant_array[$plant]->get_min_air_humidity(), $this->plant_array[$plant]->get_max_air_humidity(),$prio);
				if($offset == -1){
					//TODO: get negative offset feedback
				} else if($offset == 1){
					//TODO: get positive offset feedback
				}
				break;
				
			case "Light_sensor":
				//TODO: Light_sensor is ein Value, aber Light_hours is eine Zeitspanne, die irgendwie noch gezählt werden muss.
				$offset = $this->sensor_offset($this->get_sensorunit($plant)->get_sensor($sensor), $this->plant_array[$plant]->get_min_light_hours(), $this->plant_array[$plant]->get_max_light_hours(),$prio);
				if($offset == -1){
					//TODO: get negative offset feedback
				} else if($offset == 1){
					//TODO: get positive offset feedback
				}
				break;
				
			case "Soil_humidity_sensor":
				$offset = $this->sensor_offset($this->get_sensorunit($plant)->get_sensor($sensor), $this->plant_array[$plant]->get_min_soil_humidity(), $this->plant_array[$plant]->get_max_soil_humidity(),$prio);
				if($offset == -1){
					//TODO: get negative offset feedback
				} else if($offset == 1){
					//TODO: get positive offset feedback
				}
				break;
				
			case "Soil_temperature_sensor":
				$offset = $this->sensor_offset($this->get_sensorunit($plant)->get_sensor($sensor), $this->plant_array[$plant]->get_min_soil_temperature(), $this->plant_array[$plant]->get_max_soil_temperature(),$prio);
				if($offset == -1){
					//TODO: get negative offset feedback
				} else if($offset == 1){
					//TODO: get positive offset feedback
				}
				break;
				
			default:;break;
		}
	}
	
	/**
	 * @param takes plant_id + 5 priorities for (in this order): air_temp, air_hum, light, soil_hum, soil_temp
	 * @return color as string, for later CSS;
	 */
	public function color_state($plant_id, $g_at, $g_ah, $g_l, $g_sh, $g_st){
		$color_value = 0;
		$su_id = $this->get_plant($plant_id)->get_sensor_unit_id();

		$min_air_temperature = $this->get_plant($plant_id)->get_min_air_temperature();
		$min_air_humidity = $this->get_plant($plant_id)->get_min_air_humidity();
		$min_soil_temperature = $this->get_plant($plant_id)->get_min_soil_temperature();
		$min_light_hours = $this->get_plant($plant_id)->get_min_light_hours();
		$min_soil_humidity = $this->get_plant($plant_id)->get_min_soil_humidity();
		$max_air_temperature = $this->get_plant($plant_id)->get_max_air_temperature();
		$max_air_humidity = $this->get_plant($plant_id)->get_max_air_humidity();
		$max_soil_temperature = $this->get_plant($plant_id)->get_max_soil_temperature();
		$max_light_hours = $this->get_plant($plant_id)->get_max_light_hours();
		$max_soil_humidity = $this->get_plant($plant_id)->get_max_soil_humidity();
		
		foreach($this->get_sensorunit($su_id)->get_array() as $key => $value){
			switch (get_class($value)) {
				case "Air_temperature_sensor": 
					$color_value += abs($this->sensor_offset($value, $min_air_temperature, $max_air_temperature, $g_at)); break;
				case "Air_moisture_sensor": 
					$color_value += abs($this->sensor_offset($value, $min_air_humidity, $max_air_humidity,$g_ah)); break;
				case "Light_sensor":
					$color_value += abs($this->sensor_offset($value, $min_light_hours, $max_light_hours,$g_l)); break;
				case "Soil_humidity_sensor":
					$color_value += abs($this->sensor_offset($value, $min_soil_humidity, $max_soil_humidity,$g_sh)); break;
				case "Soil_temperature_sensor":
					$color_value += abs($this->sensor_offset($value, $min_soil_temperature, $max_soil_temperature, $g_st)); break;
				default:break;
			}
		}
	
		if($color_value >= 3) return "black"; else if($color_value >= 2) return "red";
		else if($color_value >= 1) return "orange"; else if($color_value >= 0.5) return "yellow"; else return "green";
	}
	
	/**
	 * return the sum of used water during $days from plant with $plant_id
	 * @param  $plant_id
	 * @param  $days
	 * @return $water_usage_sum
	 */
	public function sum_water_usage($plant_id, $days){
		

		$date = new DateTime("-".$days." days");
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."Controller::sum_water_usage(Plant Id: ".$plant_id.", days: ".$days.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Datum: ".$date."\n";
		
		$this->write_log($logtext);
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		$water_usage_sum = $db_handler->sum_water_usage($plant_id,$date->format("Y-m-d"));
		$db_handler->disconnect_sql();
		$unit = "ml";
		
		if($water_usage_sum >= 1000){
			
			$water_usage_sum = $water_usage_sum/1000;
			$unit = "L";
			
		}
		
		
		return $water_usage_sum.$unit;
	}
	
	public function water_usage_per_day($plant_id, $days){
		
		//  Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::water_usage_per_day(Plant ID: ".$plant_id.", Days: ".$days.")\n";
		$this->write_log($logtext);
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		
		for ($i = $days-1; $i >= 0; $i--){
			$date = new DateTime("-".$i." days");
			$water_usage_per_day[$date->format("Y-m-d")] = $db_handler->water_usage_on_day($plant_id,$date->format("Y-m-d"));
			
		}
		
		
		$db_handler->disconnect_sql();
		$logtext = date(LOG_TIME_FORMAT)."	End	Controller::water_usage_per_day\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Result: ".$water_usage_per_day[$date->format("Y-m-d")]."\n";
		
		
		return $water_usage_per_day;
		
	}
	
	
	//messwerte
	
	/**
	 * takes data from the DB, counts the values of hours of light per day
	 * @returns hours of light in an array indexed on days, 
	 */
	public function lighthours_per_day($sensor_unit_id, $days){
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		
		$light_hours_per_day = [];
		for ($i = $days-1; $i >= 0; $i--){
			$date = new DateTime("-".$i." days");
			$light_hours_per_day[$date->format("Y-m-d")] = $db_handler->fetch_light_hours($sensor_unit_id, $date->format("Y-m-d"));	
		}
		
		
		$db_handler->disconnect_sql();
		
		
		
		return $light_hours_per_day;
		
		
		
	}

	public function air_humidity_per_day($sensor_unit_id, $days){
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		
		$air_humidity = [];
		for ($i = $days-1; $i >= 0; $i--){
			$date = new DateTime("-".$i." days");
			$air_humidity[$date->format("Y-m-d")] = $db_handler->fetch_air_humidity($sensor_unit_id, $date->format("Y-m-d"));
		}
		
		$db_handler->disconnect_sql();
		
		return $air_humidity;
	}
	
	public function soil_humidity_per_day($sensor_unit_id, $days){
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		
		$soil_humidity = [];
		for ($i = $days-1; $i >= 0; $i--){
			$date = new DateTime("-".$i." days");
			$soil_humidity[$date->format("Y-m-d")] = $db_handler->fetch_soil_humidity($sensor_unit_id, $date->format("Y-m-d"));
		}
		
		$db_handler->disconnect_sql();
		
		return $soil_humidity;
	}
	
	public function air_temperature_per_day($sensor_unit_id, $days){
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		
		$air_temperature = [];
		for ($i = $days-1; $i >= 0; $i--){
			$date = new DateTime("-".$i." days");
			$air_temperature[$date->format("Y-m-d")] = $db_handler->fetch_air_temperature($sensor_unit_id, $date->format("Y-m-d"));
		}
		
		$db_handler->disconnect_sql();
		
		return $air_temperature;
	}
	
	public function soil_temperature_per_day($sensor_unit_id, $days){
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		
		$soil_temperature = [];
		for ($i = $days-1; $i >= 0; $i--){
			$date = new DateTime("-".$i." days");
			$soil_temperature[$date->format("Y-m-d")] = $db_handler->fetch_soil_temperature($sensor_unit_id, $date->format("Y-m-d"));
		}
		
		$db_handler->disconnect_sql();
		
		return $soil_temperature;
	}
	
	public function waterlogging_per_day($sensor_unit_id, $days){
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		
		$waterlogging = [];
		for ($i = $days-1; $i >= 0; $i--){
			$date = new DateTime("-".$i." days");
			$waterlogging[$date->format("Y-m-d")] = $db_handler->fetch_waterlogging($sensor_unit_id, $date->format("Y-m-d"));
		}
		
		$db_handler->disconnect_sql();
		
		return $waterlogging;
	}
	
	
	
	/**
	 * updates all sensors then writes data to database
	 * 
	 * 
	 * 
	 * @param unknown $manual if this flag is set this means that the sensor data wont be used for diagrams, set this flag if the meassurement got triggered manually and not by cron
	 * 
	 */
	public function update_sensor_data($manual){
		//TODO damit das hier funktioniert muss das updaten der sensorwerte funktionieren 
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		foreach ($this->sensorunit_array as $sensorunit_id => $sensorunit){
			$this->sensorunit_array[$sensorunit_id]->update_all();
			$sensor_ids = $this->sensorunit_array[$sensorunit_id]->get_sensor_ids();
			foreach ($sensor_ids as $sensor_id){
				$value = $this->sensorunit_array[$sensorunit_id]->get_sensor($sensor_id)->get_value();
				
				$db_handler->insert_sensor_data($sensor_id, $value, $manual);
				
			}
			
		}
		$db_handler->disconnect_sql();
		$this->refresh_local_objects();
	}
	
	public function test(){

	}

	
	//logging functions
	
	public function write_log($logtext){
		
		if (CONTROLLER_LOGGING){
			$logfile = fopen("/var/log/gartnetzwerg/gartnetzwerg_log.".date('W'), "a");
			
			fwrite($logfile, $logtext);
			
			fclose($logfile);
		}
	
	}
}
$test = new Controller();

?>