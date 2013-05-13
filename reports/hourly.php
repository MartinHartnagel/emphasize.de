<?php
  reportHead("<i18n key='hor0'><en>Hour</en><de>Stunde</de><fr>Heure</fr><es>Hora</es></i18n>",
  "<i18n ref='rep1'></i18n>",
  "<i18n key='hor2'><en>Total</en><de>Summe</de><fr>Total</fr><es>Total</es></i18n>",
  "<i18n key='hor3'><en>Daily total</en><de>Tagessumme</de><fr>Total journalier</fr><es>Diaria total</es></i18n>", 
  "<i18n ref='inf2'></i18n>");

function getNextTimeRange($date) {
	return date("Y-m-d H:00:00", strtotime($date." +1 hour"));
}

function getSqlTimeRange($id, $date, $date_format, $time, $next) {
	return "SELECT DATE_FORMAT('$date', '" . $date_format . "') AS date, e.name AS event, SUM(UNIX_TIMESTAMP(IF(IFNULL(n.end, '$time') < '$next', IFNULL(n.end, '$time'), '$next'))-UNIX_TIMESTAMP(IF(n.start > '$date', n.start, '$date'))) AS sum, e.color FROM " . DB_PREFIX . "ENTRY n " .
	"LEFT JOIN " . DB_PREFIX . "EVENT e ON e.id=n.id_event WHERE n.ID_USER=" . p($id) . " AND n.ID_USER=e.ID_USER AND (n.start<'$next' AND (n.end>='$date' OR n.end IS NULL)) GROUP BY n.id_event ORDER BY DATE(n.start) ASC, e.name ASC";
}	
	
function getMaxCellValueTimeRange($date) {
	return 3600;
}

function getInfoDateTimeRange($start) {
	return substr($start,11,5);
}

function getMailReportAbo() {
	global $rangeReports;

	return "<select name=\"\"><option>kein</option><option value=\"days=1\">am Tag danach</option><option value=\"days=2\">zwei Tage später</option></select> Versand des \"".$rangeReports["daily"] . "\" per Email";
}

include_once(dirname(__FILE__).'/timeranged.php');
?>
