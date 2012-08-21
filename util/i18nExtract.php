<?php 
include_once(dirname(__FILE__).'/../includes/config.php');
$export="js";

header("Content-Type: text/plain;charset=UTF-8");
echo('<?php'."\n");
echo('$i18n=array('."\n");
User::connectDb();
$first=true;
foreach($lc as $l=>$name) {
	$set=getI18Ns("en", $l);
	foreach($set as $k=>$v) {
		if (strlen(trim($v[1])) > 0) {
			if ($first) {
				$first=false;
			} else {
				echo(",\n");
			}
			echo("\"$k.$l\"=>'".str_replace("'", "\\'", trim(preg_replace('/\s+?/imsU', " ", $v[1])))."'");
		}
	}
}
echo("\n);\n");
echo('?>'."\n");
?>
