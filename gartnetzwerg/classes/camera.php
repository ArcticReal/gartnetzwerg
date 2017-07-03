<?php
require_once 'sensor.php';
class Camera{
		
	public function take_pic($mac_address,$plant_id,$nickname){
		
		
		$cmd = "sudo /var/www/html/gartnetzwerg/get_ip_address.sh ".$mac_address;
		$ip = shell_exec($cmd);
		
		$cmd = "sudo ".__DIR__."/../take_picture.sh ".$ip." ".$plant_id."_".$nickname;
		shell_exec($cmd);
				
	}
}

?>
