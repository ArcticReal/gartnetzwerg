<?php
require_once 'sensorunit.php';
require_once 'plant.php';
require_once 'db_handler.php';
//require_once 'Mail.php'; [Bratrich] hat bei mir bei meinem Test rumgefucked; Mail.php oder PEAR_mail.php?
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
	
	//functions
	
	/**
	 * Always execute this after restarting the script
	 */
	public function init(){
		//read config file 
		$this->set_notification_receiving_email_address($this->lookup_config("SEND_MAIL_TO"));
	
		//read openweathermap info
		$this->set_openweathermap_api_key($this->lookup_config("OPENWEATHERMAP_API_KEY"));
		$this->set_openweathermap_location($this->lookup_config("OPENWEATHERMAP_LOCATION"));
		//read info for vacation function
		if ($this->lookup_config("VACATION_FUNCTION" == "ON")){
			$this->set_vacation_start_date($this->lookup_config("VACATION_START_DATE"));
			$this->set_vacation_end_date($this->lookup_config("VACATION_END_DATE"));
		}else {
			$this->set_vacation_start_date("");
			$this->set_vacation_end_date("");
		}
			
		
		//$this->write_config("SEND_MAIL_TO", "test@test.com");
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		$db_handler->fetch_all_plants();
		$this->plant_array = $db_handler->get_plants();
		$db_handler->fetch_all_sensorunits();
		$this->sensorunit_array = $db_handler->get_sensorunits();
		$db_handler->disconnect_sql();
	}
	
	
	public function add_sensor_unit($mac_address, $name){
		
		// TODO pj: ich bau das noch in add_plant($data_array)
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		$db_handler->insert_sensor_unit($mac_address, $name);
		$db_handler->disconnect_sql();
		
	}
	
	/**
	 * checks all plants, if they are indoor or outdoor and 
	 */
	public function check_plants(){
		foreach ($this->plant_array as $plant) {
			if($plant->is_indoor() == false){
				//TODO: plant is outdoor, check for weather and watering stuff
			} else {
				//TODO: plant is indoor, no owm rain check (still check for sun though)
			}
		}
	}
	
	public function change_plant_nickname($plant_id,$nickname){
		
		// TODO Logging
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		$db_handler->update_plant_nickname($plant_id, $nickname);
		$db_handler->disconnect_sql();
		
	}
	
	public function change_plant_location($plant_id,$location,$is_indoor){
		
		// TODO Logging
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		$db_handler->update_plant_location($plant_id, $location,$is_indoor);
		$db_handler->disconnect_sql();
		
	}
	
	public function insert_sensor_data(){
		
		// TODO
		
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
		$config = file('__FILE__/../config.txt');
		$substr = "";
	//	var_dump($config);
	//	echo "\nLooking up ".$search_keyword.": \n";
		foreach ($config as $key => $line){
			if (strpos($line, $search_keyword." =") !== FALSE){
			//	echo "\t".$search_keyword." found in line: ".$key."\n";
				$pos = strpos($line, "\"");
				$substr = substr($line, $pos+1);
				$substr = substr($substr, 0, strpos($substr, "\""));
			//	echo "\tValue: ".$substr."\n\n";
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
		$config = file('__FILE__/../config.txt');
		$substr = "";
		$changed = FALSE;
	//	echo "searching for ".$keyword.":\n";
		foreach ($config as $key => $line){
			if (strpos($line, $keyword." =") !== FALSE){
			//	echo "\t".$keyword." found in line: ".$key."\n";
				$config[$key] = $keyword." = \""."$value"."\"\n";
				$changed = TRUE;	
			}
		}
		if (!$changed){
			$config[count($config)+1] = $keyword." = \""."$value"."\"\n";
		}
		file_put_contents('__FILE__/../config.txt', $config);	
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
		echo "Sensorvalue: ".$sensor->get_value()." min: ".$min." max: ".$max."\n";
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
	 * @param takes 5 priorities for (in this order): air_temp, air_hum, light, soil_hum, soil_temp
	 * @return color as string, for later CSS;
	 * 
	 */
	public function color_state($g_at,$g_ah,$g_l,$g_sh,$g_st){
		$color_value = 0;
				
		//TODO: woher weiß ich welche sensorunit was ist?
		//ich geh einfach mal davon aus, dass sensorunit[0] sozusagen zu plant[0] gehört
		//pj: nein Alex in der tabelle plants steht die sensorunit_id drin und nach dem erstellen 
		// der plant objekte, hat die plant ein Attribut $sensor_unit_id
		//EDIT: ich geh mal davon aus, das läuft über den DB_Handler oder es fehlt noch ne plant_id in der Unit (oder andersrum);
		
		foreach($this->plant_array as $key => $value){
			foreach($this->get_sensorunit($key)->get_array() as $key2 => $value2){
				echo $key .", ". $key2 .", ". get_class($value2) ."\n";
				
				switch (get_class($value2)) {
					case "Air_temperature_sensor": 
						echo $g_at." ";
						$color_value += abs($this->sensor_offset($value2, $value->get_min_air_temperature(), $value->get_max_air_temperature(),$g_at));
						break;
						
					case "Air_moisture_sensor": 
						echo $g_ah." ";
						$color_value += abs($this->sensor_offset($value2, $value->get_min_air_humidity(), $value->get_max_air_humidity(),$g_ah));
						break;
						
					case "Light_sensor":
						//TODO: Light_sensor is ein Value, aber Light_hours is eine Zeitspanne, die irgendwie noch gezählt werden muss.
						echo $g_l." ";
						$color_value += abs($this->sensor_offset($value2, $value->get_min_light_hours(), $value->get_max_light_hours(),$g_l));
						break;
						
					case "Soil_humidity_sensor":
						echo $g_sh." ";
						$color_value += abs($this->sensor_offset($value2, $value->get_min_soil_humidity(), $value->get_max_soil_humidity(),$g_sh));
						break;
						
					case "Soil_temperature_sensor":
						echo $g_st." ";
						$color_value += abs($this->sensor_offset($value2, $value->get_min_soil_temperature(), $value->get_max_soil_temperature(),$g_st));
						break;
						
					default:;break;
				}
			}
		}
		
		echo "\n".$color_value." ";
		
		if($color_value >= 3){
			echo "red\n\n";
			return "red";
		} else if($color_value >= 2){
			echo "orange\n\n";
			return "orange";
		} else if($color_value >= 1){
			echo "yellow\n\n";
			return "yellow";
		} else if($color_value >= 0.5){
			echo "green\n\n";
			return "green";
		} else {
			echo "gold\n\n";
			return "gold";
		}
	}
	
	/**
	 * return the sum of used water during $days from plant with $plant_id
	 * @param  $plant_id
	 * @param  $days
	 * @return $water_usage_sum
	 */
	public function sum_water_usage($plant_id, $days){
		
		// TODO Logging
		
		$day = date('d');
		$month = date('m');
		$year = date('Y');
		
		while($days > date("t",$month)){
			
			if($month == 1){
				
				$days = $days-date("t",$month);
				$month = 12;
				$year--;
				
			}else{
				
				$days = $days-date("t",$month);
				$month--;
				
			}
			
		}
		
		if($days < date('d')){
			
			$day = $day-$days;
			
		}else{
			
			if(date('m') > 1){
				
				$month--;
				$tmp = date("t",$month);
				$days = $days-$day;
				$day = $tmp-$days;
				
			}else{
				
				$year--;
				$month = 12;
				$tmp = date("t",$month);
				$days = $days-$day;
				$day = $tmp-$days;
				
			}
		}
		
		$date = $year."-".$month."-".$day;
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		$water_usage_sum = $db_handler->sum_water_usage($plant_id,$date);
		$db_handler->disconnect_sql();
		$unit = "ml";
		
		if($water_usage_sum >= 1000){
			
			$water_usage_sum = $water_usage_sum/1000;
			$unit = "L";
			
		}
		
		
		return $water_usage_sum.$unit;
	}
	
	/**
	 * takes data from the DB, counts the values of hours of light per day
	 * @returns hours of light, TODO: kommt noch auf Lichttests (mit den anderen Zeros) an, wie hoch die Sensoren bei Volllicht/Halbschatten/Dunkel reagieren
	 */
	public function count_lighthours($sensor_unit_id){
		$count = 0;
				
		//fetch_akt_light_hours($sensor_unit_id);
		
		echo $count . "\n";
		return $count;
	}
	
/*	public function make_time_lapse(){
		
	}
	
	public function calculate_water_level(){
		
	}
	
	public function open_sensorunit_connection(){
		
	}
	
	public function close_sensorunit_connection(){
		
	}
*/
}

/*$test2 = new Controller();
$test2->init();
var_dump($test2->get_sensorunit(1));
var_dump($test2->get_sensorunit(2));
$test2->get_openweathermap_data('Kempten');*/

?>