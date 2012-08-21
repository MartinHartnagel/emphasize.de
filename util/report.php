<?php
include_once (dirname(__FILE__) .
'/../includes/config.php');

if (isset ($_GET["cron"]) && !empty ($_GET["cron"])) {
	$cron = $_GET["cron"];
}

if (isset ($_GET["range"]) && !empty ($_GET["range"])) {
	$range = $_GET["range"];
}

if (isset ($_GET["export"]) && !empty ($_GET["export"])) {
	$export = $_GET["export"];
} else {
	$export_csv = "export=csv&" . $_SERVER['QUERY_STRING'];
	$export_xml = "export=xml&" . $_SERVER['QUERY_STRING'];
}
$report_name = $_GET["type"];
if (!isset ($reports[$report_name])) {
	fail("Report " . $report_name . " not found");
}
$time = $_GET["time"];
$from_date = $_GET["from"];
$to_date = $_GET["to"];
$from = $_GET["from"] . " 00:00:00";
$to = $_GET["to"] . " 23:59:59";

$id = User :: getInstance()->getId();

$lastFirst = "";
$evenRow = false;

$col = -1;
$color = "000000";
$value = 0;
$max = 0;
$height = 16;
$width = 150;
$aligns = array ();

function reportHead() {
	$numargs = func_num_args();
	$arg_list = func_get_args();
	global $export;
	if ($export == "csv") {
		echo ("\"");
		for ($i = 0; $i < $numargs; $i++) {
			if ($i > 0) {
				echo ("\",\"");
			}
			echo ($arg_list[$i]);
		}
		echo ("\"\n");
	}
	elseif ($export == "xml") {
		echo ("\t<descriptions>\n");
		for ($i = 0; $i < $numargs; $i++) {
			echo ("\t\t<t$i>" . $arg_list[$i] . "</t$i>\n");
		}
		echo ("\t</descriptions>\n");
	} else {
		echo ("<tr>");
		for ($i = 0; $i < $numargs; $i++) {
			echo ("<th>" . $arg_list[$i] . "</th>");
		}
		echo ("</tr>\n");
	}
}

function setAligns($_aligns) {
	global $aligns;
	$aligns = $_aligns;
}

function setBackCol($_col, $_color, $_value, $_max, $_height, $_width) {
	global $col;
	global $color;
	global $value;
	global $max;
	global $height;
	global $width;

	$col = $_col;
	if (strpos($_color, "#", 0) === 0) {
		$color = substr($_color, 1);
	} else {
		$color = $_color;
	}
	$value = $_value;
	$max = $_max;
	$height = $_height;
	$width = $_width;
}

function reportData() {
	$numargs = func_num_args();
	$arg_list = func_get_args();
	global $export;
	global $lastFirst;
	global $evenRow;
	global $domain;
	global $col;
	global $color;
	global $value;
	global $max;
	global $height;
	global $width;
	global $aligns;
	global $base_href;

	if ($export == "csv") {
		echo ("\"");
		for ($i = 0; $i < $numargs; $i++) {
			if ($i > 0) {
				echo ("\",\"");
			}
			$val = $arg_list[$i];
			if (($i < 2) || (!is_numeric($val))) {
				echo ($val);
			} else {
				echo (csvTime($val));
			}
		}
		echo ("\"\n");
	}
	elseif ($export == "xml") {
		echo ("\t<set>\n");
		for ($i = 0; $i < $numargs; $i++) {
			if ($arg_list[$i] != null) {
				echo ("\t\t<t$i>");
				$val = $arg_list[$i];
				if (($i < 2) || (!is_numeric($val))) {
					echo ($val);
				} else {
					echo (hhmmss($val));
				}
				echo ("</t$i>\n");
			}
		}
		echo ("\t</set>\n");
	} else {

		if ($lastFirst != $arg_list[0]) {
			$evenRow = !$evenRow;
			$lastFirst = $arg_list[0];
		}

		echo ("<tr class=\"" . ($evenRow ? "even" : "odd") . "\">");
		for ($i = 0; $i < $numargs; $i++) {
			$val = $arg_list[$i];
			if ($val == null) {
				$val = "&nbsp;";
			}
			if ($i < count($aligns)) {
				echo ("<td align=\"" . $aligns[$i] . "\"");
			} else {
				if ($i == 0) {
					echo ("<td align=\"center\"");
				}
				elseif ($i == 1) {
					echo ("<td");
				} else {
					echo ("<td align=\"right\"");
				}
			}
			if (($i == $col) && ($col != -1)) {
				echo (" style=\"width:" . ($width -8) . "px;padding: 2px 4px;\" background=\"" . $domain . "/util/col.php?c=$color&v=$value&m=$max&h=$height&w=$width\"");
				$col = -1;
			}
			echo (">");
			if (($i < 2) || (!is_numeric($val))) {
				if ($i == 1 && strlen($base_href) > 1) {
					echo ('<a href="' . $base_href . $val . '" target="_blank">' . $val . '</a>');
				} else {
					echo ($val);
				}
			} else {
				echo (duration($val));
			}
			echo ("</td>");
		}
		echo ("</tr>\n");
	}
}

$title = User :: getInstance()->getName() . " ";
if (isset ($range)) {
	$title .= $rangeReports[$range];
} else {
	$title .= $reports[$report_name];
}
$subtitle = $from_date . " " . i18n("<i18n key='rpb0'><en>to</en><de>bis</de><fr>-</fr><es>-</es></i18n>") . " " . $to_date . " " . i18n("<i18n key='rpb1'><en>at</en><de>um</de><fr>à</fr><es>a</es></i18n>") . " " . $time;
$filename = User :: getInstance()->getName() . "-";
if (isset ($range)) {
	$filename .= $range;
} else {
	$filename .= $report_name;
}
$filename .= "_" . date("Y-m-d");

if (isset ($export) && $export == "csv") {
	header("Content-type: application/vnd.ms-excel;charset=UTF-8"); // text/x-csv
	header("Content-disposition: attachment; filename=" . $filename . ".csv");
	echo ("#" . $title . " " . $subtitle . "\n");
} else
	if (isset ($export) && $export == "xml") {
		header('Content-Type: text/xml;charset=UTF-8');
		header("Content-disposition: attachment; filename=" . $filename . ".xml");
		echo ('<?xml version="1.0" encoding="UTF-8"?>');
		echo ("\n<report title=\"" . $title . " " . $subtitle . "\">\n");
	} else {
		echo ("<title>" . $title . " " . $subtitle . "</title>");
?>
</head>
<body onload="initReport()">
	<h1>
		<?php echo($title); ?>
	</h1>
	<?php echo($subtitle); ?>
	<table class="bodyTable" width="800" border="1" cellspacing="1">
		<?php


	}
include_once (dirname(__FILE__) . "/../reports/" . $report_name . ".php");
if ($export == "csv") {
	echo ("\n");
} else
	if ($export == "xml") {
		echo ("</report>\n");
	} else {
?>
	</table>
	<br /> Export
	<a href="<?php echo($domain.'/util/report.php?'.$export_csv);?>">csv</a>,
	<a href="<?php echo($domain.'/util/report.php?'.$export_xml);?>">xml</a>
	<br />
	<?php
		if (function_exists("getMailReportAbo")) {
			echo(getMailReportAbo());
		}
	}
bottom();
?>
