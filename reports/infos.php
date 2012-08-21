<?php
  reportHead("<i18n ref='rep0'></i18n>",
  "<i18n key='inf2'><en>Info</en><de>Info</de><fr>Info</fr><es>Info</es></i18n>");
  
    $sql = @mysql_query("SELECT DATE_FORMAT(t0.start, '" . $date_format . " %H:%i') AS date, t0.info AS info, t1.event AS event, t1.color AS color FROM " . DB_PREFIX . "INFO t0, " . DB_PREFIX . "ENTRY t1 WHERE t0.id_user=$id AND t0.start>='$from' AND t0.start<='$to' AND t0.start<='$time' AND t1.id_user=$id AND t0.start>=t1.start AND (t0.start<t1.end OR (t0.start<'$time' AND t1.end IS NULL)) ORDER BY t0.start ASC");
    
    $aligns=array();
    $aligns[]="left";
    $aligns[]="center";
    $aligns[]="left";
    setAligns($aligns);
    while ($row = mysql_fetch_array($sql)) {
      setBackCol(1, $row["color"], 10, 10, 16, 250);
      reportData($row["date"], $row["event"], $row["info"]);
    }
?>
