<?php
include_once (dirname(__FILE__) .
"/../includes/config.php");
User::connectDb();

if ((isset ($_GET["cid"]) && $_GET["cid"] != "")) {
	$cid = $_GET["cid"];
	$delete = @ mysql_query("DELETE FROM " . DB_PREFIX . "CRON WHERE cid='" . p($cid) . "'"); // AND id_user=".$id
	if (!$delete) {
		fail("delete failed");
	}
	if (mysql_affected_rows() == 1) {
		echo ("deleted");
	} else {
		fail("delete failed, please login");
	}
}

if ((isset ($_GET["type"]) && $_GET["type"] != "") && (isset ($_GET["add"]) && $_GET["add"] != "")) {
	$type = $_GET["type"];
	$range = $_GET["range"];
	$run=getMailReportNextRunDate($type, $range);
	$cid=generateUniqueId(5, "CRON", "cid");
	
	$insert = @mysql_query("INSERT " . DB_PREFIX . "CRON SET id_user=".p(User :: getInstance()->getId()).", cid='".p($cid)."', type='".p($type)."', range='".p($range)."', run='".p($run)."'");
	if (!$insert) {
		fail("insert failed");
	}
	echo("inserted new cron job.");
}


if (User::getInstance()->getId() != null) {
	$sql = @ mysql_query("SELECT `cid`, `type`, `range`, `run` FROM " . DB_PREFIX . "CRON WHERE id_user=" . User :: getInstance()->getId() . " ORDER BY `run` ASC");
	while ($row = mysql_fetch_array($sql)) {
		$cid = $row["cid"];
		$type = $row["type"];
		$range = $row["range"];
		$run = $row["run"];

		echo ($rangeReports[type] . " every " . $range . " runs next " . $run . "<br/>\n");
	}
	mysql_free_result($sql);
}

bottom();
?>
