<?php
function myErrorHandler($errno, $errstr, $errfile, $errline) {
 if (!(error_reporting() & $errno)) {
  // This error code is not included in error_reporting
  return;
 }

 switch ($errno) {
  case E_USER_ERROR :
   echo "<br clear='all' /><b>My ERROR</b> [$errno] $errstr<br />\n";
   echo "  Fatal error on line $errline in file $errfile";
   echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
   echo "Aborting...<br />\n";
   exit (1);
   break;

  case E_USER_WARNING :
   echo "<br clear='all' /><b>My WARNING</b> [$errno] $errstr<br />\n";
   break;

  case E_USER_NOTICE :
   echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
   break;

  default :
   echo "<br clear='all' />$errfile:$errline Unknown error type: [$errno] $errstr<br />\n";
   // var_dump(debug_backtrace());
   break;
 }

 /* Don't execute PHP internal error handler */
 return true;
}

/*function debug_memory($reason) {
 error_log($reason.' (memory: '.memory_get_usage().')');
$e = new Exception;
error_log(var_export($e->getTraceAsString(), true));
}*/

if (!defined('TESTING')) {
 $old_error_handler = set_error_handler("myErrorHandler");
}

$content_names = array ();
$content_value = array ();
$content_names["work"] = i18n("<i18n key='con0'><en>Work</en><de>Arbeit</de><fr>Travail</fr><es>Trabajo</es></i18n>");
$content_value["work"] = i18n('<tr><td style="background-color:#e88013"><i18n key="con2"><en>Work</en><de>Arbeit</de><fr>Travail</fr><es>Trabajo</es></i18n></td></tr>');
$content_names["dev"] = i18n("<i18n key='con3'><en>Software Development</en><de>Software Entwicklung</de><fr>Software Development</fr><es>Desarrollo de Software</es></i18n>");
$content_value["dev"] = i18n('<tr><td rowspan="2" style="background-color:#bdc406"><i18n key="con5"><en>Implementation</en><de>Implementierung</de><fr>La mise en œuvre</fr><es>Aplicación</es></i18n></td><td style="background-color:#6ba163"><i18n key="con6"><en>Documentation</en><de>Dokumentation</de><fr>Documentation</fr><es>Documentación</es></i18n></td></tr><tr><td style="background-color:#a16363"><i18n key="con7"><en>Test</en><de>Testen</de><fr>Test</fr><es>Prueba</es></i18n></td></tr>');
$content_names["support"] = i18n("<i18n key='con8'><en>Support</en><de>Support</de><fr>Support</fr><es>Apoyo</es></i18n>");
$content_value["support"] = i18n('<tr><td style="background-color:#ecfe32" colspan="2"><i18n key="con10"><en>Training</en><de>Schulung</de><fr>Formation</fr><es>Capacitación</es></i18n></td></tr><tr><td style="background-color:#001ff7"><i18n key="con11"><en>Consultation</en><de>Beratung</de><fr>Conseils</fr><es>Consulta</es></i18n></td><td style="background-color:#ff0b1f"><i18n key="con12"><en>Troubleshooting</en><de>Fehlerbehebung</de><fr>Dépannage</fr><es>Solución de problemas</es></i18n></td></tr>');
$content_names["admin"] = i18n("<i18n key='con13'><en>Administration</en><de>Administration</de><fr>Administration</fr><es>Administración</es></i18n>");
$content_value["admin"] = i18n('<tr><td style="background-color:#3f57ff"><i18n key="con15"><en>Installation</en><de>Installation</de><fr>Installation</fr><es>Instalación</es></i18n></td></tr><tr><td style="background-color:#6ba163"><i18n ref="con6"></i18n></td></tr><tr><td style="background-color:#e80068"><i18n key="con17"><en>Data Backup</en><de>Datensicherung</de><fr>Sauvegarde des données</fr><es>Copia de seguridad</es></i18n></td></tr>');

$reports = array ();
$reports["daily"] = i18n("<i18n key='con18'><en>Daily Report</en><de>Täglicher Bericht</de><fr>Rapport journalier</fr><es>Informe día a día</es></i18n>");
$reports["monthly"] = i18n("<i18n key='con19'><en>Monthly Report</en><de>Monatlicher Bericht</de><fr>Rapport de mois</fr><es>Informe mes a mes</es></i18n>");
$reports["hourly"] = i18n("<i18n key='con20'><en>Hourly Report</en><de>Stündlicher Bericht</de><fr>Rapport horaires</fr><es>Informe hora a hora</es></i18n>");
$reports["reportables"] = i18n("<i18n key='con21'><en>activities in detail</en><de>Tätigkeiten im Detail</de><fr>Activités en détail</fr><es>Activitdads en detalle</es></i18n>");
$reports["infos"] = i18n("<i18n key='con22'><en>Infos in detail</en><de>Infos im Detail</de><fr>Infos en détail</fr><es>Infos en detalle</es></i18n>");

