<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once(dirname(__FILE__)."/../includes/configuration.php");
require_once(INC."/languages.php");
require_once(INC."/translations.php");
require_once(INC.'/minify/JSMin.php');

$js=array(
 "jquery-1.9.1.min.js",
 "jquery-ui-1.10.3.custom.min.js",
 "ajaxfileupload.js",
 "dashboard.js",
 "emphasize.js",
 "progress.js",
 "timesortedset.js",
 "timeline.js",
 "avatar.js",
 "metaballs.js",
);

$translate=array(
 "ajaxfileupload.js",
 "dashboard.js",
 "emphasize.js",
 "progress.js",
 "timesortedset.js",
 "timeline.js",
 "avatar.js",
 "metaballs.js",
);

$minify=array(
 "ajaxfileupload.js",
 "dashboard.js",
 "emphasize.js",
 "progress.js",
 "timesortedset.js",
 "timeline.js",
 "avatar.js",
 "metaballs.js",
);

function compress($s) {
 $s="\x1f\x8b\x08\x00\x00\x00\x00\x00".gzcompress($s, 9);
 return $s;
}

function create_js($lang) {
  global $js;
  global $translate;
  global $minify;

  $s="";
  foreach($js as $j) {
    $c=file_get_contents(INC.'/js/'.$j);
    if (in_array($j, $translate)) { // with i18n
      $c.=i18n($c, $lang);
    }
    if (!DEV && in_array($j, $minify)) { // minify
     $c.=JSMin::minify($c);
    }
    $s.=$c."\n";
  }
  $s=compress($s);
  file_put_contents(CACHE.'/'.$lang.'.js.gz', $s);
  return $s;
}

$lang=$_GET["lang"];
ini_set('max_execution_time', '120');

if (!DEV) {
 header('Cache-Control: max-age='.(60*24*3600));
} else {
 header('Cache-Control: max-age=0');
}
header('Content-Type: text/javascript; charset=utf-8');
if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip")) {
  header("X-Compression: gzip");
  header("Content-Encoding: gzip");
}

if (!DEV && file_exists(CACHE.'/'.$lang.'.js.gz')) {
  if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip")) {
    readfile(CACHE.'/'.$lang.'.js.gz');
    exit();
  } else {
    echo(gzuncompress(substr(file_get_contents(CACHE.'/'.$lang.'.js.gz'), 8)));
  }
} else {
  $s=create_js($lang);
  if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip")) {
    echo($s);
  } else {
    echo(gzuncompress(substr($s, 8)));
  }
}
?>
