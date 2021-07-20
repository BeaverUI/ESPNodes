<?php

foreach($node_new_status as $node_index=>$node_key){
	foreach($node_new_status[$node_index] as $status_index=>$status_key){
	
		// rewrite value to correct format
		if($node_sensors[$node_index][$status_index][1]=="boolean"){
			if($node_new_status[$node_index][$status_index]=="true"){
			$node_new_status[$node_index][$status_index]=1;
			}else{
			$node_new_status[$node_index][$status_index]=0;
			}
		}else if($node_sensors[$node_index][$status_index][1]=="integer"){
			$node_new_status[$node_index][$status_index]=intval($node_new_status[$node_index][$status_index]);
		}else if($node_sensors[$node_index][$status_index][1]=="float"){
			$node_new_status[$node_index][$status_index]=floatval(str_replace(",",".", $node_new_status[$node_index][$status_index]));
		}
	}
}

foreach($node_new_status as $node_index=>$node_key){		
	// Setup request to send json via POST
	$payload = json_encode($node_new_status[$node_index]);

	// Create a new cURL resource
	$url = $node_address[$node_index];
	$ch = curl_init($url);

	// Attach encoded JSON string to the POST fields
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	$result = curl_exec($ch);
	curl_close($ch);
}

?>