$rangeReports["monthly"] = i18n("<i18n key='con40'><en>Report of Month</en><de>Bericht des Monats</de><fr>Rapport du mois</fr><es>Informe del Mes</es></i18n>");
$rangeReports["weekly"] = i18n("<i18n key='con41'><en>Report of Week</en><de>Bericht der Woche</de><fr>Rapport de la Semaine</fr><es>Informe de la Semana</es></i18n>");
$rangeReports["daily"] = i18n("<i18n key='con42'><en>Report of Day</en><de>Bericht des Tages</de><fr>Rapport de la Journée</fr><es>Informe del Día</es></i18n>");

// current not-user vars
$max_import_filesize = 81920;

// avatar constants
$max_avatar_width = 256;
$max_avatar_height = 256;
$max_avatar_filesize = 81920;
$thumb_size = 128;

// mail queue processing
$processMailsInQueuesAtOnce = 5;

/**
 * Checks if php is run in client mode.
 * @return boolean true if in client mode.
 */
function isCli() {
 return defined('STDIN');
}

/**
 * Gets a request parameter which is either defined in the POST oder GET arrray-variable.
 * @param String $param key to obtain the parameter for.
 * @return the value or <code>null</code> if not defined.
 */
function r($param) {
 if (!empty($_REQUEST)) {
  if (isset($_REQUEST[$param])) {
   return $_REQUEST[$param];
  }
 }
 return null;
}

// replace single quote with two single quotes for sql-injection prevention
function p($param) {
 if (get_magic_quotes_gpc() == 1) {
  $r = stripslashes($param);
 } else {
  $r = $param;
 }
 $r = str_replace("'", "&apos;", $r);
 return $r;
}

function h($param) {
 if (get_magic_quotes_gpc() == 1) {
  $r = stripslashes($param);
 } else {
  $r = $param;
 }
 // workaround for migration-data-fix 1.5
 if (strpos($r, '\"') !== false) {
  $r = stripslashes($param);
 }
 $r = htmlspecialchars($r);
 return $r;
}

function pw_hash($pw) {
 return substr("00000000000000000000" . md5($pw), -20);
}

function gen_token($name) {
 do {
  $t=substr("0000000" . base_convert(md5($name . time()), 16, 36), -7);
 } while(is_dir(dirname(__FILE__).'/../i/'.$t));
 $success=mkdir(dirname(__FILE__).'/../i/'.$t);
 if (!$success) {
  fail("creating token dir ".$t." failed");
 }
 file_put_contents(dirname(__FILE__).'/../i/'.$t.'/index.php', '<?php' . "\n" . ' $token="' . $t . '";' . "\n" . ' require_once(dirname(__FILE__)."/../../index.php");'."\n".'?>');
 return $t;
}

function confirm_code() {
 return substr("000000" . base_convert(md5(time()), 16, 36), -6);
}

function generateUniqueId($length, $table, $idRow) {
 do {
  $uniqueId = substr(str_repeat("0", $length) . base_convert(md5(time() . rand()), 16, 36), - $length);
  if (isset ($asql))
   mysql_free_result($asql);
  $asql = @ mysql_query("SELECT " . $idRow . " FROM " . DB_PREFIX . $table . " WHERE " . $idRow . "='" . p($uniqueId) . "'");
 } while ($row = mysql_fetch_array($asql));
 mysql_free_result($asql);
 return $uniqueId;
}

