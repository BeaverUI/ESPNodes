<p>Configure timers here.</p><p>Timers can be triggered on regular time (e.g. "07:30"), "sunset" and "sunrise", and times can use PHP supported time and date formats (e.g. "sunset - 1 hour", "07:30+5 minutes", "friday sunset", "thursday 9:30", "last day of december 09:25", "2021-07-15 21:30"; <a target="_blank" href="https://www.php.net/manual/en/datetime.formats.php">read more</a>).</p>
<p>The list of timers is sorted on sensor name, then timer name. Creating a new timer with an existing sensor name + timer name overwrites the old timer for that sensor.</p>

<?php
include "include/node_get_status.php";
include "config/timers.php";

// based on original work from the PHP Laravel framework
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

$timestamp=mktime(date("H"),date("i"),0,date("n"),date("j"),date("Y"));
$sunrise_time=date("H:i", date_sunrise($timestamp, SUNFUNCS_RET_TIMESTAMP, $lat, $long, $zenith_sunrise));
$sunset_time=date("H:i", date_sunset($timestamp, SUNFUNCS_RET_TIMESTAMP, $lat, $long, $zenith_sunset));

echo "<p>Sunrise today at ". $sunrise_time . ", sunset today at ". $sunset_time . ".</p>";
?>

<?php
	// iterate through all nodes
	foreach($node_name as $node_index=>$name){
		$n_timers=0;

		echo '<hr class="bg-primary border-3 border-top border-primary">';		
		echo "<h3>".$name."</h3>";
		// ensure directory for node exists
		if(!is_dir($TIMER_CONF_DIR.'/'.$node_index)){
			mkdir($TIMER_CONF_DIR.'/'.$node_index);
		}
		echo 'Create new timer for this node:<br/>';
		echo '<form action="functions/timer_create.php" method="POST">';
		echo '<input type="hidden" name="node_index" value="'.$node_index.'">';
		echo '<select name="status_index" style="width:140px;" >';
		// iterate through all sensors of node to make list
		foreach($node_sensors[$node_index] as $status_index=>$status_key){
			if($status_key[2]=="w"){
				echo '<option value="'.$status_index.'">'.$status_key[0].'</option>';
				echo "\r\n";
			}
		}
		echo '</select>';
		echo '<input type="text" name="timer_name" pattern="^[A-Za-z0-9]{1,8}" title="Timer name (max. 8 characters)" style="width:120px;" placeholder="Timer name">';
		echo '<input type="text" name="status_value" pattern="^[A-Za-z0-9]{1,16}" title="Actuator value (max. 16 characters)" style="width:120px;" placeholder="Actuator value"><br/>';
		echo '<input type="text" name="timer_time" pattern="^.{1,160}" title="Time (max. 160 characters)" style="width:300px;" placeholder="Time">';

		echo '<button type="submit" class="btn btn-primary btn-sm" style="width:80px;">Create</button>';	
		echo '</form>';
		echo '<br/>Currently set timers:<br/><br/>';

		echo '<small><table class="table">';
		echo '<tr>';
		echo '<th>Sensor</th><th>Name</th><th>Value</th><th>Time</th><th>Interpreted time</th><th>&nbsp;</th>';
		echo '</tr>';
		
		// iterate through all sensors of node
		foreach($node_sensors[$node_index] as $status_index=>$status_key){	
		
			//if sensor is an actuator, then it can be used with timers
			if($status_key[2]=="w"){
				// ensure directory for sensor exists
				$directory=$TIMER_CONF_DIR.'/'.$node_index.'/'.$status_index;
				if(!is_dir($directory)){
					mkdir($directory);
				}
								
				// iterate through all timers of sensor
				$timers = array();
				foreach (scandir($directory, SCANDIR_SORT_ASCENDING) as $file) {
					if ($file !== '.' && $file !== '..') {

						$handle = fopen($directory."/".$file, "r");
						if ($handle) {
							// process the timer file
							if(($line = fgets($handle)) !== false) {
								$timer_time_str=rtrim($line);
								$timer_time=date("Y-m-d H:i",strtotime(str_replace(array("sunrise","sunset"),array($sunrise_time,$sunset_time), $timer_time_str)));
							}
							if(($line = fgets($handle)) !== false) {
								$timer_actuator_state=rtrim($line);
							}
							fclose($handle);

							if(isset($timer_actuator_state)&&isset($timer_time)&&(!($timer_time_str===""))&&(!($timer_actuator_state===""))){
								// if valid timer, print settings
								$n_timers++;
								?>
								<tr>
									<td><?php echo $status_key[0]; ?></td>
									<td><?php echo $file; ?></td>
									<td><?php echo $timer_actuator_state; ?></td>
									<td><?php echo $timer_time_str; ?></td>
									<td><?php echo $timer_time; ?></td>
									<td><?php
											echo '<form action="functions/timer_delete.php" method="POST">';
											echo '<input type="hidden" name="node_index" value="'.$node_index.'">';
											echo '<input type="hidden" name="status_index" value="'.$status_index.'">';
											echo '<input type="hidden" name="timer_name" value="'.$file.'">';
											echo '<button type="submit" class="btn btn-close btn-sm" style="background-color: #FF0000;"></button>';
											echo '</form>';									
									?></td>
									
								</tr>
								<?php
							}else{
								// if invalid timer, print error
								?>
								<tr>
									<td><?php echo $file; ?></td>
									<td><?php echo $status_key[0]; ?></td>
									<td><?php echo "Error: timer invalid"; ?></td>
									<td></td>
									<td></td>
									<td><?php
											echo '<form action="functions/timer_delete.php" method="POST">';
											echo '<input type="hidden" name="node_index" value="'.$node_index.'">';
											echo '<input type="hidden" name="status_index" value="'.$status_index.'">';
											echo '<input type="hidden" name="timer_name" value="'.$file.'">';
											echo '<button type="submit" class="btn btn-close btn-sm" style="background-color: #FF0000;"></button>';
											echo '</form>';									
									?></td>
								</tr>
								<?php
							}
							
						} else {
							// error opening the file.
								?>
								<tr>
									<td><?php echo $status_key[0]; ?></td>
									<td><?php echo "Error reading timer data"; ?></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<?php
						}
					}
				}
			}
		}
		if($n_timers==0){
			echo "<tr><td style='padding: 0.25rem;'>No sensors available for this node.</td></tr>";
		}

		echo '</table></small>';
	}
?>
