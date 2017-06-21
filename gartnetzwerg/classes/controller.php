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
	private $general_notification_settings;
	
	
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
	
	public function set_general_notification_settings($new_settings){
		$this->general_notification_settings = $new_settings;
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
	
	public function get_general_notification_settings(){
		return $this->general_notification_settings;
	}

	
	/**
	 * TODO: eingabeüberprüfung
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
		
		// logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Cntroller::get_all_species()\n";
		$this->write_log($logtext);
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
	
	public function get_last_sensor_update($plant_id){
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::get_last_sensor_update(Plant Id: ".$plant_id.")\n";
		$this->write_log($logtext);
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		
		$last_date = $db_handler->fetch_last_data_update($this->plant_array[$plant_id]->get_sensor_unit_id());
		
		$db_handler->disconnect_sql();
		
		return $last_date;
	}
	
	public function get_picture_array($plant_id){
		
		$nickname = $this->eliminate_whitespace($this->plant_array[$plant_id]->get_nickname());
		
		$cmd = "ls /var/www/html/gartnetzwerg/Pictures/".$plant_id."_".$nickname."/";
		$picture_array = explode("\n", shell_exec($cmd));
		
		$picture_array = array_reverse($picture_array);
		
		for($i = 0;$i < count($picture_array)-1; $i++){
			$picture_array[$i] = $picture_array[$i+1];
		}
		
		array_pop($picture_array);
		
		return $picture_array;
		
	}
	
	/**
	 * Always execute this after restarting the script
	 */
	public function init(){
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::init()\n";
		
		
		//read notification settings
		$this->set_general_notification_settings($this->lookup_config("SEND_NOTIFICATIONS"));
		if ($this->get_general_notification_settings() == "ON"){
			$this->set_notification_receiving_email_address($this->lookup_config("SEND_MAIL_TO"));
		}else{
			$this->set_notification_receiving_email_address("");
		}
		//read openweathermap info
		$this->set_openweathermap_api_key($this->lookup_config("OPENWEATHERMAP_API_KEY"));
		$this->set_openweathermap_location($this->lookup_config("OPENWEATHERMAP_LOCATION"));

		//logging
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Lookups: \n					";
		$logtext = $logtext."NOTFICATIONS:			".$this->get_general_notification_settings()."\n					";
		if ($this->get_general_notification_settings() == "ON"){
			$logtext = $logtext."SEND_MAIL_TO:			".$this->get_notification_receiving_email_address()."\n					";
		}
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
			$logtext = $logtext."VACATION_FUNCTION:		OFF\n";
			
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
					
				$return_string = " ".$nickname;

				$plant_id = $db_handler->fetch_last_plant_id();
				$folder_name = $plant_id."_".($this->eliminate_whitespace($nickname));
				
				$command = "mkdir /var/www/html/gartnetzwerg/Pictures/".$folder_name;
				
				shell_exec($command);
				
				
				$db_handler->update_sensorunit_status($sensorunit_id, "active");
			}else {
				$return_string = 0;				
			}
		}
				
		$db_handler->disconnect_sql();
		$this->refresh_local_objects();
		
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
				$error_return = $db_handler->insert_sensor_unit($mac_address, $name);
				
				$this->refresh_local_objects();
			}else{
				
				//error
				$error_return = -1;
			}
			$db_handler->disconnect_sql();
		}else {
			
			//error too much units
			$error_return = -1;
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
			$nickname = $this->plant_array[$plant_id]->get_nickname();
			$cmd = "rm -r '/var/www/html/gartnetzwerg/Pictures/".$plant_id."_".$nickname."'";
			shell_exec($cmd);
			
		}
		
		$db_handler->disconnect_sql();
		$this->refresh_local_objects();
	}
	
	public function delete_sensorunit($sensorunit_id){
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::delete_sensorunit(Sensorunit Id: ".$sensorunit_id.")\n";
		$this->write_log($logtext);
		
		$return = 0;
		if ($this->sensorunit_array[$sensorunit_id]->get_status() == "free"){
			$db_handler = new DB_Handler();
			$db_handler->connect_sql();
			
			if ($db_handler->delete_sensors($sensorunit_id)){
				$return = $db_handler->delete_sensor_unit($sensorunit_id);
				
			}else {
				$return = 0;
			}
			
			
			$db_handler->disconnect_sql();
		}
		else {
			$return = 0;
			
		}
		
		return $return;
	}
	
	/**
	 * checks all plants, if they are indoor or outdoor and if they need water
	 */
	public function check_for_watering(){
		
		$data = $test->get_openweathermap_data();
		$data = $data["list"];
		$rain = 0.0;
		foreach ($data as $i => $data_entry){
			/*echo "\n".$data["list"][$i]["dt_txt"]."\n";
			echo "	".$data["list"][$i]["weather"][0]["description"]."\n";*/
			if (count($data_entry) > 7){
			
				if (count($data_entry["rain"]) > 0){
					$rain += $data_entry["rain"]["3h"];
					//echo "	Rain: ".$data["list"][$i]["rain"]["3h"]."\n";
				}
				
				
			}
		}
		
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		foreach ($this->plant_array as $plant_id => $plant) {

			$intervall_max = $plant->get_max_watering_period();
			$intervall_min = $plant->get_min_watering_period();
			$last_watering = date("Y-m-d", $db_handler->fetch_last_watering($plant_id));
			
			$max_date = new DateTime("-".$intervall_max."days");
			$min_date = new DateTime("-".$intervall_min."days");
			if ($max_date->format("Y-m-d") < $last_watering){
				//max_watering_period_reached
				if ($plant->get_is_indoor() == 1){
					//plant is indoor
					
					if ($plant->get_auto_watering() == 1){
						//autowatering on
						$this->water($plant_id);
					}
					else {
						//auto watering off
						// TODO auf notification checken und dementsprechende mail schreiben
						$this->send_notification($plant_id, "needs_water");
					}
				}else {
					//plant is outdoor
					if ($rain < 1){
						if ($plant->get_auto_watering() == 1){
							//autowatering on
							$this->water($plant_id);
						}
						else {
							//auto watering off
							// TODO auf notification checken und dementsprechende mail schreiben
							$this->send_notification($plant_id, "needs_watering");
						}
					}
				}
			}elseif ($min_date->format("Y-m-d") < $last_watering) {
				//min_watering_period_reached
				
				//check for sensors
				$akt_humidity = $db_handler->fetch_akt_soil_humidity($plant->get_sensor_unit_id());
				$min_humidity = $plant->get_min_soil_humidity();
				if ($akt_humidity < $min_humidity){
					//max_patering_period reached but sensor says plant needs water
					
					if ($plant->get_is_indoor() == 1){
						//plant is indoor
						
						if ($plant->get_auto_watering() == 1){
							//autowatering on
							$this->water($plant_id);
						}
						else {
							//auto watering off
							// TODO auf notification checken und dementsprechende mail schreiben
							$this->send_notification($plant_id, "needs_watering");
						}
					}else {
						//plant is outdoor
						if ($rain < 1){
							if ($plant->get_auto_watering() == 1){
								//autowatering on
								$this->water($plant_id);
							}
							else {
								//auto watering off
								// TODO auf notification checken und dementsprechende mail schreiben
								$this->send_notification($plant_id, "needs_watering");
							}
						}
					}
				}
			}
		}
		
		$db_handler->disconnect_sql();
	}
	
	/**
	 * turns the pump on to pump WATER_PER_TIME ml of water
	 * 
	 * @param unknown $plant_id
	 */
	public function water($plant_id){
		
		
		
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::water(Plant Id: ".$plant_id.")\n";
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Getting sensorunit_id: ".$sensorunit_id."\n";
		$this->write_log($logtext);
		
		$sensorunit_id = $this->plant_array[$plant_id]->get_sensor_unit_id();

		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		$mac_address = $db_handler->fetch_mac_address($sensorunit_id);
		
		//gets ip
		$cmd = "sudo /var/www/html/gartnetzwerg/get_ip_address.sh ".$mac_address;
		$ip = shell_exec($cmd);
		//calls water.py on raspy zero
		$path = "/home/pi/gartnetzwerg/water.py";
		$cmd = "sudo /var/www/html/gartnetzwerg/water.sh ".$ip." ".$path;
		shell_exec($cmd);
		
		$db_handler->insert_water_usage($plant_id, WATER_PER_TIME);
		$db_handler->disconnect_sql();
		
		
		
	}
	
	public function change_plant_nickname($plant_id,$nickname){
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::change_plant_nickname(Plant Id: ".$plant_id.", Nickname: ".$nickname.")\n";
		$this->write_log($logtext);
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		$result = $db_handler->update_plant_nickname($plant_id, $nickname);
		$db_handler->disconnect_sql();
		
		if ($result != 0){
			$nickname = $this->eliminate_whitespace($nickname);
			$old_nickname = $this->eliminate_whitespace($this->plant_array[$plant_id]->get_nickname());
			
	
			//Bilder-Ordner umbenennen
			echo $cmd = "mv /var/www/html/gartnetzwerg/Pictures/".$plant_id."_".$old_nickname." /home/pi/Pictures/".$plant_id."_".$nickname;
			shell_exec($cmd);
			
			echo $cmd = "mv /var/www/html/gartnetzwerg/Gifs/".$plant_id."_".$old_nickname.".gif /home/pi/Pictures/".$plant_id."_".$nickname.".gif";
			shell_exec($cmd);
			
			$this->refresh_local_objects();
			
		}
		
	}
	
	public function change_plant_location($plant_id,$location,$is_indoor){
		
		// Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::change_plant_location(Plant Id: ".$plant_id.", Location: ".$location.", Is Indoor: ".$is_indoor.")\n";
		$this->write_log($logtext);
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		$db_handler->update_plant_location($plant_id, $location, $is_indoor);
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
	
	public function change_openweathermap_api_key($new_api_key){

		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::change_openweathermap_api_key(New Key; ".$new_api_key.")\n";
		$this->write_log($logtext);
		
		$this->set_openweathermap_api_key($new_api_key);
		$this->write_config("OPENWEATHERMAP_API_KEY", $new_api_key);
	}
	
	/**
	 * 
	 * @param unknown $new_settings has to be "ON" or "OFF"
	 */
	public function change_general_notification_settings($new_settings){
		
		if ($new_settings == "OFF"){
			$this->set_notification_receiving_email_address("");
		}
		$this->set_general_notification_settings($new_settings);
		$this->write_config("SEND_NOTIFICATIONS", $new_settings);
		
	}
	

	public function change_plant_notfication_settings($plant_id, $new_settings){
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::change_plant_notification_settings(Plant Id: ".$plant_id.", New Settings: ".$new_settings.")\n";
		$this->write_log($logtext);
		
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		
		$result = $db_handler->update_notification_settings($plant_id, $new_settings);
		
		$db_handler->disconnect_sql();
		
		if ($result == NULL){
			$result = 0;
		}else {
			$this->get_plant($plant_id)->set_notification_settings($new_settings);
		}

		return $result;
		
		
	}
	
	/**
	 * 
	 * @param unknown $plant_id
	 * @param unknown $new_auto_watering 1 for on or 0 for off
	 */
	public function change_auto_watering($plant_id, $new_auto_watering){
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::change_auto_watering(Plant Id: ".$plant_id.")\n";
		$this->write_log($logtext);
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		$result = $db_handler->update_auto_watering($plant_id, $new_auto_watering);
		$db_handler->disconnect_sql();
		
		return $result;
		
	}
	
	public function turn_on_vacation($new_vacation_start, $new_vacation_end){
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::turn_on_vacation(New Start: ".$new_vacation_start.", New End: ".$new_vacation_end.")\n";
		$this->write_log($logtext);
		
		$this->write_config("VACATION_FUNCTION", "ON");
		$this->write_config("VACATION_START_DATE", $new_vacation_start);
		$this->write_config("VACATION_END_DATE", $new_vacation_end);
		
		$this->set_vacation_start_date($new_vacation_start);
		$this->set_vacation_end_date($new_vacation_end);
		
		
	}
	
	
	public function turn_off_vacation(){
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::turn_off_vacation()\n";
		$this->write_log($logtext);
		
		$this->write_config("VACATION_FUNCTION", "OFF");
		
		$this->set_vacation_start_date("");
		$this->set_vacation_end_date("");
		
	}
	
	public function take_pictures(){
		
		$plants = $this->plant_array;
		$sensorunits = $this->sensorunit_array;
		$camera = new Camera();
		
		foreach($plants as $plant){
			
			$nickname = $plant->get_nickname();
			$plant_id = $plant->get_plant_id();
			$sensor_unit_id = $plant->get_sensor_unit_id();
			$mac_address = $sensorunits[$sensor_unit_id]->get_mac_address();
			
			$camera->take_pic($mac_address, $plant_id, $this->eliminate_whitespace($nickname));
		}
		
	}
	
	public function take_picture($plant_id){
		$sensorunit_id = $this->plant_array[$plant_id]->get_sensor_unit_id();
		$mac_address = $this->sensorunit_array[$sensorunit_id]->get_mac_address();
		$nickname = $this->plant_array[$plant_id]->get_nickname();
		
		$camera = new Camera();
		$camera->take_pic($mac_address, $plant_id, $this->eliminate_whitespace($nickname));
	}
	
	/**
	 *
	 * @param unknown $frames an array with the pictures
	 * @param $duration this sets how long a picure will be shown in a time lapse
	 */
	public function make_time_lapse($plant_id, $frames, $duration){
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::make_time_lapse(Plant Id: ".$plant_id.", Frames: ".$frames.", Duration: ".$duration.")\n";		
		//links zu den bilder im internet
		/*$frames = array("http://www.sarracenia.com/photos/dionaea/dionamusci070.jpg",
		 "http://www.flowers.org.uk/wp-content/uploads/2012/12/Pitcher-Plant.jpg",
		 "http://i1110.photobucket.com/albums/h443/meizzwang/IMG_6847.jpg");
		 //geht aber auch mit lokalen pfaden
		 $frames_local = array('plants/plant01.jpg',
		 'plants/plant02.jpg',
		 'plants/plant03.jpg');
		 */
		//wie lang jedes bild angezeigt wird
		
		$nickname = $this->eliminate_whitespace($this->plant_array[$plant_id]->get_nickname());
		$path = "/var/www/html/gartnetzwerg/Pictures/".$plant_id."_".$nickname."/";
		
		$durations = [];
		foreach ($frames as $key => $frame){
			$frames[$key] = $path.$frame;
			$durations[] = $duration;
			
		}
		
		
		try{
			$gc = new GifCreator\GifCreator();
			$gc->create($frames, $durations, 0);
			$gif_binary = $gc->getGif();
			file_put_contents('/var/www/html/gartnetzwerg/Gifs/'.$plant_id.'_'.$nickname.'.gif', $gif_binary); //speichert gif lokal ab
		}
		catch (\Exception $ex){
			$logtext = $logtext.date(LOG_TIME_FORMAT)."	ERROR: ".$ex->getMessage()."\n";
		}
		$this->write_log($logtext);
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
			
			$data = json_decode($json, true);
			if ($data == ""){ //if api doesnt work then use the default one
				$this->set_openweathermap_api_key("");
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
	
	
	public function send_notification($plant_id, $reason){
		
		//TODO neue cases für neue $reason
		if ($this->get_general_notification_settings() == "ON"){
			
			$db_handler = new DB_Handler();
			$db_handler->connect_sql();
			
			$settings = $db_handler->fetch_notification_settings($plant_id);
			$nickname = $db_handler->fetch_nickname($plant_id);
			$message = "";
			$subject = "Pflanze ".$nickname." benötigit ihre Aufmerksamkeit!";
			switch ($reason){
				case "needs_water":				
					switch ($settings){
						case "data_only": 
							$message = "Pflanze ".$nickname." hat zu trockenen Boden.\n";
							break;
						case "both": 
							$message = "Pflanze ".$nickname." hat zu trockenen Boden.\n";
							$message .= "Jetzt gießen!";
							break;
						case "instructions_only": 
							$message = "Pflanze ".$nickname." sollte jetzt gegossen werden.";
							break;
						case "off": 
							$message = "";
							break;
						
					}
					break;
			}
			$db_handler->disconnect_sql();
			if ($message != ""){
				$this->send_mail($subject, $message);
			}
		}
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
	 * @param takes $min and $max value from the database and compares it to the current $akt value
	 * @return offset/difference between current value and ideal value
	 *
	 */
	public function sensor_offset($akt, $min, $max, $gewichtung){
		if($akt - $min < 0){
			return (-1 * $gewichtung);
		} else if($max - $akt < 0){
			return (1 * $gewichtung);
		}
		return 0;
	}
	
	/**
	 * @param takes plant_id and sensor_id
	 * @return text depending on sensor and offset-value
	 */
	public function correction_text($plant_id){
		$return_string = "";
		
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
		
		$akt_air_temperature = $this->get_plant($plant_id)->get_akt_air_temperature();
		$akt_air_humidity = $this->get_plant($plant_id)->get_akt_air_humidity();
		$akt_soil_temperature = $this->get_plant($plant_id)->get_akt_soil_temperature();
		$akt_light_hours = $this->get_plant($plant_id)->get_akt_light_hours();
		$akt_soil_humidity = $this->get_plant($plant_id)->get_akt_soil_humidity();

		$offset_at = $this->sensor_offset($akt_air_temperature, $min_air_temperature, $max_air_temperature, 1);
		$offset_ah = $this->sensor_offset($akt_air_humidity, $min_air_humidity, $max_air_humidity, 1);
		$offset_st = $this->sensor_offset($akt_soil_temperature, $min_soil_temperature, $max_soil_temperature, 1);
		$offset_sh  = $this->sensor_offset($akt_soil_humidity, $min_soil_humidity, $max_soil_humidity, 1);
		$offset_l = $this->sensor_offset($akt_light_hours, $min_light_hours, $max_light_hours, 1);

		if($offset_at < 0){
			//negative
			$return_string .= "<i class='fa fa-thermometer-empty' aria-hidden='true'></i> Deiner Pflanze ist es anscheinend zu kalt. Etwas mehr Wärme würde ihr gut tun.<br/>";
		} else if($offset_at > 0){
			//positive
			$return_string .= "<i class='fa fa-thermometer-full' aria-hidden='true'></i> Deiner Pflanze ist es anscheinend zu warm. Drehe die Heizung runter.<br/>";
		}

		if($offset_ah < 0){
			//negative
			$return_string .= "<i class='fa fa-cloud' aria-hidden='true'></i> Die Luftfeuchtigkeit ist anscheinend zu niedrig. Ein Wasserspray hilf temporär.<br/>";
		} else if($offset_ah > 0){
			//positive
			$return_string .= "<i class='fa fa-cloud' aria-hidden='true'></i> Die Luftfeuchtigkeit ist anscheinend zu hoch. Kurz Durchlüften sollte helfen.<br/>";
		}

		if($offset_st < 0){
			//negative
			$return_string .= "<i class='fa fa-thermometer-1' aria-hidden='true'></i> Die Erde ist anscheinend etwas kalt. Je nach Tageszeit ist das kein Problem, sollte dies doch ganztägig erscheinen, versuche deine Pflanze etwas mehr zuwärmen.<br/>";
		} else if($offset_st > 0){
			//positive
			$return_string .= "<i class='fa fa-thermometer-2' aria-hidden='true'></i> Die Erde ist anscheinend etwas warm. Je nach Tageszeit ist das kein Problem, sollte dies doch ganztägig erscheinen, versuche deine Pflanze etwas zukühlen.";
		}

		if($offset_l < 0){
			//negative
			$return_string .= "<i class='fa fa-low-vision' aria-hidden='true'></i> Deine Pflanze könnte etwas mehr Licht vertragen. Stelle sie etwas näher zum Fenster.<br/>";
		} else if($offset_l > 0){
			//positive
			$return_string .= "<i class='fa fa-lightbulb-o' aria-hidden='true'></i> Deine Pflanze könnte etwas mehr Schatten vertragen. Stelle sie etwas weiter vom Fenster weg.<br/>";
		}

		if($offset_sh < 0){
			//negative
			$return_string .= "<i class='fa fa-tint' aria-hidden='true'></i> Deine Pflanze fühlt sich durstig. Überprüfe das vorher mit deinem Finger.<br/>";
		} else if($offset_sh > 0){
			//positive
			$return_string .= "<i class='fa fa-umbrella' aria-hidden='true'></i> Deine Pflanze fühlt sich etwas zu nass. Stelle das Gießen für die nächsten paar Tage etwas ein. Allgemein gilt: Lieber ein paar Tage zu wenig als zu viel.<br/>";
		}

		return $return_string;
	}
	
	/**
	 * @param takes plant_id + 5 priorities for (in this order): air_temp, air_hum, light, soil_hum, soil_temp
	 * @return color as string, for later CSS;
	 */
	public function color_state($plant_id, $g_at, $g_ah, $g_l, $g_sh, $g_st){
		$color_value = 0;

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
		
		$akt_air_temperature = $this->get_plant($plant_id)->get_akt_air_temperature();
		$akt_air_humidity = $this->get_plant($plant_id)->get_akt_air_humidity();
		$akt_soil_temperature = $this->get_plant($plant_id)->get_akt_soil_temperature();
		$akt_light_hours = $this->get_plant($plant_id)->get_akt_light_hours();
		$akt_soil_humidity = $this->get_plant($plant_id)->get_akt_soil_humidity();

		$color_value += abs($this->sensor_offset($akt_air_temperature, $min_air_temperature, $max_air_temperature, $g_at));
		$color_value += abs($this->sensor_offset($akt_air_humidity, $min_air_humidity, $max_air_humidity, $g_ah));
		$color_value += abs($this->sensor_offset($akt_soil_temperature, $min_soil_temperature, $max_soil_temperature, $g_st));
		$color_value += abs($this->sensor_offset($akt_soil_humidity, $min_soil_humidity, $max_soil_humidity, $g_sh));
		$color_value += abs($this->sensor_offset($akt_light_hours, $min_light_hours, $max_light_hours, $g_l));

		if($color_value >= 3) return "black"; else if($color_value >= 2) return "red";
		else if($color_value >= 1) return "orange"; else if($color_value >= 0.5) return "yellow"; else return "green";
	}
	
	//messwerte
	
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
		$logtext = $logtext.date(LOG_TIME_FORMAT)."	Datum: ".$date->format("Y-m-d")."\n";
		
		$this->write_log($logtext);
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		$water_usage_sum = $db_handler->sum_water_usage($plant_id,$date->format("Y-m-d"));
		$db_handler->disconnect_sql();
		
		return $water_usage_sum;
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
		
		$this->write_log($logtext);
		
		return $water_usage_per_day;
		
	}
	
	
	
	/**
	 * takes data from the DB, counts the values of hours of light per day
	 * @returns hours of light in an array indexed on days, 
	 */
	public function lighthours_per_day($sensor_unit_id, $days){
		
		//  Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::lighthours_per_day_per_day(Sensorunit ID: ".$sensor_unit_id.", Days: ".$days.")\n";
		$this->write_log($logtext);
		
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
		
		//  Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::air_humidity_per_day(Sensorunit ID: ".$sensor_unit_id.", Days: ".$days.")\n";
		$this->write_log($logtext);
		
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

		//  Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::soil_humidity_per_day(Sensorunit ID: ".$sensor_unit_id.", Days: ".$days.")\n";
		$this->write_log($logtext);
		
		
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
		
		//  Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::air_temperature_per_day(Sensorunit ID: ".$sensor_unit_id.", Days: ".$days.")\n";
		$this->write_log($logtext);
		
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
		
		//  Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::soil_temperature_per_day(Sensorunit ID: ".$sensor_unit_id.", Days: ".$days.")\n";
		$this->write_log($logtext);
		
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
		
		//  Logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::waterlogging_per_day(Sensorunit ID: ".$sensor_unit_id.", Days: ".$days.")\n";
		$this->write_log($logtext);
		
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
	public function update_all_sensor_data($manual){
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::update_all_sensor_data(Manual: ".$manual.")\n";
		$this->write_log($logtext);
		
		foreach ($this->sensorunit_array as $sensorunit_id => $sensorunit){
			if ($sensorunit->get_status() == "active"){
				$this->update_sensor_data($sensorunit_id, $manual);
			}
		}
	}
	
	public function update_sensor_data($sensorunit_id, $manual){
		
		//logging
		$logtext = "\n".date(LOG_TIME_FORMAT)."	Controller::update_sensor_data(Sensorunit Id: ".$sensorunit_id.", Manual: ".$manual.")\n";
		$this->write_log($logtext);
		
		$this->sensorunit_array[$sensorunit_id]->update_all();
		$sensor_ids = $this->sensorunit_array[$sensorunit_id]->get_sensor_ids();
		
		$db_handler = new DB_Handler();
		$db_handler->connect_sql();
		
		foreach ($sensor_ids as $sensor_id){
			$value = $this->sensorunit_array[$sensorunit_id]->get_sensor($sensor_id)->get_value();
			
			$db_handler->insert_sensor_data($sensor_id, $value, $manual);
			
		}
		
		$db_handler->disconnect_sql();
		$this->refresh_local_objects();
		
	}
	
	
	public function eliminate_whitespace($string){
	
		return str_replace(" ", "_", $string);
		
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
//$test->sum_water_usage(2, 2);


//var_dump($data);
?>