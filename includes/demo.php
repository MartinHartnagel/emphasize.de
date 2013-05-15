<?php
$lang=detectLang();
$cacheFile="demo_".$lang.".html";
// nizip
if (!(isset($bid) && $bid > 0)) {
 if ($lang=='de') {
  $blang=$lang;
 } else {
  $blang="en";
 }
 include_once(INC.'/news.php');
 $bid=$latestId;
 $title=title($blang, $bid);
}
$cacheFile="demo_".$lang."_".$bid.".html";
// /nizip
checkCache($cacheFile);
// nizip

if (isset($title)) {
  echo('<title>'.$title.'</title>'."\n");
} else {
// /nizip
  echo('<title>{i18n key="demo0"} {en}The Simple Time Registration{/en} {de}Arbeitszeiterfassung
	Ganz Einfach{/de} {fr}Gestion Du Temps Tout Simplement{/fr} {es}Gestión
	Del Tiempo Hizo Fáci{/es}{/i18n} - {app_name/}</title>'."\n");
// nizip
}
// /nizip
?>
<script type="text/javascript">
<!--
var step=0;
var steps=10;

function setName() {
  setCookieParam("name", document.login.name.value);
}

function setCookieParam(param, value) {
  var n=param+"="+value+";";
  document.cookie = n;
}

function getCookieParam(param) {
  if (document.cookie) {
    var chocolates=document.cookie.split(";");
    for(var i=0; i<chocolates.length; i++) {
      if (chocolates[i].match("^ *" + param + "=")) {
        return chocolates[i].substr(chocolates[i].indexOf('=')+1);
      }
    }
  }
  return "";
}

function initLogin() {
  $('.docu').hide();
  if (typeof(window["confirmed_name"]) != "undefined") {
    document.login.name.value = confirmed_name;
    setName();
  } else if (document.login.name.value == "") {
    document.login.name.value = getCookieParam("name");
  }
  if (document.login.name.value.length > 0) {
    document.login.password.focus();
  }
  init();
}

var currentContent="";

function init() {
  t=new Dashboard(document.getElementById("table"));
  t.setDemo(true);
  initTimeline();
  window.setTimeout("animate()", 2000);

  $(window).hashchange(function(){
    var hash = location.hash;
    if (hash.length > 1) {
      var url=hash.substr(1)+".php";
      if (currentContent != url) {
       $.post(domain+url, {"ajax":"true", "grep":"bContent"}, function(data) {
         $("#bContent").replaceWith(data);
         currentContent=url;
       });
      }
    } else if (currentContent != "") {
      $.post(domain, {"ajax":"true", "grep":"bContent", "lang":lang}, function(data) {
        $("#bContent").replaceWith(data);
        currentContent="";
      });
    }
  });

  $(window).hashchange();
}

function animate() {
  if (step%steps==0 && t.isValid()) Avatar.jumpTo(t.getTd(0,0));
  if (step%steps==1 && t.isValid()) Avatar.jumpTo(t.getTd(1,0));
  if (step%steps==2 && t.isValid()) { t.showEdits(t.getTd(0,0));}
  if (step%steps==3 && t.isValid()) { t.setDemo(false); t.horizontalSplit(t.getTd(0,0)); t.getTd(0,1).innerHTML='{i18n ref="con10" />'; t.setDemo(true); $(t.getTd(0,1)).css("background-color", "#ecfe32");}
  if (step%steps==4 && t.isValid()) Avatar.jumpTo(t.getTd(0,1));
  if (step%steps==5 && t.isValid()) Avatar.jumpTo(t.getTd(1,1));
  if (step%steps==6 && t.isValid()) t.showEdits(t.getTd(0,0));
  if (step%steps==7 && t.isValid()) { t.setDemo(false); t.horizontalMerge(t.getTd(0,0)); t.setDemo(true); }
  if (step%steps==8 && t.isValid()) Avatar.jumpTo(t.getTd(1,1));
  if (step%steps==9 && t.isValid()) Avatar.jumpTo(t.getTd(1,0));

  step++;
  window.setTimeout("animate()", 2000);
}

$('a.blog').on('click',function(){
  var url = $(this).attr("href");
  $.post(domain+url, {"ajax":"true", "grep":"bContent"}, function(data) {
    $("#bContent").replaceWith(data);
    window.location.hash = '#' + url.replace('.php','');
  });
  return false;
});

function track(url, title) {
  // nizip
  try {
    piwikTracker.setCustomUrl(url);
    piwikTracker.setDocumentTitle(title);
    piwikTracker.trackPageView();
    piwikTracker.enableLinkTracking();
  }catch(err) {
    //Piwik funktioniert nicht
  }
  // /nizip
}
//-->
</script>
<!-- nizip -->
<script
	type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
<script
	type="text/javascript"
	src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
<script
	type="text/javascript"
	src="<?php echo(DOMAIN.'/js/jquery.ba-hashchange.min.js'); ?>"></script>
<script
	type="text/javascript" src="<?php echo(DOMAIN.'/js/swfobject.js'); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
 var flashvars = false;
 var params = {
   transparent: "true",
   allowFullScreen: "true",
   allowscriptaccess: "always",
          wmode: "transparent"
 };
 var attributes = {
               style: "z-index:1"
 };

 swfobject.embedSWF('http://www.youtube.com/p/{i18n key='tech1'}{en}8D1C1809D9BAB718?hl=en_US{/en}{de}F2A34232616B5BF1?hl=de_DE{/de}{fr}8D1C1809D9BAB718?hl=fr_FR{/fr}{es}8D1C1809D9BAB718?hl=es_ES{/es}{/i18n}&fs=1', "emphasizeTube", "420", "261", "9.0.0", null, flashvars, params, attributes);
});

