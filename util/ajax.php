<?php
include_once(dirname(__FILE__).'/../includes/config.php');
	if (r("do") == "getPlace") {
		$export="json";
		header("Content-Type: text/html;charset=UTF-8");
		$time = r("time");
		echo(getPlace($time));
	} else if (r("do") == "updateTbody") {
		$export="json";
		$newtbody=r('tbody');
		$success=User::getInstance()->updateTbody($newtbody);
		exit($success);
	} else if (r("do") == "trackEvent") {
		$export="json";
		$event=r('event');
		$color=r('color');
		$time=r('time');
		$link=r('link');
		$success=addEvent($event, $color, $time, $link);
		exit($success);
	} else if (r("do") == "addInfo") {
		$export="json";
		$info=r('info');
		$time=r('time');
		$success=addInfo($info, $time);
		exit($success);
	} else if (r("do") == "getTimelineHistory") {
		$export="json";
		header("Content-Type: text/html;charset=UTF-8");
		$now=r('now');
		$before=r('before');
		getTimelineHistory($now, $before);
	} else if (r("do") == "setLang") {
		$export="json";
		$newlang=r('lang');
		User::getInstance()->setUserLang($newlang);
  } else if (r("do") == "passwd") {
		$export="json";
		User::getInstance()->setPasswd(r("password"), r("verify"));
	} else if (r("do") == "logout") {
		User::getInstance()->logout();
		include_once(dirname(__FILE__)."/../includes/demo.php");
	} else if (r("do") == "debug") {
		// nizip
		debugMail(r('txt'));
		// /nizip
	} else if (r("do") == "setTemplate") {
  $export="json";
  $template=r('template');
  User::getInstance()->setActiveTemplate($template);
  echo('<table id="table" class="dashboard" width="100%" height="100%" border="0" cellspacing="2" cellpadding="0">');
  echo(User::getInstance()->getActiveTemplateValue());
  echo('</table>');
  exit();
 }

bottom();
?>
