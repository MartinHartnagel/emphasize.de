<?php
  reportHead("<i18n key='mon0'><en>Month</en><de>Monat</de><fr>Mois</fr><es>Mes</es></i18n>",
  "<i18n ref='rep1'></i18n>",
  "<i18n ref='hor2'></i18n>",
  "<i18n key='mon3'><en>Monthly total</en><de>Monatssumme</de><fr>Total de mois</fr><es>Total mensual</es></i18n>", 
  "<i18n ref='inf2'></i18n>");

function getNextTimeRange($date) {
	return date("Y-m-01 00:00:00", strtotime($date." +1 month"));
}

function getSqlTimeRange($id, $date, $date_format, $time, $next) {
	return "SELECT CONCAT(YEAR(n.start),'-',MONTH(n.start)) AS date, e.name AS event, SUM(UNIX_TIMESTAMP(IF(IFNULL(n.end, '$time') < '$next', IFNULL(n.end, '$time'), '$next'))-UNIX_TIMESTAMP(IF(n.start > '$date', n.start, '$date'))) AS sum, e.color FROM " . DB_PREFIX . "ENTRY n " .
	"LEFT JOIN " . DB_PREFIX . "EVENT e ON e.id=n.id_event WHERE n.ID_USER=" . p($id) . " AND n.ID_USER=e.ID_USER AND (n.start<'$next' AND (n.end>='$date' OR n.end IS NULL)) GROUP BY n.id_event ORDER BY DATE(n.start) ASC, e.name ASC";
}	
	
function getMaxCellValueTimeRange($date) {
	return 86400*cal_days_in_month(CAL_GREGORIAN, substr($date, 5), substr($date, 0, 4));
}

function getInfoDateTimeRange($start) {
	return substr($start,8,2).'. '.substr($start,11,5);
}

function getMailReportAbo() {
	global $rangeReports;
	
	$type="monthly";

	return '<form action="'.DOMAIN.'/util/crons.php" method="GET" target="_blank"><input name="token" type="hidden" value="'.User::getInstance()->getToken().'"><input name="type" type="hidden" value="'.$type.'"><select name="add"><option value="nextmonth=0">am 1. Tag des Folgemonats</option><option value="nextmonth=2">am 2. Tag des Folgemonats</option></select> Versand des "'.$rangeReports[$type] . '" per Email <input type="submit" value="abonnieren" /></form>';
}


include_once(dirname(__FILE__).'/timeranged.php');
?>
