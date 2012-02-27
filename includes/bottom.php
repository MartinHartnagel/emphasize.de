<div id="id_bottom">
	<span style="float: left;"><img id="pause" src="graphics/pause.png"
		align="bottom" style="cursor: pointer;" onclick="alert('pause')"
		title="<i18n   key='bot0'> <en>show help</en> <de>Hilfe einblenden</de>
		<fr>afficher l'aide</fr> <es>mostrar Ayuda</es> </i18n>" alt="<i18n
			key='bot1'> <en>hide help</en> <de>Hilfe ausblenden</de> <fr>Cacher
		l'aide</fr> <es>Esconder la ayuda</es></i18n>" border="0" width="48"
		height="47" /><img id="showHelp" src="graphics/help.png"
		align="bottom" style="cursor: help;" onclick="toggleShowHelp()"
		title="<i18n   key='bot0'> <en>show help</en> <de>Hilfe einblenden</de>
		<fr>afficher l'aide</fr> <es>mostrar Ayuda</es> </i18n>" alt="<i18n
			key='bot1'> <en>hide help</en> <de>Hilfe ausblenden</de> <fr>Cacher
		l'aide</fr> <es>Esconder la ayuda</es></i18n>" border="0" width="48"
		height="47" /> &nbsp;
		<div class="hc">
			<span id="language" class="help"><select name="langSel"
				id="langSelect"
				onchange="switchLang($('select#langSelect option:selected').val())">
					<?php 
					$lang=detectLang();
					foreach($al as $l) {
						if ($lang == $l) {
							echo('<option selected="selected" value="'.$l.'">'.$lc[$l].'</option>'."\n");
						} else {
							echo('<option value="'.$l.'">'.$lc[$l].'</option>'."\n");
						}
					}
					?>
			</select> </span>
	
	</span>
	<div id="help_language" class="docu"
		style="width: 140px; height: 16px;">
		<i18n key="bot3"> <en>Select the language.</en> <de>Sprache wählen.</de>
		<fr>Sélectionnez la langue.</fr> <es>Seleccione el idioma.</es></i18n>
	</div>
</div>
</span>
<div id="doingHolder">
<div id="doing" title="<i18n   key='bot4'>
<en>in progress</en>
<de>aktualisiere</de>
<fr>en cours</fr>
<es>en curso</es>
</i18n>
" style="display:none;"></div></div>
<span id="status" class="status"></span>
<span style="float: right;"><a id="about"
	href="<?php echo($domain."/util/about.php"); ?>"
	onclick="return createAbout();" title="<i18n     key='bot7'><en>Simple
		Time Registration</en> <de>Einfache Arbeitszeiterfassung</de> <fr>Gestion
		Du Temps Tout bSimplement</fr> <es>Gestión Del Tiempo Hizo Fáci</es> </i18n>">emphasize.de</a>
	<div class="hc">
		<a href="<?php echo($domain."/util/feedform.php"); ?>"
			title="<i18n 
			   ref='fdf0'></i18n>" onclick="return createFeedback();"><img
			id="feedback" class="help"
			src="<?php echo($domain."/graphics/feedback.png"); ?>" align="bottom" />
		</a>
		<div id="help_feedback" class="docu"
			style="width: 200px; height: 44px;">
			<i18n key="bot6"> <en>Report a spelling error, a malfunction or
			suggest a feature.</en> <de>Einen Rechtschreibfehler, eine
			Fehlfunktion oder einen Verbesserungsvorschlag melden.</de> <fr>Rapport
			une faute d'orthographe, un dysfonctionnement ou faire un suggestion.</fr>
			<es>Informe de un error de ortografía, informar de algún problema o
			hacer una sugerencia.</es></i18n>
		</div>
	</div>&nbsp;</span>
</div>
<!-- nizip -->
<?php if ($domain=="http://emphasize.de") { ?>
<!-- Piwik -->
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://pi.emphasize.de/" : "http://pi.emphasize.de/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 3);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script>
<noscript>
	<p>
		<img src="http://pi.emphasize.de/piwik.php?idsite=3" style="border: 0"
			alt="" />
	</p>
</noscript>
<!-- End Piwik Tracking Code -->
<?php } ?>
<!-- /nizip -->
</body>
</html>
