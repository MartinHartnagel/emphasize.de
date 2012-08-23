<?php
include_once(dirname(__FILE__).'/../includes/config.php');
$export="png";

User::connectDb();
if (isset($_GET["w"])) {
  $w=$_GET["w"];
} else {
  $w=415; // one day
}
if (!is_numeric($w)) {
	$w=415; // one day
}
$h=68; //real img size $w+5, $h+2

$s=date('Y-m-d H:i:s', time()-($w*600));
$since=substr($s,0,strlen($s)-1)."0";
$offset=strtotime($since);
$img = imagecreatetruecolor($w+5, $h+2);
imagealphablending($img, false);
$back=imagecolorallocatealpha($img, 127,127,127, 127);
imagefilledrectangle($img, 0, 0, $w+5, $h+1, $back);
imagealphablending($img, true);
$line=imagecolorallocatealpha($img, 25,25,200, 20);
@mysql_query("DELETE FROM `LOG_LOAD` WHERE UNIX_TIMESTAMP(`TIME`)<UNIX_TIMESTAMP(CURRENT_TIMESTAMP)-2592000");
$sql = @mysql_query("SELECT time AS x, COUNT(*) AS y FROM " . DB_PREFIX . "LOAD WHERE time > '" . $since . "' GROUP BY time ORDER BY time ASC");
while($row = mysql_fetch_array($sql)) {
	$x=floor((strtotime($row["x"])-$offset)/600);
	$y=$row["y"];
	imageline($img, $x,$h-1,$x,$h-$y-1, $line);
}
$grid=imagecolorallocatealpha($img, 25,25,25, 80);
$p=null;
for ($x=0; $x < $w;$x++) {
 $t=date("d", $x*600+$offset);
 if ($p != null && $t != $p) {
  imageline($img, $x,0,$x,$h+1, $grid);
 }
 $p=$t;
}
mysql_free_result($sql);

imageline($img, 0,$h-1,$w+5,$h-1, $grid);
imageline($img, $w,$h-3,$w+5,$h-1, $grid);
imageline($img, $w,$h-2,$w+5,$h-1, $grid);
imageline($img, $w,$h+1,$w+5,$h-1, $grid);
imageline($img, $w,$h,$w+5,$h-1, $grid);
//exit();
header('Content-Type: image/png');
imagesavealpha($img, true);
imagepng($img, null, 9);
imagedestroy($img);
bottom();
?>
