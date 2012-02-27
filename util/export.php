<?php 
include_once(dirname(__FILE__).'/../includes/config.php');
$export='js';
if (!empty($_POST)) {
	if (isset($_POST["do"]) && $_POST["do"] != null) {
		if ($_POST["do"] == "export") {
			// deliver it
			$tbody="<emphasize>".stripslashes($_POST["tbody"])."</emphasize>";
			$lock=md5("mph".$tbody);
			$s="emphasize-$version export from $domain, untouched-lock:$lock\n\n".$tbody;
			header("Content-type: application/emphasize");
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=\"".$_POST["key"].".emphasize\";");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".strlen($s));
			echo($s);
			exit();
		}
	}
}
?>
<form id="exporterForm" method="POST" action="export.php">
	<input type="hidden" id="exporterDo" name="do" value="export" /> <input
		type="hidden" id="exporterKey" name="key" value="" /> <input
		type="hidden" id="exporterToken" name="token" value="" />
	<textarea id="exporterTbody" name="tbody"></textarea>
	<input type="submit" id="exporterSubmit" name="submit" value="Submit" />
</form>
</body>
</html>
