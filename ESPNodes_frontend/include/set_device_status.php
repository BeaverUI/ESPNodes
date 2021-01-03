<?php
include "../config/devices.php";
$device_index=$_POST["device_index"];
$status_index=$_POST["status_index"];
$status_value=$_POST["status_value"];

header('Location: ../index.php');

// rewrite value if neccessary
if($device_sensors[$device_index][$status_index][1]=="boolean"){
	if($status_value=="true"){
    	$status_value=1;
    }else{
		$status_value=0;
    }
}

if($device_sensors[$device_index][$status_index][1]=="integer"){
	$status_value=(int)$status_value;
}

if($device_sensors[$device_index][$status_index][1]=="float"){
	$status_value=(float)$status_value;
}

// Setup request to send json via POST
$payload = json_encode(array($status_index => $status_value));

// Create a new cURL resource
$url = $device_address[$device_index];
$ch = curl_init($url);

// Attach encoded JSON string to the POST fields
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
$result = curl_exec($ch);
curl_close($ch);
?>