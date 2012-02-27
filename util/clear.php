<?php 
function endsWith($haystack,$needle,$case=true) {
	if($case){
		return (strcmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);
	}
	return (strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);
}

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

/*
// clean up unconfirmed (1400 is hack to avoid still awaiting confirmations, better use: today-1month)
DELETE FROM LOG_ENTRY WHERE ID_USER IN (SELECT u.ID FROM `LOG_USER` u WHERE u.`confirmed` <> 't' AND  NOT EXISTS (SELECT * FROM LOG_USAGE x WHERE x.id_USER=u.ID)) AND ID_USER < 1400;
DELETE FROM LOG_INFO WHERE ID_USER IN (SELECT u.ID FROM `LOG_USER` u WHERE u.`confirmed` <> 't' AND  NOT EXISTS (SELECT * FROM LOG_USAGE x WHERE x.id_USER=u.ID)) AND ID_USER < 1400;
DELETE FROM LOG_TEMPLATES WHERE ID_USER IN (SELECT u.ID FROM `LOG_USER` u WHERE u.`confirmed` <> 't' AND  NOT EXISTS (SELECT * FROM LOG_USAGE x WHERE x.id_USER=u.ID)) AND ID_USER < 1400;
DELETE FROM `LOG_USER` WHERE `confirmed` <> 't' AND  NOT EXISTS (SELECT * FROM LOG_USAGE x WHERE x.id_USER=ID) AND ID < 1400;

// clean up accounts unused for 12 months:


*/
?>
