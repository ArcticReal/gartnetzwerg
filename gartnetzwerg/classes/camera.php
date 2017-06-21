<?php
require_once 'sensor.php';
class Camera extends Sensor{
	/*public function update(){
		/*TODO: (wurde aber noch nicht auf'm Zero getestet)
		
		if($rReturn != ""){
			$this->value = $rReturn;
		}
	}*/
	
	public function take_pic($mac_address,$plant_id,$nickname){
		
		
		$cmd = "sudo /var/www/html/gartnetzwerg/get_ip_address.sh ".$mac_address;
		$ip = shell_exec($cmd);
		
		$cmd = "sudo ".__DIR__."/../take_picture.sh ".$ip." ".$plant_id."_".$nickname;
		shell_exec($cmd);
				
	}
	
	public function set_cam(){
		
	}
}

?>
