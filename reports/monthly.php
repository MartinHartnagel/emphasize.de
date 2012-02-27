<?php
  reportHead("<i18n key='mon0'><en>Month</en><de>Monat</de><fr>Mois</fr><es>Mes</es></i18n>",
  "<i18n ref='rep1'></i18n>",
  "<i18n ref='hor2'></i18n>",
  "<i18n key='mon3'><en>Monthly total</en><de>Monatssumme</de><fr>Total de mois</fr><es>Total mensual</es></i18n>");
 
  $previousDate=null;
  $previousEvent=null;
  $previousDuration=null;
  $sum2=0;
 
  $date=$from;
  while (strtotime($date) < strtotime($to)) {
    $next=date("Y-m-01 00:00:00", strtotime($date." +1 month"));
     
     $sql = @mysql_query("SELECT CONCAT(YEAR(start),'-',MONTH(start)) AS date, event AS event, SUM(UNIX_TIMESTAMP(IF(IFNULL(end, '$time') < '$next', IFNULL(end, '$time'), '$next'))-UNIX_TIMESTAMP(IF(start > '$date', start, '$date'))) AS sum, color FROM " . $db_prefix . "ENTRY WHERE id_user=$id AND (start<'$next' AND (end>='$date' OR end IS NULL)) GROUP BY event ORDER BY DATE(start) ASC, event ASC");
    
    while ($row = mysql_fetch_array($sql)) {
      if ($previousDate != null && $previousDate!=$row["date"]) {
        reportData($previousDate, $previousEvent, $previousDuration, $sum2);
        $sum2=0;
      } elseif ($previousDate != null) {
        reportData($previousDate, $previousEvent, $previousDuration, null);
      }
      $previousDate=$row["date"];
      $previousEvent=$row["event"];
      $previousDuration=$row["sum"];
      #echo("setBackCol(2, ".$row["color"].", ".$row["sum"].", 86400*cal_days_in_month(CAL_GREGORIAN, substr(".$row["date"].", 5), substr(".$row["date"].", 0, 4)), 16, 150);");
      setBackCol(2, $row["color"], $row["sum"], 86400*cal_days_in_month(CAL_GREGORIAN, substr($row["date"], 5), substr($row["date"], 0, 4)), 16, 150);
      $sum2+=$row["sum"];
    }
    mysql_free_result($sql);
    $date=$next;  
  }
  if ($sum2 > 0) {
    reportData($previousDate, $previousEvent, $previousDuration, $sum2);
  }
?>
