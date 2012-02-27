<?php

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
	if (!(error_reporting() & $errno)) {
		// This error code is not included in error_reporting
		return;
	}

	switch ($errno) {
		case E_USER_ERROR:
			echo "<br clear='all' /><b>My ERROR</b> [$errno] $errstr<br />\n";
			echo "  Fatal error on line $errline in file $errfile";
			echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
			echo "Aborting...<br />\n";
			exit(1);
			break;

		case E_USER_WARNING:
			echo "<br clear='all' /><b>My WARNING</b> [$errno] $errstr<br />\n";
			break;

		case E_USER_NOTICE:
			echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
			break;

		default:
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

$dbcnx=false;

$old_error_handler = set_error_handler("myErrorHandler");

$tbody_names=array();
$tbody_value=array();
$tbody_names["work"]=i18n("<i18n key='con0'><en>Work</en><de>Arbeit</de><fr>Travail</fr><es>Trabajo</es></i18n>");
$tbody_value["work"]=i18n('<tr><td bgcolor="#e88013"><i18n key="con2"><en>Work</en><de>Arbeit</de><fr>Travail</fr><es>Trabajo</es></i18n></td></tr>');
$tbody_names["dev"]=i18n("<i18n key='con3'><en>Software Development</en><de>Software Entwicklung</de><fr>Software Development</fr><es>Desarrollo de Software</es></i18n>");
$tbody_value["dev"]=i18n('<tr><td rowspan="2" bgcolor="#bdc406"><i18n key="con5"><en>Implementation</en><de>Implementierung</de><fr>La mise en œuvre</fr><es>Aplicación</es></i18n></td><td bgcolor="#6ba163"><i18n key="con6"><en>Documentation</en><de>Dokumentation</de><fr>Documentation</fr><es>Documentación</es></i18n></td></tr><tr><td bgcolor="#a16363"><i18n key="con7"><en>Test</en><de>Testen</de><fr>Test</fr><es>Prueba</es></i18n></td></tr>');
$tbody_names["support"]=i18n("<i18n key='con8'><en>Support</en><de>Support</de><fr>Support</fr><es>Apoyo</es></i18n>");
$tbody_value["support"]=i18n('<tr><td bgcolor="#ecfe32" colspan="2"><i18n key="con10"><en>Training</en><de>Schulung</de><fr>Formation</fr><es>Capacitación</es></i18n></td></tr><tr><td bgcolor="#001ff7"><i18n key="con11"><en>Consultation</en><de>Beratung</de><fr>Conseils</fr><es>Consulta</es></i18n></td><td bgcolor="#ff0b1f"><i18n key="con12"><en>Troubleshooting</en><de>Fehlerbehebung</de><fr>Dépannage</fr><es>Solución de problemas</es></i18n></td></tr>');
$tbody_names["admin"]=i18n("<i18n key='con13'><en>Administration</en><de>Administration</de><fr>Administration</fr><es>Administración</es></i18n>");
$tbody_value["admin"]=i18n('<tr><td bgcolor="#3f57ff"><i18n key="con15"><en>Installation</en><de>Installation</de><fr>Installation</fr><es>Instalación</es></i18n></td></tr><tr><td bgcolor="#6ba163"><i18n ref="con6"></i18n></td></tr><tr><td bgcolor="#e80068"><i18n key="con17"><en>Data Backup</en><de>Datensicherung</de><fr>Sauvegarde des données</fr><es>Copia de seguridad</es></i18n></td></tr>');

$default_tbody=i18n("<tr><td rowspan=\"2\" bgcolor=\"#c5ffd0\">Implementation</td><td bgcolor=\"#ffd0c5\">Documentation</td></tr><tr><td bgcolor=\"#ffc5d0\">Testing</td></tr>");

$reports=array();
$reports["daily"]=i18n("<i18n key='con18'><en>Daily Report</en><de>Täglicher Bericht</de><fr>Rapport journalier</fr><es>Informe día a día</es></i18n>");
$reports["monthly"]=i18n("<i18n key='con19'><en>Monthly Report</en><de>Monatlicher Bericht</de><fr>Rapport de mois</fr><es>Informe mes a mes</es></i18n>");
$reports["hourly"]=i18n("<i18n key='con20'><en>Hourly Report</en><de>Stündlicher Bericht</de><fr>Rapport horaires</fr><es>Informe hora a hora</es></i18n>");
$reports["reportables"]=i18n("<i18n key='con21'><en>activities in detail</en><de>Tätigkeiten im Detail</de><fr>Activités en détail</fr><es>Activitdads en detalle</es></i18n>");
$reports["infos"]=i18n("<i18n key='con22'><en>Infos in detail</en><de>Infos im Detail</de><fr>Infos en détail</fr><es>Infos en detalle</es></i18n>");

// current user vars
$id=-1;
$template_key="";
$tbody="";
$name="";
$email="";
$avatar="";
$date_format="";
$base_href="";
if (!isset($lang)) $lang="";

$max_import_filesize=81920;

// avatar constants
$max_avatar_width=256;
$max_avatar_height=256;
$max_avatar_filesize=81920;
$thumb_size=128;

// mail queue processing
$processMailsInQueuesAtOnce=5;

// replace single quote with two single quotes for sql-injection prevention
function p($param) {
	if (get_magic_quotes_gpc() == 1) {
		$r=stripslashes($param);
	} else {
		$r=$param;
	}
	$r=str_replace("'", "&apos;", $r);
	return $r;
}

function h($param) {
	if (get_magic_quotes_gpc() == 1) {
		$r=stripslashes($param);
	} else {
		$r=$param;
	}
	// workaround for migration-data-fix 1.5
	if (strpos($r, '\"') !== false) {
		$r=stripslashes($param);
	}
	$r=htmlspecialchars($r);
	return $r;
}

function pw_hash($pw) {
	return substr("00000000000000000000".md5($pw), -20);
}

function gen_token($name) {
	return substr("00000000000000000000".base_convert(md5($name.time()), 16, 36), -20);
}

function confirm_code() {
	return substr("000000".base_convert(md5(time()), 16, 36), -6);
}

function gen_aid() {
	return substr("00000".base_convert(md5(time().rand()), 16, 36), -5);
}

function detectLang()
{
	global $al;
	global $lang;

	if (isset($lang) && strlen($lang)>1) {
		// use $lang
	} else if (isset($_GET["lang"]) && $_GET["lang"] != "") {
		$lang=$_GET["lang"];
		$_SESSION['lang']=$lang;
	} else if (isset($_POST["lang"]) && $_POST["lang"] != "") {
		$lang=$_POST["lang"];
		$_SESSION['lang']=$lang;
	} else if (isset($_SESSION['lang'])) {
		$lang=$_SESSION['lang'];
	} else {
	  $lang="";
	}

	if ($lang == "") {
		$langs = $al;

    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
	    $_AL=strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
		} else {
		  $_AL="";
		}
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
		  $_UA=strtolower($_SERVER['HTTP_USER_AGENT']);
		} else {
		  $_UA="";
		}

		foreach($langs as $K) {
			if(strpos($_AL, $K)===0)
				return $K;
		}

		foreach($langs as $K) {
			if(strpos($_AL, $K)!==false)
				return $K;
		}
		foreach($langs as $K) {
			if ((strpos($_UA, "[".$K.";")!==false) ||
					(strpos($_UA, "(".$K.";")!==false) ||
					(strpos($_UA, " ".$K.";")!==false) ||
					(strpos($_UA, "[".$K.",")!==false) ||
					(strpos($_UA, "(".$K.",")!==false) ||
					(strpos($_UA, " ".$K.",")!==false) ||
					(strpos($_UA, "[".$K."_")!==false) ||
					(strpos($_UA, "(".$K."_")!==false) ||
					(strpos($_UA, " ".$K."_")!==false) ||
					(strpos($_UA, "[".$K."-")!==false) ||
					(strpos($_UA, "(".$K."-")!==false) ||
					(strpos($_UA, " ".$K."-")!==false) ||
					(strpos($_UA, "[".$K.")")!==false) ||
					(strpos($_UA, "(".$K.")")!==false) ||
					(strpos($_UA, " ".$K.")")!==false))
				return $K;
		}

		return "en";
	}
	return $lang;
}


