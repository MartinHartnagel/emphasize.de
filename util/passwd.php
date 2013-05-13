<?php include_once(dirname(__FILE__).'/../includes/config.php'); ?>
<?php
$export="js";
header("Content-Type: text/html;charset=UTF-8");
?>
<title><i18n key="pwd0"> <en>Change your password</en> <de>Passwort ändern</de> <fr>Changer le mot de passe</fr> <es>Cambiar la contraseña</es></i18n></title>
<script type="text/javascript">
<!--
function verifyPasswords() {
  if (document.passwd.password.value != document.passwd.verify.value) {
    if (document.passwd.verify.value.length > 0) {
      document.passwd.password.style.backgroundColor = "red";
    }
    document.passwd.verify.style.backgroundColor = "red";
  } else {
    document.passwd.verify.style.backgroundColor = null;
    document.passwd.password.style.backgroundColor = null;
  }
}

function submitPassword() {
    var values = {"token":token};
    $.each($('#passwdForm').serializeArray(), function(i, field) {
      values[field.name] = field.value;
    });

		$.ajax({
      type: "POST",
      url:domain+"util/ajax.php",
      data: values,
      success: function(msg){
        if (aboveClose != undefined) {
          aboveClose();
        }
        Progress.showStatus(false, "<i18n key='pwd3'><en>Password changed</en><de>Passwort wurde geändert</de><fr>Mot de passe a été changé</fr><es>La contraseña ha sido cambiada</es></i18n>");
      }, error: function(req, status, error) {
        Progress.showStatus(true, error+" "+status);
      }});
    return false;
}
-->
</script>
</head>
<body>
	<form action="<?php echo(DOMAIN.'/util/ajax.php');?>" id="passwdForm" name="passwd" method="POST" onsubmit="return submitPassword();">
		<table border="0">
			<tr>
				<td valign="top"><input type="hidden" name="do" value="passwd" />
					<i18n
						key="pwd1"><en>New password</en> <de>Neues Passwort</de> <fr>Nouveau mot de passe</fr>
					<es>nueva contraseña</es></i18n>:<br />
					<input id="passwdPassword" class="help" type="password"
							name="password" onkeyup="verifyPasswords()" />
					<br />&nbsp;<i18n ref="rtr5" />:<br />
					<div class="hc">
						<input id="verifyPassword" class="help" type="password"
							name="verify" onkeyup="verifyPasswords()" />
						<div id="help_verifyPassword" class="docu"
							style="width: 220px; height: 80px;">
							<i18n ref="rtr6" />
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="2"><input id="passwdSubmit"
					type="submit" value="<i18n key='pwd2'><en>Change</en> <de>Ändern</de> <fr>Changer</fr>
					<es>Cambiar</es></i18n>" /> <input
					id="passwdReset" type="reset" value="<i18n
					 ref='fdf7' />" /></td>
			</tr>
		</table>
	</form>

	<?php bottom(); ?>
