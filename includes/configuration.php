<?php
define("APP_NAME", "Emphasize");
define("VERSION", "2.0.0");

// example db-configuration
$db_host = "localhost"; //Database host (usually this is "localhost")
$db_name = "some"; //Name of the host database
$db_username = "some"; //Database login
$db_password = "some"; //Database password
$db_prefix = "EMPHASIZE"; //Prefix for tables (use different for multiple installations)

// installation setup
$domain = "http://next.emphasize.de";
$feedback_to = "admin@emphasize.de";

// configuration-replace
// installed db-configuration (overriding example above)
// nizip
$db_host = "localhost";
$db_name = "usr_web78_1";
$db_username = "root";
$db_password = "heru108ka";
$db_prefix = "EMPHASIZE";
$domain = "http://next.emphasize.de";
$feedback_to = "admin@emphasize.de";
// /nizip
// /configuration-replace

define("DB_PREFIX", ($db_prefix==""?"":$db_prefix."_"));
define("DOMAIN", $domain);
define("FEEDBACK_TO", $feedback_to);

// directory defines
define("CACHE", dirname(__FILE__)."/../cache");
define("INC", dirname(__FILE__)."/../includes");
define("SHORTS", dirname(__FILE__)."/../i");


define("DEV", (DOMAIN == "http://next.emphasize.de"));
// no/full error reporting:
if (DEV) {
 error_reporting(E_ALL);
 ini_set('display_errors', '1');
 ini_set('memory_limit', '16M');
}
?>