function detectTranslate() {
	global $lc;
	$langs = array_keys($lc);

	$_AL=strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
	$_UA=strtolower($_SERVER['HTTP_USER_AGENT']);

	foreach($langs as $K) {
		if (strpos($_AL, $K)===0)
			return $K;
	}

	foreach($langs as $K) {
		if (strpos($_AL, $K)!==false)
			return $K;
	}
	foreach($langs as $K) {
		if ((strpos($_UA, "[".$K.";")!==false) ||
				(strpos($_UA, "(".$K.";")!==false) ||
				(strpos($_UA, " ".$K.";")!==false) ||
				(strpos($_UA, "[".$K.",")!==false) ||
				(strpos($_UA, "(".$K.",")!==false) ||
				(strpos($_UA, " ".$K.",")!==false) ||
				(strpos($_UA, "[".$K."_")!==false) ||
				(strpos($_UA, "(".$K."_")!==false) ||
				(strpos($_UA, " ".$K."_")!==false) ||
				(strpos($_UA, "[".$K."-")!==false) ||
				(strpos($_UA, "(".$K."-")!==false) ||
				(strpos($_UA, " ".$K."-")!==false) ||
				(strpos($_UA, "[".$K.")")!==false) ||
				(strpos($_UA, "(".$K.")")!==false) ||
				(strpos($_UA, " ".$K.")")!==false))
			return $K;
	}

	return "en";
}

function filter($buffer) {

	global $export;
	global $token;
	global $domain;
	global $name;
	global $email;
	global $cache;
	global $cached;
	global $lang;
	global $bottomId;

	if (!isset($export)) {
	  if ($export == "txt") {
	    header("Content-Type: text/plain;charset=UTF-8");
	  } else {
		  header("Content-Type: text/html;charset=UTF-8");
		}
	}

	if (isset($cached)) {
		return $buffer;
	}

	if (!isset($export)) {
		$head=file_get_contents(dirname(__FILE__)."/head.html");
		$head=str_replace("<domain/>", $domain, $head);

		if (isset($token)) {
			$script="  var token='$token';";
		} else {
			$script="  // not logged in";
		}
		$script.="\n  var domain=\"".$domain."/\";\n  var user=\"".$name."\";\n  var email=\"".$email."\";\n  var lang=\"".$lang."\";";

		$head=str_replace("<!-- script -->", $script, $head);
    unset($script);
		$buffer=$head.$buffer;
		unset($head);
	}

  if (strpos($buffer, "<command/>") !== false) {
	  global $command;
	  if (isset($command)) {
		  $buffer=str_replace("<command/>", $command, $buffer);
	  } else {
		  $buffer=str_replace("<command/>", "", $buffer);
	  }
	}
	if (isset($bottomId)) {
	  $buffer=str_replace(" id=\"id_bottom\"", " id=\"".$bottomId."\"", $buffer);
  }
	
	if (strpos($buffer, "<i18n") !== false) {
	  $buffer=i18n($buffer);
	} 
	
	if (isset($cache)) {
		file_put_contents($cache, $buffer);
	}

	$buffer=translationWelcome($buffer);
	if (isset($_POST["ajax"]) && $_POST["ajax"]==="true") {
		$grep=$_POST["grep"];
		return between($buffer, "<!--".$grep."-->", "<!--/".$grep."-->");
	}

  if (isset($_SESSION)) {
	  session_destroy();
  }

	return $buffer;
}

ob_start("filter");

function i18n($text, $useLang="") {
	global $i18n;

  if ($useLang != "") {
    $lang=$useLang;
  } else {
	  $lang=detectLang();
	}
  // nizip
	global $db_prefix;
	global $lc;
	connectDb();
	
	$c = preg_match_all('/\s*?<i18n\s+key=["\'](.*)["\']>(.*)<\/i18n>\s*?/imsU', $text, $matches, PREG_PATTERN_ORDER);
	for($i=0; $i < $c; $i++) {
	  $match=$matches[0][$i];
		$key=trim($matches[1][$i]);
		$chunk=$matches[2][$i];
		$lfallback="";
		$result="";
		foreach(array_keys($lc) as $l) {
		  if (strpos($chunk, "<" . $l . ">") !== false) {
		    $lt=preg_replace("/\s\s*/", " ", trim(between($chunk, "<" . $l . ">", "</" . $l . ">")));
			  if (strlen($lt) > 0) { 
			    if ($l == $lang) {
			      $result=$lt;
			    }
			    if ($l == "en") {
			      $lfallback=$lt;
			    }
			    // initial inserts
				  $insert = @mysql_query("REPLACE INTO " . $db_prefix . "I18N SET `key`='".p($key)."', `update`='2011-03-24 00:00:01', lang='".p($l)."', value='".p($lt)."'");
				  if (!$insert) {
					  // fail("insert for \"$key\".\"$l\" failed: ".p($lt));
				  }
			  }
			}
	  }
    if (strlen($result) == 0) { 
		  $result=$lfallback;
		}
		$text=str_replace($match, $result, $text);
		unset($match);
		unset($result);
		unset($key);
	}
	unset($matches);
	// /nizip
	
	$text=preg_replace('/>\s*?<\/i18n>\s*?/imsU', "/>", $text);
	$text=preg_replace('/\s*?<i18n/imsU', '<i18n', $text);
	$text=preg_replace('/<i18n\s+?/imsU', '<i18n ', $text);
	
	while(($f=strpos($text, "<i18n ref=")) !== false) {
	  $sep=substr($text, $f+10, 1);
	  $e=strpos($text, $sep, $f+11);
	  $key=substr($text, $f+11, $e-$f-11);
		if (!isset($i18n[$key.".".$lang]) || $i18n[$key.".".$lang]=="") {
		  if (!isset($i18n[$key.".en"]) || $i18n[$key.".en"]=="") {
		    $result="[undefined i18n ref='".$key.".en']";
		  } else {
  	    $result=trim($i18n[$key.".en"]);
		  }
		} else {
		  $result=trim($i18n[$key.".".$lang]);
		}
		$te=strpos($text, "/>", $f);
		$text=substr_replace($text, $result, $f, $te-$f+2);
	}

	return str_replace('<lang/>', $lang, $text);
}

function bottom() {
	global $export;
	global $domain;
	global $name;
	global $email;
	global $lc;
	global $al;
	if (!isset($export)) {
		include_once(dirname(__FILE__)."/bottom.php");
	}
	ob_end_flush();
	exit();
}


