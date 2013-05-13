<?php
if (!class_exists("SimpleTest")) {
 require_once (dirname(__FILE__) . '/../lib/simpletest/autorun.php');
}

require_once(dirname(__FILE__)."/util.php");
require_once (dirname(__FILE__) . "/../includes/config.php");
require_once(dirname(__FILE__)."/../util/securimage.php");

class PrepareGuiTests extends UnitTestCase {

 function setUp() {
  global $ob_buffer;
  global $testmail;
  global $lc;
  global $tbody_value;
  global $tbody_names;
  
  global $registration_from;
  global $feedback_to;
  global $reports;
  global $guiTestLogin;
  global $guiTestPassword;

  $ob_buffer="";
  $testmail="";

  // cleanup previous guiTest-data
  User::getInstance()->login(true, $guiTestLogin, pw_hash($guiTestPassword));

  if (User::getInstance()->getId() != null) {
   User::deleteUser(User::getInstance()->getId());
  }

  // create new
  $_POST["do"] = "createUser";
  $_POST["name"]=$guiTestLogin;
  $_POST["password"]=$guiTestPassword;
  $_POST["verify"]=$guiTestPassword;
  $_POST["email"]="webmaster@emphasize.de";
  $_POST["termsAndConditions"]="readAccepted";
  $_POST["lang"]="de";

  $img = new Securimage();
  $img->createCode();
  $_POST["code"]=$img->getCode();

  ob_start("getTestOut");
  require(dirname(__FILE__) . "/../index.php");
  ob_end_flush();
  $this->assertTrue(strpos($ob_buffer, $guiTestLogin." Login mit noch nicht bestätigter Registrierung gültig für eine Stunde") !== false, "missing probe-login-text");

  $this->assertEqual(substr_count($testmail, "instantMail"), 1);
  $this->assertEqual(substr_count($testmail, "enqueuMail"), 0);
  $this->assertEqual(substr_count($testmail, "Bestätigung der ".$guiTestLogin." Registration"), 1);
 }

 function testConfirmSuccessAndLogin() {
  global $feedback_to;
  
  global $ob_buffer;
  global $testmail;
  global $guiTestLogin;
  global $guiTestPassword;

  $_SERVER['QUERY_STRING']=User::getInstance()->getConfirmCode();
  ob_start("getTestOut");
  require(dirname(__FILE__) . "/../util/confirm.php");
  ob_end_flush();
  $this->assertTrue(strpos($ob_buffer, "Registration für ".$guiTestLogin." erfolgreich abgeschlossen, bitte mit Passwort einloggen") !== false, $ob_buffer);

  User::getInstance()->login(true, $guiTestLogin, pw_hash($guiTestPassword));
  $this->assertTrue(User::getInstance()->getId() != null);

  $this->assertEqual(substr_count($testmail, "instantMail"), 1);
  $this->assertEqual(substr_count($testmail, "enqueuMail"), 0);
  $this->assertEqual(substr_count($testmail, "Bestätigung der ".$guiTestLogin." Registration"), 1);
 }

 function tearDown() {
  global $ob_buffer;
  global $testmail;
  $ob_buffer="";
  $testmail="";
 }
}
?>