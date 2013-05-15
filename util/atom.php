<?php
header('Content-Type: text/xml;charset=UTF-8');
echo('<?xml version=\'1.0\' encoding=\'UTF-8\'?>'."\n");
include_once(dirname(__FILE__)."/../includes/config.php");
$export="xml";
User::connectDb();
$aid=$_GET["a"];
$id=User::getInstance()->aid($aid);
User::getInstance()->idLogin($id);
$lang=User::getInstance()->getLang();

// last 10 events
$entries=array();

$sql = @mysql_query("SELECT e.name as event, e.color as color, n.start AS start, n.duration AS duration, n.end AS end ".
"FROM " . DB_PREFIX . "ENTRY n, " . DB_PREFIX . "EVENT e ".
"WHERE n.ID_USER=" . p($id) . " AND n.ID_USER=e.ID_USER AND e.ID=n.ID_EVENT ".
  " ORDER BY n.start DESC LIMIT 0,10");
while ($row = mysql_fetch_array($sql)) {
	$event=h($row["event"]);
	$color=$row["color"];
	$start=str_replace(" ", "T", $row["start"])."+01:00";
	$duration=duration($row["duration"]);
	$end=$row["end"];
	$entries[$start]=array(
	  "title"=>"&quot;".$event.'&quot;'.($end!=null?
	  ' (<i18n ref="rep2" />: '.$duration.")":""),
	  "link"=>"/util/briefing.php?a=".$aid."&amp;q=".$event,
	  "summary"=>i18n('<i18n ref="mph2" />', $lang)." &quot;".$event.'&quot;'.($end!=null?
	  ' (<i18n ref="rep2" />: '.$duration.")":""),
	  "time"=>$start
	);
}
mysql_free_result($sql);

$before=array_pop(array_keys($entries));

// infos since first of 10 events
$sql = @mysql_query("SELECT info, start FROM ". DB_PREFIX ."INFO WHERE ID_USER=".p($id)." AND start > '".p($before)."' ORDER BY start DESC");
while ($row = mysql_fetch_array($sql)) {
	$info=h($row["info"]);
	$start=str_replace(" ", "T", $row["start"])."+01:00";
	$entries[$start]=array(
	  "title"=>$info,
	  "link"=>"",
	  "summary"=>'<i18n ref="mph4" />: '.$info,
	  "time"=>$start
	);
}
mysql_free_result($sql);

krsort($entries);
// first entry
$lastUpdate=array_shift(array_keys($entries));
$tagPrefix=substr(DOMAIN, strpos(DOMAIN, "://")+3).",";
?>
<feed xml:lang="<?php echo('<lang/>-<LANG/>'); ?>" xmlns="http://www.w3.org/2005/Atom">
  <title><?php echo(User::getInstance()->getName() . " - ".i18n("<app_name/>")); ?>  Activity Feed</title>
  <subtitle><?php echo(User::getInstance()->getName()); ?>:<i18n ref='con21' /></subtitle>
  <link href="<domain/>/util/atom.php?a=<?php echo($aid);?>" rel="self"/>
  <updated><?php echo($lastUpdate); ?></updated>
  <author>
   <name><?php echo(User::getInstance()->getName()); ?></name>
   <email><?php echo(User::getInstance()->getEmail()); ?></email>
  </author>
  <id>tag:<?php echo($tagPrefix.substr($lastUpdate, 0, 4).":"); ?><domain/></id>
<?php foreach($entries as $update=>$entry) { ?>
  <entry>
   <title><?php echo($entry["title"]); ?></title>
   <link type='text/html' href='<domain/><?php echo($entry["link"]); ?>'/>
   <id>tag:<?php echo($tagPrefix.substr($entry["time"], 0, 4).":"); ?><domain/><?php echo($entry["link"]."#".h($entry["time"])); ?></id>
   <updated><?php echo($entry["time"]); ?></updated>
   <author>
    <name><?php echo(User::getInstance()->getName()); ?></name>
   </author>
   <summary><?php echo($entry["summary"]); ?></summary>
  </entry>
<?php } ?>
</feed>