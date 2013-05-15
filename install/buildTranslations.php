<?php
include_once (dirname(__FILE__) . "/../includes/config.php");
$export = "php";

$set = array ();

$refs = array ();

// nizip
$insertI18ns = false;
User::connectDb();
// /nizip
recurseFiles(dirname(__FILE__) . "/..");

function recurseFiles($path) {
 $dir_handle = @ opendir($path) or die("Unable to open path " . $path);
 while ($file = readdir($dir_handle)) {
  if (is_file($path . '/' . $file) && $file != "." && $file != ".." && $file != basename(__FILE__)) {
   if (endsWith($file, ".php") || endsWith($file, ".js") || endsWith($file, ".html")) {
    $text = file_get_contents($path . '/' . $file);
    if (strpos($text, "<i18n") !== false) {
     echo ("translating " . $file . "<br/>\n");
     extractI18n($text, $path . '/' .$file);
    }
   }
  } else
   if (is_dir($path . '/' . $file) && $file != "." && $file != "..") {
   recurseFiles($path . '/' . $file);
  }
 }
 //closing the directory
 closedir($dir_handle);
}

function extractI18n($text, $file) {
 global $set;
 global $refs;
 global $lc;
 // nizip
 global $insertI18ns;
 // /nizip
 $c = preg_match_all('/\s*?[<{]i18n\s+key=["\'](.*)["\'][>}](.*)[<{]\/i18n[>}]\s*?/imsU', $text, $matches, PREG_PATTERN_ORDER);
 for ($i = 0; $i < $c; $i++) {
  $match = $matches[0][$i];
  $key = trim($matches[1][$i]);
  $chunk = $matches[2][$i];
  foreach (array_keys($lc) as $l) {
   if (strpos($chunk, "<" . $l . ">") !== false) {
    $lt = preg_replace("/\s\s*/", " ", trim(between($chunk, "<" . $l . ">", "</" . $l . ">")));

   } else if (strpos($chunk, "{" . $l . "}") !== false) {
    $lt = preg_replace("/\s\s*/", " ", trim(between($chunk, "{" . $l . "}", "{/" . $l . "}")));

   } else {
    echo("<br/>missing $l for $key");
    continue;
   }
   if (strlen($lt) > 0) {
    if (isset($set[$key . "." . $l])) {
     fail($key." already defined, duplicate entry in ".$file."\n<br/>");
    }
    $set[$key . "." . $l] = $lt;
    // nizip
    if ($insertI18ns) {
     $insert = @ mysql_query("REPLACE INTO " . DB_PREFIX . "I18N SET `key`='" . p($key) . "', `update`='2011-03-24 00:00:01', lang='" . p($l) . "', value='" . p($lt) . "'");
     if (!$insert) {
      fail("REPLACE INTO " . DB_PREFIX . "I18N SET `key`='" . p($key) . "', `update`='2011-03-24 00:00:01', lang='" . p($l) . "', value='" . p($lt) . "'" . "<br/>" . "insert for \"$key.$l\" failed: " . p($lt));
     }
    }
    // /nizip
   }
  }
 }
}
$c = preg_match_all('/[<{]i18n ref=["\'](.*)["\']/imsU', $text, $matches, PREG_PATTERN_ORDER);
for ($i = 0; $i < $c; $i++) {
 $key = trim($matches[1][$i]);
 if (strpos($key, "$") === false) {
  $refs[] = $key . '.en';
 }
}
}
echo("<br/>");
$missings = false;
foreach ($refs as $k) {
 if (!array_key_exists($k, $set)) {
  echo ("error, not found: " . $k . "!<br/>\n");
  $missings = true;
 }
}
if (!$missings) {
 // output
 $out = '<?php' . "\n";
 $out .= '$i18n=array(' . "\n";
 $first = true;
 foreach ($set as $k => $v) {
  if (strlen(trim($v)) > 0) {
   if ($first) {
    $first = false;
   } else {
    $out .= ",\n";
   }
   $out .= "\"$k\"=>'" . str_replace("'", "\\'", trim(preg_replace('/\s+?/imsU', " ", $v))) . "'";
  }
 }

 $out .= "\n);\n";
 $out .= '?>' . "\n";
 chmod(dirname(__FILE__).'/../includes', 0777);
 file_put_contents(dirname(__FILE__) . "/../includes/translations.php", $out);
 chmod(dirname(__FILE__).'/../includes', 0755);
 echo ("translations.php written<br/>\n<b style=\"color:green;\">done</b>.");
 include_once (dirname(__FILE__) . "/clear.php");
} else {
 echo("<b style=\"color:red;\">aborted</b>.");
}
?>

