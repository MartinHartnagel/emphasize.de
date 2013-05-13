<?php
if (!class_exists("SimpleTest")) {
  require_once (dirname(__FILE__) . '/../lib/simpletest/autorun.php');
}


$no_ob_start = true;
require_once (dirname(__FILE__) . "/../includes/config.php");

class MonthlyTest extends UnitTestCase {
	function testMonthGetMailReportNextRunDate() {
		$run = getMailReportNextRunDate("monthly", "0", strtotime("2012-05-25 10:00:00"));
		$this->assertEqual(date('Y-m-d', $run), date('Y-m-d', strtotime("2012-06-01 00:00:00")));
	}

	function testMonth1GetMailReportNextRunDate() {
		$run = getMailReportNextRunDate("monthly", "1", strtotime("2012-05-25 10:00:00"));
		$this->assertEqual(date('Y-m-d', $run), date('Y-m-d', strtotime("2012-06-02 00:00:00")));
	}

	function testMonth30GetMailReportNextRunDate() {
		$run = getMailReportNextRunDate("monthly", "29", strtotime("2012-05-25 10:00:00"));
		$this->assertEqual(date('Y-m-d', $run), date('Y-m-d', strtotime("2012-06-30 00:00:00")));
	}

	function testFeb30GetMailReportNextRunDate() {
		$run = getMailReportNextRunDate("monthly", "29", strtotime("2012-01-25 10:00:00"));
		$this->assertEqual(date('Y-m-d', $run), date('Y-m-d', strtotime("2012-03-01 00:00:00")));
	}

	function testMonthMinus1GetMailReportNextRunDate() {
		$run = getMailReportNextRunDate("monthly", "-1", strtotime("2012-05-25 10:00:00"));
		$this->assertEqual(date('Y-m-d', $run), date('Y-m-d', strtotime("2012-06-30 00:00:00")));
	}

	function testFebMinus1GetMailReportNextRunDate() {
		$run = getMailReportNextRunDate("monthly", "-1", strtotime("2012-01-25 10:00:00"));
		$this->assertEqual(date('Y-m-d', $run), date('Y-m-d', strtotime("2012-02-29 00:00:00")));
	}
}
?>