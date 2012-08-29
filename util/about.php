<?php
include_once (dirname(__FILE__) .
'/../includes/config.php');
$export = "js";
header("Content-Type: text/html;charset=UTF-8");
?>
<title><i18n key="abo0"> <en>About</en> <de>Info über</de> <fr>A propos</fr>
			<es>Acerca de</es></i18n>
			&nbsp;emphasize.de</title>
</head>
<body>
	<!--bContent-->
	<div id="bContent">
	    <i18n ref="demo0" /><br/><br/>
		<i18n key="abo1"> <en>Version</en> <de>Version</de> <fr>Version</fr> <es>Versión</es></i18n>
		:
		<?php echo(VERSION); ?> &copy; 2011-2012 Martin Hartnagel
		<br /> <br />
		<i18n key="abo2"> <en>Author</en> <de>Autor</de> <fr>Auteur</fr> <es>Autor</es></i18n>
		: <a href="http://martin.emphasize.de" target="_blank">Martin
			Hartnagel</a>
			&nbsp;<a href="https://plus.google.com/100885612700684695500?rel=author" target="_blank">+</a>
			<br /> <br />

		<h2>
			<i18n key="abo3"> <en>Thanks to</en> <de>Dank an</de> <fr>Merci à</fr>
			<es>Gracias a</es></i18n>
		</h2>
		<ul>
			<li><a href="http://jquery.com/" target="_blank">jQuery: The Write
					Less, Do More, JavaScript Library</a></li>
			<li><a href="http://jqueryui.com/"
				target="_blank">JQuery UI</a></li>
			<li><a href="http://benalman.com/projects/jquery-hashchange-plugin/"
				target="_blank">Ben Alman: jQuery hashchange event</a></li>
			<li><a href="http://php.net" target="_blank">PHP: Hypertext
					Preprocessor</a></li>
			<li><a href="http://www.phpcaptcha.org/" target="_blank">Securimage
					CAPTCHA - Free PHP Captcha Script</a></li>
			<li><a href="http://www.phpletter.com/Demo/AjaxFileUpload-Demo"
				target="_blank">Jquery Ajax File Uploader</a></li>
			<li><a href="http://selfhtml.org/" target="_blank">SELFHTML</a></li>
			<li><a href="https://github.com/rgrove/jsmin-php" target="_blank">Ryan
					Grove</a></li>
			<li><a href="http://piwik.org" target="_blank">Piwik - Web analytics</a>
			</li>
			<li><a href="http://code.google.com/p/swfobject" target="_blank">SWFObject - embed Piwik-Flash content on welcome site</a>
			</li>
			<li>Frank P. (concept)
			</li>
			<li><a href="http://www.lotus-moon.de/" target="_blank" title="Lotus-Moon-Development - Freiberuflicher Software Entwickler">Andreas Schönefeldt</a> (concept, testing)
			</li>
			<li>Tini Keck (voice)
			</li>
			<li>tryto2006 (testing)
			</li>
			<li><a href="http://www.tonstrom.de/" target="_blank">Tobias Luther</a> (testing)
			</li>
		</ul>
	</div>
	<!--/bContent-->
	<?php bottom(); ?>
