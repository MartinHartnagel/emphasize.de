<?php
include_once(dirname(__FILE__)."/../includes/config.php");
$export="zip";

function recurseFiles($path) {
	global $files;
	$dir_handle = @opendir($path) or die("Unable to open path ".$path);
	while ($file = readdir($dir_handle))
	{
		if (is_file($path.'/'.$file) && $file!="." && $file!="..") {
			$files[] = $path . '/' . $file;
		} else if (is_dir($path.'/'.$file) && $file!="." && $file!="..") {
			recurseFiles($path.'/'.$file);
		}
	}
	//closing the directory
	closedir($dir_handle);
}

function getFiltered($c) {
	$c=preg_replace("/<!-- nizip -->.*<!-- \/nizip -->/msU", "", $c);
	$c=preg_replace("/\/\/ nizip.*\/\/ \/nizip/msU", "", $c);
	$c=preg_replace("/-- nizip.*-- \/nizip/msU", "", $c);
  $c=preg_replace("/<!-- izip/msU", "", $c);
  $c=preg_replace("/\/izip -->/msU", "", $c);
	return $c;
}

function readFiltered($f) {
	global $lc;
	$c=file_get_contents($f);

	return getFiltered($c);
}

function replaceTagContent($tag, $content, $buffer) {
	return preg_replace("/<" . $tag . ">.*?<\/" . $tag . ">/msU", "<" . $tag . ">".$content."</" . $tag . ">", $buffer);
}

chdir(dirname(__FILE__)."/..");
$file="emphasize-".VERSION.".zip";

if (!is_file($file)) {
	// create it
	$zip = new ZipArchive();

	if ($zip->open($file, ZIPARCHIVE::CREATE)!==TRUE) {
		exit("cannot open <$file>\n");
	}

	$files = array();
	recurseFiles(".");
	foreach($files as $f) {
		// files with special treatments:
		if ($f=="./includes/configuration.php") {
			$zip->addFromString("emphasize-".VERSION.substr($f, 1), readFiltered($f));
		} else if ($f=="./includes/news.php" || (startsWith($f, "./") && strpos(substr($f, 2), "/")===FALSE && $f!="./favicon.ico" && $f!="./style.css" && $f!="./index.php")) {
			// skip
		} else if (startsWith($f, "./test/")
				|| (startsWith($f, "./i/") && $f!="./i/.readme.txt")
				|| startsWith($f, "./cache/")
		    || $f=="./install/.htaccess"
				|| $f=="./util/download.php"
				|| $f=="./util/load.php"
				|| $f=="./util/clear.php"
				|| $f=="./util/tbodies.php"
				|| $f=="./util/i18n.php"
				|| $f=="./util/i18nExtract.php"
				|| $f=="./util/delegate.php"
				|| $f=="./util/worldmap.php"
				|| $f=="./graphics/facebook.png"
				|| $f=="./graphics/twitter.png") {
			// skip
		} else if ($f=="./".$file && $f!="./index.php") {
			// skip
		} else if (startsWith($f, "./avatars/") && !($f=="./avatars/default.png"
				|| $f=="./avatars/_clock.png"
				|| $f=="./avatars/_cube.png"
				|| $f=="./avatars/_dice.png"
				|| $f=="./avatars/_viking.png")) {
			// skip
		} else {
			$zip->addFromString("emphasize-".VERSION.substr($f, 1), readFiltered($f));
		}
	}
	// additionals
	if (file_exists(dirname(__FILE__)."/../../pad/emphasize_roadmap.php")) {
	  $zip->addFromString("roadmap.php", readFiltered(dirname(__FILE__)."/../../pad/emphasize_roadmap.php"));
	}
	$zip->addFromString("emphasize-".VERSION."/cache/dummy.txt", "# dummy file for cache to be unzipped");
	$zip->close();

	// write pad.xml
	if (file_exists(dirname(__FILE__)."/../../pad/emphasize.pad.xml")) {
	  $pad=file_get_contents(dirname(__FILE__)."/../../pad/emphasize.pad.xml");
	  $pad=replaceTagContent("Program_Version", VERSION, $pad);
	  $pad=replaceTagContent("Program_Release_Month", date("m"), $pad);
	  $pad=replaceTagContent("Program_Release_Day", date("d"), $pad);
	  $pad=replaceTagContent("Program_Release_Year", date("Y"), $pad);
	  $pad=replaceTagContent("File_Size_Bytes", filesize($file), $pad);
	  $pad=replaceTagContent("File_Size_K", floor(filesize($file)/1024), $pad);
	  $pad=replaceTagContent("File_Size_MB", floor(filesize($file)*100/1024/1024)/100, $pad);
	  file_put_contents(dirname(__FILE__)."/../pad.xml", $pad);
	}
}


// deliver it
header("Content-type: application/zip");
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=".basename($file).";");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".filesize($file));

@readfile($file);
?>