function connectDb() {
	global $db_host;
	global $db_username;
	global $db_password;
	global $db_name;
	global $dbcnx;

	if ($dbcnx) { // already connected
		return;
	}

	//CONNECT TO DB
	$dbcnx = @mysql_connect($db_host, $db_username, $db_password);
	if (!$dbcnx) {
		fail("Datenbank nicht erreichbar, bitte später erneut versuchen");
	}
	mysql_query("SET NAMES 'utf8'");
	//Select the database
	if (!@mysql_select_db($db_name)) {
		fail("Datenbank nicht korrekt konfiguriert");
	}
}

function login($confirmed, $name, $pw_hash, $stay) {
	global $db_prefix;
	global $id;
	global $template_key;
	global $tbody;
	global $command;
	global $token;
	global $name;
	global $email;
	global $avatar;
	global $date_format;
	global $base_href;

	if (!is_numeric($stay)) {
		fail("stay is not numeric");
	}
	// delete invalid tokens
	$delete = @mysql_query("DELETE FROM " . $db_prefix . "USAGE WHERE (stay<>0 AND DATE_ADD(login, INTERVAL stay MINUTE) <= CURRENT_TIMESTAMP)");
	if (!$delete) {
		fail("delete failed");
	}

	// try to pickup and prolong the token
	$sql = @mysql_query("SELECT t1.id_user AS id, t2.name AS name, t2.email AS email, t2.template_key AS template_key, t3.tbody as tbody, t2.confirmed AS confirmed, t2.lang as lang, t2.avatar as avatar, t2.FORMAT_DATE as FORMAT_DATE, t2.BASE_HREF AS BASE_HREF, UNIX_TIMESTAMP(DATE_ADD(t1.login, INTERVAL t1.stay MINUTE))- UNIX_TIMESTAMP(CURRENT_TIMESTAMP) AS remaining, t1.token AS token FROM " . $db_prefix . "USAGE t1, " . $db_prefix . "USER t2, " . $db_prefix . "TEMPLATES t3 WHERE t3.id_user=t2.id AND t3.key=t2.template_key AND t2.name='".p($name)."' AND t2.pw_hash='".p($pw_hash)."' AND t1.id_user=t2.id AND (t1.stay=0 OR DATE_ADD(t1.login, INTERVAL t1.stay MINUTE) > CURRENT_TIMESTAMP)");
	if ($sql === FALSE) {
	  fail("pickup query failed");
	}
	if ($row = mysql_fetch_array($sql)) {
		$token=$row["token"];
	} else {
		mysql_free_result($sql);
		$sql = @mysql_query("SELECT t2.id AS id, t2.name AS name, t2.email AS email, t2.template_key AS template_key, t3.tbody AS tbody, t2.confirmed AS confirmed, t2.lang AS lang, t2.avatar AS avatar, t2.FORMAT_DATE AS FORMAT_DATE, t2.BASE_HREF AS BASE_HREF FROM " . $db_prefix . "USER t2, " . $db_prefix . "TEMPLATES t3 WHERE t3.id_user=t2.id AND t3.key=t2.template_key AND t2.name='".p($name)."' AND t2.pw_hash='".p($pw_hash)."'");
		if ($sql === FALSE) {
	    fail("query failed");
	  }
		$row = mysql_fetch_array($sql);
		if (is_numeric($row["id"])) {
			$token=gen_token($name);
		}
	}

	if (is_numeric($row["id"])) {
		$id=$row["id"];
		$template_key=$row["template_key"];
		$tbody=$row["tbody"];
		$name=$row["name"];
		$email=$row["email"];
		$bcon=$row["confirmed"];
		$_SESSION['lang']=$row["lang"];
		$avatar=$row["avatar"];
		$date_format=$row["FORMAT_DATE"];
		$base_href=$row["BASE_HREF"];
		mysql_free_result($sql);
		if ($confirmed && $bcon!='t') {
			fail("<i18n key='con23'><en>Login not allowed. Please use link in the registration email to confirm user</en><de>Login noch nicht erlaubt. Bitte Link in der Registrierungs-Email zur Bestätigung des Benutzers verwenden</de><fr>Connectez-vous pas autorisés. S'il vous plaît lien dans l'utilisation du courrier électronique d'enregistrement pour confirmer l'utilisateur</fr><es>Entrada no está permitida. Por favor, enlace en el uso de correo electrónico de registro para confirmar del usuario</es></i18n>");
		}
		$insert = @mysql_query("REPLACE INTO " . $db_prefix . "USAGE SET id_user=".p($id).", token='".p($token)."', stay=".p($stay));
		if (!$insert) {
			fail("unexpected: usage insert failed");
		}
		$_SESSION['token'] = $token;
		if (!$confirmed && $bcon!='t') {
			$command="toggleShowHelp();";
		}
		
		setState($id, "");
		return $token;
	} else {
		mysql_free_result($sql);
		fail("<i18n key='log0'><en>This combination of login and password and is unknown.</en><de>Diese Kombination aus Login und Passwort ist und unbekannt.</de><fr>Cette combinaison de login et mot de passe et est inconnue.</fr><es>Esta combinación de login y password y no se conoce.</es></i18n>");
	}
}

function idLogin($userId) {
	global $db_prefix;
	global $id;
	global $template_key;
	global $tbody;
	global $command;
	global $token;
	global $name;
	global $lang;
	global $email;
	global $avatar;
	global $date_format;
	global $base_href;

	if (!is_numeric($userId)) {
		fail("id $userId is not numeric");
	}

	// try to pickup and prolong the token
	$sql = @mysql_query("SELECT t1.id_user AS id, t2.name AS name, t2.email AS email, t2.template_key AS template_key, t3.tbody as tbody, t2.confirmed AS confirmed, t2.lang as lang, t2.avatar as avatar, t2.FORMAT_DATE as FORMAT_DATE, t2.BASE_HREF AS BASE_HREF, UNIX_TIMESTAMP(DATE_ADD(t1.login, INTERVAL t1.stay MINUTE))- UNIX_TIMESTAMP(CURRENT_TIMESTAMP) AS remaining, t1.token AS token FROM " . $db_prefix . "USAGE t1, " . $db_prefix . "USER t2, " . $db_prefix . "TEMPLATES t3 WHERE t3.id_user=t2.id AND t3.key=t2.template_key AND t2.id=".$userId." AND t2.confirmed='t' AND t1.id_user=t2.id AND (t1.stay=0 OR DATE_ADD(t1.login, INTERVAL t1.stay MINUTE) > CURRENT_TIMESTAMP)");
	if ($sql === FALSE) {
	  fail("pickup query failed");
	}
	if ($row = mysql_fetch_array($sql)) {
		$token=$row["token"];
	} else {
		mysql_free_result($sql);
		$sql = @mysql_query("SELECT t2.id AS id, t2.name AS name, t2.email AS email, t2.template_key AS template_key, t3.tbody AS tbody, t2.confirmed AS confirmed, t2.lang AS lang, t2.avatar AS avatar, t2.FORMAT_DATE AS FORMAT_DATE, t2.BASE_HREF AS BASE_HREF FROM " . $db_prefix . "USER t2, " . $db_prefix . "TEMPLATES t3 WHERE t3.id_user=t2.id AND t3.key=t2.template_key AND t2.confirmed='t' AND t2.id=".$userId);
		if ($sql === FALSE) {
	    fail("query failed");
	  }
		$row = mysql_fetch_array($sql);
		if (is_numeric($row["id"])) {
			$token=gen_token($name);
		}
	}

	if (is_numeric($row["id"])) {
		$id=$row["id"];
		$template_key=$row["template_key"];
		$tbody=$row["tbody"];
		$name=$row["name"];
		$email=$row["email"];
		$bcon=$row["confirmed"];
		$lang=$row["lang"];
		$avatar=$row["avatar"];
		$date_format=$row["FORMAT_DATE"];
		$base_href=$row["BASE_HREF"];
		mysql_free_result($sql);
		$insert = @mysql_query("REPLACE INTO " . $db_prefix . "USAGE SET id_user=".p($id).", token='".p($token)."'");
		if (!$insert) {
			fail("unexpected: usage insert failed");
		}
		return $token;
	} else {
		mysql_free_result($sql);
		fail("unknown id ".$userId);
	}
}

