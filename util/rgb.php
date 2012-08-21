<?php
$src =  "../".addslashes($_GET['src']);
$x =  addslashes($_GET['x']);
$y =  addslashes($_GET['y']);

$filetype = strtolower(substr($src,strlen($src)-4,4));
if ($filetype == ".gif")  $image = @imagecreatefromgif($src);
if ($filetype == ".jpg" OR $filetype == "jpeg") $image = @imagecreatefromjpeg($src);
if ($filetype == ".png")  $image = @imagecreatefrompng($src);
if (!$image) die();

$imagewidth = imagesx($image);
$imageheight = imagesy($image);
$rgb = imagecolorat($image, floor($x*$imagewidth), floor($y*$imageheight));
$r = dechex(($rgb >> 16) & 0xFF);
$g = dechex(($rgb >> 8) & 0xFF);
$b = dechex($rgb & 0xFF);
if(strlen($r) < 2) {
	$r = 0 . $r;
}
if(strlen($g) < 2) {
	$g = 0 . $g;
}
if(strlen($b) < 2) {
	$b = 0 . $b;
}
echo("#" . $r . $g . $b);
imagedestroy($image);
?>
