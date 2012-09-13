<?php
include_once(dirname(__FILE__).'/../includes/config.php');

$files=array('js/ajaxfileupload.js', 'js/dashboard.js', 'js/emphasize.js', 'js/progress.js', 'js/timesortedset.js', 'js/timeline.js', 'js/avatar.js');

$export="js";
header("Content-Type: application/x-javascript;charset=UTF-8");

$lang=detectLang();

$args=array_merge(array("emphasize_".$lang.".js", "includes/translations.php"), $files);
checkCache($args);

$js='';
foreach($files as $file) {
 $js.=file_get_contents(dirname(__FILE__).'/../'.$file, true);
}

require(dirname(__FILE__).'/jsmin.php');
// no minify in development mode
if ($domain != 'http://next.emphasize.de') {
  $js=JSMin::minify($js);
}
readfile(dirname(__FILE__).'/../js/jquery-1.8.1.min.js');
echo("\n");
readfile(dirname(__FILE__).'/../js/jquery-ui-1.8.17.custom.min.js');
echo("\n");
readfile(dirname(__FILE__).'/../js/jquery.ui.datepicker-'.$lang.'.js');
echo("\n");
echo($js);
?>
