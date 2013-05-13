<?php
if (!class_exists("SimpleTest")) {
  require_once (dirname(__FILE__) . '/../lib/simpletest/autorun.php');
}

$no_ob_start = true;
require_once (dirname(__FILE__) . "/../includes/config.php");

class WeeklyTest extends UnitTestCase {

	function testMoGetMailReportNextRunDate() {
		$run = getMailReportNextRunDate("weekly", "1", strtotime("2012-05-25 10:00:00")); // now=friday
		$this->assertEqual(date('Y-m-d', $run), date('Y-m-d', strtotime("2012-05-28 00:00:00")));
	}

	function testTuGetMailReportNextRunDate() {
		$run = getMailReportNextRunDate("weekly", "2", strtotime("2012-05-25 10:00:00")); // now=friday
		$this->assertEqual(date('Y-m-d', $run), date('Y-m-d', strtotime("2012-05-29 00:00:00")));
	}

	function testSuGetMailReportNextRunDate() {
		$run = getMailReportNextRunDate("weekly", "0", strtotime("2012-05-25 10:00:00")); // now=friday
		$this->assertEqual(date('Y-m-d', $run), date('Y-m-d', strtotime("2012-05-27 00:00:00")));
	}

	function testSaGetMailReportNextRunDate() {
		$run = getMailReportNextRunDate("weekly", "-1", strtotime("2012-05-25 10:00:00")); // now=friday
		$this->assertEqual(date('Y-m-d', $run), date('Y-m-d', strtotime("2012-06-02 00:00:00")));
	}

	function testFrGetMailReportNextRunDate() {
		$run = getMailReportNextRunDate("weekly", "-9", strtotime("2012-05-25 10:00:00")); // now=friday
		$this->assertEqual(date('Y-m-d', $run), date('Y-m-d', strtotime("2012-06-01 00:00:00")));
	}
}
?>