function detectLang() {
 global $al;
 global $lang;

 if (isset ($lang) && strlen($lang) > 1) {
  // use $lang
 } else
  if (r("lang") != "") {
  $lang = r("lang");
 } else {
  $lang = "";
 }

 if (!in_array($lang, $al)) {
  $lang="";
 }

 if ($lang == "") {
  if (isset($al)) {
   $langs = $al;

   if (isset ($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $_AL = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
   } else {
    $_AL = "";
   }
   if (isset ($_SERVER['HTTP_USER_AGENT'])) {
    $_UA = strtolower($_SERVER['HTTP_USER_AGENT']);
   } else {
    $_UA = "";
   }


   foreach ($langs as $K) {
    if (strpos($_AL, $K) === 0)
     return $K;
   }

   foreach ($langs as $K) {
    if (strpos($_AL, $K) !== false)
     return $K;
   }
   foreach ($langs as $K) {
    if ((strpos($_UA, "[" . $K . ";") !== false) || (strpos($_UA, "(" . $K . ";") !== false) || (strpos($_UA, " " . $K . ";") !== false) || (strpos($_UA, "[" . $K . ",") !== false) || (strpos($_UA, "(" . $K . ",") !== false) || (strpos($_UA, " " . $K . ",") !== false) || (strpos($_UA, "[" . $K . "_") !== false) || (strpos($_UA, "(" . $K . "_") !== false) || (strpos($_UA, " " . $K . "_") !== false) || (strpos($_UA, "[" . $K . "-") !== false) || (strpos($_UA, "(" . $K . "-") !== false) || (strpos($_UA, " " . $K . "-") !== false) || (strpos($_UA, "[" . $K . ")") !== false) || (strpos($_UA, "(" . $K . ")") !== false) || (strpos($_UA, " " . $K . ")") !== false))
     return $K;
   }
  }

  return "en";
 }
 return $lang;
}

function detectTranslate() {
 global $lc;
 $langs = array_keys($lc);

 if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && isset($_SERVER['HTTP_USER_AGENT'])) {

  $_AL = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
  $_UA = strtolower($_SERVER['HTTP_USER_AGENT']);

  foreach ($langs as $K) {
   if (strpos($_AL, $K) === 0)
    return $K;
  }

  foreach ($langs as $K) {
   if (strpos($_AL, $K) !== false)
    return $K;
  }
  foreach ($langs as $K) {
   if ((strpos($_UA, "[" . $K . ";") !== false) || (strpos($_UA, "(" . $K . ";") !== false) || (strpos($_UA, " " . $K . ";") !== false) || (strpos($_UA, "[" . $K . ",") !== false) || (strpos($_UA, "(" . $K . ",") !== false) || (strpos($_UA, " " . $K . ",") !== false) || (strpos($_UA, "[" . $K . "_") !== false) || (strpos($_UA, "(" . $K . "_") !== false) || (strpos($_UA, " " . $K . "_") !== false) || (strpos($_UA, "[" . $K . "-") !== false) || (strpos($_UA, "(" . $K . "-") !== false) || (strpos($_UA, " " . $K . "-") !== false) || (strpos($_UA, "[" . $K . ")") !== false) || (strpos($_UA, "(" . $K . ")") !== false) || (strpos($_UA, " " . $K . ")") !== false))
    return $K;
  }

 }

 return "en";
}

/**
 * Output-buffer filter.
 * @param string $buffer contents outputted so far.
 * @return string the filtered final output.
 */
function filter($buffer) {

 global $lang;
 global $export;
 global $cache;
 global $ob_cancel;
 global $ob_buffer;

 if (isset($ob_cancel) && $ob_cancel) {
  $ob_buffer=$buffer;
  return "";
 }

 if (!isset ($export)) {
   header("Content-Type: text/html;charset=UTF-8");
   $buffer = file_get_contents(dirname(__FILE__) . "/head.html").$buffer;
 }
 $buffer = i18n($buffer);

 if (r("ajax") == "true") {
  $grep = r("grep");
  $buffer=between($buffer, "<!--" . $grep . "-->", "<!--/" . $grep . "-->");
 }

 if (!DEV && isset ($cache)) {
  file_put_contents($cache, $buffer);
 }

 return $buffer;
}

if (!isset ($no_ob_start)) {
 ob_start("filter");
}

function bottom() {
 global $export;

 global $lc;
 global $al;
 global $ob_cancel;

 if (isset($ob_cancel) && $ob_cancel) {
  return;
 }

 if (!isset ($export)) {
  include_once (dirname(__FILE__) . "/bottom.php");
 }

 ob_end_flush();
 exit ();
}

// nizip
function debugMail($txt) {
 enqueueMail(FEEDBACK_TO, "[Emphasize] debug", $txt, "From: debug@emphasize.de\r\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: 8bit\r\n\r\n");
}

function longUrl($header, $bid) {
 return DOMAIN . "/" . longLink(stripslashes(str_replace(array (
   "&amp;",
   "'",
   "#252;",
   "#246;",
   "&uuml;"
 ), array (
   "and",
   "",
   "u",
   "oe",
   "ue"
 ), $header)), $bid);
}

