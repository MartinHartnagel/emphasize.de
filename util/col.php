<?php
$c=$_GET['c'];
$w=$_GET['w'];
$h=$_GET['h'];
$v=$_GET['v'];
$m=$_GET['m'];
header("Content-type: image/png");
$image = @imagecreatetruecolor($w+20, $h);
imagealphablending($image, false);
$back=imagecolorallocatealpha($image, 127,127, 127, 127);
imagefilledrectangle($image, 0, 0, $w+20, $h, $back);
imagealphablending($image, true);
$red=hexdec(substr($c,0,2));
$green=hexdec(substr($c,2,2));
$blue=hexdec(substr($c,4,2));
$color=imagecolorallocatealpha($image, $red, $green, $blue, 64);
imagefilledrectangle($image, 0, 0, $w*$v/$m,$h, $color);
imagesavealpha($image, true);
imagepng($image);
imagedestroy($image);
?>