function logout($token) {
	global $db_prefix;

	$delete = @mysql_query("DELETE FROM " . $db_prefix . "USAGE WHERE token='".p($token)."'");
	if (!$delete) {
		fail("delete failed");
	}
}

function getPlace($time) {
	global $db_prefix;
	global $id;
	$sql = @mysql_query("SELECT event FROM " . $db_prefix . "ENTRY WHERE ".p($id)."=id_user AND start <='".p($time)."' ORDER BY start DESC LIMIT 0,1");
	if ($row = mysql_fetch_array($sql)) {
		$event=$row["event"];
		return $event;
	}
	return '';
}

function pickup($token) {
	global $db_prefix;
	global $id;
	global $template_key;
	global $tbody;
	global $name;
	global $email;
	global $avatar;
	global $date_format;
	global $base_href;

	$sql = @mysql_query("SELECT t1.id_user AS id, t2.name AS name, t2.email AS email, t2.template_key AS template_key, t3.tbody as tbody, t2.lang as lang, t2.avatar as avatar, t2.FORMAT_DATE as FORMAT_DATE, UNIX_TIMESTAMP(DATE_ADD(t1.login, INTERVAL t1.stay MINUTE))-UNIX_TIMESTAMP(CURRENT_TIMESTAMP) AS remaining, t2.BASE_HREF AS BASE_HREF FROM " . $db_prefix . "USAGE t1, " . $db_prefix . "USER t2, " . $db_prefix . "TEMPLATES t3 WHERE t3.id_user=t2.id AND t3.key=t2.template_key AND t1.token='".p($token)."' AND t1.id_user=t2.id AND (t1.stay=0 OR DATE_ADD(t1.login, INTERVAL t1.stay MINUTE) > CURRENT_TIMESTAMP)");
	if ($row = mysql_fetch_array($sql)) {
		if (is_numeric($row["id"])) {
			$id=$row["id"];
			$name=$row["name"];
			$email=$row["email"];
			$template_key=$row["template_key"];
			$tbody=$row["tbody"];
			$_SESSION['lang']=$row["lang"];
			$avatar=$row["avatar"];
			$date_format=$row["FORMAT_DATE"];
			$remaining=$row["remaining"];
			$base_href=$row["BASE_HREF"];
			mysql_free_result($sql);
			return $id;
		}
	}
	fail("Automatic Logout");
}

function aid($aid) {
	global $db_prefix;
	global $id;
	global $date_format;

	$sql = @mysql_query("SELECT id, FORMAT_DATE FROM " . $db_prefix . "USER WHERE aid='".p($aid)."'");
	if ($row = mysql_fetch_array($sql)) {
		if (is_numeric($row["id"])) {
			$id=$row["id"];
			$date_format=$row["FORMAT_DATE"];
			mysql_free_result($sql);
			return $id;
		}
	}
	$id=-1;
	mysql_free_result($sql);
	return $id;
}

function updateTbody($tbody) {
	global $db_prefix;
	global $id;
	global $template_key;

	$update = @mysql_query("UPDATE " . $db_prefix . "TEMPLATES SET tbody='".p($tbody)."' WHERE id_user=".p($id)." AND `key`='".p($template_key)."'");
	if (!$update) {
		fail("update failed");
	}
	$t=stripslashes($tbody);
	$c = preg_match_all('/<td(.*)bgcolor="(#.*)"([^>]*)>(.*)<\/td>/imsU', $t, $matches);
	for($i=0; $i < $c; $i++) {
		$color=$matches[2][$i];
		$event=trim($matches[4][$i]);
		if (strlen($color)==7 && strlen($event) > 0) {
			$update2 = @mysql_query("UPDATE " . $db_prefix . "ENTRY SET color='".p($color)."' WHERE id_user=".p($id)." AND event='".p($event)."'");
			if (!$update2) {
				fail("update for \"$event\" failed");
			}
		}
	}
}

function setBaseHref($newBaseHref) {
	global $db_prefix;
	global $id;

	$update = @mysql_query("UPDATE " . $db_prefix . "USER SET BASE_HREF='".p($newBaseHref)."' WHERE id=".p($id));
	if (!$update) {
		fail("update failed");
	}
}

function setUserLang($lang) {
	global $db_prefix;
	global $id;
	global $lc;

	if (array_key_exists($lang,$lc)) {
		$update = @mysql_query("UPDATE " . $db_prefix . "USER SET lang='".p($lang)."' WHERE id=".p($id));
		if (!$update) {
			fail("update failed");
		}
	}
}

// nizip
function debugMail($txt) {
	global $feedback_to;
	mail($feedback_to, "[Emphasize] debug", $txt, "From: debug@emphasize.de\r\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: 8bit\r\n\r\n");
}

function entry($lang, $bid) {
	global $author;
	global $db_prefix;
	global $domain;

	$sql = mysql_query("SELECT log_heading, log_text, log_day, log_date, log_time FROM emphasize_blog WHERE log_author='".p($lang)."' AND log_id=".p($bid));
	if ($row = mysql_fetch_array($sql)) {
		$header = stripslashes($row["log_heading"]);
		echo("<p><h1><a href=\"" . longUrl($header, $bid) . "\" title=\"link\">" . $header . "</a></h1>\n");
		$text=stripslashes($row["log_text"]);
		echo($text."</p><br clear=\"all\" />\n");
	} else {
		echo("missing entry ".$bid." lang=".$lang);
	}
	mysql_free_result($sql);
}

function longUrl($header, $bid) {
	global $domain;

	return $domain."/".longLink(stripslashes(str_replace(array("&amp;","'","#252;","#246;", "&uuml;"), array("and","","u","oe","ue"), $header)), $bid);
}

function longLink($title, $bid) {
	global $lang;
	$file=str_replace(array("\\", "/", " - ", " ", "&", ".", "'"), array("\\", "_", "-", "_", "", "", "_"), $title).".php";
	if (!file_exists(dirname(__FILE__)."/../".$file)) {
		file_put_contents(dirname(__FILE__)."/../".$file, '<?php'."\n".' if (!isset($_GET["bid"]) || empty($_GET["bid"])) $bid='.$bid.';'."\n".' $lang="'.$lang.'";'."\n".' include_once(dirname(__FILE__)."/index.php");?>');
	}
	return $file;
}
// /nizip

/**
 * @param $event if "", then pause starts.
 */
