<?php
include_once(dirname(__FILE__)."/../includes/config.php");
$export="txt";
User::connectDb();

$load_days_back=14;
$no_entry_mail_months_back=11;
$no_entry_delete_months_back=12;

// deletion
$deleteIds=array();
$sql = @mysql_query("SELECT DISTINCT id FROM ".DB_PREFIX."USER WHERE state='dwa' AND id not in (SELECT DISTINCT id_user from ".DB_PREFIX."LOAD where DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -".$load_days_back." DAY) < time) AND id not in (SELECT DISTINCT id_user from ".DB_PREFIX."USAGE where DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -".$no_entry_delete_months_back." MONTH) < login) AND id not in (SELECT DISTINCT id_user from ".DB_PREFIX."ENTRY where DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -".$no_entry_delete_months_back." MONTH) < start) AND id not in (SELECT DISTINCT id_user from ".DB_PREFIX."INFO where DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -".$no_entry_delete_months_back." MONTH) < start)");
while ($row = mysql_fetch_array($sql)) {
	$id=$row["id"];
	$deleteIds[]=$id;
}
mysql_free_result($sql);
echo("deleting ".sizeof($deleteIds)." inactive users and their data\n");
foreach($deleteIds as $id) {
 User::deleteUser($id);
}

//deletion-warning
$sql = @mysql_query("SELECT DISTINCT id, name, email, lang FROM ".DB_PREFIX."USER WHERE id not in (SELECT DISTINCT id_user from ".DB_PREFIX."LOAD where DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -".$load_days_back." DAY) < time) AND id not in (SELECT DISTINCT id_user from ".DB_PREFIX."USAGE where DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -".$no_entry_mail_months_back." MONTH) < login) AND id not in (SELECT DISTINCT id_user from ".DB_PREFIX."ENTRY where DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -".$no_entry_mail_months_back." MONTH) < start) AND id not in (SELECT DISTINCT id_user from ".DB_PREFIX."INFO where DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -".$no_entry_mail_months_back." MONTH) < start) AND email<>''");
while ($row = mysql_fetch_array($sql)) {
	$id=$row["id"];
	$name=$row["name"];
	$email=$row["email"];
	$userlang=$row["lang"];
	// inform
	$title=str_replace("_name_", $name, "[Emphasize] ".i18n("<i18n key='pcu0'><en>Deletion warning for \"_name_\" account</en><de>Löschungs-Hinweis des \"_name_\" Benutzers</de><fr>Remarque suppression des \"_name_\" l'utilisateur</fr><es>Tenga en cuenta la eliminación de \"_name_\" usuario</es></i18n>", $userlang));
	$body=str_replace(array("_name_", "_domain_"), array($name, DOMAIN), i18n("<i18n key='pcu1'><en>Hello _name_,\r\nthis email has automatically been sent to inform you that the user \"_name_\" on _domain_ will be deleted in one month, because of inactivity.\r\nIf you want to avoid automatic deletion, please login as \"_name_\" on _domain_ and continue using the web-service (add an info or replace your pawn).\r\n\r\nIf for any reason you want the user \"_name_\" to be deleted, just ignore this email. A short feedback about _domain_ is welcome, just reply to this email to do so. \r\nKind regards,</en><de>Guten Tag _name_,\r\ndiese Email wurde automatisch an Sie verschickt, um Ihnen mitzuteilen, dass der Benutzer \"_name_\" auf _domain_ in einem Monat aufgrund von Inaktivität gelöscht wird.\r\nWenn Sie das automatische Löschen verhindern wollen, loggen Sie sich bitte als \"_name_\" auf _domain_ ein und benutzen Sie den Web-Service (fügen Sie eine Info hinzu oder setzen Sie die Spielfigur um).\r\n\r\nWenn Sie den Benutzer \"_name_\" löschen lassen möchten, ignorieren Sie diese E-Mail. Eine kurze Rückmeldung über _domain_ ist willkommen, dazu einfach auf diese Email antworten.\r\nMit freundlichen Grüssen,</de><fr>Bonjour _name_,\r\nCe message a été automatiquement envoyé pour vous de vous informer que l'utilisateur sur \"_name_\" _domain_ seront supprimés en un mois, en raison de l'inactivité.\r\nSi vous voulez éviter la suppression automatique, s'il vous plaît vous connecter en tant \"_name_\" sur _domain_ et continuer à utiliser le web service (ajouter une info ou remplacer votre pion).\r\n\r\nSi pour une raison quelconque vous voulez que le \"_name_\" utilisateur à supprimer, ignorer cet e-mail. Un retour de courte sujet _domain_ est la bienvenue, répondez simplement à cet email à le faire.\r\nCordialement,</fr><es>Hola _name_,\r\n Este correo electrónico ha sido enviado automáticamente a usted para informarle de que el usuario en \"_name_\" _domain_ se eliminará en un mes, debido a la inactividad.\r\nSi desea evitar la eliminación automática, por favor ingrese como \"_name_\" en _domain_ y continuar utilizando el servicio web (agregar una información o cambiar su peón). \r\n\r\npor cualquier motivo desea que el \"_name_\" usuario que desea eliminar, simplemente ignore este mensaje. Una respuesta breve sobre _domain_ es bienvenida, simplemente responda a este correo electrónico para hacerlo.\r\n
			Le saluda atentamente,</es></i18n>", $userlang)."\r\n\r\n  Martin Hartnagel.");
	if (getState($id) != "dwa") {
	  $success=enqueueMail($email, $title, $body, "From: $feedback_to\r\nMime-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\n");
	  setState($id, "dwa"); //deletion-warning-after
	  echo($sucess." mailed deletion-warning to ".$name." (".$email.")\n");
	} else {
	  echo("skipping already warned ".$name." (".$email.")\n");
	}
}
mysql_free_result($sql);

echo("\ncompleted.");
bottom();
?>

