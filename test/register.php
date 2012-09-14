<?php
if (!class_exists("SimpleTest")) {
  require_once (dirname(__FILE__) . '/../lib/simpletest/autorun.php');
}


require_once(dirname(__FILE__)."/util.php");
require_once (dirname(__FILE__) . "/../includes/config.php");
require_once(dirname(__FILE__)."/../util/securimage.php");

class RegisterTest extends UnitTestCase {

 function testInvalidCode() {
  global $ob_buffer;
  global $testmail;

  $_POST["do"] = "createUser";
  $img = new Securimage();
  $img->createCode();
  $code=$img->getCode();
  $_POST["code"]="fail".$img->getCode();
  try {
   require(dirname(__FILE__) . "/../index.php");
   $this->fail();
  } catch (Exception $e) {
   $this->assertEqual("Sorry, the code \"fail".$code."\" you entered was invalid", $e->getMessage());
  }
  $this->assertEqual($testmail, "");
 }

 function testNameTooShort() {
  global $ob_buffer;
  global $testmail;

  $_POST["do"] = "createUser";
  $_POST["name"]="e";
  $img = new Securimage();
  $img->createCode();
  $_POST["code"]=$img->getCode();
  try {
   require(dirname(__FILE__) . "/../index.php");
   $this->fail();
  } catch (Exception $e) {
   $this->assertEqual("Sorry, user name is not long enough (>1 character)", $e->getMessage());
  }
  $this->assertEqual($testmail, "");
 }

 function testNoPassword() {
  global $ob_buffer;
  global $testmail;

  $_POST["do"] = "createUser";
  $_POST["name"]="es";
  $img = new Securimage();
  $img->createCode();
  $_POST["code"]=$img->getCode();
  try {
   require(dirname(__FILE__) . "/../index.php");
   $this->fail();
  } catch (Exception $e) {
   $this->assertEqual("password not specified", $e->getMessage());
  }
  $this->assertEqual($testmail, "");
 }

 function testPasswordNotVerifying() {
  global $ob_buffer;
  global $testmail;

  $_POST["do"] = "createUser";
  $_POST["name"]="es";
  $_POST["password"]="bla";
  $_POST["verify"]="blupp";
  $img = new Securimage();
  $img->createCode();
  $_POST["code"]=$img->getCode();
  try {
   require(dirname(__FILE__) . "/../index.php");
   $this->fail();
  } catch (Exception $e) {
   $this->assertEqual("Sorry, password does not verify", $e->getMessage());
  }
  $this->assertEqual($testmail, "");
 }

 function testEmailTooShort() {
  global $ob_buffer;
  global $testmail;

  $_POST["do"] = "createUser";
  $_POST["name"]="es";
  $_POST["password"]="bla";
  $_POST["verify"]="bla";
  $_POST["email"]="m@g.d";
  $img = new Securimage();
  $img->createCode();
  $_POST["code"]=$img->getCode();
  try {
   require(dirname(__FILE__) . "/../index.php");
   $this->fail();
  } catch (Exception $e) {
   $this->assertEqual("Sorry, email is not long enough (>5 character)", $e->getMessage());
  }
  $this->assertEqual($testmail, "");
 }

 function testNoTermsAndConditions() {
  global $ob_buffer;
  global $testmail;

  $_POST["do"] = "createUser";
  $_POST["name"]="es";
  $_POST["password"]="bla";
  $_POST["verify"]="bla";
  $_POST["email"]="m@g.de";
  $img = new Securimage();
  $img->createCode();
  $_POST["code"]=$img->getCode();
  try {
   require(dirname(__FILE__) . "/../index.php");
   $this->fail();
  } catch (Exception $e) {
   $this->assertEqual("Sorry, terms and conditions not accepted", $e->getMessage());
  }
  $this->assertEqual($testmail, "");
 }

 function testFunnyLang() {
  global $ob_buffer;
  global $testmail;
  global $lc;

  $_POST["do"] = "createUser";
  $_POST["name"]="es";
  $_POST["password"]="bla";
  $_POST["verify"]="bla";
  $_POST["email"]="m@g.de";
  $_POST["termsAndConditions"]="readAccepted";
  $_POST["lang"]="-!";
  $img = new Securimage();
  $img->createCode();
  $_POST["code"]=$img->getCode();
  try {
   require(dirname(__FILE__) . "/../index.php");
   $this->fail();
  } catch (Exception $e) {
   $this->assertEqual("Sorry, unrecognized language -!", $e->getMessage());
  }
  $this->assertEqual($testmail, "");
 }

 function testSuccess() {
  global $ob_buffer;
  global $testmail;
  global $lc;
  global $tbody_value;
  global $tbody_names;
  global $domain;
  global $registration_from;
  global $feedback_to;
  global $reports;

  $_POST["do"] = "createUser";
  $testName="test".substr(md5(time()), 0, 5);
  $_POST["name"]=$testName;
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
  $this->assertTrue(strpos($ob_buffer, $testName." Login mit noch nicht bestätigter Registrierung gültig für eine Stunde") !== false, "missing probe-login-text");
  $this->assertEqual(substr_count($testmail, "instantMail"), 1);
  $this->assertEqual(substr_count($testmail, "enqueuMail"), 0);
  $this->assertEqual(substr_count($testmail, "Bestätigung der ".$testName." Registration"), 1);
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