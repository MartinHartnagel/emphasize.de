<?php 
include_once(dirname(__FILE__).'/../includes/config.php');
$export="png";

connectDb();
if (isset($_GET["w"])) {
  $w=$_GET["w"];
} else {
  $w=144; // one day
}
if (!is_numeric($w)) {
	$w=144; // one day
}
$h=20;

$s=date('Y-m-d H:i:s', time()-($w*600));
$since=substr($s,0,strlen($s)-1)."0";
$offset=strtotime($since);
$img = imagecreatetruecolor($w, $h);
imagealphablending($img, false);
$back=imagecolorallocatealpha($img, 127,127,127, 127);
imagefilledrectangle($img, 0, 0, $w, $h, $back);
imagealphablending($img, true);
$line=imagecolorallocatealpha($img, 25,25,200, 20);
@mysql_query("DELETE FROM `LOG_LOAD` WHERE UNIX_TIMESTAMP(`TIME`)<UNIX_TIMESTAMP(CURRENT_TIMESTAMP)-2592000");
$sql = @mysql_query("SELECT time AS x, COUNT(*) AS y FROM " . $db_prefix . "LOAD WHERE time > '" . $since . "' GROUP BY time ORDER BY time ASC");
while($row = mysql_fetch_array($sql)) {
	$x=floor((strtotime($row["x"])-$offset)/600);
	$y=$row["y"];
	//echo($x.": ".$y."\n");
	imageline($img, $x,$h-1,$x,$h-$y-1, $line);
}
mysql_free_result($sql);

header('Content-Type: image/png');
imagesavealpha($img, true);
imagepng($img, null, 9);
imagedestroy($img);
bottom();
?>
