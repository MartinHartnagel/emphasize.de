<?php 
$lang=detectLang();
$cacheFile="demo_".$lang.".html";
// nizip

if (!(isset($bid) && $bid > 0)) {
	connectDb();
	if ($lang=='de') {
		$blang=$lang;
	} else {
		$blang="en";
	}
	$sql = mysql_query("SELECT log_id AS bid FROM emphasize_blog WHERE log_author='$blang' AND TIMESTAMP(log_date, log_time) <= CURRENT_TIMESTAMP ORDER BY TIMESTAMP(log_date, log_time) DESC");
	if ($row = mysql_fetch_array($sql)) {
		$latestId = $row["bid"];
	}
	mysql_free_result($sql);
	$bid=$latestId;
}
$cacheFile="demo_".$lang."_".$bid.".html";
// /nizip
checkCache($cacheFile, "includes/config.php", "includes/demo.php");
?>
<title><i18n key="demo0"> <en>The Simple Time Registration</en> <de>Arbeitszeiterfassung
	Ganz Einfach</de> <fr>Gestion Du Temps Tout Simplement</fr> <es>Gestión
	Del Tiempo Hizo Fáci</es></i18n>&nbsp;- Emphasize</title>
<script type="text/javascript">
<!--
var step=0;
var steps=10;

function setName() {
  setCookieParam("name", document.login.name.value);
}

function setStay() {
  setCookieParam("stay", document.login.stay.value);
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
  } else {
    document.login.name.value = getCookieParam("name");
  }
  var sel=getCookieParam("stay");
  if (sel != "") {
    document.login.stay.value = sel;
  } 
  if (document.login.name.value.length > 0) {
    document.login.password.focus();
  }
  init();
  <command/>
}

function init() {
  t=new Tabletti(document.getElementById("table"));
  t.setDemo(true);
  initTimeline();
  window.setTimeout("animate()", 2000);
  
  $(window).hashchange(function(){
    var hash = location.hash;
    if (hash.length > 1) {
      var url=hash.substr(1)+".php";
      $("#doing").css("visibility", "visible");
      $.post(domain+url, {"ajax":"true", "grep":"bContent"}, function(data) {
        $("#bContent").replaceWith(data);
        $("#doing").css("visibility", "hidden");
      });
    } else {
      $("#doing").css("visibility", "visible");
      $.post(domain, {"ajax":"true", "grep":"bContent", "lang":lang}, function(data) {
        $("#bContent").replaceWith(data);
        $("#doing").css("visibility", "hidden");
      });
    }
  });
  
  $(window).hashchange();
}

function animate() {
  if (step%steps==0 && t.isValid()) moveAvatar(t.getTd(0,0));
  if (step%steps==1 && t.isValid()) moveAvatar(t.getTd(1,0)); 
  if (step%steps==2 && t.isValid()) { t.showEdits(t.getTd(0,0));}
  if (step%steps==3 && t.isValid()) { t.setDemo(false); t.horizontalSplit(t.getTd(0,0)); t.getTd(0,1).innerHTML='<i18n key="con10"><en>Training</en><de>Schulung</de><fr>Formation</fr><es>Capacitación</es></i18n>'; t.setDemo(true); t.getTd(0,1).bgColor="#ecfe32";}
  if (step%steps==4 && t.isValid()) moveAvatar(t.getTd(0,1));
  if (step%steps==5 && t.isValid()) moveAvatar(t.getTd(1,1));
  if (step%steps==6 && t.isValid()) t.showEdits(t.getTd(0,0));
  if (step%steps==7 && t.isValid()) { t.setDemo(false); t.horizontalMerge(t.getTd(0,0)); t.setDemo(true); }
  if (step%steps==8 && t.isValid()) moveAvatar(t.getTd(1,1));
  if (step%steps==9 && t.isValid()) moveAvatar(t.getTd(1,0));

  step++;
  window.setTimeout("animate()", 2000);
}

