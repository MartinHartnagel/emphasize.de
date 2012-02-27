<?php
  reportHead("<i18n key='day0'><en>Day</en><de>Tag</de><fr>Jour</fr><es>Día</es></i18n>",
  "<i18n ref='rep1'></i18n>",
  "<i18n ref='hor2'></i18n>",
  "<i18n ref='hor3'></i18n>");

  $previousDate=null;
  $previousEvent=null;
  $previousDuration=null;
  $sum2=0;

  $date=$from;
  while (strtotime($date) < strtotime($to)) {
    $next=date("Y-m-d 00:00:00", strtotime($date." +1 day"));
    
    $sql = @mysql_query("SELECT DATE_FORMAT('$date', '" . $date_format . "') AS date, event AS event, SUM(UNIX_TIMESTAMP(IF(IFNULL(end, '$time') < '$next', IFNULL(end, '$time'), '$next'))-UNIX_TIMESTAMP(IF(start > '$date', start, '$date'))) AS sum, color FROM " . $db_prefix . "ENTRY WHERE id_user=$id AND (start<'$next' AND (end>='$date' OR end IS NULL)) GROUP BY event ORDER BY DATE(start) ASC, event ASC");
    
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
      setBackCol(2, $row["color"], $row["sum"], 86400, 16, 150);
      $sum2+=$row["sum"];
    }
    mysql_free_result($sql);
    $date=$next;  
  }
  if ($sum2 > 0) {
    reportData($previousDate, $previousEvent, $previousDuration, $sum2);
  }
?>
