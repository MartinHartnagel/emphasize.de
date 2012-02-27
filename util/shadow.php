<?php
$src=$_SERVER['QUERY_STRING'];
$cached=false;
if (!isset($incl)) { 
  // check cache
  $cache=dirname(__FILE__)."/../cache/shadow".str_replace(array("..", "/"), array("", "_"), $src);
  if (file_exists($cache)) {
      header('Content-Type: image/png');
      readfile($cache);
      $cached=true;
  }
}
if (!$cached) {
  $image = @imagecreatefrompng($src);
  if (!$image) die();
  imagealphablending($image, false);
  $w= imagesx($image);
  $h= imagesy($image);
  $ow=$h*0.5;
  $mw=$w+$ow;
  $oh=$h*0.5;
  $g=3;
  $mask = imagecreatetruecolor($mw+$g*2, $h+$g*2);
  imagealphablending($mask, false);
  $back=imagecolorallocatealpha($image, 127,127, 127, 0);
  imagefilledrectangle($mask, 0, 0, $mw+$g*2, $h+$g*2, $back);
  // alpha-mask als b/w übernehmen
  for($x=0; $x < $w; $x++) {
	  for($y=0; $y < $h; $y++) {
		  $c = imagecolorat($image, $x, $y );
		  $alpha = ( $c >> 24 ) & 0xFF;
		  $m=imagecolorallocatealpha($mask, $alpha, $alpha, $alpha, 0);
		  imagesetpixel($mask, $x+$g+$y*$ow/$h, $y*($h-$oh)/$h+$g+$oh, $m);
	  }
  }
  if (!isset($incl)) {
	  imagedestroy($image);
  }

  imagefilter($mask, IMG_FILTER_GAUSSIAN_BLUR);
  imagefilter($mask, IMG_FILTER_GAUSSIAN_BLUR);
  imagefilter($mask, IMG_FILTER_GAUSSIAN_BLUR);

  $fw= imagesx($mask);
  $fh= imagesy($mask);

  for($x=0; $x < $fw; $x++) {
	  for($y=0; $y < $fh; $y++) {
		  $c = imagecolorat($mask, $x, $y );
		  $b=(1.0-$y/$h)*80+20;
		  $alpha = max(0, min(127, ($c & 0xFF)*((127-$b)/127)+$b));
		  $m=imagecolorallocatealpha($mask, 0,0,0, $alpha);
		  imagesetpixel($mask, $x, $y, $m);
	  }
  }

  if (!isset($incl)) {
	  header('Content-Type: image/png');
    imagesavealpha($mask, true);
    // write cache
    imagepng($mask, $cache, 9);
  	readfile($cache);
	  imagedestroy($mask);
  }
}
?>
