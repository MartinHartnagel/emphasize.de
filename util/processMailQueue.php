<?php
include_once(dirname(__FILE__)."/../includes/config.php");
$export="txt";
User::connectDb();
for ($i=0; $i<$processMailsInQueuesAtOnce; $i++) {
  $processed=dequeueMail();
  if ($processed > 0) {
    echo("mail queue processed\n");
  } else if ($processed < 0) {
    echo("mail queue malfunction\n");
    break;
  } else {
    echo("mail queue empty\n");
    break;
  }
}
?>

