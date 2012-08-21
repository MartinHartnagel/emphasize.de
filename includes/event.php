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

function getEvents($now, $before) {
	global $domain;

	$id=User :: getInstance()->getId();
	$delta = (strtotime($now) - strtotime($before)) / 60;

	$update = @ mysql_query("UPDATE " . DB_PREFIX . "ENTRY SET duration=UNIX_TIMESTAMP('" . p($now) . "')-UNIX_TIMESTAMP(start) WHERE id_user=" . p($id) . " AND end IS NULL");
	if (!$update) {
		fail("unexpected: entry duration update failed");
	}

	$sql = @ mysql_query("SELECT e.name as event, e.color as color, FLOOR(" . $delta . "-(UNIX_TIMESTAMP('" . p($now) . "') - UNIX_TIMESTAMP(IF(n.start < '" . p($before) . "','" . p($before) . "',n.start)))/60) AS offset, n.start AS start, FLOOR(n.duration/60) as minutes, FLOOR((UNIX_TIMESTAMP(IF(n.end >'" . p($now) . "','" . p($now) . "',n.end))-UNIX_TIMESTAMP(IF(n.start < '" . p($before) . "','" . p($before) . "',n.start)))/60) AS width FROM " . DB_PREFIX . "ENTRY n, " . DB_PREFIX . "EVENT e WHERE n.ID_USER=" . p($id) . " AND n.ID_USER=e.ID_USER AND e.ID=n.ID_EVENT AND n.END > '" . p($before) . "' AND n.DURATION >= 60 ORDER BY n.start ASC");
	// aktuelle render-position
	$cursor = 0;
	while ($row = mysql_fetch_array($sql)) {
		$event = str_replace('"', '&quot;', $row["event"]);
		$color = $row["color"];
		if (strlen($color) != 7) {
			$color = "#ff0000";
		}
		// offset zählt von 0 (=-7d) bis 10080 (=jetzt)
		$offset = $row["offset"];
		$start = $row["start"];
		$minutes = $row["minutes"];
		$width = $row["width"];
		if ($offset > $cursor) {
			echo ('<img src="' . $domain . '/graphics/void.png" width="' . ($offset - $cursor) . '" height="10" class="te" />');
			$cursor = $offset;
		}
		echo ('<img src="' . $domain . '/util/i.php?' . substr($color, 1, 6) . '" title="' . $event . '" width="' . $width . '" height="10" class="te" />');
		echo ('<img src="' . $domain . '/graphics/seperator.png" width="15" height="12" class="tsep" style="left:' . ($offset +52) . 'px;" />');
		$cursor = $offset + $width;
	}
	mysql_free_result($sql);

	// render the actual event which has end=null
	$sql = @ mysql_query("SELECT e.name, e.color, FLOOR(" . $delta . "-(UNIX_TIMESTAMP('" . p($now) . "')-UNIX_TIMESTAMP(IF(n.start < '" . p($before) . "','" . p($before) . "',n.start)))/60) AS offset, n.start, FLOOR(n.duration/60) as minutes, FLOOR((UNIX_TIMESTAMP('" . p($now) . "')-UNIX_TIMESTAMP(IF(n.start < '" . p($before) . "','" . p($before) . "', n.start)))/60) AS width FROM " . DB_PREFIX . "ENTRY n, " . DB_PREFIX . "EVENT e WHERE n.id_user=" . p($id) . " AND n.id_user=e.id_user AND e.id=n.id_event AND n.end IS NULL");
	if ($row = mysql_fetch_array($sql)) {
		$event = str_replace('"', '&quot;', $row["event"]);
		$color = $row["color"];
		if (strlen($color) != 7) {
			$color = "#ff0000";
		}
		// offset zählt von 0 (=-7d) bis 10080 (=jetzt)
		$offset = $row["offset"];
		$start = $row["start"];
		$minutes = $row["minutes"];
		$width = $row["width"];
		if ($offset > $cursor) {
			echo ('<img src="' . $domain . '/graphics/void.png" width="' . ($offset - $cursor) . '" height="10" class="te" />');
			$cursor = $offset;
		}
		echo ('<img src="' . $domain . '/util/i.php?' . substr($color, 1, 6) . '" title="' . $event . '" width="' . $width . '" height="10" class="te" />');
		echo ('<img src="' . $domain . '/graphics/seperator.png" width="15" height="12" class="tsep" style="left:' . ($offset +52) . 'px;" />');
		$cursor = $offset + $width;
	}
	mysql_free_result($sql);

	// infos
	$sql = @ mysql_query("SELECT info, FLOOR(" . $delta . "-(UNIX_TIMESTAMP('" . p($now) . "')-UNIX_TIMESTAMP(IF(start < '" . p($before) . "','" . p($before) . "',start)))/60) AS offset, start FROM " . DB_PREFIX . "INFO WHERE ID_USER=" . p($id) . " AND start > '" . p($before) . "' ORDER BY start ASC");
	// aktuelle render-position
	while ($row = mysql_fetch_array($sql)) {
		$info = str_replace('"', '&quot;', $row["info"]);
		// offset zählt von 0 (=-7d) bis 10080 (=jetzt)
		$offset = $row["offset"];
		$start = $row["start"];
		echo ('<img src="' . $domain . '/graphics/info.png" title="' . substr($start, 11, 5) . ' ' . $info . '" style="left:' . ($offset +60 - 2) . 'px;" class="ti" />');
	}
	mysql_free_result($sql);
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
