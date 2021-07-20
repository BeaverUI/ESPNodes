<?php
include "../config/nodes.php";
include "../config/timers.php";

$node_index=substr(preg_replace("/[^A-Za-z0-9]/", "", $_POST["node_index"]),0,16);
$status_index=substr(preg_replace("/[^A-Za-z0-9\_\-]/", "", $_POST["status_index"]),0,42);
$timer_name=substr(preg_replace("/[^A-Za-z0-9]/", "", $_POST["timer_name"]),0,16);
$status_value=substr($_POST["status_value"],0,16);
$timer_time=substr($_POST["timer_time"],0,255);

if($timer_name===""){
	die("Error: timer name not set or incorrect");
}

if(is_dir($TIMER_CONF_DIR.'/'.$node_index.'/'.$status_index)==false){
	die("Error: directory not found: ".$node_index.'/'.$status_index);
}

$filename=$TIMER_CONF_DIR.'/'.$node_index.'/'.$status_index.'/'.$timer_name;

$file = fopen($filename, "w") or die("Error: unable to open file ".$node_index.'/'.$status_index.'/'.$timer_name. ". Is the folder readable and writeable?");
$txt = $timer_time."\n".$status_value;
fwrite($file, $txt);
fclose($file);

header('Location: '.$_SERVER['HTTP_REFERER']);

?>
