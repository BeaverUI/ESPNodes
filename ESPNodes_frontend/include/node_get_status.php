<?php
$ch = curl_init();

foreach($node_name as $node_index=>$name){
	curl_setopt($ch, CURLOPT_URL, $node_address[$node_index]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	$output = curl_exec($ch);

	$node_status[$node_index]=json_decode($output,TRUE);
}

curl_close($ch);
?>
