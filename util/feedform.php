<?php include_once(dirname(__FILE__).'/../includes/config.php'); ?>
<?php 
$export="js";
header("Content-Type: text/html;charset=UTF-8");
?>
<form name="feedback" action="" method="post"
	onsubmit="return submitFeedback();">
	<i18n key="fdf0"> <en>give feedback</en> <de>Feedback geben</de> <fr>Donner
	de la rétroaction</fr> <es>Dar información</es></i18n>
	: <select id="feedbackType" class="help" name="type"
		onchange="checkFeedbackType()"><option selected="selected"
			value="none">
			<i18n key="fdf1"> <en>choose the type ...</en> <de>Typ wählen ...</de>
			<fr>choisir le type ...</fr> <es>elija el tipo ...</es></i18n>
		</option>
		<option value="typo">
			<i18n key="fdf2"> <en>Spelling error</en> <de>Rechtschreibfehler</de>
			<fr>faute d'orthographe</fr> <es>Ortografía de error</es></i18n>
		</option>
		<option value="malfunction">
			<i18n key="fdf3"> <en>Malfunction</en> <de>Fehlfunktion</de> <fr>Dysfonctionnement</fr>
			<es>Mal funcionamiento</es></i18n>
		</option>
		<option value="vulnerabilities">
			<i18n key="fdf8"> <en>Vulnerability</en> <de>Sicherheitslücke</de> <fr>Vulnérabilité</fr>
			<es>Vulnerabilidad</es></i18n>
		</option>
		<option value="feature">
			<i18n key="fdf4"> <en>Improvement</en> <de>Verbesserung</de> <fr>Amélioration</fr>
			<es>Mejora</es></i18n>
		</option>
	</select>
	<div id="help_feedbackType" class="docu"
		style="width: 180px; height: 44px;">
		<i18n key="fdf5"> <en>Choose type of feedback (obligatory to "Send").</en>
		<de>Art des Feedbacks wählen (obligatorisch um "Senden" zu können).</de>
		<fr>Choisissez le type de feedback (obligatoire pour "Envoyer").</fr>
		<es>Elige el tipo de comentarios (obligatorio por "Enviar").</es></i18n>
	</div>
	<br />
	<textarea id="feedMessage" name="message"
		style="width: 100%; height: 110px;">
</textarea>
	<br /> <input id="feedbackLang" type="hidden" name="lang"
		value="<lang/> " /> <input id="feedbackSubmit" name="submit"
		value="<i18n   key='fdf6'>
	<en>Send</en>
	<de>Senden</de>
	<fr>Envoyer</fr>
	<es>Enviar</es>
	</i18n>
	" type="submit" disabled="true" /> <input type="reset" value="<i18n 
		
		key='fdf7'>
	<en>Clear fields</en>
	<de>Felder leeren</de>
	<fr>Effacer les champs</fr>
	<es>Borrar campos</es>
	</i18n>
	" />
</form>
<?php bottom(); ?>
