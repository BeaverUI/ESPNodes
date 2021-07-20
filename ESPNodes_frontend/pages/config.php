<?php include "include/node_get_status.php"; ?>
<p>The configuration of the nodes are shown here (as defined in config/nodes.php).</p>
<p><i>Warning: you are changing the configuration of your sensors.</i></p>
<?php
foreach($node_name as $node_index=>$name){
	echo '<hr class="bg-primary border-3 border-top border-primary">';	
	echo "<h3>".$name."</h3>";
?>
<table class="table">
<?php
$n_items=0;
	foreach($node_sensors[$node_index] as $status_index=>$status_key){
		if($status_key[2]=="c"){
			$n_items++;
?>
  <tr>
  <td><?php echo $status_key[0]; ?></td>
  <td width="200px">
<?php
			echo '<form action="functions/node_set_status.php" method="POST">';
			echo '<input type="hidden" name="node_index" value="'.$node_index.'">';
			echo '<input type="hidden" name="status_index" value="'.$status_index.'">';
			$disabled="";


			switch($status_key[1]) {
				case "integer":
					echo '<input type="text" name="status_value" style="width:110px;" value="'.$node_status[$node_index][$status_index].'" '. $disabled .'>';
					break;

				case "float":
					echo '<input type="text" name="status_value" style="width:110px;" value="'.$node_status[$node_index][$status_index].'" '. $disabled .'>';
					break;

				case "boolean":
					if($node_status[$node_index][$status_index]==1){
						$checked="checked";
        			}else{
        				$checked="";
        			}
					echo '<div class="form-check form-switch">
						<input type="checkbox" class="form-check-input" id="toggle-'.$node_index.'-'.$status_index.'" '. $checked .' name="status_value" value="true" onClick="this.form.submit();" '. $disabled .'>
						<label class="form-check-label" for="toggle-'.$node_index.'-'.$status_index.'"></label>
					  </div>';

					break;

				case "string":
					echo '<input type="text" name="status_value" style="width:110px;" value="'.$node_status[$node_index][$status_index].'" '. $disabled .'>';
					break;
			}

		if($status_key[1]!="boolean"){
			echo '<button type="submit" class="btn btn-primary btn-sm" style="width:70px;">Set</button>';
		}
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
