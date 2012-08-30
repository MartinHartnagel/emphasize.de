<?php


/**
 * @param $event if "", then pause starts.
 */
function addEvent($event, $color, $time, $link) {
	$id=User :: getInstance()->getId();
	if (strlen($event) > 0) {
		$sql = @ mysql_query("SELECT id FROM " . DB_PREFIX . "EVENT WHERE id_user=" . p($id) . " AND name='" . p($event) . "' LIMIT 0,1");
		if ($row = mysql_fetch_array($sql)) {
			$id_event = $row["id"];
			mysql_free_result($sql);
			echo ("exists: " . $id_event);
		} else {
			mysql_free_result($sql);
			$insert = @ mysql_query("INSERT INTO " . DB_PREFIX . "EVENT SET id_user=" . p($id) . ", name='" . p($event) . "', color='" . p($color) . "', link='" . p(link) . "'");
			if (!$insert) {
				fail("insert0 failed: "."INSERT INTO " . DB_PREFIX . "EVENT SET id_user=" . p($id) . ", name='" . p($event) . "', color='" . p($color) . "', link='" . p(link) . "'");
			}
			$id_event = mysql_insert_id();
			echo ("inserted: " . $id_event);
		}
	}
    echo("id_event: ".$id_event);

	$delete = @ mysql_query("DELETE FROM " . DB_PREFIX . "ENTRY WHERE id_user=" . p($id) . " AND start >='" . p($time) . "' AND start <= DATE_ADD('" . p($time) . "', INTERVAL 5 MINUTE)");
	if (!$delete) {
		fail("delete failed");
	}
	echo ("deleted [$time,+5mins], ");

	$sql = @ mysql_query("SELECT start FROM " . DB_PREFIX . "ENTRY WHERE id_user=" . p($id) . " AND start <'" . p($time) . "' ORDER BY start DESC LIMIT 0,1");
	$row = mysql_fetch_array($sql);
	$startBefore = $row["start"];
	mysql_free_result($sql);

	$sql = @ mysql_query("SELECT start FROM " . DB_PREFIX . "ENTRY WHERE id_user=" . p($id) . " AND start >'" . p($time) . "' ORDER BY start ASC LIMIT 0,1");
	$row = mysql_fetch_array($sql);
	$startAfter = $row["start"];
	mysql_free_result($sql);

	if ($startBefore) {
		$update = @ mysql_query("UPDATE " . DB_PREFIX . "ENTRY SET end='" . p($time) . "', duration=UNIX_TIMESTAMP('" . p($time) . "')-UNIX_TIMESTAMP(start) WHERE id_user=" . p($id) . " AND start='" . p($startBefore) . "'");
		if (!$update) {
			fail("update failed");
		}
		echo ("updated $startBefore duration before, ");
	}
	if (strlen($event) > 0) {
		if ($startAfter) {
			$insert = @ mysql_query("REPLACE INTO " . DB_PREFIX . "ENTRY SET id_user=" . p($id) . ", id_event='" . p($event) . "', start='" . p($time) . "', duration=UNIX_TIMESTAMP('" . p($startAfter) . "')-UNIX_TIMESTAMP('" . p($time) . "'), end='" . p($startAfter) . "'");
			if (!$insert) {
				fail("insert1 failed");
			}
			echo ("inserted new entry at $time which ends at $startAfter.");
		} else {
			$insert = @ mysql_query("REPLACE INTO " . DB_PREFIX . "ENTRY SET id_user=" . p($id) . ", id_event=" . p($id_event) . ", start='" . p($time) . "'");
			if (!$insert) {
				fail("insert2 failed");
			}
			echo ("inserted new event at $time.");
		}
	}
}

function getEvents($from, $to) {
	global $domain;

	$id=User :: getInstance()->getId();

	$a=array();
	$events=array();

	$sql = @ mysql_query("SELECT e.name as event, e.color as color, n.start AS start ".
	  "FROM " . DB_PREFIX . "ENTRY n, " . DB_PREFIX . "EVENT e ".
	  "WHERE n.ID_USER=" . p($id) . " AND n.ID_USER=e.ID_USER AND e.ID=n.ID_EVENT ".
	  "AND (n.start >= '" . p($from) . "' OR n.end > '" . p($from) . "') AND n.start <= '" . p($to) . "' ORDER BY n.start ASC");
	while ($row = mysql_fetch_array($sql)) {
		$events[]=array($row["start"], $row["event"], $row["color"]);
	}
	mysql_free_result($sql);
	$a[]=$events;

  $infos=array();
	$sql = @ mysql_query("SELECT info, start FROM " . DB_PREFIX . "INFO ".
	  "WHERE ID_USER=" . p($id) . " AND start >= '" . p(from) . "' ORDER BY start ASC");
	while ($row = mysql_fetch_array($sql)) {
		$infos[]=array($row["start"], $row["info"]);
	}
	mysql_free_result($sql);
	$a[]=$infos;

	echo (json_encode($a,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP));
}

function getPlace($time) {
	$sql = @ mysql_query("SELECT e.name AS event FROM " . DB_PREFIX . "ENTRY n, " . DB_PREFIX . "EVENT e WHERE n.ID_USER=" . p(User :: getInstance()->getId()) . " AND n.ID_USER=e.ID_USER AND e.ID=n.ID_EVENT AND n.start <='" . p($time) . "' ORDER BY n.start DESC LIMIT 0,1");
	if ($row = mysql_fetch_array($sql)) {
		$event = $row["event"];
		return $event;
	}
	return '';
}
?>
