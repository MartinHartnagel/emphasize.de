<?php
if (!class_exists("SimpleTest")) {
  require_once (dirname(__FILE__) . '/../lib/simpletest/autorun.php');
}

$no_ob_start = true;
require_once (dirname(__FILE__) . "/../includes/config.php");

class DailyTest extends UnitTestCase {

	function testTomorrowGetMailReportNextRunDate() {
		$run = getMailReportNextRunDate("daily", "0,1,2,3,4,5,6");
		$this->assertEqual($run, strtotime("tomorrow"));
	}

	function testSundayGetMailReportNextRunDate() {
		$run = getMailReportNextRunDate("daily", "0", strtotime("2012-05-25 10:00:00")); // now=friday
		$this->assertEqual($run, strtotime("2012-05-27 00:00:00"));
	}

	function testSunday2GetMailReportNextRunDate() {
		$run = getMailReportNextRunDate("daily", "0,1,2,3,4", strtotime("2012-05-25 10:00:00")); // now=friday
		$this->assertEqual($run, strtotime("2012-05-27 00:00:00"));
	}

	function testFridayGetMailReportNextRunDate() {
		$run = getMailReportNextRunDate("daily", "5", strtotime("2012-05-25 10:00:00")); // now=friday
		$this->assertEqual(date('Y-m-d', $run), date('Y-m-d', strtotime("2012-06-01 00:00:00")));
	}

	function testFriday2GetMailReportNextRunDate() {
		$run = getMailReportNextRunDate("daily", "5,6", strtotime("2012-05-26 10:00:00")); // now=saturday
		$this->assertEqual(date('Y-m-d', $run), date('Y-m-d', strtotime("2012-06-01 00:00:00")));
	}
}
?>