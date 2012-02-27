<?php
include_once(dirname(__FILE__).'/../includes/config.php');
if (!isset($_SESSION)) {
	session_start();
}
$aid=$_GET["a"];
$event=$_GET["q"];

connectDb();
$id=aid($aid);

$sql = @mysql_query("SELECT MIN(start) AS first, MAX(end) AS last, UNIX_TIMESTAMP(MAX(end))-UNIX_TIMESTAMP(MIN(start)) AS span, MAX(color) AS color FROM " . $db_prefix . "ENTRY WHERE id_user=$id AND event='".p($event)."' AND end IS NOT NULL GROUP BY id_user ORDER BY DATE(start) ASC");

if ($row = mysql_fetch_array($sql)) {
	$first=$row["first"];
	$last=$row["last"];
	$span=$row["span"];
	$color=$row["color"];
} else {
	mysql_free_result($sql);
	fail("unkown event");
}
mysql_free_result($sql);

$sql = @mysql_query("SELECT UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start) AS sum FROM " . $db_prefix . "ENTRY WHERE id_user=$id AND event='".p($event)."' AND end IS NOT NULL");

$sum=0;
while ($row = mysql_fetch_array($sql)) {
	$sum+=$row["sum"];
}
mysql_free_result($sql);
?>
<title>Briefing: <?php echo($event); ?>
</title>
</head>
<body onload="initReport()">
	<h1>
		Briefing:
		<?php echo($event); ?>
	</h1>
	<table width="800" cols="2" border="0" cellspacing="8">
		<tr>
			<td align="right" width="40%">Erste Startzeit:</td>
			<td align="center" width="60%"><?php echo($first); ?></td>
		</tr>
		<tr>
			<td align="right">Letztes abgeschlossenes Ende:</td>
			<td align="center"><?php echo($last); ?></td>
		</tr>
		<tr>
			<td align="right">Summe:</td>
			<td align="center"><?php echo(duration($sum)); ?></td>
		</tr>
		<tr>
			<td align="right">Farbe:</td>
			<td align="center" bgColor="<?php echo($color); ?>"><?php echo($color); ?>
			</td>
		</tr>
		<tr>
			<td align="right">Durchschnitt pro Tag:</td>
			<td align="center"><?php echo(duration($sum*24*3600/$span)); ?></td>
		</tr>
		<tr>
			<td align="right">Durchschnitt pro Monat:</td>
			<td align="center"><?php echo(duration($sum*30*24*3600/$span)); ?></td>
		</tr>
	</table>
	<br />
	<?php bottom();?>