function longLink($title, $bid) {
 global $lang;
 $file = str_replace(array (
   "<app_name/>",
   "<domain/>",
   "\\",
   "/",
   " - ",
   " ",
   "&",
   ".",
 ), array (
   APP_NAME,
   DOMAIN,
   "\\",
   "_",
   "-",
   "_",
   "",
   "",
   "_"
 ), $title) . ".php";
 if (!file_exists(dirname(__FILE__) . "/../" . $file)) {
  file_put_contents(dirname(__FILE__) . "/../" . $file, '<?php' . "\n" . ' if (!isset($_GET["bid"]) || empty($_GET["bid"])) $bid=' . $bid . ';' . "\n" . ' $lang="' . $lang . '";' . "\n" . ' include_once(dirname(__FILE__)."/index.php");?>');
 }
 return $file;
}
// /nizip

function addInfo($info, $time) {
 $id=User :: getInstance()->getId();

 $delete = @ mysql_query("DELETE FROM " . DB_PREFIX . "INFO WHERE id_user=" . p($id) . " AND start >=DATE_ADD('" . p($time) . "', INTERVAL -2 MINUTE) AND start <= DATE_ADD('" . p($time) . "', INTERVAL 2 MINUTE)");
 if (!$delete) {
  fail("delete failed");
 }
 echo ("deleted [$time,+-2mins], ");

 if (strlen(trim($info)) > 0) {
  $insert = @ mysql_query("INSERT " . DB_PREFIX . "INFO SET id_user=" . p($id) . ", info='" . p($info) . "', start='" . p($time) . "'");
  if (!$insert) {
   fail("insert failed");
  }
  echo ("inserted new info at $time.");
 }
}

function fail($msg) {
 global $error;

 global $no_ob_start;
 global $ob_buffer;

 $message = i18n($msg);

 if (!isset($no_ob_start)) {
  ob_end_clean();
 }

 if (defined('TESTING')) {
  $ob_buffer=$message;
  throw new Exception($message);
 }

 echo ('<title>Emphasize - "' . substr($message, 0, 40) . '"</title>');
 echo ('<body><p class=\"red\"><center>' . $message . '</center></p>' . "\n");
 echo ('<form action="' . DOMAIN . '/" method="POST"><input type="submit" value="Re-Login" /></form>');
 echo ("<!-- " . mysql_error() . " -->");

 exit ();
}

function duration($secs) {
 $vals = array (
   'w' => (int) ($secs / 86400 / 7),
   'd' => $secs / 86400 % 7,
   'h' => $secs / 3600 % 24,
   'm' => $secs / 60 % 60,
   's' => $secs % 60
 );

 $ret = array ();
 foreach ($vals as $k => $v) {
  if ($v > 0) {
   $ret[] = $v . $k;
  }
 }
 return join(' ', $ret);
}

function csvTime($secs) {
 $vals = array (
   'h' => floor($secs / 3600),
   'm' => $secs / 60 % 60,
   's' => $secs % 60
 );

 $ret = array ();
 foreach ($vals as $k => $v) {
  if ($v == 0) {
   $ret[] = "00";
  } else
   if ($v < 10) {
   $ret[] = "0" . $v;
  } else {
   $ret[] = $v;
  }
 }
 return "=" . i18n("<i18n key='con26'><en>TIME</en><de>ZEIT</de><fr>TEMPS</fr><es>NSHORA</es><cs>ČAS</cs><da>TID</da><fi>AIKA</fi><hu>IDŐ</hu><it>ORARIO</it><no>TID</no><nl>TIJD</nl><pl>CZAS</pl><pt>TEMPO</pt><ru>ВРЕМЯ</ru><sv>KLOCKSLAG</sv><tr>ZAMAN</tr></i18n>") . "(" . join(';', $ret) . ")";
}

function hhmmss($secs) {
 $vals = array (
   'h' => floor($secs / 3600),
   'm' => $secs / 60 % 60,
   's' => $secs % 60
 );

 $ret = array ();
 foreach ($vals as $k => $v) {
  if ($v == 0) {
   $ret[] = "00";
  } else
   if ($v < 10) {
   $ret[] = "0" . $v;
  } else {
   $ret[] = $v;
  }
 }
 return join(':', $ret) . ".0";
}

function getTimelineHistory($now, $before) {
 $id=User :: getInstance()->getId();
 // load-entry
 $insert = @ mysql_query("REPLACE INTO " . DB_PREFIX . "LOAD SET id_user=" . p($id) . ", time=FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(CURRENT_TIMESTAMP)/600)*600)");
 if (!$insert) {
  fail("unexpected: load insert failed");
 }

 getEvents($before, $now);
}