$(document).ready(function() {
 var flashvars = true;
 var params = {
               menu: "false",
               scale: "noScale",
               allowscriptaccess: "always",
               wmode: "opaque",
               bgcolor: "#FFFFFF",
               allowfullscreen: "true",
                      flashvars: "dataUrl={domain/}/util/delegate.php&hueMin=0&hueMax=40&satMin=0.5&satMax=0.9&lgtMin=0.97&lgtMax=0.44&iconOffset=0&defaultMetric=nb_visits&txtLoading=........loading...&txtLoadingData=loading%20data...&txtToggleFullscreen=Fullscreen&txtExportImage=Export"
 };
 var attributes = {
               style: "z-index:1"
 };

 swfobject.embedSWF(domain+'/util/worldmap.php', "worldmap", "420", "238", "9.0.0", null, flashvars, params, attributes);
});
</script>
<!-- /nizip -->
</head>
<body onload="initLogin()">
	<div id="login_top"
		style="margin: 10px 20px 10px 20px; text-align: center;">
		<form name="login" action="<?php echo(DOMAIN.'/');?>" method="post">
			<span style="text-align: left;white-space:nowrap;"> <input type="hidden" name="do"
				value="login" /> {i18n key="demo14"} {en}login{/en} {de}Login{/de} {fr}login{/fr}
				{es}iniciar sesión{/es}{/i18n} :
				<div class="hc">
					<input id="loginName" class="input_text help" type="text"
						name="name" value="" size="8" onchange="setName()" />
					<div id="help_loginName" class="docu"
						style="width: 140px; height: 44px;">
						{i18n key="demo15"} {en}login name of an already created
						user-account{/en} {de}Login-Name eines bereits angelegten
						Benutzer-Accounts{/de} {fr}Nom de connexion d'un des comptes
						d'utilisateur déjà créé{/fr} {es}Nombre de inicio de una cuenta de
						usuario ya ha creado{/es}{/i18n}
					</div>
				</div> {i18n ref="rtr3"}{/i18n} :
				<div class="hc">
					<input id="loginPassword" class="input_text help" type="password"
						name="password" size="8" value="" />
					<div class="hc">
						<input id="loginSubmit" class="button help" type="submit"
							name="Submit" value="{i18n key='demo79'}
						{en}Login{/en}
						{de}Anmelden{/de}
						{fr}Connectez-vous{/fr}
						{es}Iniciar sesión{/es}
						{/i18n}
						">
						<div id="help_loginSubmit" class="docu"
							style="width: 160px; height: 62px;">
							{i18n key="demo25"} {en}Log on to the specified user account name
							and password for the specified time.{/en} {de}In den angegebenen
							Benutzer-Account mit Name und Passwort für die angegebene Zeit
							einloggen.{/de} {fr}Ouvrez une session sur le nom du compte
							utilisateur spécifié et mot de passe pour l'heure spécifiée.{/fr}
							{es}Inicie sesión en el nombre de cuenta de usuario y la
							contraseña especificados durante el tiempo especificado.{/es}{/i18n}
						</div>
					</div>
					<div id="help_loginPassword" class="docu"
						style="width: 180px; height: 44px;">
						{i18n key="demo17"} {en}Password to login matching the name of the
						already created user account.{/en} {de}Login-Passwort passend zum
						Namen des bereits angelegten Benutzer-Accounts.{/de} {fr}Mot de
						passe qui correspondre au nom des comptes utilisateur déjà créé.{/fr}
						{es}contraseña de inicio de sesión para que coincida con el nombre
						de las cuentas de usuario ya ha creado.{/es}{/i18n}
					</div>
				</div>
			</span>
			<!-- nizip -->
			<span style="text-align:center;padding-left:1%;padding-right:1%;white-space:nowrap;">
				<div class="hc">
					<span id="recommend" class="help">{i18n key="demo69"} {en}Recommend{/en} {de}Empfehle{/de} {fr}Recommande{/fr}
						{es}Recomiendo{/es}{/i18n} <span> <a id="about" href="util/about.php"
					title="{i18n key='bot7'}{en}Simple Time Registration{/en} {de}Einfache
						Arbeitszeiterfassung{/de} {fr}Gestion Du Temps Tout Simplement{/fr}
						{es}Gestión Del Tiempo Hizo Fáci{/es} {/i18n}"
						class="blog">{app_name/}</a></span>:</span>
					<g:plusone size="medium" annotation="inline" width="180"></g:plusone>
					<fb:like href="<?php echo(DOMAIN);?>" layout="button_count"
						show_faces="false" height="30" font=""></fb:like>
					<div id="help_recommend" class="docu"
						style="width: 120px; height: 44px;">
						{i18n key="demo78"} {en}Recommend {app_name/} in social networks.{/en}
						{de}{app_name/} in einem Sozialen Netzwerk weiterempfehlen.{/de} {fr}Mettre
						l'{app_name/} sur les réseaux sociaux.{/fr} {es}Hacer {app_name/} en
						las redes sociales.{/es}{/i18n}
					</div>
				</div> <!-- /nizip -->
			</span> <span style="text-align: right;white-space:nowrap;"> <span id="language"
				class="help"><select name="langSel" id="langSelect"
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
				</select>
					<div id="help_language" class="docu"
						style="width: 140px; height: 16px;">
						{i18n key="bot3"} {en}Select the language.{/en} {de}Sprache
						wählen.{/de} {fr}Sélectionnez la langue.{/fr} {es}Seleccione el
						idioma.{/es}{/i18n}
					</div> </span>
			  <span> <img id="showHelp" src="graphics/help.png"
					style="vertical-align:bottom;cursor: help;" onclick="toggleShowHelp()"
					title="{i18n key='bot0'} {en}show help{/en} {de}Hilfe
					einblenden{/de} {fr}afficher l'aide{/fr} {es}mostrar Ayuda{/es} {/i18n}"
					alt="{i18n key='bot1'} {en}hide help{/en} {de}Hilfe ausblenden{/de}
					{fr}Cacher l'aide{/fr} {es}Esconder la ayuda{/es}{/i18n}"
					border="0" width="24" height="24" />
			</span><span> <a id="feedLink"
					href="<?php echo(DOMAIN."/util/feedform.php"); ?>"
					title="{i18n ref='fdf0'}{/i18n}" onclick="return showAbove('feedback',
						$('#feedback').get(0), '<?php echo(DOMAIN."/util/feedform.php?lang=".$lang); ?>',
						'#feedMessage', -300, -320);"><img id="feedback" class="help"
						src="<?php echo(DOMAIN."/graphics/feedback.png"); ?>" style="vertical-align:bottom;" /> </a>
					<div id="help_feedback" class="docu"
						style="width: 200px; height: 44px;">
						{i18n key="bot6"} {en}Report a spelling error, a malfunction or
						suggest a feature.{/en} {de}Einen Rechtschreibfehler, eine
						Fehlfunktion oder einen Verbesserungsvorschlag melden.{/de} {fr}Rapport
						une faute d'orthographe, un dysfonctionnement ou faire un
						suggestion.{/fr} {es}Informe de un error de ortografía, informar
						de algún problema o hacer una sugerencia.{/es}{/i18n}
					</div>
			</span>
		</form>
	</div>
	<div id="doingHolder">
  	<div id="doing" title="{i18n key='bot4'}
  		{en}in progress{/en}
  		{de}aktualisiere{/de}
  		{fr}en cours{/fr}
  		{es}en curso{/es}
  		{/i18n}
  		" style="display:none;">
  	</div>
	  <div id="status" class="status"></div>
  </div>
	<?php echo(User::getInstance()->getUserAvatar("default"));?>
	<div
		style="position: relative; margin: 10px auto 10px auto; width: 900px;">
		<div style="position: absolute; top: 0px; left: 0px; width: 420px;">
			<div class="hc">
				<input id="register" class="button help" style="float: left;"
					type="button"
					onclick="track('/util/register.php', 'Register');return showAbove('register', $('#register').get(0), '<?php echo(DOMAIN."/util/register.php?lang=".$lang); ?>', '#registerName');"
					value="{i18n key='demo49'}
				{en}register user{/en}
				{de}Benutzer anlegen{/de}
				{fr}créer un utilisateur{/fr}
				{es}crear usuario{/es}
				{/i18n}
				" />
				<div id="help_register" class="docu"
					style="width: 140px; height: 44px;">
					{i18n key="demo12"} {en} <a
						href="javascript:tubeTutorial('A1wF8aVZOfg')">open form to
						register for a new user-account</a>.{/en} {de} <a
						href="javascript:tubeTutorial('jVxjkfQj6UE')">Formular öffnen um
						einen neuen Benutzer-Account anzulegen</a>.{/de} {fr} <a
						href="javascript:tubeTutorial('A1wF8aVZOfg')">ouvrir une
						formulaire pour créer un nouveau compte d'utilisateur</a>.{/fr} {es}
					<a href="javascript:tubeTutorial('A1wF8aVZOfg')">aquí se abrirá un
						formulario para crear una nueva cuenta de usuario</a>.{/es}{/i18n}
				</div>
			</div>
			<!-- nizip -->
			<div class="hc">
				<input id="tryout" class="button help"
					style="float: right; background: #BEE6F2;" type="button"
					onclick="location.href='<?php echo(DOMAIN.'?tryout'.substr('00000000000000'.md5(time()), -14));?>';"
					value="{i18n key='gast3'}
				{en}Test it{/en}
				{de}Ausprobieren{/de}
				{fr}Essayez{/fr}
				{es}Pruébelo{/es}
				{/i18n}
				" />
				<div id="help_tryout" class="docu"
					style="width: 140px; height: 44px;">
					{i18n key="gast4"} {en}Test it for 20 minutes{/en} {de}Ausprobieren
					für 20 Minuten{/de} {fr}Essayez-le pendant 20 minutes{/fr} {es}Pruébelo
					durante 20 minutos{/es}{/i18n}
					!
				</div>
			</div>
			<!-- /nizip -->
			<div id="fields" class="demo"></div>
			<div id="timeline" class="demo_timeline">
				<noscript>
					<center
						style="font-size: 20px; color: red; text-decoration: blink;">
						{i18n key='tab56'} {en}Scripts disabled in browser, enable to use
						{app_name/}{/en} {de}Skripte sind deaktiviert im Browser, bitte
						aktivieren, um {app_name/} zu verwenden{/de} {fr}Scripts désactivé
						dans le navigateur, permettant d'utiliser {app_name/}{/fr} {es}Scripts
						con discapacidad en el navegador, permiten utilizar en {app_name/}{/es}{/i18n}
						!
					</center>
				</noscript>
				<div id="time">
					<div id="tHours">{i18n ref="bot7" /></div>
					<div id="tLine" class="tLine">
						<img style="left: 0px;" src="graphics/void.png" class="te"
							height="10" width="3961"><img
							src="<?php echo(DOMAIN."/util/i.php?bdc406"); ?>"
							title="{i18n ref="con5"}{/i18n}" class="te" height="10" width="133"><img
							src="<?php echo(DOMAIN."/util/i.php?6ba163"); ?>"
							title="{i18n ref="con6"}{/i18n}" class="te" height="10" width="74"><img
							src="<?php echo(DOMAIN."/util/i.php?a16363"); ?>"
							title="{i18n ref='con7'}{/i18n}" class="te" height="10" width="113">
					</div>
					<img id="now" src="graphics/now.png"
						title="{i18n ref='tab43'}{/i18n}" width="19" height="58" class="tNow" /><img
						src="graphics/info.png"
						title="<?php echo(date('H:m')); ?> {i18n key="demo31"}
					{en}Attend activities in the project with time tracking{/en}
					{de}Tätigkeiten im Projekt mit Zeiterfassung nachverfolgen{/de}
					{fr}Suivi des présences des activités du projet avec une gestion du
					temps{/fr}
					{es}Actividades en el proyecto con el tiempo y la asistencia de
					seguimiento{/es}
					{/i18n}
					" style="left: 1459px;top: 48px;" class="ti">
				</div>
			</div>
			<!-- izip
                                </td><td width="700" valign="top">
                                /izip -->
			<div>
				<br />
				<div id="emphasizeTube"
					style="width: 420px; height: 261px; z-index: 1;">
					<h1>
						{i18n ref="hed1" />
					</h1>
					<p>
						<a href="http://www.adobe.com/go/getflashplayer"><img
							src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif"
							alt="Get Adobe Flash player" /> </a>
					</p>
				</div>
				<!-- nizip -->
				<br /> <br />
				{i18n key="demo44"} {en}{app_name/} users come from{/en} {de}{app_name/}
				Benutzer kommen aus{/de} {fr}{app_name/} utilisateurs proviennent de{/fr}
				{es}{app_name/} usuarios provienen de{/es}{/i18n}
				...<br />
				<div id="worldmap" style="width: 420px; height: 238px; z-index: 1;">
					<h1>
						{i18n ref="demo45" />
					</h1>
					<p>
						<a href="http://www.adobe.com/go/getflashplayer"><img
							src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif"
							alt="Get Adobe Flash player" /> </a>
					</p>
				</div>
				<br /> <br />
				{i18n key="demo45"} {en}Active time-tracking users of this Web
				service{/en} {de}Aktive Zeiterfassungs-Benutzer
				dieses Webdienstes{/de} {fr}Les utilisateurs
				de la gestion du temps actifs de ce service Web{/fr} {es}Active los usuarios de gestión del tiempo de este
				servicio web{/es}{/i18n}
				:
				<div
					style="width: 420px; height: 70px; background: url('graphics/grow.jpg') repeat-x scroll left bottom transparent;">
					<img src="<?php echo(DOMAIN.'/util/load.php');?>"
						alt="{i18n ref="demo45" />" width="420" height="70" />
				</div>
				<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/aggregaterating">
				<?php
				if (r("ajax") != "true") {
				 User::connectDb();
				 echo(str_replace(array("_week_users_", "_month_users_"), getUserLoads(), i18n('{i18n key="demo71"}{de}_week_users_ aktive Benutzer seit einer Woche von _month_users_ aktive Benutzer des vergangenen Monats{/de}
				  {en}_week_users_ active users since one week with _month_users_ active users since one month{/en}
				  {fr}_week_users_ utilisateurs actifs depuis une semaine avec les utilisateurs _month_users_ actifs depuis un mois{/fr}
				  {es}_week_users_ usuarios activos desde una semana con _month_users_ usuarios activos desde un mes{/es}
				  {/i18n}.')));
                } ?></span>
				<br /> <br />
				{i18n key="demo46"} {en}The server software {app_name/} is
				available as a download to install for yourself{/en} {de}Die
				Server-Software {app_name/} zum selbst Installieren
				herunterladen{/de} {fr}Le logiciel serveur lui-même {app_name/}
				pour télécharger et installer{/fr} {es}El software del servidor se
				{app_name/} para descargar e instalar{/es}{/i18n}
				(<span itemprop="offers" itemscope itemtype="http://schema.org/Offer"><meta itemprop="price" content="0" />{i18n key="demo70"}{en}free{/en}{de}kostenlos{/de}{fr}gratuit{/fr}{es}gratuito{/es}{/i18n}</span>):<br/><br/>
				<div style="float:right;">
				  <form action="https://www.paypal.com/cgi-bin/webscr" target="_blank" method="post">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="L8A7JQWGFNJYG">
            <?php
              echo('<input type="image" src="https://www.paypalobjects.com/'.i18n("<lang/>_{i18n key='don0'}{de}DE/DE{/de}{en}US/GB{/en}{fr}FR/FR{/fr}{es}ES/ES{/es}{/i18n}").'/i/btn/btn_donateCC_LG.gif" onclick="track(\'/util/donate.php\', \'Donate\')" border="0" name="submit" alt="'.i18n("{i18n key='don1'}{de}Jetzt einfach, schnell und sicher online spenden – mit PayPal.{/de}{en}quickly and securely donate online now - with PayPal.{/en}{fr}rapidement et en toute sécurité un don en ligne maintenant - avec PayPal.{/fr}{es}rápida y segura donar en línea ahora - con PayPal.{/es}{/i18n}").'">');
              echo('<img alt="" border="0" src="https://www.paypalobjects.com/'.i18n("<lang/>_<LANG/>").'/i/scr/pixel.gif" width="1" height="1">');
            ?>
          </form>
        </div>
        <?php echo('<a href="'.DOMAIN.'/util/download.php" alt="download zip" onclick="track(\'/util/download.php\', \'Download\')" title="download {app_name/}-<?php echo(VERSION);?>.zip" class="download"<span itemprop="download" itemscope itemtype="http://schema.org/WebApplication">{app_name/}</span> '.VERSION.'</a>'); ?>
        <br /> <br /><br />
				{i18n key="demo50"} {en}Do you need support for this, then please
				contact{/en} {de}Benötigen Sie Support hierzu, dann kontaktieren Sie{/de}
				{fr}Avez-vous besoin d'aide pour cela, alors s'il vous plaît
				contactez{/fr} {es}¿Necesita ayuda para esto, entonces por favor
				póngase en contacto con{/es}{/i18n}
				: <a href="http://www.emphasize-it.de/#imprint" class="newWindow"
					alt="commercial support" title="commercial support" target="_blank">Emphasize
					IT</a>
				<!-- /nizip -->
			</div>
		</div>
		<!-- nizip -->
		<div style="position: relative; left: 440px; width: 420px;">
			<div id="bContainer">
				<!--bContent-->
				<div id="bContent">
					<?php
					include_once(INC.'/news.php');
					if ($lang=='de') {
					 entry($lang, $bid);
					} else {
					 entry("en", $bid);
					}
					?>
				</div>
				<!--/bContent-->
			</div>
		</div>
		<!-- /nizip -->
	</div>
