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
  var sel=getCookieParam("stay");
  if (sel != "") {
    document.login.stay.value = sel;
  } 
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
	connectDb();
	$sql = @mysql_query("SELECT id, name, lang FROM " . $db_prefix . "USER WHERE confirmed='".p($confirmcode)."'");
	$row = mysql_fetch_array($sql);

	if (is_numeric($row["id"])) {
		$id=$row["id"];
		$name=$row["name"];
		$_SESSION['lang']=$row["lang"];
		mysql_free_result($sql);
		$update = @mysql_query("UPDATE " . $db_prefix . "USER SET confirmed='t' WHERE id=".p($id));

		if (!$update) {
			fail("Confirmation denied");
		}
		// inform admin
		mail($feedback_to, "[Emphasize] Confirmed: $name", "$name on $domain with $email", "From: $feedback_to\r\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: 8bit\r\n\r\n");
	} else {
		fail("Confirmation failed");
	}

  echo(str_replace("_name_", $name, i18n('<i18n key="cfm1"><en>Registration for _name_ successfully completed, please login with password:</en> <de>Registration
	für _name_ erfolgreich abgeschlossen, bitte mit Passwort
	einloggen:</de> <fr>Inscription pour _name_ réussi, s\'il
	vous plaît login avec mot de passe:</fr> <es>Registro de _name_ completado
	con éxito, por favor inicio de sesión con contraseña:</es></i18n>')));
	?>
	
<body onload="initLogin()">
	<div class="top">
		<form name="login" action="<?php echo($domain.'/'); ?>" method="POST"
			onsubmit="setName()"><input type="hidden" name="do" value="login" /><input
				id="loginName" type="hidden" name="name"
				value="<?php echo($name);?>" size="8" />
			<i18n ref="rtr3"></i18n>
			: <input type="password" name="password" size="8" value="" />
			<i18n ref="demo18"></i18n>
			: <select name="stay" onchange="setStay()"><option value="60">
					<i18n ref="demo19"></i18n>
				</option>
				<option selected value="480">
					<i18n ref="demo20"></i18n>
				</option>
				<option value="1440">
					<i18n ref="demo21"></i18n>
				</option>
				<option value="10080">
					<i18n ref="demo22"></i18n>
				</option>
				<option value="0">
					<i18n ref="demo23"></i18n>
				</option>
			</select> <input type="submit" name="Submit" value="Login">
		</form>
	</div>
	<?php 
bottom();
?>
