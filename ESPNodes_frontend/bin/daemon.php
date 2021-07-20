<?php
/* Daemon for ESPNodes
 * Functions:
 * - Gather all sensor data at regular intervals, store them in a CSV-file
 * - Execute timer-based functions
 */

include dirname(__FILE__)."/../config/timers.php"; 
include dirname(__FILE__)."/../config/logs.php"; 

printf("ESPNodes by Bas Vermulst, https://github.com/BeaverUI\r\n");
printf("Daemon running.\r\n");
printf("Logging interval is ".$LOG_INTERVAL." minutes.\r\n");
printf("Maximum log size is ".$LOG_PERIOD." minutes. Older entries will be overwritten.\r\n");

$log_prevtime=time()-$LOG_INTERVAL*60;
$timer_prevtime="2000-01-01 00:00";

ListTimers();

while(1){
	// handle logging
	if(time() - $log_prevtime >= $LOG_INTERVAL*60){
		$log_prevtime=time();
		LogSensorData($LOG_DIR,ceil($LOG_PERIOD/$LOG_INTERVAL));
	}
	
	// handle timers
	if(date("H:i") <> $timer_prevtime){
		$timer_prevtime=date("H:i");
		Timers();
	}
	
	sleep(30); // wait for 30 seconds
}


function ListTimers(){
	global $TIMER_CONF_DIR, $lat, $long, $zenith_sunrise, $zenith_sunset;
	
	include dirname(__FILE__)."/../config/nodes.php";

	$current_time=date("Y-m-d H:i");
	
	// find sunrise and sunset
	$timestamp=mktime(date("H"),date("i"),0,date("n"),date("j"),date("Y"));
	$sunrise_time=date("H:i", date_sunrise($timestamp, SUNFUNCS_RET_TIMESTAMP, $lat, $long, $zenith_sunrise));
	$sunset_time=date("H:i", date_sunset($timestamp, SUNFUNCS_RET_TIMESTAMP, $lat, $long, $zenith_sunset));

	printf("Current time is ".$current_time.".\r\n");

	// iterate through all nodes
	foreach($node_name as $node_index=>$name){
		// ensure directory for node exists
		if(is_dir($TIMER_CONF_DIR.'/'.$node_index)){
			
			// iterate through all sensors of node
			foreach($node_sensors[$node_index] as $status_index=>$status_key){	
			
				//if sensor is an actuator, then it can be used with timers
				if($status_key[2]=="w"){
					// ensure directory for sensor exists
					$directory=$TIMER_CONF_DIR.'/'.$node_index.'/'.$status_index;
					if(is_dir($directory)){
						
						// iterate through all timers of sensor
						$timers = array();
						foreach (scandir($directory) as $file) {
							if ($file !== '.' && $file !== '..') {
								$handle = fopen($directory."/".$file, "r");
								if ($handle) {
									// process the timer file
									if(($line = fgets($handle)) !== false) {
										$timer_time_str=rtrim($line);
										$timer_time_str=str_replace(array("sunrise","sunset"),array($sunrise_time,$sunset_time), $timer_time_str);
										$timer_time=date("Y-m-d H:i", strtotime($timer_time_str));
									}
									if(($line = fgets($handle)) !== false) {
										$timer_actuator_state=rtrim($line);
									}
									fclose($handle);

									if(isset($timer_actuator_state)&&isset($timer_time)){
										printf("Timer found for node ".$node_name[$node_index].", actuator ". $status_key[0] .", actuator value: ".$timer_actuator_state .", time: ". $timer_time_str . ", interpreted time ".$timer_time.".\r\n");
									}
								} else {
									// error opening the file.
									printf("Error opening timer file ". $directory."/".$file);
								} 
							}
						}
					}
				}
			}
		}
	}
}

function Timers(){
	global $TIMER_CONF_DIR, $lat, $long, $zenith_sunrise, $zenith_sunset;
	
	include dirname(__FILE__)."/../config/nodes.php";

	$current_time=date("Y-m-d H:i");
		
	// find sunrise and sunset
	$timestamp=mktime(date("H"),date("i"),0,date("n"),date("j"),date("Y"));
	$sunrise_time=date("H:i", date_sunrise($timestamp, SUNFUNCS_RET_TIMESTAMP, $lat, $long, $zenith_sunrise));
	$sunset_time=date("H:i", date_sunset($timestamp, SUNFUNCS_RET_TIMESTAMP, $lat, $long, $zenith_sunset));

	// iterate through all nodes
	foreach($node_name as $node_index=>$name){
		// ensure directory for node exists
		if(is_dir($TIMER_CONF_DIR.'/'.$node_index)){		
		
			// iterate through all sensors of node
			foreach($node_sensors[$node_index] as $status_index=>$status_key){	
			
				//if sensor is an actuator, then it can be used with timers
				if($status_key[2]=="w"){
					// ensure directory for sensor exists
					$directory=$TIMER_CONF_DIR.'/'.$node_index.'/'.$status_index;
					if(is_dir($directory)){
												
						// iterate through all timers of sensor
						$timers = array();
						foreach (scandir($directory) as $file) {
							if ($file !== '.' && $file !== '..') {
								$handle = fopen($directory."/".$file, "r");
								if ($handle) {
									// process the timer file
									if(($line = fgets($handle)) !== false) {
										$timer_time_str=rtrim($line);
										$timer_time_str=str_replace(array("sunrise","sunset"),array($sunrise_time,$sunset_time), $timer_time_str);
										$timer_time=date("Y-m-d H:i", strtotime($timer_time_str));
									}
									if(($line = fgets($handle)) !== false) {
										$timer_actuator_state=rtrim($line);
									}
									fclose($handle);

									if(isset($timer_actuator_state)&&isset($timer_time)){
										// check whether action is needed, and set action if neccessary
										if($timer_time===$current_time){
											printf("Timer is triggered for node ".$node_name[$node_index].", actuator ". $status_key[0] ." set to ".$timer_actuator_state ." (". $timer_time_str . ").\r\n");
											$node_new_status[$node_index][$status_index]=$timer_actuator_state;
										}
									}
									
								} else {
									// error opening the file.
									printf("Error opening timer file ". $directory."/".$file);
								} 

							}
						}
					}
				}
			}
		}
	}

	// check if timer is triggered and update actuator value if appropriate
	if(isset($node_new_status)){
		include dirname(__FILE__)."/../include/node_set_status.php";
		unset($node_new_status);
	}
}


function LogSensorData($LOG_DIR,$LOG_MAX_LINES){
	include dirname(__FILE__)."/../config/nodes.php";
	include dirname(__FILE__)."/../include/node_get_status.php";
	
	// iterate through all nodes
	foreach($node_name as $node_index=>$name){
		// ensure directory for node exists
		if(!is_dir($LOG_DIR.'/'.$node_index)){
			mkdir($LOG_DIR.'/'.$node_index);
		}
		
		// iterate through all sensors of node
		foreach($node_sensors[$node_index] as $status_index=>$status_key){	
			if($status_key[3]==true){ // sensor must be logged
				$log_filename=$LOG_DIR.'/'.$node_index.'/'.$status_index.'.csv';

				$handle = fopen($log_filename, "a");				
				fprintf($handle, time() . ";" . $node_status[$node_index][$status_index] . "\r\n");
				fclose($handle);

				$file_contents = file_get_contents($log_filename);
				$lines = explode("\r\n", $file_contents);
				$lines = array_slice($lines,-intval($LOG_MAX_LINES)-1);
				$file_contents=implode("\r\n", $lines);
				file_put_contents($log_filename, $file_contents);
			}
		}
	}
}

?>