/**
 * Checks if a cache file $cacheFile exists and delivers the contents if existing.
 * Otherwise output-buffering will be used in {@link filter()} to create the $cacheFile.
 * @param string $cacheFile name of a file in the cache specific for a certain context content.
 */
function checkCache($cacheFile) {
 global $export;
 global $cache;

 $prefix = substr(DOMAIN, strpos(DOMAIN, "//") + 2);
 if (strpos($prefix, "/") !== false) {
  $prefix = substr($prefix, 0, strpos($prefix, "/"));
 }
 $cache = CACHE . "/" . $prefix . "_" . $cacheFile;

 if (r("ajax") == "true") {
  $grep = r("grep");
  $cache.=".".$grep;
 }

 if (!file_exists($cache)) {
  return;
 }
 $cachetime = filemtime($cache);
 if ($cachetime < time()-86400) {
  // cached objects invalidate after 24 h
  if (file_exists($cache)) {
   unlink($cache);
  }
  if (file_exists($cache.".gz")) {
   unlink($cache.".gz");
  }
  return;
 }
 if (DEV) {
  if (file_exists($cache)) {
   unlink($cache);
  }
  if (file_exists($cache.".gz")) {
   unlink($cache.".gz");
  }
  return;
 }

 // a valid cached copy exists
 global $ob_cancel;
 $ob_cancel=true;
 ob_end_clean();

 if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip")){
  if (file_exists($cache) && !file_exists($cache.".gz")) {
   if($fp_out=gzopen($cache.".gz", 'wb9')){
    if($fp_in=fopen($cache, 'rb')){
     while(!feof($fp_in))
      gzwrite($fp_out,fread($fp_in, 2048));
     fclose($fp_in);
    }
    gzclose($fp_out);
   }
  }
  header("X-Compression: gzip");
  header("Content-Encoding: gzip");
  $cache.=".gz";
 } else {
  header("X-Compression: None");
 }

 if (!isset ($export)) {
   header("Content-Type: text/html;charset=UTF-8");
 }

 header("Content-Length: ".filesize($cache));
 readfile($cache);
 exit();
}

function getI18NProcess($lang) {
 $sql = @ mysql_query("SELECT COUNT(DISTINCT(`KEY`)) AS c FROM " . DB_PREFIX . "I18N WHERE `LANG`='en'");
 if ($row = mysql_fetch_array($sql)) {
  $total = $row["c"];
 }
 mysql_free_result($sql);

 $sql = @ mysql_query("SELECT COUNT(DISTINCT(`KEY`)) AS c FROM " . DB_PREFIX . "I18N WHERE `LANG`='" . p($lang) . "'");
 if ($row = mysql_fetch_array($sql)) {
  $part = $row["c"];
 }
 mysql_free_result($sql);
 return array (
   $part,
   $total
 );
}

function getI18Ns($from_lang, $lang) {
 $sql = @ mysql_query("SELECT `KEY` AS k, en_value, `VALUE` AS v FROM (SELECT t2.`KEY`, t2.`VALUE` AS en_value, t0.`VALUE` FROM " . DB_PREFIX . "I18N t0 RIGHT JOIN " . DB_PREFIX . "I18N t2 ON t0.`KEY` = t2.`KEY` AND t0.`LANG`='" . p($lang) . "' WHERE t2.`LANG`='" . p($from_lang) . "' AND t2.`KEY` IN (SELECT DISTINCT(t1.`KEY`) FROM " . DB_PREFIX . "I18N t1 WHERE t1.`LANG`='" . p($from_lang) . "') ORDER BY t2.`UPDATE` DESC, t0.`UPDATE` DESC) AS tmp GROUP BY `KEY` ORDER BY LENGTH(`VALUE`) ASC");
 $set = array ();
 while ($row = mysql_fetch_array($sql)) {
  $vals = array ();
  $vals[] = $row["en_value"];
  $vals[] = $row["v"];
  $set[$row["k"]] = $vals;
 }
 mysql_free_result($sql);
 return $set;
}

function writeI18N($lang, $key, $value) {
 $insert = @ mysql_query("INSERT INTO " . DB_PREFIX . "I18N SET `UPDATE`=CURRENT_TIMESTAMP, `LANG`='" . p($lang) . "', `KEY`='" . p($key) . "', `VALUE`='" . p($value) . "'");
 if (!$insert) {
  fail("insert failed");
 }
 debugMail($lang . "-" . $key . " added " . $value);
}

