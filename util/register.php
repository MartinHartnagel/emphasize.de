<?php include_once(dirname(__FILE__).'/../includes/config.php'); ?>
<?php
$export="js";
header("Content-Type: text/html;charset=UTF-8");
?>
<title><i18n key="rtr0"> <en>Register for time-tracking with</en> <de>Konto
	Anlegen zur Zeiterfassung mit</de> <fr>Créer un compte pour
	l'enregistrement du temps avec</fr> <es>Crear una cuenta de tiempo de
	grabación con</es></i18n> <app_name/></title>
</head>

<body>
	<script type="text/javascript">
<!--
function verifyPasswords() {
  if (document.register.password.value != document.register.verify.value) {
    if (document.register.verify.value.length > 0) {
      document.register.password.style.backgroundColor = "red";
    }
    document.register.verify.style.backgroundColor = "red";
  } else {
    document.register.verify.style.backgroundColor = null;
    document.register.password.style.backgroundColor = null;
  }
}

function termsAccepted() {
  if (!document.register.termsAndConditions.checked) {
    alert("<i18n key='rtr1'><en>You must read, understand and accept\nthe Terms & Conditions before registering</en><de>Sie müssen die AGB gelesen und akzeptiert haben,\nbevor Sie sich registrieren können</de><fr>Il faut lire et accepter les\nconditions pout créer un compte</fr><es>Usted debe haber leído y acepto\nlas Condiciones antes de poder registrar</es></i18n>.");
  }
  return document.register.termsAndConditions.checked;
}
-->
</script>
	<form action="<?php echo(DOMAIN.'/');?>" name="register" method="POST"
		onsubmit="return termsAccepted();">
		<table border="0" width="560">
			<tr>
				<td valign="top" width="50%"><input type="hidden" name="do"
					value="createUser" /> <input type="hidden" name="lang"
					value="<lang/> " /> <i18n key="rtr2"> <en>Create user</en> <de>Benutzer
					anlegen</de> <fr>Créer un utilisateur</fr> <es>Crear usuario</es></i18n>:<br />
					<input id="registerName" type="text" name="name" maxlength="30" /><br />
					<i18n key="rtr3"> <en>Password</en> <de>Passwort</de> <fr>Mot de
					passe</fr> <es>Contraseña</es></i18n>:<br />
					<div class="hc">
						<input id="registerPassword" class="help" type="password"
							name="password" onkeyup="verifyPasswords()" />
						<div id="help_registerPassword" class="docu"
							style="width: 280px; height: 80px;">
							<i18n key="rtr4"> <en>Password for new user account. Should be at
							least 3 letters or numbers long. The password is stored encrypted
							and is therefore not visible to the administrator.</en> <de>Passwort
							des Benutzer-Accounts wählen. Sollte mindestens 3 Buchstaben oder
							Zahlen lang sein. Das Passwort wird verschlüsselt gespeichert und
							ist somit für den Administrator nicht sichtbar.</de> <fr>Mot de
							passe pour nouveau compte d'utilisateur. Devrait être au moins 3
							lettres ou chiffres. Le mot de passe est stocké crypté et n'est
							donc pas visible par l'administrateur.</fr> <es>Contraseña de la
							cuenta de usuario nueva. Debe ser por lo menos 3 letras o
							números. La contraseña se guarda encriptada y por tanto, no
							visible para el administrador.</es></i18n>
						</div>
					</div> <br /> <i18n key="rtr5"> <en>Verify password</en> <de>Passwort
					wiederholen</de> <fr>Répéter mot de passe</fr> <es>Repita la
					contraseña</es></i18n>:<br />
					<div class="hc">
						<input id="verifyPassword" class="help" type="password"
							name="verify" onkeyup="verifyPasswords()" />
						<div id="help_verifyPassword" class="docu"
							style="width: 220px; height: 80px;">
							<i18n key="rtr6"> <en>Re-enter the password chosen above to avoid
							errors. The fields have a red background until passwords match.</en>
							<de>Erneute Eingabe des gewählten Passworts oben drüber zur
							Fehlervermeidung. Die Felder bekommen einen roten Hintergrund bis
							die Passwörter übereinstimmen.</de> <fr>Re-saisissez le mot de
							passe choisi ci-dessus sur elle pour éviter les erreurs. Les
							champs ont un fond rouge pour correspondre les mots de passe.</fr>
							<es>Vuelva a introducir la contraseña elegida por encima del
							mismo para evitar errores. Los campos tienen un fondo rojo para
							que coincida con las contraseñas.</es></i18n>
						</div>
					</div> <br /> <i18n key="rtr7"> <en>Send authenticate email to</en>
					<de>Verifizierungs Email senden an</de> <fr>e-mail pour
					vérification</fr> <es>Correo electrónico de verificación</es></i18n>:<br />
					<div class="hc">
						<input id="verifyEmail" class="help" type="text" name="email"
							maxlength="60" />
						<div id="help_verifyEmail" class="docu"
							style="width: 280px; height: 110px;">
							<i18n key="rtr8"> <en>E-mail address to which after the
							registration, a confirmation email is sent. The new user account
							can be "tried out" for 60 minutes without this confirmation. For
							further use the confirmation link in the confirmation email must
							be opened.</en> <de>Email-Adresse an die nach der Registrierung
							eine Bestätigungs-Email verschickt wird. Das neue Benutzerkonto
							kann 60 Minuten ohne diese Bestätigung "ausprobiert" werden. Zur
							weiteren Benutzung muß anschließend der Bestätigungs-Link in der
							Bestätigungs-Email geöffnet werden.</de> <fr>Adresse e-mail qui
							est envoyé après l'inscription, un courriel de confirmation. Le
							nouveau compte d'utilisateur peut être "essayé" 60 minutes sans
							cette confirmation. Pour une utilisation ultérieure doit alors
							être ouverte lien de confirmation dans l'email de confirmation.</fr>
							<es>Dirección de correo electrónico que se envía después del
							registro, un mensaje de confirmación. La nueva cuenta de usuario
							puede ser "probado" 60 minutos sin esta confirmación. Para su uso
							posterior a continuación se abrirá enlace de confirmación en el
							correo electrónico de confirmación.</es></i18n>
						</div>
					</div>
				</td>
				<td valign="top" width="50%">
					<div>
						<img id="siimage" align="left" width="250" height="80"
							style="padding-right: 5px; border: 0"
							src="util/securimage_show.php?sid=<?php echo(md5(time())); ?>" />
						<br />
						<!-- pass a session id to the query string of the script to prevent ie caching -->
						<a tabindex="-1" style="border-style: none" href="#"
							title="Refresh Image"
							onclick="document.getElementById('siimage').src = 'util/securimage_show.php?sid=' + Math.random(); return false"><img
							src="images/refresh.gif" alt="Reload Image" border="0"
							onclick="this.blur()" align="bottom" /> </a>
					</div>
					<br/>
					<i18n key="rtr12"> <en>Visual safety code</en> <de>Sicherheits-Code</de>
					<fr>Clé de sécurité</fr> <es>Llave de seguridad</es></i18n>:
					<div class="hc">
						<input id="safetyCode" class="help" type="text" name="code"
							size="12" />
						<div id="help_safetyCode" class="docu"
							style="width: 220px; height: 62px;">
							<i18n key="rtr13"> <en>Text of the captcha image above, with
							which the human (i.e. non-automated) registration will be
							secured.</en> <de>Text des Captcha-Bild oben drüber eingeben, mit
							dem die menschliche (d.h. nicht automatisierte) Registrierung
							abgesichert wird.</de> <fr>Texte de l'image du captcha ci-dessus
							sur elle, avec l'homme (non automatisée) d'inscription seront
							garantis.</fr> <es>Texto de la imagen captcha anterior sobre el
							mismo, con los humanos (es decir, no automatizados) El registro
							estará asegurado.</es></i18n>
						</div>
					</div> <br /> <?php echo(str_replace(array("_agb_href_"), array(DOMAIN.'/agb.php'), i18n("<i18n key='rtr11'><en>I have read, understood	and accept the <a href=\"_agb_href_\"
							target=\"_blank\">Terms & Conditions</a></en> <de>Ich habe die <a
							href=\"_agb_href_\" target=\"_blank\">AGB</a>s
							gelesen	und akzeptiere diese</de> <fr>J'ai lu et j'accepte
							les <a href=\"_agb_href_\" target=\"_blank\">conditions</a></fr>
					<es>He leído y acepto las <a href=\"_agb_href_\" target=\"_blank\">Condiciones</a></es></i18n>"))); ?>:
					<input id="registerTermsAndConditions" type="checkbox"
					name="termsAndConditions" value="readAccepted" /> <br /> <input
					id="registerSubmit" type="submit" value="<i18n ref='rtr2'> </i18n>"
					/>
				</td>
			</tr>
		</table>
	</form>

	<?php bottom(); ?>