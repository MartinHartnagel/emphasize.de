<html>
<head>
<title><?php
  include_once(dirname(__FILE__)."/../includes/configuration.php");
  echo(APP_NAME.' '.VERSION);
?> Administration - Configuration</title>
<style type="text/css"><!--
form {
  line-height: 1.5em;
}
input {
  position: absolute;
  left: 300px;
  margin-top: 2px;
}
-->
</style>
</head>

<body>
<h1><?php echo(APP_NAME.' '.VERSION);
?> Administration - Configuration</h1>
<form action="create.php" method="POST">
<h2>Database Settings</h2>
Database host:
<input type="text" name="db_host" value="<?php if (isset($db_host)) echo($db_host); else echo("localhost"); ?>" />
<br/>
Database name:
<input type="text" name="db_name" value="<?php if (isset($db_name)) echo($db_name); else echo("mydb"); ?>" />
<br/>
Database user:
<input type="text" name="db_username" value="<?php if (isset($db_username)) echo($db_username); else echo("mylogin"); ?>" />
<br/>
Database password:
<input type="password" name="db_password" value="<?php if (isset($db_password)) echo($db_password); ?>" />
<br/>
Reconfirm database password:
<input type="password" name="reconfirm_password" value="<?php if (isset($db_password)) echo($db_password); ?>" />
<br/>
Prefix for emphasize-tables:
<input type="text" name="db_prefix" value="<?php echo($db_prefix); ?>" />
<br/>
<h2>Installation Settins</h2>
Domain:
<input type="text" name="domain" value="<?php echo($domain); ?>" />
<br/>
Send registration-emails from and feedback to:
<input type="text" name="feedback_to" value="<?php echo($feedback_to); ?>" />
<br/>
<br/>
<input type="submit" value="Configure" />
</form>
</body>
</html>