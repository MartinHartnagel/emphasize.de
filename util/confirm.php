<?php
include_once(dirname(__FILE__).'/../includes/config.php');
?>
<title>Emphasize -&nsbp;<i18n ref="demo0"></i18n>
</title>
<script type="text/javascript">
function setName() {
  setCookieParam("name", document.login.name.value);
  return true;
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
  document.login.password.value = "";
  if (document.login.name.value.length > 0) {
    document.login.password.focus();
  }
}
</script>
</head>
<body>
	<?php
	$confirmcode=$_SERVER['QUERY_STRING'];
	User::connectDb();
	$sql = @mysql_query("SELECT id, name, lang FROM " . DB_PREFIX . "USER WHERE confirmed='".p($confirmcode)."'");
	$row = mysql_fetch_array($sql);

	if (is_numeric($row["id"])) {
		$id=$row["id"];
		$name=$row["name"];
		User::getInstance()->setUserLang($row["lang"]);
		mysql_free_result($sql);
		$update = @mysql_query("UPDATE " . DB_PREFIX . "USER SET confirmed='t' WHERE id=".p($id));

		if (!$update) {
			fail("Confirmation denied");
		}

		if (!(isset($testing) && $testing)) {
		 // inform admin
		 enqueueMail(FEEDBACK_TO, "[Emphasize] Confirmed: $name", "$name on DOMAIN", "From: ".FEEDBACK_TO."\r\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: 8bit\r\n\r\n");
		}
	} else {
		fail("Confirmation failed");
	}

	echo(str_replace("_name_", $name, i18n("<i18n key='cfm1'><en>Registration for _name_ successfully completed, please login with password</en>
	  <de>Registration für _name_ erfolgreich abgeschlossen, bitte mit Passwort einloggen</de>
	  <fr>Inscription pour _name_ réussi, s'il vous plaît login avec mot de passe</fr>
	  <es>Registro de _name_ completado	con éxito, por favor inicio de sesión con contraseña</es></i18n>")));
	?>
<body onload="initLogin()">
	<div class="top">
		<form name="login" action="<?php echo(DOMAIN.'/'); ?>" method="POST"
			onsubmit="setName()">
			<input type="hidden" name="do" value="login" /><input id="loginName"
				type="hidden" name="name" value="<?php echo($name);?>" size="8" />
			<i18n ref="rtr3"></i18n>
			: <input type="password" name="password" size="8" value="" /> </select>
			<input type="submit" name="Submit" value="Login">
		</form>
	</div>
	<?php
	bottom();
	?>