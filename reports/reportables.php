<?php
  reportHead("<i18n key='rep0'><en>Time</en><de>Zeit</de><fr>Temps</fr><es>Tiempo</es></i18n>",
  "<i18n key='rep1'><en>Activity</en><de>Aktivität</de><fr>Activité</fr><es>Actividad</es></i18n>",
  "<i18n key='rep2'><en>Duration</en><de>Dauer</de><fr>Durée</fr><es>Duración</es></i18n>");
  
    $update = @mysql_query("UPDATE " . DB_PREFIX . "ENTRY SET duration=UNIX_TIMESTAMP('$time')-UNIX_TIMESTAMP(start) WHERE id_user=$id AND end IS NULL");
    $sql = @mysql_query("SELECT start AS date, event AS event, duration AS sum, color FROM " . DB_PREFIX . "ENTRY WHERE id_user=$id AND start>='$from' AND (end<='$to' OR ('$from'<='$time' AND '$to' >= '$time' AND end IS NULL)) ORDER BY start ASC");
    
    while ($row = mysql_fetch_array($sql)) {
      setBackCol(1, $row["color"], 10, 10, 16, 250);
      reportData($row["date"], $row["event"], $row["sum"]);
    }
?>