function trackEvent($event, $color, $time) {
	global $db_prefix;
	global $id;

	// nizip
	global $feedback_to;
	if (strlen($color)!=7) {
		mail($feedback_to, "[Emphasize] trackEvent-Fished: $id - $time", "_$event _ color _$color _", "From: error@emphasize.de\r\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: 8bit\r\n\r\n");
		$color="#ffffff";
	}
	// /nizip

	$delete = @mysql_query("DELETE FROM " . $db_prefix . "ENTRY WHERE id_user=".p($id)." AND start >='".p($time)."' AND start <= DATE_ADD('".p($time)."', INTERVAL 5 MINUTE)");
	if (!$delete) {
		fail("delete failed");
	}
	echo("deleted [$time,+5mins], ");

	$sql=@mysql_query("SELECT start FROM " . $db_prefix . "ENTRY WHERE id_user=".p($id)." AND start <'".p($time)."' ORDER BY start DESC LIMIT 0,1");
	$row = mysql_fetch_array($sql);
	$startBefore=$row["start"];
	mysql_free_result($sql);

	$sql=@mysql_query("SELECT start FROM " . $db_prefix . "ENTRY WHERE id_user=".p($id)." AND start >'".p($time)."' ORDER BY start ASC LIMIT 0,1");
	$row = mysql_fetch_array($sql);
	$startAfter=$row["start"];
	mysql_free_result($sql);

	if ($startBefore) {
		$update = @mysql_query("UPDATE " . $db_prefix . "ENTRY SET end='".p($time)."', duration=UNIX_TIMESTAMP('".p($time)."')-UNIX_TIMESTAMP(start) WHERE id_user=".p($id)." AND start='".p($startBefore)."'");
		if (!$update) {
			fail("update failed");
		}
		echo("updated $startBefore duration before, ");
	}

  if (strlen($event) > 0) {
	  if ($startAfter) {
		  $insert = @mysql_query("REPLACE INTO " . $db_prefix . "ENTRY SET id_user=".p($id).", event='".p($event)."', color='".p($color)."', start='".p($time)."', duration=UNIX_TIMESTAMP('".p($startAfter)."')-UNIX_TIMESTAMP('".p($time)."'), end='".p($startAfter)."'");
		  if (!$insert) {
			  fail("insert failed");
		  }
		  echo("inserted new event at $time which ends at $startAfter.");
	  } else {
		  $insert = @mysql_query("REPLACE INTO " . $db_prefix . "ENTRY SET id_user=".p($id).", event='".p($event)."', color='".p($color)."', start='".p($time)."'");
		  if (!$insert) {
			  fail("insert failed");
		  }
		  echo("inserted new event at $time.");
	  }
	}
}


function addInfo($info, $time) {
	global $db_prefix;
	global $id;

	$delete = @mysql_query("DELETE FROM " . $db_prefix . "INFO WHERE id_user=".p($id)." AND start >=DATE_ADD('".p($time)."', INTERVAL -2 MINUTE) AND start <= DATE_ADD('".p($time)."', INTERVAL 2 MINUTE)");
	if (!$delete) {
		fail("delete failed");
	}
	echo("deleted [$time,+-2mins], ");

	if (strlen(trim($info))>0) {
		$insert = @mysql_query("INSERT " . $db_prefix . "INFO SET id_user=".p($id).", info='".p($info)."', start='".p($time)."'");
		if (!$insert) {
			fail("insert failed");
		}
		echo("inserted new info at $time.");
	}
}

function fail($message) {
	global $error;
	global $domain;
	global $token;
	
  ob_end_clean();

	echo('<title>Emphasize - Ups, an ERROR occured - "'.substr($message, 0, 40).'"</title>');
	echo('<body><p class=\"red\"><center>'.$message.'</center></p>'."\n");
	echo('<form action="'.$domain.'/" method="POST"><input type="hidden" name="do" value="logout" /><input type="hidden" name="token" value="');
	
	if (isset($token)) {
		echo($token);
	} else if (isset($_POST["token"]) && $_POST["token"] != "") {
		echo($_POST["token"]);
	} else if (isset($_GET["token"]) && $_GET["token"] != "") {
		echo($_GET["token"]);
	} else {
		echo($_SESSION["token"]);
	}
	echo('" /><input type="submit" value="Re-Login" /></form>');
	echo("<!-- " . mysql_error()." -->");
	if (isset($_SESSION)) {
		unset($_SESSION);
	}
	unset($GLOBALS['token']); // isLogin==false
	exit();
}

function duration($secs) {
	$vals = array('w' => (int) ($secs / 86400 / 7),
			'd' => $secs / 86400 % 7,
			'h' => $secs / 3600 % 24,
			'm' => $secs / 60 % 60,
			's' => $secs % 60);

	$ret = array();
	foreach ($vals as $k => $v) {
		if ($v > 0) {
			$ret[] = $v . $k;
		}
	}
	return join(' ', $ret);
}

function csvTime($secs) {
	$vals = array('h' => floor($secs / 3600),
			'm' => $secs / 60 % 60,
			's' => $secs % 60);

	$ret = array();
	foreach ($vals as $k => $v) {
		if ($v == 0) {
			$ret[] = "00";
		} else if ($v < 10) {
			$ret[] = "0".$v;
		} else {
			$ret[] = $v;
		}
	}
	return "=".i18n("<i18n key='con26'><en>TIME</en><de>ZEIT</de><fr>TEMPS</fr><es>NSHORA</es><cs>ČAS</cs><da>TID</da><fi>AIKA</fi><hu>IDŐ</hu><it>ORARIO</it><no>TID</no><nl>TIJD</nl><pl>CZAS</pl><pt>TEMPO</pt><ru>ВРЕМЯ</ru><sv>KLOCKSLAG</sv><tr>ZAMAN</tr></i18n>")."(".join(';', $ret).")";
}

function hhmmss($secs) {
	$vals = array('h' => floor($secs / 3600),
			'm' => $secs / 60 % 60,
			's' => $secs % 60);

	$ret = array();
	foreach ($vals as $k => $v) {
		if ($v == 0) {
			$ret[] = "00";
		} else if ($v < 10) {
			$ret[] = "0".$v;
		} else {
			$ret[] = $v;
		}
	}
	return join(':', $ret).".0";
}

