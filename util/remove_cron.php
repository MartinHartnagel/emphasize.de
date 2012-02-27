<?php
include_once(dirname(__FILE__)."/../includes/config.php");
connectDb();

$delete = @mysql_query("DELETE FROM " . $db_prefix . "CRON WHERE cid='".p($_GET["cid"])."'"); // AND id_user=".$id
if (!$delete) {
	fail("delete failed");
}
if (mysql_affected_rows() == 1) {
  echo("deleted");
} else {
  fail("delete failed, please login");
}
bottom();
?>