$('a.blog').live('click',function(){ 
  url = $(this).attr("href");
  $("#doing").css("visibility", "visible");
  $.post(domain+url, {"ajax":"true", "grep":"bContent"}, function(data) {
    $("#bContent").replaceWith(data);
    window.location.hash = '#' + url.replace('.php','');
    $("#doing").css("visibility", "hidden");
  });
  return false;
});
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
	src="<?php echo($domain.'/js/jquery.ba-hashchange.min.js'); ?>"></script>
<!-- /nizip -->
</head>
<body onload="initLogin()">
	<div id="login_top">
		<form name="login" action="<?php echo($domain.'/');?>" method="post">
			<input type="hidden" name="do" value="login" />
			<i18n key="demo14"> <en>User</en> <de>Benutzer</de> <fr>Utilisateur</fr>
			<es>Usuario</es></i18n>
			:
			<div class="hc">
				<input id="loginName" class="input_text help" type="text" name="name" value=""
					size="8" onchange="setName()" />
				<div id="help_loginName" class="docu"
					style="width: 140px; height: 44px;">
					<i18n key="demo15"> <en>login name of an already created
					user-account</en> <de>Login-Name eines bereits angelegten
					Benutzer-Accounts</de> <fr>Nom de connexion d'un des comptes
					d'utilisateur déjà créé</fr> <es>Nombre de inicio de una cuenta de
					usuario ya ha creado</es></i18n>
				</div>
			</div>
			<i18n ref="rtr3"></i18n>
			:
			<div class="hc">
				<input id="loginPassword" class="input_text help" type="password"
					name="password" size="8" value="" />
				<div class="hc">
					<input id="loginSubmit" class="button help" type="submit" name="Submit"
						value="<i18n       key='demo79'>
					<en>Login</en>
					<de>Anmelden</de>
					<fr>Connectez-vous</fr>
					<es>Iniciar sesión</es>
					</i18n>
					">
					<div id="help_loginSubmit" class="docu"
						style="width: 160px; height: 62px;">
						<i18n key="demo25"> <en>Log on to the specified user account name
						and password for the specified time.</en> <de>In den angegebenen
						Benutzer-Account mit Name und Passwort für die angegebene Zeit
						einloggen.</de> <fr>Ouvrez une session sur le nom du compte
						utilisateur spécifié et mot de passe pour l'heure spécifiée.</fr>
						<es>Inicie sesión en el nombre de cuenta de usuario y la
						contraseña especificados durante el tiempo especificado.</es></i18n>
					</div>
				</div>
				<div id="help_loginPassword" class="docu"
					style="width: 180px; height: 44px;">
					<i18n key="demo17"> <en>Password to login matching the name of the
					already created user account.</en> <de>Login-Passwort passend zum
					Namen des bereits angelegten Benutzer-Accounts.</de> <fr>Mot de
					passe qui correspondre au nom des comptes utilisateur déjà créé.</fr>
					<es>contraseña de inicio de sesión para que coincida con el nombre
					de las cuentas de usuario ya ha creado.</es></i18n>
				</div>
			</div>
			<i18n key="demo18"> <en>Log in for</en> <de>Einloggen für</de> <fr>Connecté
			pour</fr> <es>Conectado por</es></i18n>
			:
			<div class="hc">
				<select id="loginStay" class="help" name="stay" onchange="setStay()"><option
						value="60">
						<i18n key="demo19"> <en>one hour</en> <de>eine Stunde</de> <fr>une
						heure</fr> <es>una hora</es></i18n>
					</option>
					<option value="480">
						<i18n key="demo20"> <en>8 hours</en> <de>8 Stunden</de> <fr>8
						heures</fr> <es>8 horas</es></i18n>
					</option>
					<option value="1440">
						<i18n key="demo21"> <en>one day</en> <de>ein Tag</de> <fr>un jour</fr>
						<es>un día</es></i18n>
					</option>
					<option value="10080">
						<i18n key="demo22"> <en>one week</en> <de>eine Woche</de> <fr>un
						semaine</fr> <es>una semana</es></i18n>
					</option>
					<option selected value="0">
						<i18n key="demo23"> <en>ever</en> <de>immer</de> <fr>toujours</fr>
						<es>siempre</es></i18n>
					</option>
				</select>
				<div id="help_loginStay" class="docu"
					style="width: 240px; height: 62px;">
					<i18n key="demo24"> <en>How long will the login to be valid. <a
						href="javascript:tubeTutorial('FIRhzFfefTY')">For "always" enables
						embedding with Active Desktop as a Web page in Windows</a>.</en> <de>Wie
					lange das Login gültig sein soll. <a
						href="javascript:tubeTutorial('JCAXvyypUrA')">Für "immer"
						ermöglicht das einbinden als Webseite im Active Desktop unter
						Windows</a>.</de> <fr>Combien de temps la connexion pour être
					valide. <a href="javascript:tubeTutorial('FIRhzFfefTY')">Pour
						«toujours» permet intégration avec Active Desktop comme une page
						Web dans Windows</a>.</fr> <es>¿Cuánto tiempo el inicio de sesión
					sea válida. <a href="javascript:tubeTutorial('FIRhzFfefTY')">Para
						"siempre" le permite integrar con Active Desktop como una página
						Web en Windows</a>.</es></i18n>
				</div>
			</div>
		</form>
		<!-- nizip -->
		<div class="hc">
			<span id="recommend" class="help"><i18n key="demo69"> <en>Recommend
				it</en> <de>Empfehle es</de> <fr>Recommande</fr> <es>Recomiendo</es></i18n>:</span>
			<g:plusone size="medium" annotation="inline" width="180"></g:plusone>
			<fb:like
				href="<?php echo($domain);?>"
				layout="button_count" show_faces="false" height="30"
				font=""></fb:like>
			<div id="help_recommend" class="docu"
				style="width: 120px; height: 44px;">
				<i18n key="demo78"> <en>Recommend Emphasize in social networks.</en>
				<de>Emphasize in einem Sozialen Netzwerk weiterempfehlen.</de> <fr>Mettre
				l'Emphasize sur les réseaux sociaux.</fr> <es>Hacer Emphasize en las
				redes sociales.</es></i18n>
			</div>
		</div>
		<!-- /nizip -->
	</div>
	<?php echo(getUserAvatar("default"));?>
	<table border="0" width="100%" cellspacing="16">
		<tr>
			<td width="420" valign="top">
				<div class="hc">
					<input id="register" class="button help" style="float:left;" type="button"
						onclick="createUser()" value="<i18n key='demo49'>
						<en>register new user</en>
					<de>neuen Benutzer anlegen</de>
					<fr>créer un nouvel utilisateur</fr>
					<es>crear nuevo usuario</es>
					</i18n>
					" />
					<div id="help_register" class="docu"
						style="width: 140px; height: 44px;">
						<i18n key="demo12"> <en> <a
							href="javascript:tubeTutorial('A1wF8aVZOfg')">open form to
							register for a new user-account</a>.</en> <de> <a
							href="javascript:tubeTutorial('jVxjkfQj6UE')">Formular öffnen um
							einen neuen Benutzer-Account anzulegen</a>.</de> <fr> <a
							href="javascript:tubeTutorial('A1wF8aVZOfg')">ouvrir une
							formulaire pour créer un nouveau compte d'utilisateur</a>.</fr> <es>
						<a href="javascript:tubeTutorial('A1wF8aVZOfg')">aquí se abrirá un
							formulario para crear una nueva cuenta de usuario</a>.</es></i18n>
					</div>
				</div> <!-- nizip -->
				<div class="hc">
					<input id="tryout" class="button help" style="float: right;background: #BEE6F2;" type="button"
						onclick="location.href='<?php echo($domain.'?tryout'.substr('00000000000000'.md5(time()), -14));?>';"
						value="<i18n       key='gast3'>
					<en>Test it</en>
					<de>Ausprobieren</de>
					<fr>Essayez</fr>
					<es>Pruébelo</es>
					</i18n>
					" />
					<div id="help_tryout" class="docu"
						style="width: 140px; height: 44px;">
						<i18n key="gast4"> <en>Test it for 20 minutes</en> <de>Ausprobieren
						für 20 Minuten</de> <fr>Essayez-le pendant 20 minutes</fr> <es>Pruébelo
						durante 20 minutos</es></i18n>
						!
					</div>
				</div> <!-- /nizip -->
			  <div id="timeline" class="demo_timeline">
				  <noscript>
					  <center
						  style="font-size: 20px; color: red; text-decoration: blink;">
						  <i18n key='tab56'> <en>Scripts disabled in browser, enable to use
						  Emphasize</en> <de>Skripte sind deaktiviert im Browser, bitte
						  aktivieren, um Emphasize zu verwenden</de> <fr>Scripts désactivé
						  dans le navigateur, permettant d'utiliser Emphasize</fr> <es>Scripts
						  con discapacidad en el navegador, permiten utilizar en Emphasize</es></i18n>
						  !
					  </center>
				  </noscript>
				  <div id="time" class="tDiv">
					  <div id="tHours">
						  <span class="tHour">initializing...</span>
					  </div>
					  <div id="tLine" class="tLine">
						  <img style="left: 0px;" src="graphics/void.png" class="te"
							  height="10" width="3961"><img
							  src="<?php echo($domain."/util/i.php?bdc406"); ?>"
							  title="<i18n 
							       ref="con5">
						  </i18n>
						  " class="te" height="10" width="133"><img
							  src="<?php echo($domain."/util/i.php?6ba163"); ?>"
							  title="<i18n 
							       ref="con6">
						  </i18n>
						  " class="te" height="10" width="74"><img
							  src="<?php echo($domain."/util/i.php?a16363"); ?>"
							  title="<i18n 
							       ref='con7'>
						  </i18n>
						  " class="te" height="10" width="113">
					  </div>
					  <img id="now" src="graphics/now.png" title="<i18n      
						  ref='tab43'>
					  </i18n>
					  " width="19" height="58" class="tNow" /><img
						  src="graphics/info.png"
						  title="<?php echo(date('H:m')); ?>&nbsp;<i18n 
						      
						  key="demo31">
					  <en>Attend activities in the project with time tracking</en>
					  <de>Tätigkeiten im Projekt mit Zeiterfassung nachverfolgen</de>
					  <fr>Suivi des présences des activités du projet avec une gestion
					  du temps</fr>
					  <es>Actividades en el proyecto con el tiempo y la asistencia de
					  seguimiento</es>
					  </i18n>
					  " style="left: 1459px;top: 48px;" class="ti">
				  </div>
			  </div>
			  <table id="table" class="tabletti" width="420" height="200"
				  border="0" cellspacing="2" cellpadding="0">
				  <tr>
					  <td rowspan="2" bgcolor="#bdc406"><i18n ref="con5"></i18n></td>
					  <td bgcolor="#6ba163"><i18n ref="con6"></i18n></td>
				  </tr>
				  <td bgcolor="#a16363"><i18n ref="con7"></i18n></td>
				  </tr>
			  </table>
			        <!-- izip
                                </td><td width="700" valign="top">
                                /izip -->
				<div>
					<br />
					<object width="420" height="261" style="z-index:1">
						<param value="transparent" />
						<param name="movie"
							value="http://www.youtube.com/p/<i18n 
							
							
							
							
							
							key="tech1">
						<en>8D1C1809D9BAB718?hl=en_US</en>
						<de>F2A34232616B5BF1?hl=de_DE</de>
						<fr>8D1C1809D9BAB718?hl=fr_FR</fr>
						<es>8D1C1809D9BAB718?hl=es_ES</es>
						</i18n>
						&fs=1">
						</param>
						<param name="allowFullScreen" value="true"></param>
						<param name="allowscriptaccess" value="always"></param>
						<embed src="http://www.youtube.com/p/<i18n       ref="tech1">
							</i18n>
							&fs=1" type="application/x-shockwave-flash" width="420"
							height="261" allowscriptaccess="always" allowfullscreen="true" wmode="transparent">
						</embed>
					</object>
					<!-- nizip -->
					<br /> <br />
					<i18n key="demo44"> <en>Emphasize users come from</en> <de>Emphasize
					Benutzer kommen aus</de> <fr>Emphasize utilisateurs proviennent de</fr>
					<es>Emphasize usuarios provienen de</es></i18n>
					...<br />
					<object data="<?php echo($domain.'/util/worldmap.php');?>"
						id="UserCountryMap" type="application/x-shockwave-flash"
						width="420" height="238">
						<param value="false" name="menu">
						<param value="noScale" name="scale">
						<param value="always" name="allowscriptaccess">
						<param value="opaque" name="wmode">
						<param value="#FFFFFF" name="bgcolor">
						<param value="true" name="allowfullscreen">
						<param
							value="dataUrl=<?php echo($domain.'/util/delegate.php');?>&hueMin=0&hueMax=40&satMin=0.5&satMax=0.9&lgtMin=0.97&lgtMax=0.44&iconOffset=0&defaultMetric=nb_visits&txtLoading=........loading...&txtLoadingData=loading%20data...&txtToggleFullscreen=Fullscreen&txtExportImage=Export"
							name="flashvars">
					</object>
					<br /> <br />
					<i18n key="demo45"> <en>Active users of this Web service of the
					last 24 hours</en> <de>Aktive Benutzer dieses Webdienstes der
					letzten 24 Stunden</de> <fr>Les utilisateurs actifs de ce service
					Web en les dernières 24 heures</fr> <es>Active los usuarios de este
					servicio web las últimas 24 horas</es></i18n>
					:
					<div
						style="width: 300px; height: 30px; background-image: url('graphics/load_bg.png');">
						<img src="<?php echo($domain.'/util/load.php');?>"
							alt="last 24h load" width="288" height="20" />
					</div>
					<br /> <br />
					<i18n key="demo46"> <en>The server software Emphasize.de is
					available as a download to install for yourself</en> <de>Die
					Server-Software von Emphasize.de zum selbst Installieren
					herunterladen</de> <fr>Le logiciel serveur lui-même Emphasize.de
					pour télécharger et installer</fr> <es>El software del servidor se
					Emphasize.de para descargar e instalar</es></i18n>
					: <a href="<?php echo($domain.'/util/download.php');?>"
						alt="download zip" title="download emphasize.de-<?php echo($version);?>.zip" class="download">emphasize.de&nbsp;<?php echo($version);?></a>
					
					<br /> <br />
					<i18n key="demo50"> <en>Do you need support for this, then please contact</en> <de>Benötigen Sie Support hierzu, dann kontaktieren Sie</de><fr>Avez-vous besoin d'aide pour cela, alors s'il vous plaît contactez</fr> <es>¿Necesita ayuda para esto, entonces por favor póngase en contacto con</es></i18n>
					: <a href="http://www.it.emphasize.de/#imprint" class="newWindow"
						alt="commercial support" title="commercial support" target="_blank">Emphasize IT</a>
					<!-- /nizip -->
				</div>
			</td>
			<!-- nizip -->
			<td width="700" valign="top">
				<div id="bContainer">
					<!--bContent-->
					<div id="bContent">
						<?php 
						connectDb();
						if ($lang=='de') {
							entry($lang, $bid);
						} else {
							entry("en", $bid);
						}
						?>
					</div>
					<!--/bContent-->
				</div>
			</td>
 			<!-- /nizip -->
		</tr>
	</table>