function getTimelineHistory($now, $before) {
	global $db_prefix;
	global $id;
	global $domain;

	$delta=(strtotime($now)-strtotime($before))/60;

	// load-entry
	$insert = @mysql_query("REPLACE INTO " . $db_prefix . "LOAD SET id_user=".p($id).", time=FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP(CURRENT_TIMESTAMP)/600)*600)");
	if (!$insert) {
		fail("unexpected: load insert failed");
	}

	$update = @mysql_query("UPDATE " . $db_prefix . "ENTRY SET duration=UNIX_TIMESTAMP('".p($now)."')-UNIX_TIMESTAMP(start) WHERE id_user=".p($id)." AND end IS NULL");
	if (!$update) {
		fail("unexpected: entry duration update failed");
	}

	$sql = @mysql_query("SELECT event, color, FLOOR(".$delta."-(UNIX_TIMESTAMP('".p($now)."') - UNIX_TIMESTAMP(IF(start < '".p($before)."','".p($before)."',start)))/60) AS offset, start, FLOOR(duration/60) as minutes, FLOOR((UNIX_TIMESTAMP(IF(end >'".p($now)."','".p($now)."',end))-UNIX_TIMESTAMP(IF(start < '".p($before)."','".p($before)."',start)))/60) AS width FROM ".$db_prefix."ENTRY WHERE ID_USER=".p($id)." AND END > '".p($before)."' AND DURATION >= 60 ORDER BY start ASC");
	// aktuelle render-position
	$cursor=0;
	while ($row = mysql_fetch_array($sql)) {
		$event=str_replace('"', '&quot;', $row["event"]);
		$color=$row["color"];
		if (strlen($color) != 7) {
			$color="#ff0000";
		}
		// offset zählt von 0 (=-7d) bis 10080 (=jetzt)
		$offset=$row["offset"];
		$start=$row["start"];
		$minutes=$row["minutes"];
		$width=$row["width"];
		if ($offset > $cursor) {
			echo('<img src="'.$domain.'/graphics/void.png" width="'.($offset-$cursor).'" height="10" class="te" />');
			$cursor=$offset;
		}
		echo('<img src="'.$domain.'/util/i.php?'.substr($color, 1, 6).'" title="'.$event.'" width="'.$width.'" height="10" class="te" />');
		echo('<img src="'.$domain.'/graphics/seperator.png" width="15" height="12" class="tsep" style="left:'.($offset+52).'px;" />');
		$cursor=$offset+$width;
	}
	mysql_free_result($sql);

	// render the actual event which has end=null
	$sql = @mysql_query("SELECT event, color, FLOOR(".$delta."-(UNIX_TIMESTAMP('".p($now)."')-UNIX_TIMESTAMP(IF(start < '".p($before)."','".p($before)."',start)))/60) AS offset, start, FLOOR(duration/60) as minutes, FLOOR((UNIX_TIMESTAMP('".p($now)."')-UNIX_TIMESTAMP(IF(start < '".p($before)."','".p($before)."',start)))/60) AS width FROM ".$db_prefix."ENTRY WHERE ID_USER=".p($id)." AND END IS NULL");
	if ($row = mysql_fetch_array($sql)) {
		$event=str_replace('"', '&quot;', $row["event"]);
		$color=$row["color"];
		if (strlen($color) != 7) {
			$color="#ff0000";
		}
		// offset zählt von 0 (=-7d) bis 10080 (=jetzt)
		$offset=$row["offset"];
		$start=$row["start"];
		$minutes=$row["minutes"];
		$width=$row["width"];
		if ($offset > $cursor) {
			echo('<img src="'.$domain.'/graphics/void.png" width="'.($offset-$cursor).'" height="10" class="te" />');
			$cursor=$offset;
		}
		echo('<img src="'.$domain.'/util/i.php?'.substr($color, 1, 6).'" title="'.$event.'" width="'.$width.'" height="10" class="te" />');
		echo('<img src="'.$domain.'/graphics/seperator.png" width="15" height="12" class="tsep" style="left:'.($offset+52).'px;" />');
		$cursor=$offset+$width;
	}
	mysql_free_result($sql);

	// infos
	$sql = @mysql_query("SELECT info, FLOOR(".$delta."-(UNIX_TIMESTAMP('".p($now)."')-UNIX_TIMESTAMP(IF(start < '".p($before)."','".p($before)."',start)))/60) AS offset, start FROM ".$db_prefix."INFO WHERE ID_USER=".p($id)." AND start > '".p($before)."' ORDER BY start ASC");
	// aktuelle render-position
	while ($row = mysql_fetch_array($sql)) {
		$info=str_replace('"', '&quot;', $row["info"]);
		// offset zählt von 0 (=-7d) bis 10080 (=jetzt)
		$offset=$row["offset"];
		$start=$row["start"];
		echo('<img src="'.$domain.'/graphics/info.png" title="'.substr($start,11,5).' '.$info.'" style="left:'.($offset+60-2).'px;" class="ti" />');
	}
	mysql_free_result($sql);
}

function getUserAvatar($avatar) {
	global $name;
	global $domain;
	global $max_avatar_width;
	global $max_avatar_height;
	return '<img id="shadow" src="'.$domain.'/util/shadow.php?../avatars/'.$avatar.'.png" style="position:absolute;z-index:107;top:0px;left:'.($max_avatar_width*-2).'px;" /><div id="avatar" style="position:absolute; z-index:108; top:100px;left:'.($max_avatar_width*-2-3).'px;width:1px;height:1px;"><img id="user" class="help" src="'.$domain.'/avatars/'.$avatar.'.png" title="'.$name.'" /><div id="help_user" class="docu" style="width:220px;height:44px;"><i18n key="con25"><en><a href="javascript:tubeTutorial(\'akZ90qEgKEQ\')">Character</a> placed on the current activity by clicking on the corresponding field.</en><de><a href="javascript:tubeTutorial(\'R1XmQ9pioJU\')">Spielfigur</a> die jeweils auf die aktuelle Aktivität durch Klick auf das entsprechende Feld gesetzt wird.</de><fr>Le <a href="javascript:tubeTutorial(\'akZ90qEgKEQ\')">caractère</a> en cours à chaque activité en cliquant sur le coffret.</fr><es>El <a href="javascript:tubeTutorial(\'akZ90qEgKEQ\')">carácter</a> actual de cada actividad haciendo clic en la caja.</es></i18n></div></div>';
}

function setUserAvatar($avatar) {
	global $db_prefix;
	global $id;

	if ((strpos($avatar, "/") !== false) || (strpos($avatar, ":") !== false) || (strpos($avatar, ".") !== false) || (strpos($avatar, "?") !== false)) {
		fail("invalid avatar name ".$avatar);
	}
	$update = @mysql_query("UPDATE " . $db_prefix . "USER SET avatar='".p($avatar)."' WHERE id=".p($id));
	if (!$update) {
		fail("update failed");
	}
}

function deleteUserAvatar($theavatar) {
	global $id;

	$dir = opendir("avatars");
	while(($file = readdir($dir)) !== false) {
		if($file !== '.' && $file !== '..' && (substr($file, 0, strlen($id."_"))==$id."_") && ($file == $theavatar.".png")) {
			unlink("avatars/".$file);
			break;
		}
	}
	closedir($dir);
}

function checkCache() {
  global $domain;
  $prefix=substr($domain, strpos($domain, "//")+2);
  if (strpos($prefix, "/") !== false) {
    $prefix=substr($prefix, 0, strpos($prefix, "/"));
  }
	$numargs = func_num_args();
	if ($numargs == 0) {
		return;
	}
	$arg_list = func_get_args();
	global $cache;
	$cache=dirname(__FILE__)."/../cache/".$prefix."_".$arg_list[0];
	if (!file_exists($cache)) {
		return;
	}
	$cachetime=filemtime($cache);
	for ($i = 1; $i < $numargs; $i++) {
		if ($cachetime < filemtime(dirname(__FILE__)."/../".$arg_list[$i])) {
			return;
		}
	}

	$i18n=file_get_contents($cache);
	$i18n=translationWelcome($i18n);
	if (isset($_POST["ajax"]) && $_POST["ajax"]==="true") {
		$grep=$_POST["grep"];
		$f=strpos($i18n, "<!--".$grep."-->")+strlen("<!--".$grep."-->");
		$t=strpos($i18n, "<!--/".$grep."-->");
		echo(substr($i18n, $f, $t-$f));
	} else {
		echo($i18n);
	}

	global $cached;
	$cached=true;
	exit();
}

