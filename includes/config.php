<?php

define("VERSION", "2.0.0");
define("DB_PREFIX", "EMPHASIZE_");
$domain = "http://next.emphasize.de";
// alternative domains for the same installation:
$domains = array("de"=>"http://zeit.emphasize.de", 
"en"=>"http://time.emphasize.de", 
"fr"=>"http://temps.emphasize.de", 
"es"=>"http://tiempo.emphasize.de",
"cs"=>'http://cas.emphasize.de',
"da"=>'http://tid.emphasize.de',
"fi"=>'http://aika.emphasize.de',
"hu"=>'http://ido.emphasize.de',
"it"=>'http://orario.emphasize.de',
"nl"=>'http://tijd.emphasize.de',
"no"=>'http://tid.emphasize.de',
"pl"=>'http://czas.emphasize.de',
"pt"=>'http://tempo.emphasize.de',
"ru"=>'http://ВРЕМЯ.emphasize.de',
"sv"=>'http://klockslag.emphasize.de',
"tr"=>'http://zaman.emphasize.de');
// for dev purposes, unset domains again
unset($domains);

$db_name = "some"; //Name of the host database
$db_host = "localhost"; //Database host (usually this is "localhost")
$db_username = "some"; //Database login
$db_password = "some"; //Database password
// nizip
include_once(dirname(__FILE__).'/../../../files/db1.php');
// /nizip

$feedback_to = "admin@emphasize.de";
$registration_from = "registration@emphasize.de";


// no/full error reporting:
error_reporting(E_ALL);
ini_set('display_errors', '1');
//ini_set('max_execution_time', '600');
//ini_set('memory_limit', '64M');

include_once(dirname(__FILE__)."/user.php");
include_once(dirname(__FILE__)."/languages.php");
include_once(dirname(__FILE__)."/functions.php");
include_once(dirname(__FILE__)."/event.php");
include_once(dirname(__FILE__)."/template.php");
?>
