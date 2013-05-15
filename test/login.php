<?php
if (!class_exists("SimpleTest")) {
 require_once (dirname(__FILE__) . '/../lib/simpletest/autorun.php');
}

require_once(dirname(__FILE__)."/util.php");
require_once (dirname(__FILE__) . "/../includes/config.php");
require_once(dirname(__FILE__)."/../util/securimage.php");

class LoginTest extends UnitTestCase {

 private $testName;

 function setUp() {
  global $lc;

  global $reports;
  global $ob_buffer;
  global $testmail;
  $ob_buffer="";
  $testmail="";

  $_POST["do"] = "createUser";
  $this->testName="test".substr(md5(time()), 0, 5);
  $_POST["name"]=$this->testName;
  $_POST["password"]="bla";
  $_POST["verify"]="bla";
  $_POST["email"]="webmaster@emphasize.de";
  $_POST["termsAndConditions"]="readAccepted";
  $_POST["lang"]="de";

  $img = new Securimage();
  $img->createCode();
  $_POST["code"]=$img->getCode();

  ob_start("getTestOut");
  require(dirname(__FILE__) . "/../index.php");
  ob_end_flush();
  $this->assertTrue(strpos($ob_buffer, $this->testName." Login mit noch nicht bestätigter Registrierung gültig für eine Stunde") !== false, "missing probe-login-text");
  $this->assertEqual(substr_count($testmail, "instantMail"), 1);
  $this->assertEqual(substr_count($testmail, "enqueuMail"), 0);
  $this->assertEqual(substr_count($testmail, "Bestätigung der ".$this->testName." Registration"), 1);
 }

 function testLoginWithoutConfirmFail() {
  global $testmail;
  try {
   User::getInstance()->login(true, $this->testName, pw_hash("bla"));
   $this->fail();
  } catch (Exception $e) {
   $this->assertEqual("Login noch nicht erlaubt. Bitte Link in der Registrierungs-Email zur Bestätigung des Benutzers verwenden", $e->getMessage());
  }
  $this->assertEqual(substr_count($testmail, "instantMail"), 1);
  $this->assertEqual(substr_count($testmail, "enqueuMail"), 0);
  $this->assertEqual(substr_count($testmail, "Bestätigung der ".$this->testName." Registration"), 1);
 }

 function testConfirmFail() {
  global $testmail;
  $_SERVER['QUERY_STRING']="foxTheFalseRabbit";
  ob_start("getTestOut");
  try {
   require(dirname(__FILE__) . "/../util/confirm.php");
   $this->fail();
  } catch (Exception $e) {
   $this->assertEqual("Confirmation failed", $e->getMessage());
  }
  ob_end_flush();
  $this->assertEqual(substr_count($testmail, "instantMail"), 1);
  $this->assertEqual(substr_count($testmail, "enqueuMail"), 0);
  $this->assertEqual(substr_count($testmail, "Bestätigung der ".$this->testName." Registration"), 1);
 }

 function testConfirmSuccessAndLogin() {

  global $ob_buffer;
  global $testmail;

  $_SERVER['QUERY_STRING']=User::getInstance()->getConfirmCode();
  ob_start("getTestOut");
  require(dirname(__FILE__) . "/../util/confirm.php");
  ob_end_flush();
  $this->assertTrue(strpos($ob_buffer, "Registration für ".$this->testName." erfolgreich abgeschlossen, bitte mit Passwort einloggen") !== false, $ob_buffer);

  User::getInstance()->login(true, $this->testName, pw_hash("bla"));
  $this->assertTrue(User::getInstance()->getId() != null);
  $this->assertEqual(substr_count($testmail, "instantMail"), 1);
  $this->assertEqual(substr_count($testmail, "enqueuMail"), 0);
  $this->assertEqual(substr_count($testmail, "Bestätigung der ".$this->testName." Registration"), 1);
 }

 function tearDown() {
  global $ob_buffer;
  global $testmail;
  $ob_buffer="";
  $testmail="";

  if (User::getInstance()->getId() != null) {
   User::deleteUser(User::getInstance()->getId());
  }
 }
}
?>