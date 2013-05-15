<?php
$export="none";

include_once(dirname(__FILE__)."/../includes/config.php");

$itxt=file_get_contents(dirname(__FILE__)."/../install.txt");
$needle="create required mySQL-DB tables:";
$offset=strrpos($itxt, $needle);
if ($offset !== false) {
	$sqlo=substr($itxt, $offset+strlen($needle));
	$sqls=str_replace("EMPHASIZE_", $db_prefix."_", $sqlo);
	$sqls=preg_replace("/^\s*--.*$/m","", $sqls);
	User::connectDb();
	$sqla=explode(";", $sqls);
	foreach($sqla as $sqlp) {
		$sql=trim($sqlp);
		if (strlen($sql) > 0 && stripos($sql,"CREATE TABLE") !== false) {
			preg_match('/`([^`]+)`\s+\(/', $sql, $name);
			$dsql="DROP TABLE ".$name[1];
			echo("executing: <pre>".$dsql."</pre>\n");
			$drop=@mysql_query($dsql);
			if (!$drop) {
				echo($dsql." failed");
			}
		}
	}
}
?>
