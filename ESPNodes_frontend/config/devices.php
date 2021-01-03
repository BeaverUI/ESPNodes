<?php
/* Device definitions
 *
 * JSON object structure of each device is used for GET data for setting new values
 * For example:
 * JSON status of device: {"servo_attached":1,"door_angle":130}
 *
 * Setting servo_attached to 0 can be done by sending the GET command to the device status address 
 * with JSON data {"servo_attached":0}.
 * 
 */


// EXAMPLE CONFIG FOR TWO NODES:
$id=0;
$device_name[$id]="Tuinhuis";
$device_address[$id]="http://192.168.1.35";
$device_sensors[$id]=array(
	// supported types: boolean, integer, float, string
	// supported operators: w (writeable), c (writeable config). r (read-only)
	//	"ip" => array("IP adres", "string", "r"),
	//	"ssid" => array("WIFI SSID", "string", "r"),
	"rssi" => array("WIFI RSSI (dB)", "integer", "r"),
	"relay_state" => array("Lamp tuinhuis", "boolean", "w"),
	"servo_attached" => array("Servo actief","boolean", "w"),
	"servo_ref_angle" => array("Deur ingestelde hoek (graden)", "integer", "w"),
	"servo_angle" => array("Deur hoek (graden)", "integer", "r"),
	"loadcell_value_b" => array("Zitstok gewicht (L)", "float", "r"),
	"loadcell_value_a" => array("Zitstok gewicht (R)", "float", "r"),
	"loadcell_tare_b" => array("Zitstok tare (L)", "float", "c"),
	"loadcell_tare_a" => array("Zitstok tare (R)", "float", "c"),
	"loadcell_scale_b" => array("Zitstok schaling (L)", "float", "c"),
	"loadcell_scale_a" => array("Zitstok schaling (R)", "float", "c")
	);



$id=1;
$device_name[$id]="Voordeur";
$device_address[$id]="http://192.168.1.31/";
$device_sensors[$id]=array(
//	"ip" => array("IP adres", "string", "r"),
//	"ssid" => array("WIFI SSID", "string", "r"),
	"rssi" => array("WIFI RSSI (dB)", "integer", "r"),
	"deurbel_status" => array("Deurbel ingedrukt","boolean", "r"),
	"lamp_status" => array("Lamp voordeur", "boolean", "w")
	);
?>