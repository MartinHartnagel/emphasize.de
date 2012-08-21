<?php
include_once(dirname(__FILE__).'/../includes/config.php');
$export="js";
$incl=true;
$src=$_SERVER['QUERY_STRING'];

// check cache
$cache=dirname(__FILE__)."/../cache/thumb".str_replace(array("..", "/"), array("", "_"), $src);
if (!isset($incl) && file_exists($cache)) {
    header('Content-Type: image/png');
    readfile($cache);
} else {
  include_once(dirname(__FILE__).'/shadow.php');
  imagealphablending($mask, true);
  imagecopyresampled($mask, $image, $ow+$g, $g, 0, 0, $w, $h, $w, $h);
  imagedestroy($image);
  $thumb = imagecreatetruecolor($thumb_size, $thumb_size);
  imagealphablending($thumb, false);
  $back=imagecolorallocatealpha($thumb, 0,0, 0, 127);
  imagefilledrectangle($thumb, 0, 0, $thumb_size, $thumb_size, $back);
  imagealphablending($thumb, true);
  if ($fw < $thumb_size && $fh < $thumb_size) {
	  imagecopyresampled($thumb, $mask, ($thumb_size-$fw)/2, ($thumb_size-$fh)/2, 0, 0, $fw, $fh, $fw, $fh);
  } else if ($fw>$fh) {
	  imagecopyresampled($thumb, $mask, 0, ($thumb_size-$thumb_size*$fh/$fw)/2 , 0, 0, $thumb_size, $thumb_size*$fh/$fw, $fw, $fh);
  } else {
	  imagecopyresampled($thumb, $mask, ($thumb_size-$thumb_size*$fw/$fh)/2 ,0 ,0 ,0, $thumb_size*$fw/$fh, $thumb_size, $fw, $fh);
  }
  imagedestroy($mask);
  header('Content-Type: image/png');
  imagesavealpha($thumb, true);
  // write cache
  imagepng($thumb, $cache, 9);
	readfile($cache);
  imagedestroy($thumb);
}
?>
