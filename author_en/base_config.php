<?php

if (true) {
if ((isset($_GET["id"]) && is_numeric($_GET["id"]) === false) 
|| (isset($_POST["id"]) && is_numeric($_POST["id"]) === false)
|| (isset($_GET["Entry"]) && is_numeric($_GET["Entry"]) === false) 
|| (isset($_POST["Entry"]) && is_numeric($_POST["Entry"]) === false)
|| (strpos($_GET["lang"], " ") !== false) || (strpos($_POST["lang"], " ") !== false)
|| (strpos($_GET["lang"], "union") !== false) || (strpos($_POST["lang"], "union") !== false)
|| (strpos($_GET["Filter"], "union") !== false) || (strpos($_POST["Filter"], "union") !== false)
|| (strpos($_GET["f"], "union") !== false) || (strpos($_POST["f"], "union") !== false)) {
  mail("martin-em@emphasize.de",
    	 "[attack detected] emphasize.de " . $domain,
	     "get: " . implode(',', $_GET) . "\r\npost: " . implode(',', $_POST) . "\r\nserver: " . implode(',', $_SERVER),
    	 "From: martin-em@emphasize.de\r\nContent-Type: text/plain; charset=iso-8859-1\r\nContent-Transfer-Encoding: 8bit\r\n\r\n");
  die("access denied, attack detected");
}
}

header("Content-Type: text/html;charset=UTF-8"); 

$top_domain_base = "/var/www/html/web78/"; // absolute path to the domain dir
$blog_base = $top_domain_base . "files/blog/";
$fullname=$name . " " . $surname; // full name
$domain = "http://" . $author . ".emphasize.de"; // sub-domain
$domain_path = $top_domain_base . "html/" . $author . "/";
$upload_path = $domain_path . "images/"; //images stored
$upload_base = $domain . "/images/"; //url base to stored images
$img_base = $upload_base;

$style = "http://www.emphasize.de/default.css"; // css

$db_name = "usr_web78_1"; //Name of the host database
$db_host = "localhost"; //Database host
$db_username = "web78"; //Database login
$db_password = "guru108yoga"; //Database password

$default_backlink_id=-1;

if ((isset($_GET["Filter"]) && ($_GET["Filter"]) != "")||(isset($_GET["f"]) && ($_GET["f"]) != "")) {
  $filter=$_GET["Filter"].$_GET["f"];
  $filterParam = "Filter=" . $filter;
} else {
  $filterParam = "";
}

if (!isset($id)) {
  if ((isset($_GET["Entry"]) && ($_GET["Entry"]*1 > 0))||(isset($_GET["id"]) && ($_GET["id"]*1 > 0))) {
    $id=$_GET["Entry"].$_GET["id"];
  }
}
$blogsPreviewHalf=3;

if ($_GET["Show"] == "All"){
  $max = -1;
} else {
  $max = 10;
}
$formACTION = basename($_SERVER['PHP_SELF']); 
$url=$domain . $_SERVER['REQUEST_URI'];
$refer=$_SERVER["HTTP_REFERER"];
$agent=$_SERVER['HTTP_USER_AGENT'];
$visitor = gethostbyaddr($_SERVER['REMOTE_ADDR']);
//CONNECT TO DB
$dbcnx = @mysql_connect($db_host, $db_username, $db_password);
if ($dbcnx) {
mysql_query("SET NAMES 'utf8'");
if (@mysql_select_db($db_name) ) {
$headtitle = $domain;
if (isset($id) && ($id >= 0)) {
  $sql = mysql_query("SELECT log_heading FROM " . $db_prefix. "blog WHERE log_author='$author' AND log_id='$id' ORDER BY log_id DESC"); 

if ($row = mysql_fetch_array($sql)) {
$header = stripslashes($row["log_heading"]);
if (strpos($header, "_special_" )===false) {
$headtitle = $author . " - " . $header;
}
}
}
  $sql = mysql_query("INSERT INTO visits SET title='$headtitle', referer='$refer', url='$url', agent='$agent', visitor='$visitor'");  
}
}

if (!isset($feedback_to)) {
  $feedback_to = "webmaster@emphasize.de"; //mail address to send feedback to
}

// feedback generator messages, lower index is positiv, higher negative feedback
$feedback_messages = array(
'Superb!',
'Wonderful!',
'Great!',
'Good.',
'OK.',
'Not so good.',
'Too Bad!',
'Dreadful!',
'Horrible!'
);

function langFilter($buffer) 
{
  $lang=$_GET["lang"] . $_POST["lang"];
  if ($lang == "") {
   $lang=detect_lang();
    $mode="detected";
  } else {
    $mode="set";
  }

  if ($lang=="en") {
  $buffer=preg_replace("/<(fr|de)>.*?<\/(fr|de)>/s", "", $buffer);
  $buffer=preg_replace("/<\/?en>/", "", $buffer);
  } else if ($lang=="fr") {
  $buffer=preg_replace("/<(en|de)>.*?<\/(en|de)>/s", "", $buffer);
  $buffer=preg_replace("/<\/?fr>/", "", $buffer);
  } else {
  $buffer=preg_replace("/<(en|fr)>.*?<\/(en|fr)>/s", "", $buffer);
  $buffer=preg_replace("/(<\/?)de>/", "", $buffer);
  }
  return $buffer;
}

ob_start("langFilter");


// Define default language. 
$GLOBALS['_DLANG']='en'; 

// Define all available languages. 
// WARNING: uncomment all available languages 

$GLOBALS['_LANG'] = array( 
'de', // german. 
'en', // english. 
'fr' // french. 
); 

function get_env_var($Var) 
{ 
 if(empty($GLOBALS[$Var])) 
 { 
  $GLOBALS[$Var]=(!empty($GLOBALS['_SERVER'][$Var]))? 
  $GLOBALS['_SERVER'][$Var]: 
  (!empty($GLOBALS['HTTP_SERVER_VARS'][$Var]))? 
  $GLOBALS['HTTP_SERVER_VARS'][$Var]:''; 
 } 
} 

function detect_lang() 
{ 
 // Detect HTTP_ACCEPT_LANGUAGE & HTTP_USER_AGENT. 
 get_env_var('HTTP_ACCEPT_LANGUAGE'); 
 get_env_var('HTTP_USER_AGENT'); 
  
 $_AL=strtolower($GLOBALS['HTTP_ACCEPT_LANGUAGE']); 
 $_UA=strtolower($GLOBALS['HTTP_USER_AGENT']); 
  
 foreach($GLOBALS['_LANG'] as $K) 
 { 
  if(strpos($_AL, $K)===0) 
   return $K; 
 } 
  
 foreach($GLOBALS['_LANG'] as $K) 
 { 
  if(strpos($_AL, $K)!==false) 
   return $K; 
 } 
 foreach($GLOBALS['_LANG'] as $K) 
 { 
  if(preg_match("/[[( ]{$K}[;,_-)]/",$_UA)) 
   return $K; 
 } 
  
 return $GLOBALS['_DLANG']; 
} 

function longLink($title, $id) {
  $file=str_replace(array("\\", "/", " - ", " ", "&", ".", "'"), array("\\", "_", "-", "_", "", "", "_"), $title).".php";
  if (!file_exists($file)) {
    file_put_contents($file, '<?php if (!isset($_GET["id"])) $id='.$id.';'."\n".' include_once(dirname(__FILE__)."/index.php");?>');
  }
  return $file;
}
?>
