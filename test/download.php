<?php
if (!class_exists("SimpleTest")) {
 require_once (dirname(__FILE__) . '/../lib/simpletest/autorun.php');
}

if (!class_exists("WebTestCase")) {
 require_once (dirname(__FILE__) . '/../lib/simpletest/web_tester.php');
}

require_once(dirname(__FILE__)."/util.php");
require_once (dirname(__FILE__) . "/../includes/config.php");

class DownloadTest extends WebTestCase {

 private $dest;

 function setUp() {
  $this->dest=sys_get_temp_dir()."/test".substr(md5(time()), 0, 6);
  $this->assertTrue(mkdir($this->dest), "mkdir of ".$this->dest);
 }

 function testDownload() {
  

  $filename="emphasize-".VERSION.".zip";
  if (file_exists(dirname(__FILE__)."/../".$filename)) {
   unlink(dirname(__FILE__)."/../".$filename);
  }
  $this->assertTrue($this->get(DOMAIN.'/util/download.php'));
  //$this->showHeaders();
  $this->assertMime(array('application/zip'));
  $this->assertHeader('Content-Description', 'File Transfer');
  $this->assertHeader('Content-Transfer-Encoding', 'binary');
  $this->assertHeader('Content-Disposition', 'attachment; filename='.$filename.';');
  $file=$this->dest."/".$filename;
  file_put_contents($file, $this->getBrowser()->getContent());
  $this->assertHeader('Content-Length', filesize($file));

  unzip($file, $this->dest."/out/");
  $this->assertTrue(file_exists($this->dest."/out/install.txt"));
  $this->assertTrue(file_exists($this->dest."/out/license.txt"));
  $this->assertTrue(file_exists($this->dest."/out/emphasize-".VERSION."/index.php"));
  $this->assertTrue(file_exists($this->dest."/out/emphasize-".VERSION."/favicon.ico"));
  $this->assertTrue(is_dir($this->dest."/out/emphasize-".VERSION."/i"));

  $f=file_grep($this->dest."/out", "/.php/", "/izip/");
  $this->assertEqual($f, array(), "nizip check ".implode($f, ", "));
  $f=file_grep($this->dest."/out", "/./", "/web78/");
  $this->assertEqual($f, array(), "web78 check ".implode($f, ", "));
 }

 function tearDown() {
  $this->assertTrue(deleteDirectory($this->dest), "cleanup rmdir of ".$this->dest);
 }
}
?>