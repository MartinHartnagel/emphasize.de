<?php
$previousDate = null;
$previousEvent = null;
$previousDuration = null;
$sum2 = 0;

$date = $from;
$infoElement = 0;
while (strtotime($date) < strtotime($to)) {
	$next = getNextTimeRange($date);

	$sql = @ mysql_query(getSqlTimeRange($id, $date, $date_format, $time, $next));

	while ($row = mysql_fetch_array($sql)) {
		if ($previousDate != null && $previousDate != $row["date"]) {
			reportData($previousDate, $previousEvent, $previousDuration, $sum2, $infos);
			$sum2 = 0;
		}
		elseif ($previousDate != null) {
			reportData($previousDate, $previousEvent, $previousDuration, null, $infos);
		}
		$previousDate = $row["date"];
		$previousEvent = $row["event"];
		$previousDuration = $row["sum"];
		setBackCol(2, $row["color"], $row["sum"], getMaxCellValueTimeRange($previousDate), 16, 150);
		$sum2 += $row["sum"];

		// infos
		$infos = "";
		$infoCount = 0;
		$sql2 = @ mysql_query("SELECT i.info, i.start FROM " . DB_PREFIX . "INFO i " .
		"LEFT JOIN  " . DB_PREFIX . "ENTRY n ON i.id_user=n.id_user " .
		"LEFT JOIN " . DB_PREFIX . "EVENT e ON e.id_user=i.id_user AND e.id=n.id_event " .
		"WHERE i.id_user=" . p($id) . " AND e.name='" . p($previousEvent) . "' AND i.start >= '" . p($date) . "' AND i.start < '" . p($next) . "' AND i.start>=n.start AND (i.start<n.end OR (i.start<'$next' AND n.end IS NULL)) ORDER BY i.start ASC");
		// aktuelle render-position	    
		while ($row2 = mysql_fetch_array($sql2)) {
			$info = str_replace('"', '&quot;', $row2["info"]);
			$start = $row2["start"];
			if (strlen($infos) > 0) {
				if ($export == "csv") {
					$infos .= "\n";
				}
				elseif ($export == "xml") {
					$infos .= "\n";
				} else {
					$infos .= "<br/>\n";
				}
			}
			if ($export == "csv") {
				$infos .= getInfoDateTimeRange($start) . ' ' . $info;
			}
			elseif ($export == "xml") {
				$infos .= getInfoDateTimeRange($start) . ' ' . $info;
			} else {
				$infos .= '<img id="infoIcon" src="' . $domain . '/graphics/info.png"/> ' . getInfoDateTimeRange($start) . ' ' . $info;
			}
			$infoCount++;
		}
		if ($infoCount > 0) {
			if ($export != "csv" && $export != "xml" && !isset($cron)) {
				$infos = '<img src="' . $domain . '/graphics/info.png" title="' . $infoCount . '" onclick="showAbove(\'info\', this, null, null, null, null, $(\'#i' . $infoElement . '\').html())" style="cursor:pointer;"/><div id="i' . $infoElement . '" style="display:none;"><title><i18n ref="inf2"></i18n>&nbsp;' . $previousEvent . ' ' . $previousDate . '</title>' . $infos . '</div>';
			}
		}
		mysql_free_result($sql2);
		$infoElement++;
	}
	mysql_free_result($sql);
	$date = $next;
}
if ($sum2 > 0) {
	reportData($previousDate, $previousEvent, $previousDuration, $sum2, $infos);
}
?>
