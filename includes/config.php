<?php

$version="next"; 
$domain = "http://emphasize.de";
// alternative domains for the same installation:
$domains=array("de"=>"http://next.emphasize.de");
$db_prefix="EMPHASIZE_";

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

include_once(dirname(__FILE__)."/languages.php");
include_once(dirname(__FILE__)."/translations.php");
include_once(dirname(__FILE__)."/functions.php");
?>
