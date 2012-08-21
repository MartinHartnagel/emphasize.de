<?php
if (!class_exists("SimpleTest")) {
 require_once (dirname(__FILE__) . '/../lib/simpletest/autorun.php');
}

if (!class_exists("WebTestCase")) {
 require_once (dirname(__FILE__) . '/../lib/simpletest/web_tester.php');
}

require_once(dirname(__FILE__)."/util.php");
require_once (dirname(__FILE__) . "/../includes/config.php");

class TabTest extends WebTestCase {

 private $dest;

 function setUp() {
  $this->dest=sys_get_temp_dir()."/tab".substr(md5(time()), 0, 6);
  $this->assertTrue(mkdir($this->dest), "mkdir of ".$this->dest);
 }

 function testUpload() {
  global $domain;

  file_put_contents($this->dest."/import.emphasize", 'emphasize-2.0.0 export from http://next.emphasize.de, untouched-lock:879903e2747f1a69ebfd7dfad7209d0c

<emphasize><tr><td style="background-color:#3f57ff">Installation</td></tr><tr><td style="background-color:#6ba163">Dokumentation</td></tr><tr><td style="background-color:#e80068">Datensicherung</td></tr></emphasize>');


  $this->assertTrue($this->get($domain.'/util/templates.php?lang=de'));

  $this->assertTrue($this->setField('fileToImport', $this->dest."/import.emphasize"));
  $this->assertTrue($this->click('Importieren'));
  $this->showHeaders();
  $this->showSource();
 }

 function tearDown() {
  $this->assertTrue(deleteDirectory($this->dest), "cleanup rmdir of ".$this->dest);
 }
}
?>