<?php
/* log_plot[plot_num]=array(
	node_id1 => array(sensor_id1, sensor_id2),
	node_id2 => array(sensor_id1, sensor_id2),
	);
 *
 * IMPORTANT: make sure the sensor is logged in the node configuration file (nodes.php)
 */


$id=0;
$log_plot_title[$id]='Zitstok gewicht';
$log_plot[$id]=array(
				0 => array('loadcell_value', 'loadcell_value_b','loadcell_value_a')
				);

$id=1;
$log_plot_title[$id]='Kippenhok deur status';
$log_plot[$id]=array(
				0 => array('servo_angle')
				);

$id=2;
$log_plot_title[$id]='Lamp status';
$log_plot[$id]=array(
				1 => array('lamp_status')
				);

				
// Items below normally do not need modifications. First try with the default settings.
$LOG_DIR=dirname(__FILE__)."/../logs"; // logfile directory
$LOG_INTERVAL=5; // logging interval in minutes
$LOG_PERIOD=60*24*14; // number of minutes of log data to keep 

?>
