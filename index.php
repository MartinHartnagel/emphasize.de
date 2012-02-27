<?php
include_once(dirname(__FILE__).'/includes/config.php');

if (strpos($domain, "://".$_SERVER['SERVER_NAME']) === false) {
  $redirect=true;
  if (isset($domains)) {
    foreach($domains as $l=>$d) {
      if (strpos($d, "://".$_SERVER['SERVER_NAME']) !== false) {
        $domain=$d;
        $redirect=false;
        break;
      }
    }
  }
  if ($redirect) {
	  $export="json";
	  header("Location: $domain"); /* Redirect browser */
	  exit();
	}
} else {
  if (isset($domains)) {
    $lang=detectLang();
    foreach($domains as $l=>$d) {
      if ($l==$lang) {
        $export="json";
	      header("Location: $d"); /* Redirect browser */
	      exit();
      }
    }
  }
}

if (!isset($_SESSION)) {
	session_start();
}
if (!empty($_POST)) {
	if (isset($_POST["do"]) && $_POST["do"] == "derefer") {
		$url = $_POST["url"];
		echo("<html><head><title>redirect</title><meta http-equiv=\"Refresh\" content=\"0; URL=$url\"><head><body bgcolor=\"#ffffff\" onLoad=\"javascript: window.location='$url';\"><center style=\"color:#aaaaaa;\">redirect to $url</center></body></html>");
		exit;
	} else if (isset($_POST["do"]) && $_POST["do"] == "createUser") {
		include_once(dirname(__FILE__).'/includes/registration.php');
	} else if (isset($_POST["do"]) && $_POST["do"] == "login") {
		$name = $_POST["name"];
		$pw_hash = pw_hash($_POST["password"]);
		$stay = $_POST["stay"];

		connectDb();
		login(true, $name, $pw_hash, $stay);
		if ($stay==0) {
			$export="json";
			header("Location: $domain?$token"); /* Redirect browser */
			exit;
		} else {
			include_once(dirname(__FILE__).'/includes/tabletti.php');
		}
	} else {
		include_once(dirname(__FILE__).'/includes/demo.php');
	}
} elseif (!empty($_GET)) {
	if ($_SERVER['QUERY_STRING']=="agb") {
		$lang=detectLang();
		checkCache("agb_".$lang.".html", "includes/agb.html", "includes/languages.php");
		include_once(dirname(__FILE__).'/includes/agb.html');
	} elseif (strlen($_SERVER['QUERY_STRING'])==20) {
		$token=$_SERVER['QUERY_STRING'];
		connectDb();
		// nizip
		if ($token=="kor4lodem2chok111111") {
			// testing immediate register account with
			$insert = @mysql_query("REPLACE INTO " . $db_prefix . "USER SET name='EmphasizeUnit', pw_hash=' ', email=' ', tbody='<tr><td class=\"noreport\" rowspan=\"3\" bgcolor=\"#639da1\">Pause</td><td  bgcolor=\"#3f57ff\">Installation</td></tr><tr><td bgcolor=\"#6ba163\">Dokumentation</td></tr><tr><td  bgcolor=\"#e80068\">Datensicherung</td></tr>', lang='de', confirmed='t'");
			if (!$insert) {
				//ERROR
				fail("Eintrag fehlgeschlagen");
			}
			$insert = @mysql_query("REPLACE INTO " . $db_prefix . "USAGE SET id_user=(SELECT id FROM " . $db_prefix . "USER WHERE name='EmphasizeUnit' AND pw_hash=' '), token='".p($token)."', stay=1440");
			if (!$insert) {
				fail("Eintrag fehlgeschlagen");
			}
		} else if (substr($token, 0, 6)=="tryout") {
			// 20 minute testing account
			$n=i18n("<i18n key='guest0'><en>Guest</en><de>Gast</de><fr>Visiteur</fr><es>Visitante</es></i18n>");
			$l=i18n("<lang/>");

			do {
				$aid=gen_aid($i);
				if (isset($asql)) mysql_free_result($asql);
				$asql = @mysql_query("SELECT id FROM " . $db_prefix . "USER WHERE aid='".p($aid)."'");
			} while ($row = mysql_fetch_array($asql));
			mysql_free_result($asql);

			$insert = @mysql_query("REPLACE INTO " . $db_prefix . "USER SET aid='".p($aid)."', name='".$n."', pw_hash='".$token."', email=' ', tbody='<tr><td class=\"noreport\" rowspan=\"0\" bgcolor=\"#6ba163\"><i18n key=\"guest1\"><en>use scissors to divide</en><de>teile mich mit der Schere</de><fr>ciseaux pour fracture</fr><es>tijera para dividir</es></i18n></td></tr>', lang='".$l."', confirmed='a', base_href='".p($domain."/util/briefing.php?a=".$aid."&q=")."'");
			if (!$insert) {
				//ERROR
				fail("Eintrag fehlgeschlagen");
			}
			$sql = @mysql_query("SELECT id FROM " . $db_prefix . "USER WHERE pw_hash='".$token."'");
			if ($row = mysql_fetch_array($sql)) {
				if (is_numeric($row["id"])) {
					$id=$row["id"];
				}
			}
			mysql_free_result($sql);
			$insert = @mysql_query("REPLACE INTO " . $db_prefix . "USAGE SET id_user=".$id.", token='".p($token)."', stay=20");
			if (!$insert) {
				fail("Eintrag fehlgeschlagen");
			}
			$command="showStatus(false, '... ".i18n("<i18n key='gast2'><en>20 minutes anonymous testing, register for personal account with \"Logout\" and \"register new user\"!</en><de>20 Minuten anonymer Test, persönlichen Benutzer mit \"Abmelden\" und \"neuen Benutzer anlegen\"!</de><fr>20 minutes de dépistage anonyme, utilisateur personnel avec \"Déconnexion \"et \"créer un nouvel utilisateur\"!</fr><es>20 minutos de pruebas anónimas, personal de usuario con \"Desconectarse \" y \"crear nuevo usuario\"!</es></i18n>")."');";
		}
		// /nizip
		pickup($token);
		include_once(dirname(__FILE__).'/includes/tabletti.php');
	} elseif (isset($_GET["lang"]) && strlen($_GET['lang'])==2) {
		if (isset($_SESSION['token'])) {
			$token=$_SESSION['token'];
			connectDb();
			pickup($token);
			setUserLang($_GET['lang']);
			include_once(dirname(__FILE__).'/includes/tabletti.php');
		} else {
			include_once(dirname(__FILE__).'/includes/demo.php');
		}
	} else if (isset($_GET["bid"]) && !empty($_GET["bid"])) {
		$bid=$_GET["bid"];
		include_once(dirname(__FILE__).'/includes/demo.php');
	}
} elseif (isset($_SESSION['token'])) {
	$token=$_SESSION['token'];
	connectDb();
	pickup($token);
	include_once(dirname(__FILE__).'/includes/tabletti.php');
} else {
	// correct includes filepermissions
	if (substr(decoct(fileperms('includes') ), 1) != "0700") {
		chmod('includes', 0700);
	}
	include_once(dirname(__FILE__).'/includes/demo.php');
}
bottom();
?>
