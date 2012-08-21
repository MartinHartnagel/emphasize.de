<?php 
include_once(dirname(__FILE__).'/../includes/config.php');

connectDb();

$sql = @mysql_query("SELECT name, tbody FROM " . DB_PREFIX . "USER WHERE 1 GROUP BY TBODY");
while($row = mysql_fetch_array($sql)) {
	echo($row["name"]."<table width=\"100%\" height=\"200\">".$row["tbody"]."</table><hr/>");
}
mysql_free_result($sql);
?>