function getI18NProcess($lang) {
	global $db_prefix;

	$sql = @mysql_query("SELECT COUNT(DISTINCT(`KEY`)) AS c FROM " . $db_prefix . "I18N WHERE `LANG`='en'");
	if ($row = mysql_fetch_array($sql)) {
		$total=$row["c"];
	}
	mysql_free_result($sql);

	$sql = @mysql_query("SELECT COUNT(DISTINCT(`KEY`)) AS c FROM " . $db_prefix . "I18N WHERE `LANG`='".p($lang)."'");
	if ($row = mysql_fetch_array($sql)) {
		$part=$row["c"];
	}
	mysql_free_result($sql);
	return array($part, $total);
}

function getI18Ns($from_lang, $lang) {
	global $db_prefix;

	$sql = @mysql_query("SELECT `KEY` AS k, en_value, `VALUE` AS v FROM (SELECT t2.`KEY`, t2.`VALUE` AS en_value, t0.`VALUE` FROM " . $db_prefix . "I18N t0 RIGHT JOIN " . $db_prefix . "I18N t2 ON t0.`KEY` = t2.`KEY` AND t0.`LANG`='".p($lang)."' WHERE t2.`LANG`='".p($from_lang)."' AND t2.`KEY` IN (SELECT DISTINCT(t1.`KEY`) FROM " . $db_prefix . "I18N t1 WHERE t1.`LANG`='".p($from_lang)."') ORDER BY t2.`UPDATE` DESC, t0.`UPDATE` DESC) AS tmp GROUP BY `KEY` ORDER BY LENGTH(`VALUE`) ASC");
	$set=array();
	while ($row = mysql_fetch_array($sql)) {
		$vals=array();
		$vals[]=$row["en_value"];
		$vals[]=$row["v"];
		$set[$row["k"]]=$vals;
	}
	mysql_free_result($sql);
	return $set;
}

function writeI18N($lang, $key, $value) {
	global $db_prefix;
	$insert = @mysql_query("INSERT INTO " . $db_prefix . "I18N SET `UPDATE`=CURRENT_TIMESTAMP, `LANG`='".p($lang)."', `KEY`='".p($key)."', `VALUE`='".p($value)."'");
	if (!$insert) {
		fail("insert failed");
	}
	debugMail($lang."-".$key." added ".$value);
}

function translationWelcome($buffer) {
	global $lang;
	global $al;
	global $lc;

	// post-replace after cache && !cached
	if (strpos($buffer, "<!--translationWelcome/-->") !== FALSE) {
		$translate=detectTranslate();
		if (!in_array($translate, $al) || $translate=='fr' || $translate=='es') {
			if ($lang=='de') {
				$translationWelcome="<li>Für die Übersetzung ins <a href='#' onclick='if (!isAboveOpen(\"translation\")) showAbove(\"translation\", undefined, \"util/i18n.php?translate=".$translate."&from=".$lang."\", undefined, 400, 200); return false;'>".$lc[$translate]."</a> wird Hilfe benötigt!</li>";
			} else {
				$translationWelcome="<li>Help to translate into <a href='#' onclick='if (!isAboveOpen(\"translation\")) showAbove(\"translation\", undefined, \"util/i18n.php?translate=".$translate."&from=en\", undefined, 400, 200); return false;'>".$lc[$translate]."</a> is very much welcome!</li>";
			}
		} else {
			$translationWelcome="";
		}
		$buffer=str_replace(array("<!--translationWelcome/-->"), array($translationWelcome), $buffer);
	}
	return $buffer;
}

function startsWith($haystack,$needle,$case=true) {
	if($case){
		return (strcmp(substr($haystack, 0, strlen($needle)),$needle)===0);
	}
	return (strcasecmp(substr($haystack, 0, strlen($needle)),$needle)===0);
}

function endsWith($haystack,$needle,$case=true) {
	if($case){
		return (strcmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);
	}
	return (strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);
}

function between($haystack, $before, $after, $case=true) {
  if ($case) {
    $offset=strpos($haystack, $before);
  } else {
    $offset=stripos($haystack, $before);
  }
  if ($offset !== false) {
    $offset=$offset+strlen($before);
    if ($case) {
      $end=strpos($haystack, $after, $offset);
    } else {
      $end=stripos($haystack, $after, $offset);
    }
    if ($end !== false) {
      return substr($haystack, $offset, $end-$offset);
    }
  }
}

function getContent($url) {
  if (!$url_info = parse_url($url)) {
      return $url;   
  }
 
  switch ($url_info['scheme']) {
      case 'https':
          $scheme = 'ssl://';
          $port = 443;
          break;
      case 'http':
      default:
          $scheme = '';
          $port = 80;   
  }
  
  $head="";
  $content="";
  
  $fid = @fsockopen($scheme . $url_info['host'], $port, $errno, $errstr, 30);
  if ($fid) {
    fputs($fid, 'GET ' . (isset($url_info['path'])? $url_info['path']: '/') . (isset($url_info['query'])? '?' . $url_info['query']: '') . " HTTP/1.0\r\n" .
                  "Connection: close\r\n" .
                  'Host: ' . $url_info['host'] . "\r\n\r\n");  
    $inHead=true;
    while (!feof($fid)) {
        if ($inHead) {
          $head.=fgets($fid, 128);
          $pos=strpos($head, "\r\n\r\n");
          if ($pos !== false) {
            $content=substr($head, $pos+4);
            $head=substr($head, 0, $pos);
            $inHead=false;
          }
        } else {
          $content.=fgets($fid, 128);
        }
    }
    fclose($fid);
  }
  $headers=array();
  $state="";
  foreach(explode("\r\n", $head) as $pair) {
    if (strpos($pair, ": ") !== false) {
      $p=explode(": ", $pair, 2);
      $headers[$p[0]]=$p[1];
    } else {
      if (strlen($state) > 0) {
        $state.="\r\n";
      }
      $state.=$pair;
    }
  }
  return array($state, $headers, $content);
}

function replaceInline($text, $match, $prefix) {
  $map=array();
  $c=0;
  $ol=strlen($match);
  $pos=0;
  while(($f=strpos($text, $match, $pos)) !== false) {
	  $sep=substr($text, $f+$ol, 1);
	  $e=strpos($text, $sep, $f+$ol+1);
	  $url=substr($text, $f+$ol+1, $e-$f-$ol-1);
		$res=$prefix.$c;
		$map[$res]=$url;
		$text=substr_replace($text, "cid:".$res, $f+$ol+1, $e-$f-$ol-1);
		$pos=$f+$ol+1+strlen("cid:".$res)+1;
		$c++;
	}
	return array($text, $map);
}

function getMailReportNextRunDate($interval, $offset) {
  if ($offset < 0) {
    if ($interval == "month") {
      $first=strtotime("+1 day", strtotime(date('Y-m-t', strtotime("+1 day", strtotime(date('Y-m-t'))))));
    } else if ($interval == "week") {
      $first=strtotime((7-date('w'))." days");
    }
  } else{
    if ($interval == "month") {
      $first=strtotime("+1 day", strtotime(date('Y-m-t')));
    } else if ($interval == "week") {
      $first=strtotime((7-date('w'))." days");
    }
  }
  return strtotime($offset." days", $first);
}

