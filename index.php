<?php
include_once(dirname(__FILE__).'/includes/config.php');

if (r("do") == "derefer") {
 $url = r("url");
 echo("<html><head><title>redirect</title><meta http-equiv=\"Refresh\" content=\"0; URL=$url\"><head><body bgcolor=\"#ffffff\" onLoad=\"javascript: window.location='$url';\"><center style=\"color:#aaaaaa;\">redirect to $url</center></body></html>");
 exit;
} else if (r("do") == "createUser") {
 require(INC.'/registration.php');
} else if (r("do") == "login") {
 $name = r("name");
 $pw_hash = pw_hash(r("password"));

 User::getInstance()->login(true, $name, $pw_hash);
 $export="json";
 header("Location: ".DOMAIN."/i/".User::getInstance()->getToken()); /* Redirect browser */
 exit();
}

if (r("lang") != null) {
 User::getInstance()->setUserLang(r('lang'));
}

if (r("bid") != null) {
 $bid=r("bid");
}

if (User::getInstance()->getId() != null) {
 include_once(INC.'/dashboard.php');
} else {
 include_once(dirname(__FILE__).'/includes/demo.php');
}
bottom();
?>
