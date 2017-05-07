<?php
require_once 'sensorunit.php';
require_once 'Mail.php';

class Controller{
	private $sensorunit_array;
	private $plant_array;
	private $curent_timestamp;		// HH:MM:SS-DD-MM-YYYY
	private $openweathermap_api_key;
	private $default_openweathermap_api_key = "cd91b0f34b0fd55a44899fa358743139";
	private $notification_receiving_email_address;
	
	private $value;
	
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
	
	//getters:
	
	public function get_sensorunit($sensorunitid){
		return $this->sensorunit_array[$sensorunitid];
	}
	
	public function get_plant($plant_id){
		return $this->plant_array[$plant_id];
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
	
	//openweathermap functions
	
	public function open_openweathermap_connection(){
		//TODO: könnte eigentlich gelöscht werden, muss aber noch in den Diagrammen geändert werden
	}
	
	/**
	 * request a forecast from openweathermap, if api key is an empty string, it automatically uses 
	 * the default api key
	 * 
	 * @param $location this is the location the forecast will be for
	 * 
	 * @return returns the data in an array
	 */
	public function get_openweathermap_data($location){
		if ($this->get_openweathermap_api_key() == ""){
			// gets forecast with default api key if the api key value is empty
			$json = file_get_contents('http://api.openweathermap.org/data/2.5/forecast?APPID='.$this->get_default_openweathermap_api_key().'&q='.$location);
			$data = json_decode($json, true);
		} else {
			//gets forecast with api key
			$json = file_get_contents('http://api.openweathermap.org/data/2.5/forecast?APPID='.$this->get_openweathermap_api_key().'&q='.$location);		
			echo $json;
			$data = json_decode($json, true);
			if ($data == ""){ //if api doesnt work then use the default one
				$this->set_openweathermap_api_key("");
				$data = $this->get_openweathermap_data($location);	
			}
		}
		
		return $data;
	}
	
	public function close_openweathermap_connection(){
		//TODO: könnte eigentlich gelöscht werden, muss aber noch in den Diagrammen geändert werden
	}
	
	/**
	 * Always execute this after restarting the script
	 */
	public function init(){
		$this->set_openweathermap_api_key($this->lookup_config("OPENWEATHERMAP_API_KEY"));
		$this->set_notification_receiving_email_address($this->lookup_config("SEND_MAIL_TO"));
	}
	
	/**
	 * Searches in the confix.txt for the $searchkeyword and returns the value of the config
	 * 
	 * @param unknown $search_keyword
	 * @return string
	 */
	public function lookup_config($search_keyword){
		$config = file("../config.txt");
		//var_dump($config);
		$substr = "";
		echo "\nLooking up ".$search_keyword.": \n";
		foreach ($config as $key => $line){
			if (strpos($line, $search_keyword) !== FALSE){
				echo "\t".$search_keyword." found in line: ".$key."\n";
				$pos = strpos($line, "\"");
				$substr = substr($line, $pos+1);
				$substr = substr($substr, 0, strpos($substr, "\""));
				echo "\tValue: ".$substr."\n\n";
			}
		}
		return $substr;
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
	 * TODO: je nach dem was die Diskussion in Freedcamp jetz ergibt,
	 *       muss hier vielleicht noch eine Sensorgewichtung rein (wie von mir beschrieben).
	 *       (wobei ich meine alte/Horn's Idee besser finde, was aber die ganze Funktion unnötigt macht...)
	 */
	public function sensor_offset($sensor, $min, $max){
		if($sensor->get_value() - $min < 0){
			return -1;
		} else if($max - $sensor->get_value() < 0){
			return 1;
		}
		return 0;
	}
	
	/**
	 * 
	 */
	public function color_state(){
		$this->value = 0;
		
		//TODO: woher weiß ich welcher Sensor und welche sensorunit was ist?
		//ich geh einfach mal davon aus, dass sensorunit[0] sozusagen zu plant[0] gehört; brauch da Bestätigung, Pierre 
		foreach($this->plant_array as $index => $plant ) {
			//Lufttemperatur
			$this->value += abs($this->sensor_offset($this->sensorunit_array[$index]->$sensor_array[0], 
					$plant->get_min_temperature(), $plant->get_max_temperature()));
			//Luftfeuchtigkeit
			$this->value += abs($this->sensor_offset($this->sensorunit_array[$index]->$sensor_array[1],
					$plant->get_min_air_humidity(), $plant->get_max_air_humidity()));
			//Lichtsensor
			$this->value += abs($this->sensor_offset($this->sensorunit_array[$index]->$sensor_array[2],
					$plant->get_min_light_hours(), $plant->get_max_light_hours()));
			//Bodenfeuchtigkeit
			$this->value += abs($this->sensor_offset($this->sensorunit_array[$index]->$sensor_array[3],
					$plant->get_min_soil_humidity(), $plant->get_max_soil_humidity()));
			//Bodentemperatur
			$this->value += abs($this->sensor_offset($this->sensorunit_array[$index]->$sensor_array[4],
					$plant->get_min_soil_temperature(), $plant->get_max_soil_temperature()));
		}
		
		if($this->value>= 0.5){
			return "green";
		} else if($this->value>= 1){
			return "yellow";
		} else if($this->value>= 2){
			return "orange";
		} else if($this->value>= 3){
			return "red";
		} else {
			return "gold";
			//TODO: return oder echo?
		}
	}
	
/*	public function make_time_laps(){
		
	}
	
	public function calculate_water_level(){
		
	}
	
	public function open_sensorunit_connection(){
		
	}
	
	public function close_sensorunit_connection(){
		
	}
*/
}
$test2 = new Controller();
$test2->init();
$test2->get_openweathermap_data('Kempten');
?>