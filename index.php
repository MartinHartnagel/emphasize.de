<?php
include_once(dirname(__FILE__).'/includes/config.php');

if (isset($redirect) && $redirect) {
 $export="json";
 header("Location: $domain"); /* Redirect browser */
 exit();
} else if (r("do") == "derefer") {
 $url = r("url");
 echo("<html><head><title>redirect</title><meta http-equiv=\"Refresh\" content=\"0; URL=$url\"><head><body bgcolor=\"#ffffff\" onLoad=\"javascript: window.location='$url';\"><center style=\"color:#aaaaaa;\">redirect to $url</center></body></html>");
 exit;
} else if (r("do") == "createUser") {
 require(dirname(__FILE__).'/includes/registration.php');
} else if (r("do") == "login") {
 $name = r("name");
 $pw_hash = pw_hash(r("password"));

 User::getInstance()->login(true, $name, $pw_hash);
 $export="json";
 header("Location: $domain/i/".User::getInstance()->getToken()); /* Redirect browser */
 exit;
}

if (r("lang") != null) {
 User::getInstance()->setUserLang(r('lang'));
}

if (r("bid") != null) {
 $bid=r("bid");
}

if (User::getInstance()->getId() != null) {
 include_once(dirname(__FILE__).'/includes/dashboard.php');
} else {
 // correct includes filepermissions
 if (substr(decoct(fileperms(dirname(__FILE__).'/includes') ), 1) != "0755") {
  chmod(dirname(__FILE__).'/includes', 0755);
 }
 if (substr(decoct(fileperms(dirname(__FILE__).'/i') ), 1) != "0777") {
  chmod(dirname(__FILE__).'/i', 0777);
 }
 if (substr(decoct(fileperms(dirname(__FILE__).'/cache') ), 1) != "0777") {
  chmod(dirname(__FILE__).'/cache', 0777);  
 }

 include_once(dirname(__FILE__).'/includes/demo.php');
}
bottom();
?>