function getMailReportFromDate($interval, $offset) {
  if ($interval == "month") {
    if ($offset >= 0) {
      return strtotime(date('Y-m-01 00:00:00', strtotime((-$offset-1)." days", strtotime(date('Y-m-01')))));
    } else {
      return strtotime(date('Y-m-01 00:00:00', strtotime((-$offset)." days")));
    }
  } else if ($interval == "week") {
    if ($offset >= 0) {
      return strtotime("-".(date('w')+floor($offset/7)*7)." days", strtotime("today"));
    } else {
      return strtotime("today -".(date('w')+floor($offset/7)*7)." days", strtotime("today"));
    }
  }
}

function getMailReportToDate($from, $interval) {
  if ($interval == "month") {
      return strtotime(date('Y-m-t 23:59:59', strtotime($from)));
  } else if ($interval == "week") {
      return strtotime("+7 days -1 second", strtotime($from));
  }
}

function mailReport($id_user, $cid, $type, $range, $run) {
  global $domain;
  global $lang;
  global $feedback_to;

  if ($type=="monthly" && startsWith($range, "nextmonth=")) {
    $interval="month";
    $dayoffset=substr($range, strlen("nextmonth="));    
  } else if ($type=="daily" && startsWith($range, "weekday=")) {
    $interval="week";
    $dayoffset=substr($range, strlen("weekday="));
  } else {
    echo("$cid unsupported range ".$range."\n");
    return $run;
  }
  if ($run == date('Y-m-d')) {
    $from=date('Y-m-d', getMailReportFromDate($interval, $dayoffset));
    $to=date('Y-m-d', getMailReportToDate($from, $interval));
    
    $token=idLogin($id_user);
    $sep = sha1(date('r', time())); 
    
    echo($domain."/util/report.php?type=".$type."&token=".$token."&time=".date('Y-m-d+H:i:s',time())."&from=".$from."&to=".$to."&Submit=X"."\n");
    
    $p=getContent($domain."/util/report.php?type=".$type."&token=".$token."&time=".date('Y-m-d+H:i:s',time())."&from=".$from."&to=".$to."&Submit=X");

    $title=str_replace("&nbsp;", " ", between($p[2], "<title>","</title>"));

    $style=getContent($domain."/style.css");

    $offset=strpos($p[2], "<body onload=\"initReport()\">")+strlen('<body onload="initReport()">');
    $end=strpos($p[2], "</table>")-$offset;

    $removeText=i18n("<i18n key='mai0'><en>Unsubscribe this e-mail report</en><de>Diesen Email Report Abbestellen</de><fr>Se désabonner de ce rapport par courriel</fr><es>Dejar suscripción a este informe por correo electrónico</es></i18n>");

    $poweredBy=i18n("<i18n key='mai1'><en>Powered by</en><de>Ein Dienst von </de><fr>Propulsé par</fr><es>Desarrollado por</es></i18n>");

    $c="<html><head><title></title><style type=\"text/css\">\n".$style[2]."\n</style>\n</head><body bgcolor=\"#dddddd\">".'<div align="center">'.substr($p[2], $offset, $end)."</table></div>\n<br><br/><a href=\"".$domain."/util/remove_cron.php?cid=".$cid."\">".$removeText."</a><br><i>".$poweredBy." <a href=\"".$domain."\">".$domain."</a>.</i></body>";
    $bgs=replaceInline($c, "background=", "bg");
    $c=$bgs[0];

    $c=str_replace('<table class="bodyTable" width="800" border="1" cellspacing="1">', '<table class="bodyTable" width="650" border="1" cellspacing="1" style="background: #ffffff; border: 1px dashed #CCCCCC;">', $c);

    $body ="This is a multi-part message in MIME format.\r\n\r\n--PHP-001-{$sep}\r\nContent-Type: multipart/alternative; boundary=\"PHP-002-{$sep}\"\r\nContent-Transfer-Encoding: 7bit\r\n\r\n--PHP-002-{$sep}\r\nContent-Type: text/plain; charset=\"iso-8859-1\"\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\nNot html text here\r\n\r\n--PHP-002-{$sep}\r\nContent-Type: ".$p[1]["Content-Type"]."\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\n".
    $c."\r\n\r\n--PHP-002-{$sep}--";

    foreach($bgs[1] as $key => $url) {
      $bg=getContent($url);
      $body.="\r\n\r\n--PHP-001-{$sep}\r\nContent-Type: ".$bg[1]["Content-Type"]."\r\nContent-Transfer-Encoding: base64\r\nContent-ID: <".$key.">\r\n\r\n";
      $body.=chunk_split(base64_encode($bg[2]));
    }

    $body.="\r\n\r\n--PHP-001-{$sep}--";

    if (enqueueMail($email, "[Emphasize] ".$title, $body, "From: ".$feedback_to."\r\nX-Mailer: PHP mail\r\nContent-Type: multipart/related; boundary=\"PHP-001-{$sep}\"\r\nMIME-Version: 1.0\r\n\r\n")) {
      echo("$cid email-report $title sent to $email\n");
    } else {
      echo("$cid email-report $title to $email failed\n");
    }
  }

  return date('Y-m-d', getMailReportNextRunDate($interval, $dayoffset));
}

function getState($id_user) {
	global $db_prefix;
  $sql = @mysql_query("SELECT state FROM " . $db_prefix . "USER WHERE id=".$id_user);
	if ($sql === FALSE) {
	  fail("state query failed");
	}
	if ($row = mysql_fetch_array($sql)) {
		$state=$row["state"];
	}
	mysql_free_result($sql);
	return $state;
}

function setState($id_user, $state) {
	global $db_prefix;
  $insert = @mysql_query("UPDATE " . $db_prefix . "USER SET `state`='".p($state)."' WHERE id=".$id_user);
  if (!$insert) {
	  fail("update state ".$id_user." with \"$state\" failed");
  }
}

function enqueueMail($address, $title, $body, $additionals) {
  global $db_prefix;
  $insert = @mysql_query("INSERT " . $db_prefix . "MAILQUEUE SET address='".p($address)."', title='".p($title)."', body='".p($body)."', additionals='".p($additionals)."'");
	if (!$insert) {
	  echo("INSERT " . $db_prefix . "MAILQUEUE SET address='".p($address)."', title='".p($title)."', body='".p($body)."', additionals='".p($additionals)."'");
		fail("mailqueue insert failed");
	}
}

function dequeueMail() {
  global $db_prefix;
  $sql = @mysql_query("SELECT id, address, title, body, additionals FROM " . $db_prefix . "MAILQUEUE ORDER BY id ASC LIMIT 0,1");
	if ($sql === FALSE) {
	  fail("dequeue mail query failed");
	}
	$dequeued=0;
	if ($row = mysql_fetch_array($sql)) {
		$did=$row["id"];
		$address=$row["address"];
		$title=$row["title"];
		$body=$row["body"];
		$additionals=$row["additionals"];
		if (mail($address, $title, $body, $additionals)) {
		  $delete = @mysql_query("DELETE FROM " . $db_prefix . "MAILQUEUE WHERE id=".$did);
	    if (!$delete) {
		    fail("dequeueMail delete ".$did." failed");
	    }
	    $dequeued=1;
		} else {
		  $dequeued=-1;
		}
	}
	mysql_free_result($sql);
	return $dequeued;
}

?>