function startsWith($haystack, $needle, $case = true) {
 if ($case) {
  return (strcmp(substr($haystack, 0, strlen($needle)), $needle) === 0);
 }
 return (strcasecmp(substr($haystack, 0, strlen($needle)), $needle) === 0);
}

function endsWith($haystack, $needle, $case = true) {
 if ($case) {
  return (strcmp(substr($haystack, strlen($haystack) - strlen($needle)), $needle) === 0);
 }
 return (strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)), $needle) === 0);
}

function between($haystack, $before, $after, $case = true) {
 if ($case) {
  $offset = strpos($haystack, $before);
 } else {
  $offset = stripos($haystack, $before);
 }
 if ($offset !== false) {
  $offset = $offset +strlen($before);
  if ($case) {
   $end = strpos($haystack, $after, $offset);
  } else {
   $end = stripos($haystack, $after, $offset);
  }
  if ($end !== false) {
   return substr($haystack, $offset, $end - $offset);
  }
 }
}

function getContent($url) {
 if (!$url_info = parse_url($url)) {
  return $url;
 }

 switch ($url_info['scheme']) {
  case 'https' :
   $scheme = 'ssl://';
   $port = 443;
   break;
  case 'http' :
  default :
   $scheme = '';
   $port = 80;
 }

 $head = "";
 $content = "";

 $fid = @ fsockopen($scheme . $url_info['host'], $port, $errno, $errstr, 30);
 if ($fid) {
  fputs($fid, 'GET ' . (isset ($url_info['path']) ? $url_info['path'] : '/') . (isset ($url_info['query']) ? '?' . $url_info['query'] : '') . " HTTP/1.0\r\n" .
    "Connection: close\r\n" .
    'Host: ' . $url_info['host'] . "\r\n\r\n");
  $inHead = true;
  while (!feof($fid)) {
   if ($inHead) {
    $head .= fgets($fid, 128);
    $pos = strpos($head, "\r\n\r\n");
    if ($pos !== false) {
     $content = substr($head, $pos +4);
     $head = substr($head, 0, $pos);
     $inHead = false;
    }
   } else {
    $content .= fgets($fid, 128);
   }
  }
  fclose($fid);
 }
 $headers = array ();
 $state = "";
 foreach (explode("\r\n", $head) as $pair) {
  if (strpos($pair, ": ") !== false) {
   $p = explode(": ", $pair, 2);
   $headers[$p[0]] = $p[1];
  } else {
   if (strlen($state) > 0) {
    $state .= "\r\n";
   }
   $state .= $pair;
  }
 }
 return array (
   $state,
   $headers,
   $content
 );
}

function replaceInline($text, $match, $prefix) {
 $map = array ();
 $c = 0;
 $ol = strlen($match);
 $pos = 0;
 while (($f = strpos($text, $match, $pos)) !== false) {
  $sep = substr($text, $f + $ol, 1);
  $e = strpos($text, $sep, $f + $ol +1);
  $url = substr($text, $f + $ol +1, $e - $f - $ol -1);
  $res = $prefix . $c;
  $map[$res] = $url;
  $text = substr_replace($text, "cid:" . $res, $f + $ol +1, $e - $f - $ol -1);
  $pos = $f + $ol +1 + strlen("cid:" . $res) + 1;
  $c++;
 }
 return array (
   $text,
   $map
 );
}

function getMailReportNextRunDate($type, $range, $now = null) {
 if ($now == null) {
  $now = time();
 }
 if ($type == "monthly") {
  $offset = (int) $range;
  if ($offset < 0) {
   $first = strtotime("+1 day", strtotime(date('Y-m-t', strtotime("+1 day", strtotime(date('Y-m-t', $now))))));
  } else {
   $first = strtotime("+1 day", strtotime(date('Y-m-t', $now)));
  }
 }
 elseif ($type == "weekly") {
  $offset = (int) $range;
  if ($offset < 0) {
   $offset=$offset%7+7;
  }
  $first = strtotime((7 - date('w', $now)) . " days", $now);
 }
 elseif ($type == "daily") {
  $days = explode(",", $range);
  sort($days, SORT_NUMERIC);
  $t = date('w', $now);
  $n = $days[0];
  for ($g = count($days) - 1; $g >= 0; $g--) {
   if ($t < $days[$g]) {
    $n = $days[$g];
   }
  }
  $offset = ($n + 7 - $t) % 7;
  if ($offset == 0) {
   // next week
   $offset=7;
  }
  $first = strtotime("today", $now);
 } else {
  fail("unkown " . $type);
 }
 return strtotime($offset . " days", $first);
}

