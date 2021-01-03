<?php
$PAGE_TITLE="ESPNodes frontend";

if(isset($_GET['page'])){
	$page=preg_replace('/[^A-Za-z0-9-_\/]/', '', $_GET['page']);
}else{
	$page='sensors';
}
$filename="pages/". $page .".php";


include "config/devices.php";

include "include/header.php";
include "include/navbar.php";
?>


<div class="container">
<div class="jumbotron">

<?php
if(file_exists($filename)){
	include $filename;
}else{
	echo "Error 404: page not found.";
}
?>

</div></div>
<?php
include "include/footer.php";
?>