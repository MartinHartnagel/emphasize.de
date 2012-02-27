<?php
include_once(dirname(__FILE__).'/../includes/config.php');
if (!isset($_SESSION)) {
	session_start();
}
if (!empty($_POST)) {
	if (isset($_POST["do"]) && $_POST["do"] == "getPlace") {
		$export="json";
		header("Content-Type: text/html;charset=UTF-8");
		$token = $_POST["token"];
		$time = $_POST["time"];
		connectDb();
		pickup($token);
		echo(getPlace($time));
	} else if (isset($_POST["do"]) && $_POST["do"] == "updateTbody") {
		$export="json";
		$token=$_POST['token'];
		$newtbody=$_POST['tbody'];
		connectDb();
		pickup($token);
		$success=updateTbody($newtbody);
		exit($success);
	} else if (isset($_POST["do"]) && $_POST["do"] == "setBaseHref") {
		$export="json";
		$token=$_POST['token'];
		$newbaseHref=$_POST['baseHref'];
		connectDb();
		pickup($token);
		$success=setBaseHref($newbaseHref);
		exit($success);
	} else if (isset($_POST["do"]) && $_POST["do"] == "trackEvent") {
		$export="json";
		$token=$_POST['token'];
		$event=$_POST['event'];
		$color=$_POST['color'];
		$time=$_POST['time'];
		connectDb();
		pickup($token);
		$success=trackEvent($event, $color, $time);
		exit($success);
	} else if (isset($_POST["do"]) && $_POST["do"] == "addInfo") {
		$export="json";
		$token=$_POST['token'];
		$info=$_POST['info'];
		$time=$_POST['time'];
		connectDb();
		pickup($token);
		$success=addInfo($info, $time);
		exit($success);
	} else if (isset($_POST["do"]) && $_POST["do"] == "getTimelineHistory") {
		$export="json";
		header("Content-Type: text/html;charset=UTF-8");
		$token=$_POST['token'];
		$now=$_POST['now'];
		$before=$_POST['before'];
		connectDb();
		pickup($token);
		getTimelineHistory($now, $before);
	} else if (isset($_POST["do"]) && $_POST["do"] == "setLang") {
		$export="json";
		$token=$_POST['token'];
		$newlang=$_POST['lang'];
		connectDb();
		pickup($token);
		setUserLang($newlang);
	} else if (isset($_POST["do"]) && $_POST["do"] == "logout") {
		$token=$_POST['token'];
		connectDb();
		logout($token);
		if (isset($_SESSION)) {
			session_destroy();
			unset($_SESSION);
		}
		include_once(dirname(__FILE__)."/../includes/demo.php");
	} else if (isset($_POST["do"]) && $_POST["do"] == "debug") {
		// nizip
		debugMail($_POST['txt']);
		// /nizip
	}
}
bottom();
?>
