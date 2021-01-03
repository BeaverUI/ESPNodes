<?php
$ch = curl_init();

foreach($device_name as $index=>$name){
	curl_setopt($ch, CURLOPT_URL, $device_address[$index]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
	$output = curl_exec($ch);

	$device_status[$index]=json_decode($output,TRUE);
}

curl_close($ch);
?>