<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$PAGE_TITLE="ESPNodes";

if(isset($_GET['page'])){
	$page=preg_replace('/[^A-Za-z0-9-_\/]/', '', $_GET['page']);
}else{
	$page='sensors';
}
$filename="pages/". $page .".php";


include "config/nodes.php";

include "include/header.php";
include "include/navbar.php";
?>


<div class="container container-maxwidth"><div class="bg-light p-3 rounded-3" style="margin: auto; margin-top: 3rem; margin-bottom: 3rem;">

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
