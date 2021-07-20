<?php
/* Node definitions
 *
 * Configure all your nodes and their sensors here. Make sure to set it to read (r)/write (w)/config (c) to list them in on correct page. See the format example below for more info.
 *
 * How exchanging data works:
 * - Reading data from a sensor:
 * A dataset is retrieved from each node using a GET command, the node will reply with a JSON object containing the status of the node and its sensors, e.g.: {"servo_attached":1,"door_angle":130}
 *
 * - Writing data to a sensor:
 * If a sensor (e.g. "servo_attached") is defined as writeable (w), its value can be changed via the web interface. When this is the case, changing its value to "0" via the web interface will send a GET command to the device status address with JSON data {"servo_attached":0}.
 * 
 */


// first node (id=0)
$id=0;
$node_name[$id]="Tuinhuis";
$node_address[$id]="http://192.168.1.35";
$node_sensors[$id]=array(
	// format: "sensor_id"=> array(friendly name, data type, operator type, logging enabled (true/false))
	// where:
	//  sensor_id: string used to addres the sensor, the identity is defined in code on the node itself
	//  friendly name: a string defining the name used on the webpage
	//  data types: boolean, integer, float, string
	//  operator types: w (writeable), c (writeable config). r (read-only)
	//  logging enabled: true (log this sensor), false (don't log this sensor)
	"rssi" => array("WIFI RSSI (dB)", "integer", "r", true),
	"relay_state" => array("Lamp tuinhuis", "boolean", "w", false),
	"servo_attached" => array("Servo actief","boolean", "w", false),
	"servo_ref_angle" => array("Deur ingestelde hoek (graden)", "integer", "w", false),
	"servo_angle" => array("Deur hoek (graden)", "integer", "r", true),
	"loadcell_value" => array("Zitstok gewicht", "float", "r", true),
	"loadcell_value_b" => array("Zitstok gewicht (L)", "float", "r", true),
	"loadcell_value_a" => array("Zitstok gewicht (R)", "float", "r", true),
	"loadcell_tare_b" => array("Zitstok tare (L)", "float", "c", false),
	"loadcell_tare_a" => array("Zitstok tare (R)", "float", "c", false),
	"loadcell_scale_b" => array("Zitstok schaling (L)", "float", "c", false),
	"loadcell_scale_a" => array("Zitstok schaling (R)", "float", "c", false)
	);


// second node (id=1)
$id=1;
$node_name[$id]="Voordeur";
$node_address[$id]="http://192.168.1.31/";
$node_sensors[$id]=array(
	// format: "sensor_id"=> array(friendly name, data type, operator type, logging enabled (true/false))
	// where:
	//  sensor_id: string used to addres the sensor, the identity is defined in code on the node itself
	//  friendly name: a string defining the name used on the webpage
	//  data types: boolean, integer, float, string
	//  operator types: w (writeable), c (writeable config). r (read-only)
	//  logging enabled: true (log this sensor), false (don't log this sensor)
	"rssi" => array("WIFI RSSI (dB)", "integer", "r", true),
	"deurbel_status" => array("Deurbel ingedrukt","boolean", "r", false),
	"lamp_status" => array("Lamp voordeur", "boolean", "w", true)
	);
	

// more nodes can be added easily, analog to the code above (use id=2, id=3, ...)
	
?>
