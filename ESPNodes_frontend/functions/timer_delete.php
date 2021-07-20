<?php
include "../config/nodes.php";
include "../config/timers.php";

$node_index=substr(preg_replace("/[^A-Za-z0-9]/", "", $_POST["node_index"]),0,16);
$status_index=substr(preg_replace("/[^A-Za-z0-9\_\-]/", "", $_POST["status_index"]),0,42);
$timer_name=substr(preg_replace("/[^A-Za-z0-9]/", "", $_POST["timer_name"]),0,16);

if(is_dir($TIMER_CONF_DIR.'/'.$node_index.'/'.$status_index)==false){
	die("Error: directory not found: ".$node_index.'/'.$status_index);
}

if(is_file($TIMER_CONF_DIR.'/'.$node_index.'/'.$status_index.'/'.$timer_name)==false){
	die("Error: timer not found: ".$node_index.'/'.$status_index.'/'.$timer_name);
}

$filename=$TIMER_CONF_DIR.'/'.$node_index.'/'.$status_index.'/'.$timer_name;
unlink($filename) or die("Error: unable to delete timer ".$node_index.'/'.$status_index.'/'.$timer_name. ". Is the folder readable and writeable?");

header('Location: '.$_SERVER['HTTP_REFERER']);

?>
