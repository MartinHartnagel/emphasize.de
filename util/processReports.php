<?php
include_once(dirname(__FILE__)."/../includes/config.php");
$export="txt";
connectDb();

$sql = @mysql_query("SELECT id_user, cid, type, range, run FROM ".$db_prefix."CRON WHERE run < CURRENT_TIMESTAMP");
while ($row = mysql_fetch_array($sql)) {
	$id_user=$row["id_user"];
	$cid=$row["cid"];
	$type=$row["type"];
	$range=$row["range"];
	$run=$row["run"];
	echo("running $cid\n");
	$nextrun=mailReport($id_user, $cid, $type, $range, $run);
	if ($nextrun != $run) {
	  echo("$cid next $nextrun\n");
	  $update = @mysql_query("UPDATE " . $db_prefix . "CRON SET run='".p($nextrun)."' WHERE cid='".$cid."'");
		if (!$update) {
			fail("update for \"$cid\" failed");
		}
	}
}
mysql_free_result($sql);
echo("\ncompleted.");

bottom();
?>
