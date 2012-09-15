<script type="text/javascript">
function init() {
  initEmphasize();
  initTimeline();
  Progress.showStatus(false, "<i18n key='tab9'><en>Logged in as</en><de>Eingeloggt als</de><fr>Connexion avec</fr><es>Ingresar con</es></i18n>&nbsp;<?php echo(User::getInstance()->getName()); ?>");
}
</script>
<title><?php echo(User::getInstance()->getName()); ?> - Emphasize</title>
<link rel="alternate" type="application/atom+xml" title="<?php echo(User::getInstance()->getName()); ?> - Emphasize (Atom Feed)" href="<domain/>/util/atom.php?a=<?php echo(User::getInstance()->getAid()); ?>">
</head>
<body onload="init()">
  <input id="user" type="hidden" name="user" value="<?php echo(User::getInstance()->getName()); ?>" />
	<input id="email" type="hidden" name="email" value="<?php echo(User::getInstance()->getEmail()); ?>" />
	<input id="token" type="hidden" name="token" value="<?php echo(User::getInstance()->getToken()); ?>" />
	<div id="dashboard_top"
		style="margin: 10px 20px 10px 20px; text-align: center;">
		<form name="report"
			action="<?php echo($domain.'/util/report.php'); ?>" method="GET"
			onsubmit="return updateReportTime();" target="_blank">
			<span style="text-align: left; white-space: nowrap;">
				<div class="hc">
					<a href="<?php echo($domain.'/util/settings.php');?>"
						onclick="return showAbove('settings', $('#config').get(0), '<?php echo($domain."/util/settings.php?lang=".$lang); ?>', '#fileToUpload', 460, null);"
						title="<i18n key='tab19'><en>Settings</en> <de>Einstellungen</de>
						<fr>Paramètres</fr> <es>Configuración</es> </i18n>"><img
						id="config" class="help" src="graphics/config.png" align="top"
						border="0" width="42" height="42" />
						<div id="help_config" class="docu"
							style="width: 220px; height: 44px;">
							<i18n key="tab20"> <en> <a
								href="javascript:tubeTutorial('akZ90qEgKEQ')">Change character
								and settings</a> for the user account.</en> <de> <a
								href="javascript:tubeTutorial('R1XmQ9pioJU')">Spielfigur und
								Eigenschaften ändern</a> für das Benutzerkonto.</de> <fr> <a
								href="javascript:tubeTutorial('akZ90qEgKEQ')">Personnage et des
								propriétés pour le changement</a> des comptes d'utilisateurs.</fr>
							<es> <a href="javascript:tubeTutorial('akZ90qEgKEQ')">Cambio
								personaje y las propiedades</a> para el usuario cuentas.</es></i18n>
						</div>

				</div>
				<div class="hc">
					<input id="timeText" class="input_text help" type="text" size="14"
						style="text-align: center;"
						onblur="checkTimeText(null, this.value)"
						onkeyup="checkTimeText(event, this.value)" value="<i18n ref='tab43' />" />
						<img id="closeTimeEditor" onclick="Timeline.setCursor(null);" src="graphics/close.png" title="<i18n key='tab8'><en>close time editing</en><de>Zeiteditierung Beenden</de><fr>finir édition des temps</fr><es>final edición del tiempo</es></i18n>" style="display:none;" />
					<div id="help_timeText" class="docu"
						style="width: 300px; height: 470px;">
						<i18n key="tab46"> <en>Display and input of the current time
						pointer. Possible syntax for quick selection</en> <de>Anzeige und
						Direkteingabe der aktuellen Editierzeit. Mögliche Syntax zur
						schnellen Auswahl</de> <fr>Affichage et entrée directe de
						l'Editierzeit actuelle. Syntaxe possible pour une sélection rapide</fr>
						<es>Visualización y entrada directa de la Editierzeit actual.
						sintaxis posible para una selección rápida</es></i18n>
						:
						<ul>
							<li><code>
									<i18n ref="tab43" />
								</code>:&nbsp;<i18n key="tab48"> <en>select current time</en> <de>aktuelle
								Zeit auswählen</de> <fr>Sélectionnez l'heure actuelle</fr> <es>Seleccione
								la hora actual</es></i18n>.</li>
							<li><code>-108s</code>:&nbsp;<i18n key="tab49"> <en>Select time
								108 seconds ago</en> <de>Zeit vor 108 Sekunden auswählen</de> <fr>Avant
								108 secondes</fr> <es>Seleccione el tiempo, 108 segundos</es></i18n>.</li>
							<li><code>-20m</code>:&nbsp;<i18n key="tab50"> <en>Select time 20
								minutes ago</en> <de>Zeit vor 20 Minuten auswählen</de> <fr>Sélectionnez
								le temps 20 minutes</fr> <es>Seleccionar el tiempo de 20 minutos</es></i18n>.</li>
							<li><code>-3h</code>:&nbsp;<i18n key="tab51"> <en>Select time 3
								hours ago</en> <de>Zeit vor 3 Stunden auswählen</de> <fr>Choisissez
								un moment 3 heures</fr> <es>Elige un tiempo de 3 horas</es></i18n>.</li>
							<li><code>-4d</code>:&nbsp;<i18n key="tab58"> <en>Select time 4
								daysago</en> <de>Zeit vor 4 Tagen auswählen</de> <fr>Choisissez
								un moment 4 jours</fr> <es>Elige un tiempo de 4 dias</es></i18n>.</li>
							<li><code>9:40</code>:&nbsp;<i18n key="tab52"> <en>Choose this
								morning at 9 o'clock and 40 minutes</en> <de>Heute früh um 9 Uhr
								40 auswählen</de> <fr>Ce matin à 9 heures sélectionnez 40
								minutes</fr> <es>Esta mañana a las 9 del reloj seleccione 40</es></i18n>.</li>
							<li><code>15:23:30</code>:&nbsp;<i18n key="tab53"> <en>Choose
								this afternoon at 3 o'clock, 23 minutes and 30 seconds</en> <de>Heute
								nachmittag um 3 Uhr 23 und 30 Sekunden auswählen</de> <fr>Choisissez
								cet après-midi à 3 heures 23 et 30 secondes</fr> <es>Elija esta
								tarde a las tres del reloj 23 y 30 segundos</es></i18n>.</li>
							<li><code>2010-09-29 19:01</code>:&nbsp;<i18n key="tab54"> <en>Select
								29th of September in 2010 at one minute past 7 p.m</en> <de>29.
								September im Jahr 2010 um 19 Uhr 01 auswählen</de> <fr>Sélectionnez
								29 Septembre en l'an 2010 de 19 heures 01</fr> <es>29 Seleccione
								de septiembre en el año 2010 por 19 horas 01</es></i18n>.</li>
							<li><code>2010-10-03 10:59:12</code>:&nbsp;<i18n key="tab55"> <en>Select
								third of October in 2010 at 10 o'clock, 59 minutes and 12
								seconds</en> <de>3. Oktober im Jahr 2010 um 10 Uhr 59 und 12
								Sekunden auswählen</de> <fr>Sélectionnez 3me Octobre de l'année
								2010 par 10 heures 59 et 12 secondes</fr> <es>Seleccione 3 el
								mes de octubre en el año 2010 en un 10 reloj de 59 y 12 segundos</es></i18n>.</li>
						</ul>
					</div>
				</div>
				<div class="hc">
					<input id="info" value="<i18n key='tab23'>
					<en>Add info</en>
					<de>Info hinzufügen</de>
					<fr>Ajouter Info</fr>
					<es>Añadir Info</es>
					</i18n>
					" style="color:#777777;" class="input_text help infoInput"
					maxlength="120" />
					<div id="help_info" class="docu"
						style="width: 280px; height: 134px;">
						&nbsp;<img src="graphics/info.png" valign="middle" />
						<i18n key="tab24"> <en>Add, modify or remove an information on the
						timeline. Up to 120 characters per info are available, use the
						return key to submit an info. A new info can be entered only once
						every ± 2 minutes, otherwise the existing info will be modified.
						By entering a blank info an existing info is removed.</en> <de>Infos
						zur Zeitleiste hinzufügen, ändern und wieder entfernen. Es sind
						bis zu 120 Zeichen pro Info möglich, durch die Return-Taste wird
						eine Info eingetragen. Eine neue Info kann nur alle ± 2 Minuten
						eingetragen werden, ansonsten wird die existierende Info geändert.
						Durch Eintragen einer leeren Info kann eine existierende Info
						wieder entfernt werden.</de> <fr>Informations sur la timeline pour
						ajouter, modifier et supprimer. Il ya jusqu'à 120 caractères par
						les informations disponibles, la touche de retour est une base de
						données. Une nouvelle dénonciation peut être entré une seule fois
						tous les ± 2 minutes, sinon les informations existant est modifié.
						En entrant un vide Info est une information existante sera
						supprimée.</fr> <es>La información sobre la línea de tiempo para
						agregar, modificar y eliminar. Hay hasta 120 caracteres por la
						información disponible, la tecla de retorno es una base de datos
						de información. Una nueva información se puede introducir sólo una
						vez cada ± 2 minutos, de lo contrario la información existente
						modificada. Al entrar en un espacio en blanco que la información
						es una información existente se quita.</es></i18n>
					</div>
				</div>
				<div class="hc">
					<select id="reportName" class="input_text help" name="type">
						<?php
						foreach($reports as $report=>$report_name) {
						 echo("<option value=\"$report\">$report_name</option>");
						}
						?>
					</select>
					<div id="help_reportName" class="docu"
						style="width: 180px; height: 28px;">
						<i18n key="tab25"> <en>Choose time tracking report.</en> <de>Zeiterfassungs-Bericht
						auswählen.</de> <fr>Choisissez le rapport d'assistance.</fr> <es>Elija
						el informe de asistencia.</es></i18n>
					</div>
				</div> <input name="token" type="hidden"
				value="<?php echo(User::getInstance()->getToken()); ?>"> <input
				id="reportTime" name="time" type="hidden" value="">
				<div class="hc">
					<input id="reportFrom" class="input_text help" name="from"
						value="<?php echo(gmdate('Y-m-01',time()-3600*24)); ?>" size="10" />
					<div id="help_reportFrom" class="docu"
						style="width: 180px; height: 44px;">
						<i18n key="tab26"> <en>Select a start date of the period for the
						acquisition report.</en> <de>Anfangstag des Zeitraums für den
						Zeiterfassungs-Bericht auswählen.</de> <fr>Sélectionnez le jour de
						début de la période de la déclaration d'acquisition.</fr> <es>seleccionar
						la dia de inicio del plazo para el informe de adquisición.</es></i18n>
					</div>
				</div> <label for="reportTo"><i18n ref='rpb0'></i18n> </label>
				<div class="hc">
					<input id="reportTo" class="input_text help" name="to"
						value="<?php echo(gmdate('Y-m-d',time())); ?>" size="10" />
					<div id="help_reportTo" class="docu"
						style="width: 180px; height: 44px;">
						<i18n key="tab28"> <en>Select an end date of the period for the
						acquisition report.</en> <de>Endtag des Zeitraums für den
						Zeiterfassungs-Bericht auswählen.</de> <fr>Sélectionnez le jour de
						fermeture de la période de la déclaration d'acquisition.</fr> <es>seleccionar
						la dia de cierre del plazo para el informe de adquisición.</es></i18n>
					</div>
				</div>
				<div class="hc">
					<input id="reportSubmit" class="button help" type="submit"
						name="Submit" value="<i18n key='tab29'>
					<en>Show</en>
					<de>Zeigen</de>
					<fr>Voir</fr>
					<es>Mofstrar</es>
					</i18n>
					">
					<div id="help_reportSubmit" class="docu"
						style="width: 180px; height: 18px;">
						<i18n key="tab30"> <en>Open acquisition report.</en> <de>Zeiterfassungs-Bericht
						öffnen.</de> <fr>Ouvrir la déclaration d'acquisition.</fr> <es>Abierto
						el informe de adquisición.</es></i18n>
					</div>
				</div> <span> <img id="showHelp" src="graphics/help.png"
					style="vertical-align: bottom; cursor: help;"
					onclick="toggleShowHelp()" title="<i18n ref='bot0' />"
					border="0" width="24" height="24" />
			</span><span> <a id="feedLink"
					href="<?php echo($domain."/util/feedform.php"); ?>"
					title="<i18n ref='fdf0'></i18n>" onclick="return showAbove('feedback',
						$('#feedback').get(0), '<?php echo($domain."/util/feedform.php?lang=".$lang); ?>',
						'#feedMessage', -300, -320);"><img id="feedback" class="help"
						src="<?php echo($domain."/graphics/feedback.png"); ?>"
						style="vertical-align: bottom;" /> </a>
					<div id="help_feedback" class="docu"
						style="width: 200px; height: 44px;">
						<i18n ref="bot6" />
					</div>
			</span>
				<div class="hc">
					<img id="doLogout" style="cursor: pointer" class="help"
						src="graphics/exit.png" align="top" onclick="logout()"
						title="<i18n key='tab21'>
					<en>Logout</en>
					<de>Abmelden</de>
					<fr>Déconnexion</fr>
					<es>Desconectarse</es>
					</i18n>
					" border="0" width="24" height="24" />
					<div id="help_doLogout" class="docu"
						style="width: 180px; height: 18px;">
						<i18n key="tab22"> <en>End current session.</en> <de>Zeiterfassung
						schließen.</de> <fr>Terminer la session courante.</fr> <es>Final
						de la sesión actual.</es></i18n>
					</div>
				</div>

			</span>
		</form>
	</div>
	<div id="doingHolder">
		<div id="doing" title="<i18n ref='bot4' />" style="display:none;">
		</div>
		<div id="status" class="status"></div>
	</div>
	<div id="timeline" class="timeline">
		<noscript>
			<center style="font-size: 20px; color: red; text-decoration: blink;">
				<i18n ref='tab56' />!
			</center>
		</noscript>
	</div>
	<div id="editor" style="border-color: white;" class="editorClass">
		<div id="blind" style="display: none;" title="<i18n 	  key='tab47'>
			<en>no longer existing field</en>
			<de>nicht mehr vorhandenes Feld</de>
			<fr>pas de champ plus présente</fr>
			<es>ningún campo ya existentes</es>
			</i18n>
			" class="border">
		</div>
		<div id="tabs" style="height: 100%;">
			<ul>
				<?php $templates=Template::getTemplates(User::getInstance()->getId());
			 foreach($templates as $key=>$template) {
			  echo("<li><a href=\"util/ajax.php?do=setTemplate&token=".User::getInstance()->getToken()."&template=".$template->getId()."\"");
			  if ($template->getId() == User::getInstance()->getActiveTemplate()) {
			   echo(" title=\"<i18n key='dbd6'><en>active</en><de>Aktiv</de><fr>actif</fr><es>activo</es></i18n>\"");
			  }
			  echo(">".h($key)."</a><span class='tabicons'>");
			  echo('<span class="ui-icon ui-icon-pencil"><i18n key="dbd1"><en>rename</en><de>Umbenennen</de><fr>renommer</fr><es>cambiar el nombre</es></i18n></span>');
			  echo('<span class="ui-icon ui-icon-folder-open"><i18n key="dbd2"><en>import</en><de>Importieren</de><fr>importer</fr><es>importar</es></i18n></span>');
			  echo('<span class="ui-icon ui-icon-disk"><i18n key="dbd3"><en>export</en><de>Exportieren</de><fr>exporter</fr><es>exportar</es></i18n></span>');
			  echo('<span class="ui-icon ui-icon-trash"><i18n key="dbd4"><en>delete</en><de>Löschen</de><fr>supprimer</fr><es>borrar</es></i18n></span>');
			  echo("</span></li>\n");
			 }
			 ?>
				<li style="width: 16px; height: 36px;"><span
					class="ui-icon ui-icon-document"><i18n key="dbd5"> <en>new</en> <de>Neu</de>
						<fr>nouveau</fr> <es>nuevo</es></i18n> </span><a
					href="util/ajax.php"></a></li>
			</ul>
			<table id="table" class="dashboard help" width="100%" height="100%"
				border="0" cellspacing="2" cellpadding="0">
			</table>
			<div id="help_table" class="docu"
				style="width: 320px; height: 314px;">
				<i18n key='tab12'> <en> <a
					href="javascript:tubeTutorial('gP9O8lLCleU')">Change
					distribution, description, reportables and color of the fields</a>.</en>
				<de> <a href="javascript:tubeTutorial('hk05wFpoRyM')">Aufteilung,
					Bezeichnung, Berichtbarkeit und Farbe der Felder ändern</a>.</de>
				<fr> <a href="javascript:tubeTutorial('gP9O8lLCleU')">Changement
					de distribution, description, rapportables et la couleur des
					champs</a>.</fr> <es> <a
					href="javascript:tubeTutorial('gP9O8lLCleU')">Distribución,
					descripción, notificables y cambian de color de los campos</a>.</es></i18n>
				<ul>
					<img src="graphics/horizontalSplit.png" />
					<i18n ref="tab3"></i18n>
					.
					<br />
					<img src="graphics/verticalSplit.png" />
					<i18n ref="tab4"></i18n>
					<img src="graphics/horizontalGlue.png" />
					<i18n ref="tab5"></i18n>
					.
					<br />
					<img src="graphics/verticalGlue.png" />
					<i18n ref="tab6"></i18n>
					.
					<br />
					<img src="graphics/colors.png" />
					<i18n key="tab17"> <en>pick a color for the field</en> <de>eine
					Farbe für das Feld wählen</de> <fr>choisir une couleur pour le
					domaine</fr> <es>elegir un color para el campo</es></i18n>
					.
					<br />
				</ul>
				<i18n key="tab18"> <en>Editing is possible by moving the mouse
				over the fields.</en> <de>Zum Editieren die Maus über die Felder bewegen.</de>
				<fr>Déplacer la souris sur les champs pour l'édition.</fr>
				<es>Mover el ratón sobre los campos de edición.</es></i18n>
			</div>
		</div>
	</div>
	</div>
	<?php echo(User::getInstance()->getUserAvatar());?>