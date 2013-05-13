<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('memory_limit', '16M');
$export="none";
// rewrite configuration.php
$c=file_get_contents(dirname(__FILE__)."/../includes/configuration.php");
$n='// configuration-replace
// installed db-configuration (overriding example above)
// nizip
$db_host = "'.stripslashes($_REQUEST["db_host"]).'";
$db_name = "'.stripslashes($_REQUEST["db_name"]).'";
$db_username = "'.stripslashes($_REQUEST["db_username"]).'";
$db_password = "'.stripslashes($_REQUEST["db_password"]).'";
$db_prefix = "'.stripslashes($_REQUEST["db_prefix"]).'";
$domain = "'.stripslashes($_REQUEST["domain"]).'";
$feedback_to = "'.stripslashes($_REQUEST["feedback_to"]).'";
// /nizip
// /configuration-replace';
$c=preg_replace("/\/\/ configuration-replace.*\/\/ \/configuration-replace/msU", $n, $c);
unlink(dirname(__FILE__)."/../includes/configuration.php");
file_put_contents(dirname(__FILE__)."/../includes/configuration.php", $c);

include_once(dirname(__FILE__)."/../includes/config.php");

$itxt=file_get_contents(dirname(__FILE__)."/../install.txt");
$needle="create required mySQL-DB tables:";
$offset=strrpos($itxt, $needle);
if ($offset !== false) {
	$sqlo=substr($itxt, $offset+strlen($needle));
	$sqls=str_replace("EMPHASIZE_", DB_PREFIX, $sqlo);
	$sqls=preg_replace("/^\s*--.*$/m","", $sqls);
	User::connectDb();
	$sqla=explode(";", $sqls);
	foreach($sqla as $sqlp) {
		$sql=trim($sqlp);
		if (strlen($sql) > 0) {
			$create=@mysql_query($sql);
			if (!$create) {
			  fail("execution failed: <pre>".str_replace("<", "&lt;", $sql)."</pre>");
			}
		}
	}

	// correcting includes filepermissions
	if (substr(decoct(fileperms(INC.'/configuration.php') ), 1) != "0444") {
	 if (!chmod(INC.'/configuration.php', 0444)) {
	  die("failed to set permissions on ".INC.'/configuration.php');
	 }
	}
	if (substr(decoct(fileperms(INC) ), 1) != "0755") {
	 chmod(INC, 0755);
	}
	if (substr(decoct(fileperms(SHORTS) ), 1) != "0777") {
	 chmod(SHORTS, 0777);
	}
	if (substr(decoct(fileperms(CACHE) ), 1) != "0777") {
	 chmod(CACHE, 0777);
	}
	file_put_contents(INC.'/../install/.htaccess', "deny from all\n");
	header("Location: ".DOMAIN); /* Redirect browser */
  exit();
}
?>
