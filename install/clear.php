<?php
include_once (dirname(__FILE__) . "/../includes/config.php");
$export = "php";

chdir(dirname(__FILE__).'/../cache/');
$dir_handle = @opendir(".") or die("Unable to open path");
while ($file = readdir($dir_handle))
{
	if (is_file('./'.$file) && $file!="." && $file!="..") {
		unlink($file);
		echo("deleted $file<br/>\n");
	}
}
chdir(dirname(__FILE__).'/../');
$dir_handle = @opendir(".") or die("Unable to open path");
while ($file = readdir($dir_handle))
{
	if (is_file('./'.$file) && (endsWith($file, ".zip", false) || $file=="pad.xml")) {
		unlink($file);
		echo("deleted $file<br/>\n");
	}
}
?>
