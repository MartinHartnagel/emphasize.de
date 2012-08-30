<?php
require_once(dirname(__FILE__) . '/../lib/simpletest/simpletest.php');
require_once(dirname(__FILE__) . '/../lib/simpletest/unit_tester.php');
require_once(dirname(__FILE__) . '/../lib/simpletest/mock_objects.php');
require_once(dirname(__FILE__) . '/../lib/simpletest/collector.php');
require_once(dirname(__FILE__) . '/../lib/simpletest/default_reporter.php');

require_once(dirname(__FILE__) . "/util.php");
require_once(dirname(__FILE__) . "/../includes/config.php");

$guiTestLogin="GuiTest";
$guiTestPassword="bla";

class QUnitProceedReporter extends HtmlReporter {
 function paintHeader($test_name) {
  global $domain;
  global $suite;
  global $guiTestLogin;
  global $guiTestPassword;

  $this->sendNoCacheHeaders();
  print "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
  print "<html>\n<head>\n<title>$test_name</title>\n";
  print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=\"UTF-8\"\">\n";
  print '<base href="'.$domain.'" />';
  print '<script src="js/jquery-1.6.4.min.js"></script>
  <link rel="stylesheet" href="lib/qunit/qunit-git.css" type="text/css" media="screen" />
  <script type="text/javascript" src="lib/qunit/qunit-git.js"></script>
  <script type="text/javascript">
  //set jquery to no conflict, so we do not have a problem with the version from in the page
  var $$ = jQuery.noConflict(true);
  var $ = jQuery = null; //we will be using the normal jquery vars soon enough
  </script>';
  print '<script src="test/util.js"></script>';
  print "<style type=\"text/css\">\n";
  print $this->getCss() . "\n";
  print "</style>\n";
  print('<script type="text/javascript">
    var login="'.$guiTestLogin.'";
    var password="'.$guiTestPassword.'";
    </script>');
  foreach($suite->getClientTests() as $js) {
   print '<script src="test/'.$js.'"></script>';
  }
  print "</head>\n<body>\n";
  print "<h1>$test_name</h1>\n";
  flush();
 }

 function paintFooter($test_name) {
  $failed=($this->getFailCount() + $this->getExceptionCount() > 0);
  $colour = ($failed ? "red" : "green");
  print "<div style=\"";
  print "padding: 8px; margin-top: 1em; background-color: $colour; color: white;";
  print "\">";
  print $this->getTestCaseProgress() . "/" . $this->getTestCaseCount();
  print " test cases complete:\n";
  print "<strong>" . $this->getPassCount() . "</strong> passes, ";
  print "<strong>" . $this->getFailCount() . "</strong> fails and ";
  print "<strong>" . $this->getExceptionCount() . "</strong> exceptions.";
  print "</div><br/>\n";
  print '
  <div style="float: left;width:320px;">
    <h2 id="qunit-banner"></h2>
    <div id="qunit-testrunner-toolbar"></div>
    <h2 id="qunit-userAgent"></h2>
    <ol id="qunit-tests"></ol>
    <div id="qunit-fixture">test markup, will be hidden</div>
  </div>
  <br clear="all" />
  <hr />
  <br/>
  <div><center>
    <iframe height="480" width="800" id="testFrame"></iframe></center>
  </div>';
  print('<script type="text/javascript">
    $$(document).ready(runTests);
    </script>');
  print "</body>\n</html>\n";
 }
}

class AllTests extends TestSuite {
 private $clientTests=array();

 function __construct() {
  parent::__construct('All Emphasize.de Tests');
  $this->addFile('daily.php');
  $this->addFile('weekly.php');
  $this->addFile('monthly.php');

  $this->addFile('download.php');

  $this->addFile('register.php');
  $this->addFile('login.php');

  $this->addFile('prepareGuiTests.php');

  // js gui tests with qunit

  $this->addClientTest('demo.js');
  $this->addClientTest('controls.js');
  $this->addClientTest('tabs.js');
  $this->addClientTest('timeline.js');
 }

 function addClientTest($jsFile) {
  $this->clientTests[]=$jsFile;
 }

 function getClientTests() {
  return $this->clientTests;
 }

 function run() {
  parent::run(new QUnitProceedReporter());
 }
}

$suite=new AllTests();
$suite->run();
?>