function getMailReportFromDate($type, $range) {
 if ($type == "monthly") {
  $offset = (int) $range;
  if ($offset >= 0) {
   return strtotime(date('Y-m-01 00:00:00', strtotime((- $offset -1) . " days", strtotime(date('Y-m-01')))));
  } else {
   return strtotime(date('Y-m-01 00:00:00', strtotime((- $offset) . " days")));
  }
 }
 elseif ($type == "weekly") {
  $offset = (int) $range;
  if ($offset >= 0) {
   return strtotime("-" . (date('w') + floor($offset / 7) * 7) . " days", strtotime("today"));
  } else {
   return strtotime("today -" . (date('w') + floor($offset / 7) * 7) . " days", strtotime("today"));
  }
 }
 elseif ($type == "daily") {
  return strtotime(date('Y-m-d 00:00:00', strtotime("yesterday")));
 }
}

function getMailReportToDate($from, $type) {
 if ($type == "monthly") {
  return strtotime(date('Y-m-t 23:59:59', strtotime($from)));
 }
 elseif ($type == "weekly") {
  return strtotime("+7 days -1 second", strtotime($from));
 }
 elseif ($type == "daily") {
  return strtotime(date('Y-m-d 23:59:59', strtotime($from)));
 }
}

function mailReport($id_user, $cid, $type, $range, $run) {

 global $lang;

 if ($run == date('Y-m-d')) {
  $from = date('Y-m-d', getMailReportFromDate($type, $range));
  $to = date('Y-m-d', getMailReportToDate($from, $type));

  $t = User::getInstance()->idLogin($id_user);
  $sep = sha1(date('r', time()));

  $p = getContent(DOMAIN . "/util/report.php?cron=true&range=" . $type . "&type=" . $type . "&token=" . $t . "&time=" . date('Y-m-d+H:i:s', time()) . "&from=" . $from . "&to=" . $to . "&Submit=X");

  $title = str_replace("&nbsp;", " ", between($p[2], "<title>", "</title>"));

  $style = getContent(DOMAIN . "/style.css");

  $offset = strpos($p[2], "<body onload=\"initReport()\">") + strlen('<body onload="initReport()">');
  $end = strpos($p[2], "</table>") - $offset;

  $removeText = i18n("<i18n key='mai0'><en>Unsubscribe this e-mail report</en><de>Diesen Email Report Abbestellen</de><fr>Se désabonner de ce rapport par courriel</fr><es>Dejar suscripción a este informe por correo electrónico</es></i18n>");

  $poweredBy = i18n("<i18n key='mai1'><en>Powered by</en><de>Ein Dienst von </de><fr>Propulsé par</fr><es>Desarrollado por</es></i18n>");

  $c = str_replace(DOMAIN . '/graphics/info.png', "cid:info", $c);
  $c = "<html><head><title></title><style type=\"text/css\">\n" . $style[2] . "\n</style>\n</head><body bgcolor=\"#dddddd\">" . '<div align="center">' . substr($p[2], $offset, $end) . "</table></div>\n<br><br/><a href=\"" . DOMAIN . "/util/crons.php?cid=" . $cid . "\">" . $removeText . "</a><br><i>" . $poweredBy . " <a href=\"" . DOMAIN . "\">" . DOMAIN . "</a>.</i></body>";
  $bgs = replaceInline($c, "id=\"\"infoIcon", "bg");

  $c = $bgs[0];

  $c = str_replace('<table class="bodyTable" width="800" border="1" cellspacing="1">', '<table class="bodyTable" width="650" border="1" cellspacing="1" style="background: #ffffff; border: 1px dashed #CCCCCC;">', $c);

  $body = "This is a multi-part message in MIME format.\r\n\r\n--PHP-001-{$sep}\r\nContent-Type: multipart/alternative; boundary=\"PHP-002-{$sep}\"\r\nContent-Transfer-Encoding: 7bit\r\n\r\n--PHP-002-{$sep}\r\nContent-Type: text/plain; charset=\"iso-8859-1\"\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\nNot html text here\r\n\r\n--PHP-002-{$sep}\r\nContent-Type: " . $p[1]["Content-Type"] . "\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\n" .
    $c . "\r\n\r\n--PHP-002-{$sep}--";

  foreach ($bgs[1] as $key => $url) {
   $bg = getContent($url);
   $body .= "\r\n\r\n--PHP-001-{$sep}\r\nContent-Type: " . $bg[1]["Content-Type"] . "\r\nContent-Transfer-Encoding: base64\r\nContent-ID: <" . $key . ">\r\n\r\n";
   $body .= chunk_split(base64_encode($bg[2]));
  }

  $url = DOMAIN . '/graphics/info.png';
  $key = "info";
  $bg = getContent($url);
  $body .= "\r\n\r\n--PHP-001-{$sep}\r\nContent-Type: " . $bg[1]["Content-Type"] . "\r\nContent-Transfer-Encoding: base64\r\nContent-ID: <" . $key . ">\r\n\r\n";
  $body .= chunk_split(base64_encode($bg[2]));

  $body .= "\r\n\r\n--PHP-001-{$sep}--";

  if (enqueueMail(User::getInstance()->getEmail(), "[Emphasize] " . $title, $body, "From: " . FEEDBACK_TO . "\r\nX-Mailer: PHP mail\r\nContent-Type: multipart/related; boundary=\"PHP-001-{$sep}\"\r\nMIME-Version: 1.0\r\n\r\n")) {
   echo ("$cid email-report $title sent to ".User::getInstance()->getEmail()."\n");
  } else {
   echo ("$cid email-report $title to ".User::getInstance()->getEmail()." failed\n");
  }
 }

 return date('Y-m-d', getMailReportNextRunDate($type, $range));
}

