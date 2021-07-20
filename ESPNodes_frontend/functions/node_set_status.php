<?php
include "../config/nodes.php";

$node_index=$_POST["node_index"];
$status_index=$_POST["status_index"];
$status_value=$_POST["status_value"];

header('Location: '.$_SERVER['HTTP_REFERER']);

$node_new_status[$node_index][$status_index]=$status_value;

include "../include/node_set_status.php";

?>
