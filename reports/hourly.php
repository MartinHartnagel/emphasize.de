<?php
  reportHead("<i18n key='hor0'><en>Hour</en><de>Stunde</de><fr>Heure</fr><es>Hora</es></i18n>",
  "<i18n ref='rep1'></i18n>",
  "<i18n key='hor2'><en>Total</en><de>Summe</de><fr>Total</fr><es>Total</es></i18n>",
  "<i18n key='hor3'><en>Daily total</en><de>Tagessumme</de><fr>Total journalier</fr><es>Diaria total</es></i18n>");

  $previousDate=null;
  $previousEvent=null;
  $previousDuration=null;
  $sum2=0;
     
  $date=$from;
  while (strtotime($date) < strtotime($to)) {
    $next=date("Y-m-d H:00:00", strtotime($date." +1 hour"));
    
    $sql = @mysql_query("SELECT DATE_FORMAT('$date', '" . $date_format . " %H:00') AS date, event AS event, SUM(UNIX_TIMESTAMP(IF(IFNULL(end, '$time') < '$next', IFNULL(end, '$time'), '$next'))-UNIX_TIMESTAMP(IF(start > '$date', start, '$date'))) AS sum, color FROM " . $db_prefix . "ENTRY WHERE id_user=$id AND (start<'$next' AND (end>='$date' OR end IS NULL)) GROUP BY event ORDER BY DATE(start) ASC, event ASC");
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
      setBackCol(2, $row["color"], $row["sum"], 3600, 16, 150);
      $sum2+=$row["sum"];
    }
    mysql_free_result($sql);
    $date=$next;  
  }
  if ($sum2 > 0) {
    reportData($previousDate, $previousEvent, $previousDuration, $sum2);
  }
?>
