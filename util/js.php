<?php 
include_once(dirname(__FILE__).'/../includes/config.php');
$export="js";
header("Content-Type: application/x-javascript;charset=UTF-8");
checkCache("emphasize_".$_GET["lang"].".js", 'js/ajaxfileupload.js', 'js/tabletti.js', 'js/emphasize.js', "includes/translations.php");

$js=file_get_contents(dirname(__FILE__).'/../js/ajaxfileupload.js', true);
$js.=file_get_contents(dirname(__FILE__).'/../js/tabletti.js', true);
$js.=file_get_contents(dirname(__FILE__).'/../js/emphasize.js', true);
require(dirname(__FILE__).'/jsmin.php');
$js=JSMin::minify($js);
echo($js);
?>