function instantMail($address, $title, $body, $additionals) {
 global $testmail;

 if (defined('TESTING')) {
  $testmail.="instantMail: ".$address .",".$title.",".$body.",".$additionals."\n\n";
  return true;
 }
 mail($address, $title, $body, $additionals);
}

function enqueueMail($address, $title, $body, $additionals) {
 global $testmail;

 if (defined('TESTING')) {
  $testmail.="enqueueMail: ".$address .",".$title.",".$body.",".$additionals."\n\n";
  return true;
 }

 $insert = @ mysql_query("INSERT " . DB_PREFIX . "MAILQUEUE SET address='" . p($address) . "', title='" . p($title) . "', body='" . p($body) . "', additionals='" . p($additionals) . "'");
 if (!$insert) {
  echo ("INSERT " . DB_PREFIX . "MAILQUEUE SET address='" . p($address) . "', title='" . p($title) . "', body='" . p($body) . "', additionals='" . p($additionals) . "'");
  fail("mailqueue insert failed");
 }
 return true;
}

function dequeueMail() {
 $sql = @ mysql_query("SELECT id, address, title, body, additionals FROM " . DB_PREFIX . "MAILQUEUE ORDER BY id ASC LIMIT 0,1");
 if ($sql === FALSE) {
  fail("dequeue mail query failed");
 }
 $dequeued = 0;
 if ($row = mysql_fetch_array($sql)) {
  $did = $row["id"];
  $address = $row["address"];
  $title = $row["title"];
  $body = $row["body"];
  $additionals = $row["additionals"];
  if (mail($address, $title, $body, $additionals)) {
   $delete = @ mysql_query("DELETE FROM " . DB_PREFIX . "MAILQUEUE WHERE id=" . $did);
   if (!$delete) {
    fail("dequeueMail delete " . $did . " failed");
   }
   $dequeued = 1;
  } else {
   $dequeued = -1;
  }
 }
 mysql_free_result($sql);
 return $dequeued;
}

// nizip
function getUserLoads() {

 $sql = @ mysql_query("select count(s.id) month_users from (SELECT ID_USER id, count(time) hits FROM `" . DB_PREFIX . "LOAD` WHERE TIME > DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -1 MONTH) GROUP BY ID_USER order by count(time) asc) AS s where s.hits > 10");
 if ($sql === FALSE) {
  fail("load query failed");
 }
 $month_users=-1;
 if ($row = mysql_fetch_array($sql)) {
  $month_users = $row["month_users"];
 }
 mysql_free_result($sql);

 $sql = @ mysql_query("select count(s.id) week_users from (SELECT ID_USER id, count(time) hits FROM `" . DB_PREFIX . "LOAD` WHERE TIME > DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -7 DAY) GROUP BY ID_USER order by count(time) asc) AS s where s.hits > 10");
 if ($sql === FALSE) {
  fail("load2 query failed");
 }
 $week_users=-1;
 if ($row = mysql_fetch_array($sql)) {
  $week_users = $row["week_users"];
 }
 mysql_free_result($sql);
 return array('<meta itemprop="worstRating" content="0" /><span itemprop="ratingValue">'.$week_users.'</span><meta itemprop="bestRating" content="'.$month_users.'" />', '<span itemprop="ratingCount">'.$month_users.'</span>');
}
// /nizip

?>
