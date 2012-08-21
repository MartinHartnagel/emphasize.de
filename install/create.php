<?php
$export="none";
$use_prefix=$_GET["p"];

include_once(dirname(__FILE__)."/../includes/config.php");

$itxt=file_get_contents(dirname(__FILE__)."/../install.txt");
$needle="create required mySQL-DB tables:";
$offset=strrpos($itxt, $needle);
if ($offset !== false) {
	$sqlo=substr($itxt, $offset+strlen($needle));
	$sqls=str_replace("EMPHASIZE_", $use_prefix."_", $sqlo);
	$sqls=preg_replace("/^\s*--.*$/m","", $sqls);
	User::connectDb();
	$sqla=explode(";", $sqls);
	foreach($sqla as $sqlp) {
		$sql=trim($sqlp);
		if (strlen($sql) > 0) {
			echo("executing: <pre>".str_replace("<", "&lt;", $sql)."</pre>");
			$create=@mysql_query($sql);
			if (!$create) {
				fail($sql." failed");
			}
		}
	}
}
?>
