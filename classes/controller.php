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
	
	public function color_state(){
		$this->value = 0;
		//sucht sich werte der sensoren zusammen (aus plant.php?)
		
		//berechnet abweichung (mit den soll-werten über db_handler.php?)
		//a1 = soll1 - akt1;
		//a2 = soll2 - akt2;
		//a3 = soll3 - akt3;
		//a4 = soll4 - akt4;
		//a5 = soll5 - akt5;
		
		//multipliziert sensor-werte mit sensor-gewichtung (die, theoretisch irgendwo in der Pflanze noch gespeichert werden müsste
		//m1 = a1 * g1;
		//m2 = a2 * g2;
		//m3 = a3 * g3;
		//m4 = a4 * g4;
		//m5 = a5* g5;
		
		//addiert mächtigkeiten
		//$this->value = m1 + m2 + m3 + m4 + m5;
		
		if($this->value>= 0.5){
			//return grün
		} else if($this->value>= 1){
			//return gelb
		} else if($this->value>= 2){
			//return orange
		} else if($this->value>= 3){
			//return rot
		} else {
			//return gold
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