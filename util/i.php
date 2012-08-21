<?php
$color =  $_SERVER['QUERY_STRING'];
if (strlen($color) != 6) {
	die();
}
header("Content-type: image/png");
$w=128;
$h=10;
$image = @imagecreatetruecolor($w, $h);
imagealphablending($image, false);
$back=imagecolorallocatealpha($image, 127,127, 127, 127);
imagefilledrectangle($image, 0, 0, $w, $h, $back);
imagealphablending($image, true);
$red=hexdec(substr($color,0,2));
$green=hexdec(substr($color,2,2));
$blue=hexdec(substr($color,4,2));
$color=imagecolorallocatealpha($image, $red, $green, $blue, 30);
imagefilledrectangle($image, 0, 0, $w, $h, $color);
imagesavealpha($image, true);
imagepng($image);
imagedestroy($image);
?>
