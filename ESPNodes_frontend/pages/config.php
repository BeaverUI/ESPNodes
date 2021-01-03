<?php include "include/get_device_status.php"; ?>

<p><i>Warning: you are changing the configuration of your sensors.</i></p>
<div style="width: 100%; max-width: 700px;">
<?php
foreach($device_name as $device_index=>$name){
	echo "<h2>".$name."</h2>";
?>
<table class="table">
<?php
$n_items=0;
	foreach($device_sensors[$device_index] as $status_index=>$status_key){
		if($status_key[2]=="c"){
			$n_items++;
?>
  <tr>
  <td><?php echo $status_key[0]; ?></td>
  <td width="250px">
<?php
			echo '<form action="include/set_device_status.php" method="POST">';
			echo '<input type="hidden" name="device_index" value="'.$device_index.'">';
			echo '<input type="hidden" name="status_index" value="'.$status_index.'">';
			$disabled="";

                                                                         
			switch($status_key[1]) {
				case "integer":
					echo '<input type="text" name="status_value" style="width:150px;" value="'.$device_status[$device_index][$status_index].'" '. $disabled .'>';
					break;

				case "float":
					echo '<input type="text" name="status_value" style="width:150px;" value="'.$device_status[$device_index][$status_index].'" '. $disabled .'>';
					break;

				case "boolean":
					if($device_status[$device_index][$status_index]==1){
						$checked="checked";
        			}else{
        				$checked="";
        			}
					echo '<div class="custom-control custom-switch">
      					<input type="checkbox" class="custom-control-input" id="toggle-'.$device_index.'-'.$status_index.'" '. $checked .' name="status_value" value="true" onClick="this.form.submit();"'. $disabled .'>
      					<label class="custom-control-label" for="toggle-'.$device_index.'-'.$status_index.'"></label>
      		  			</div>';

//  		echo '<input type="checkbox" '. $checked .' name="status_value" value="true" onClick="this.form.submit();"'. $disabled .'>';
					break;

				case "string":	
					echo '<input type="text" name="status_value" style="width:150px;" value="'.$device_status[$device_index][$status_index].'" '. $disabled .'>';
					break;
			}

		if($status_key[1]!="boolean") echo '<button type="submit" class="btn btn-primary">Set</button>';
		echo '</form>';
?>
  </td>
  </tr>
<?php
		}
	}
if($n_items==0){
	echo "<tr><td>No configurable sensors available for this node.</td></tr>";
}
?>
</table>
<?php
}
?>
</div>
