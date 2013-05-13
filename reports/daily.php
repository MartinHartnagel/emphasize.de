<?php
reportHead("<i18n key='day0'><en>Day</en><de>Tag</de><fr>Jour</fr><es>Día</es></i18n>", "<i18n ref='rep1'></i18n>", "<i18n ref='hor2'></i18n>", "<i18n ref='hor3'></i18n>", "<i18n ref='inf2'></i18n>");

function getNextTimeRange($date) {
	return date("Y-m-d 00:00:00", strtotime($date . " +1 day"));
}

function getSqlTimeRange($id, $date, $date_format, $time, $next) {
	return "SELECT DATE_FORMAT('$date', '" . $date_format . "') AS date, e.name AS event, SUM(UNIX_TIMESTAMP(IF(IFNULL(n.end, '$time') < '$next', IFNULL(n.end, '$time'), '$next'))-UNIX_TIMESTAMP(IF(n.start > '$date', n.start, '$date'))) AS sum, e.color FROM " . DB_PREFIX . "ENTRY n " .
	"LEFT JOIN " . DB_PREFIX . "EVENT e ON e.id=n.id_event WHERE n.ID_USER=" . p($id) . " AND n.ID_USER=e.ID_USER AND (n.start<'$next' AND (n.end>='$date' OR n.end IS NULL)) GROUP BY n.id_event ORDER BY DATE(n.start) ASC, e.name ASC";
}

function getMaxCellValueTimeRange($date) {
	return 86400;
}

function getInfoDateTimeRange($start) {
	return substr($start, 11, 5);
}

function getMailReportAbo() {
	global $rangeReports;

	return "<select name=\"\"><option>kein</option><option value=\"weekday=0\">Sonntags</option><option value=\"weekday=0\">Montags</option></select> ".$rangeReports["weekly"] . " automatisch per Email erhalten";
}

include_once (dirname(__FILE__) . '/timeranged.php');
?>
