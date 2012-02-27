<?php 
include_once(dirname(__FILE__).'/../includes/config.php');
$export="js";
header("Content-Type: text/html;charset=UTF-8");
?>
<body>
	<!--bContent-->
	<div id="bContent">
		<h1>
			<i18n key="abo0"> <en>About</en> <de>Info über</de> <fr>A propos</fr>
			<es>Acerca de</es></i18n>
			&nbsp;emphasize.de
		</h1>
		<i18n key="abo1"> <en>Version</en> <de>Version</de> <fr>Version</fr> <es>Versión</es></i18n>
		:
		<?php echo($version); ?> &copy; 2011-2012 Martin Hartnagel
		<br /> <br />
		<i18n key="abo2"> <en>Author</en> <de>Autor</de> <fr>Auteur</fr> <es>Autor</es></i18n>
		: <a href="http://martin.emphasize.de" target="_blank">Martin
			Hartnagel</a><br /> <br />

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
			<li>Frank P. (concept), Andreas Schönefeldt (concept, tests), Tini
				Keck (voice), tryto2006 (tests), <a href="http://www.tonstrom.de/"
				target="_blank">Tobias Luther</a> (tests)
			</li>
		</ul>
	</div>
	<!--/bContent-->
	<?php bottom(); ?>
