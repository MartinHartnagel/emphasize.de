<?php
include_once(dirname(__FILE__).'/../includes/config.php');
$export="js";
header("Content-Type: application/x-javascript;charset=UTF-8");
checkCache("emphasize_".$_GET["lang"].".js", 'js/ajaxfileupload.js', 'js/dashboard.js', 'js/emphasize.js', 'js/progress.js', 'js/timeline.js', 'js/avatar.js', "includes/translations.php");

$js=file_get_contents(dirname(__FILE__).'/../js/ajaxfileupload.js', true);
$js.=file_get_contents(dirname(__FILE__).'/../js/dashboard.js', true);
$js.=file_get_contents(dirname(__FILE__).'/../js/emphasize.js', true);
$js.=file_get_contents(dirname(__FILE__).'/../js/progress.js', true);
$js.=file_get_contents(dirname(__FILE__).'/../js/timeline.js', true);
$js.=file_get_contents(dirname(__FILE__).'/../js/avatar.js', true);
require(dirname(__FILE__).'/jsmin.php');
// no minify in development mode
if ($domain != 'http://next.emphasize.de') {
  $js=JSMin::minify($js);
}
echo($js);
?>
