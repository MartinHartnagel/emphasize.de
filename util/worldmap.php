<?php 
$url='http://pi.emphasize.de/plugins/UserCountryMap/PiwikMap.swf?piwik=1.5';
if (!$url_info = parse_url($url)) {
	die("not parseable");
}
switch ($url_info['scheme']) {
	case 'https':
		$scheme = 'ssl://';
		$port = 443;
		break;
	case 'http':
	default:
		$scheme = '';
		$port = 80;
}

$fid = @fsockopen($scheme . $url_info['host'], $port, $errno, $errstr, 30);

if ($fid) {
	fputs($fid, 'GET ' . (isset($url_info['path'])? $url_info['path']: '/') . (isset($url_info['query'])? '?' . $url_info['query']: '') . " HTTP/1.0\r\n" .
			"Connection: keep-alive\r\n" .
			"Accept-Encoding:	gzip,deflate\r\n" .
			'Host: ' . $url_info['host'] . "\r\n\r\n");
	while (!feof($fid)) {
		$head=fscanf($fid, "%[^\n]");
		header($head[0]);
		if (strlen(trim($head[0])) == 0) break;
	}
	fpassthru($fid);
	fclose($fid);
}
?>
